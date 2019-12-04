<?php
/* This class is part of the XP framework
 *
 * $Id$ 
 */

  uses(
    'peer.http.HttpRequest', 
    'peer.http.HttpResponse',
    'peer.http.SocketHttpTransport',
    'peer.URL'
  );
//use lang\XPClass;
//use peer\URL;
//use peer\http\proxy\EnvironmentSettings;
/**
 * HTTP Transports base class
 *
 * @see   xp://peer.http.HttpConnection
 * @test  xp://peer.http.unittest.HttpTransportTest
 */
abstract class HttpTransport {
  protected static $transports= [];
  protected static $settings;
  protected $proxy= null;
  protected $cat= null;

  static function __static() {
    // Depending on what extension is available, choose a different implementation
    // for SSL transport. CURL is the slower one, so favor SSLSockets.
    self::$transports['http']= XPClass::forName('peer.http.SocketHttpTransport');
    if (extension_loaded('openssl')) {
      self::$transports['https']= XPClass::forName('peer.http.SSLSocketHttpTransport');
    } else if (extension_loaded('curl')) {
      self::$transports['https']= XPClass::forName('peer.http.CurlHttpTransport');
    }
//    self::$settings= new EnvironmentSettings();
//    if (!self::$settings->detected() && strncasecmp(PHP_OS, 'Win', 3) === 0 && extension_loaded('com')) {
//      self::$settings= new RegistrySettings();
//    }
  }

  /**
   * Constructor
   *
   * @param   peer.URL url
   * @param   string arg
   */
  abstract public function __construct(URL $url, $arg);
  /**
   * Set proxy
   *
   * @param   peer.http.HttpProxy proxy
   */
  public function setProxy(HttpProxy $proxy= null) {
    $this->proxy= $proxy;
  }
  /**
   * Sends a request via this proxy
   *
   * @param   peer.http.HttpRequest request
   * @param   int timeout default 60
   * @param   float connecttimeout default 2.0
   * @return  peer.http.HttpResponse response object
   */
  abstract public function send(HttpRequest $request, $timeout= 60, $connecttimeout= 2.0);

  /**
   * Creates a string representation of this object
   *
   * @return  string
   */
  public function toString() {
      return $this->getClassName();
  }

  /**
   * Register transport implementation for a specific scheme
   *
   * @param   string scheme
   * @param   lang.XPClass<peer.http.HttpTransport> class
   */
  public static function register($scheme, XPClass $class) {
    if (!$class->isSubclassOf('peer.http.HttpTransport')) {
      throw new IllegalArgumentException(sprintf(
          'Given argument must be lang.XPClass<peer.http.HttpTransport>, %s given',
          $class->toString()
      ));
    }
    self::$transports[$scheme]= $class;
  }
  /**
   * Get transport implementation for a specific URL
   *
   * @param   peer.URL url
   * @return  peer.http.HttpTransport
   * @throws  lang.IllegalArgumentException in case the scheme is not supported
   */
  public static function transportFor(URL $url) {
    $scheme = '';
    $arg = '';
    sscanf($url->getScheme(), '%[^+]+%s', $scheme, $arg);

    if (!isset(self::$transports[$scheme])) {
      throw new IllegalArgumentException('Scheme "'.$scheme.'" unsupported');
    }
    $transport= self::$transports[$scheme]->newInstance($url, $arg);

    return $transport;
  }
}