<?php
/* This class is part of the XP framework
 *
 * $Id$ 
 */

  uses('peer.http.HttpTransport', 'peer.Socket', 'peer.SocketInputStream', 'peer.URL');

/**
 * Transport via sockets
 *
 * @see  xp://peer.Socket
 * @see  xp://peer.http.HttpConnection
 */
class SocketHttpTransport extends HttpTransport {
  protected $socket= null;
  protected $proxySocket= null;
  static function __static() { }
  /**
   * Constructor
   *
   * @param   peer.URL $url
   * @param   string $arg
   */
  public function __construct(URL $url, $arg) {
    $this->socket= $this->newSocket($url, $arg);
  }
  /**
   * Creates a socket
   *
   * @param   peer.URL $url
   * @param   string $arg
   * @return  peer.Socket
   */
  protected function newSocket(URL $url, $arg) {
    return new Socket($url->getHost(), $url->getPort(80));
  }
  /**
   * Set proxy
   *
   * @param   peer.http.HttpProxy proxy
   */
  public function setProxy(HttpProxy $proxy= null) {
    parent::setProxy($proxy);
    $this->proxySocket= $proxy ? new Socket($proxy->host, $proxy->port) : null;
  }
  /**
   * Connect to a socket. If socket still open from last request (which
   * is the case when unread data is left on it by not reading the body,
   * e.g.), use the quick & dirty way: Close and reopen!
   *
   * @param  peer.Socket $s
   * @param  double $read Read timeout
   * @param  double $connect Connect timeout
   * @return peer.Socket
   */
  protected function connect($s, $read, $connect) {
    $s->isConnected() && $s->close();
    $s->setTimeout($read);
    $s->connect($connect);
    return $s;
  }
  /**
   * Send proxy request
   *
   * @param  peer.Socket $s Connection to proxy
   * @param  peer.http.HttpRequest $request
   * @param  peer.URL $url
   * @return Socket peer.Socket
   */
  protected function proxy($s, $request, $url) {
    $request->setTarget(sprintf(
        '%s://%s%s%s',
        $url->getScheme(),
        $url->getHost(),
        $url->getPort() ? ':'.$url->getPort() : '',
        $url->getPath('/')
    ));
    return $s;
  }
  /**
   * Opens a request
   *
   * @param   peer.http.HttpRequest $request
   * @param   float $connectTimeout default 2.0
   * @param   float $readTimeout default 60.0
   * @return  peer.http.HttpOutputStream
   */
  public function open(HttpRequest $request, $connectTimeout= 2.0, $readTimeout= 60.0) {
    // Use proxy socket and Modify target if a proxy is to be used for this request,
    // a proxy wants "GET http://example.com/ HTTP/X.X" for (and "CONNECT" for HTTPs).
    if ($this->proxy && !$this->proxy->excludes()->contains($url= $request->getUrl())) {
      $s= $this->connect($this->proxySocket, $readTimeout, $connectTimeout);
      $this->proxy($s, $request, $url);
    } else {
      $s= $this->connect($this->socket, $readTimeout, $connectTimeout);
    }
    // Send headers, then return control to caller
    $header= $request->method.' '.$request->target().' HTTP/'.$request->version."\r\n";
    foreach ($request->headers as $name => $values) {
      foreach ($values as $value) {
        $header.= $name.': '.$value."\r\n";
      }
    }
    // Check for chunked transfer encoding
//    $this->cat && $this->cat->info('>>>', $header);
    $s->write($header."\r\n");
    $chunked= stristr($header, 'Transfer-Encoding: chunked');
    return $chunked ? new ChunkedHttpOutputStream($s) : new SocketHttpOutputStream($s);
  }
  /**
   * Finishes a transfer and returns the response
   *
   * @param  peer.http.HttpOutputStream $stream
   * @return peer.http.HttpResponse
   */
  public function finish($stream) {
    $stream->close();
    $response= new HttpResponse(new SocketInputStream($stream->socket));
//    $this->cat && $this->cat->info('<<<', $response->getHeaderString());
    return $response;
  }

  /**
   * Sends a request
   *
   * @param   peer.http.HttpRequest request
   * @param   int timeout default 60
   * @param   float connecttimeout default 2.0
   * @return  peer.http.HttpResponse response object
   */
  public function send(HttpRequest $request, $timeout= 60, $connecttimeout= 2.0) {
    // Use proxy socket and Modify target if a proxy is to be used for this request,
    // a proxy wants "GET http://example.com/ HTTP/X.X" for (and "CONNECT" for HTTPs).
    if ($this->proxy && !$this->proxy->isExcluded($url= $request->getUrl())) {
      $s= $this->connect($this->proxySocket, $timeout, $connecttimeout);
      $s = $this->proxy($s, $request, $url);
    } else {
      $s= $this->connect($this->socket, $timeout, $connecttimeout);
    }
//    $this->cat && $this->cat->info('>>>', $request->getHeaderString());
    $s->write($request->getRequestString());
    $response= new HttpResponse(new SocketInputStream($s));
//    $this->cat && $this->cat->info('<<<', $response->getHeaderString());
    return $response;
  }
}