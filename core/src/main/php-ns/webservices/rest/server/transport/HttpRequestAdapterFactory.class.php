<?php
/* This class is part of the XP framework
 *
 * $Id$
 */

  namespace webservices\rest\server\transport;
 use 
    scriptlet\HttpScriptletException,
    peer\http\HttpConstants
  ;
  
  /**
   * Factory for HTTP request adapters
   *
   * @test    xp://net.xp_framework.unittest.rest.server.transport.HttpRequestAdapterFactoryTest
   * @purpose Factory
   */
  class HttpRequestAdapterFactory extends \lang\Object {
    
    /**
     * Create adapter for request based on content type header
     * 
     * @param scriptlet.HttpScriptletRequest request The request
     * @return lang.XPClass
     */
    public static function forRequest($request) {
      static $map= array(
        'application/json' => 'webservices.rest.server.transport.JsonHttpRequestAdapter'
      );
      
      if (NULL === $request->getHeader('Content-Type')) {
        return \lang\XPClass::forName('webservices.rest.server.transport.EmptyHttpRequestAdapter');
      }
      
      if (!isset($map[$request->getHeader('Content-Type')])) throw new HttpScriptletException(
        'The content type is not supported: '.$request->getHeader('Content-Type'),
        HttpConstants::STATUS_UNSUPPORTED_MEDIA_TYPE
      );
      
      return \lang\XPClass::forName($map[$request->getHeader('Content-Type')]);
    }
  }
?>
