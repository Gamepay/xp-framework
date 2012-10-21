<?php
/* This class is part of the XP framework
 *
 * $Id$
 */

  uses(
    'scriptlet.HttpScriptlet',
    'scriptlet.Preference',
    'webservices.rest.server.RestFormat',
    'webservices.rest.server.Response',
    'webservices.rest.server.routing.RestDefaultRouter',
    'util.log.Traceable'
  );
  
  /**
   * REST scriptlet
   *
   */
  class RestScriptlet extends HttpScriptlet implements Traceable {
    protected 
      $cat     = NULL,
      $router  = NULL,
      $base    = '',
      $convert = NULL;

    /**
     * Constructor
     * 
     * @param  string package The package containing handler classes
     * @param  string base The base URL (will be stripped off from request url)
     * @param  string router The router class to use
     */

    public function __construct($package, $base= '', $router= '') {
      if ('' === (string)$router) {
        $this->router= new RestDefaultRouter();
      } else if (strstr($router, '.')) {
        $this->router= cast(
          XPClass::forName($router)->newInstance(),
          'webservices.rest.server.routing.RestRouter'
        );
      } else {
        $this->router= Package::forName('webservices.rest.server.routing')
          ->loadClass('Rest'.ucfirst($router).'Router')
          ->newInstance()
        ;
      }
      
      $this->base= rtrim($base, '/');
      $this->router->configure(Package::forName($package), $this->base);
      $this->convert= newinstance('webservices.rest.RestDeserializer', array(), '{
        public function deserialize($in, $target) {
          throw new IllegalStateException("Unused");
        }
      }');
    }

    /**
     * Set a log category fot tracing
     *
     * @param  util.log.LogCategory cat
     */
    public function setTrace($cat) {
      $this->cat= $cat;
    }

    /**
     * Calculate method to invoke
     *
     * @param   scriptlet.HttpScriptletRequest request 
     * @return  string
     */
    public function handleMethod($request) {
      parent::handleMethod($request);
      return 'doProcess';
    }

    /**
     * Get format for a given mediatype
     *
     * @param  string mediatype
     * @return webservices.rest.server.RestFormat
     */
    protected function formatFor($mediatype) {
      if ('application/x-www-form-urlencoded' === $mediatype) {
        return RestFormat::$FORM;
      } else if (preg_match('#[/\+]json$#', $mediatype)) {
        return RestFormat::$JSON;
      } else if (preg_match('#[/\+]xml$#', $mediatype)) {
        return RestFormat::$XML;
      } else {
        return RestFormat::$UNKNOWN;
      }
    }

    /**
     * Write an exception 
     *
     * @param  scriptlet.http.HttpScriptletResponse response The response
     * @param  webservices.rest.server.RestFormat format
     * @param  scriptlet.HttpScriptletException se
     */
    protected function writeError($response, $format, $se) {
      $this->cat && $this->cat->warn($se);

      $response->setStatus($se->getStatus());
      $format->write($response, array(
        'message' => $se->getMessage()
      )); 
    }


    /**
     * Handle routing item
     *
     * @param  var route
     * @param  scriptlet.HttpScriptletRequest request The request
     * @param  scriptlet.HttpScriptletResponse response The response
     * @throws scriptlet.HttpScriptletException
     */
    public function handle($route, $request, $response) {
      $this->cat && $this->cat->debug('->', $route);
      $input= NULL;

      // Instantiate the handler class
      $instance= $route['target']->getDeclaringClass()->newInstance();

      // Parameter annotations parsing
      $annotations= array();
      foreach ($route['target']->getAnnotations() as $annotation => $value) {
        if (2 === sscanf($annotation, '$%[^:]: %s', $param, $source)) {
          $annotations[$param]= array($source, $value ? $value : $param);
        }
      }

      // Extract arguments according to definition
      $args= array();
      foreach ($route['target']->getParameters() as $parameter) {
        $param= $parameter->getName();
        switch ($annotations[$param][0]) {
          case 'path':
            if (!isset($route['segments'][$annotations[$param][1]])) {
              $arg= $parameter->getDefaultValue();
            } else {
              $arg= rawurldecode($route['segments'][$annotations[$param][1]]);
            }
            $args[]= $this->convert->convert($parameter->getType(), $arg);
            break;

          case 'param':
            if (!$request->hasParam($annotations[$param][1])) {
              $arg= $parameter->getDefaultValue();
            } else {
              $arg= $request->getParam($annotations[$param][1]); 
            }
            $args[]= $this->convert->convert($parameter->getType(), $arg);
            break;

          case NULL:
            if (NULL === $input) {
              $input= $this->formatFor($route['input'])->read($request, $parameter->getType()); 
            }
            $args[]= $input;
            break;

          default: 
            throw new HttpScriptletException(sprintf(
              'Malformed source %s for parameter %s of %s',
              $annotations[$param][0],
              $param,
              $route['target']->toString()
            ));
        }
      }

      // Invoke method
      try {
        $result= $route['target']->invoke($instance, $args);
        $this->cat && $this->cat->debug('<-', $result);
      } catch (TargetInvocationException $t) {
        $this->cat && $this->cat->warn('<-', $t);
        throw new HttpScriptletException($t->getCause()->getMessage(), HttpConstants::STATUS_BAD_REQUEST, $t);
      }

      // For "VOID" methods, set status to "no content". If a response is returned, 
      // use its status, headers and payload. For any other methods, set status to "OK".
      if (Type::$VOID->equals($route['target']->getReturnType())) {
        $res= Response::status(HttpConstants::STATUS_NO_CONTENT);
      } else if ($result instanceof Response) {
        $res= $result;
      } else {
        $res= Response::status(HttpConstants::STATUS_OK)->withPayload($result);
      }

      $response->setStatus($res->status);
      $response->setContentType($route['output']);
      foreach ($res->headers as $name => $value) {
        if ('Location' === $name) {
          $url= clone $request->getURL();
          $response->setHeader($name, $url->setPath($value)->getURL());
        } else {
          $response->setHeader($name, $value);
        }
      }
      $this->formatFor($route['output'])->write($response, $res->payload);
    }

    /**
     * Process request and handle errors
     * 
     * @param  scriptlet.HttpScriptletRequest request The request
     * @param  scriptlet.HttpScriptletResponse response The response
     */
    public function doProcess($request, $response) {
      static $supported= array('application/json', 'text/json', 'text/xml', 'application/xml');

      $url= $request->getURL()->getURL();
      $accept= new Preference($request->getHeader('Accept'));
      $this->cat && $this->cat->info(
        $request->getMethod(),
        $request->getHeader('Content-Type', '(null)'),
        $url,
        $accept
      );

      // Iterate over all applicable routes
      $type= $request->getHeader('Content-Type');
      foreach ($this->router->routesFor($request) as $route) {

        // Check input type if specified by client
        if (NULL !== $type) {
          $preference= new Preference($route['input'] ? $route['input'] : '*/*');
          $route['input']= $preference->match(array($type));
        }

        // Check output type
        $route['output']= $accept->match($route['output'] ? array($route['output']) : $supported);

        try {
          $this->handle($route, $request, $response);
          return;
        } catch (HttpScriptletException $e) {
          $this->writeError($response, $this->formatFor($route['output']), $e);
          return;
        }
      }

      $this->writeError(
        $response,
        $this->formatFor($accept->match($supported)), 
        new HttpScriptletException('Could not route request to '.$url, HttpConstants::STATUS_NOT_FOUND)
      );
    }
  }
?>
