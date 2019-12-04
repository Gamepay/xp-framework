<?php
/* This class is part of the XP framework
 *
 * $Id$ 
 */

  uses('peer.http.HttpTransport', 'io.streams.MemoryInputStream', 'peer.URL', 'peer.http.CurlHttpOutputStream');

/**
 * Transport via curl functions
 *
 * @ext   curl
 * @see   xp://peer.http.HttpConnection
 */
class CurlHttpTransport extends HttpTransport {
  private $ssl= null;
  static function __static() { }
  /**
   * Constructor
   *
   * @param   peer.URL $url
   * @param   string $arg
   */
  public function __construct(URL $url, $arg) {
    sscanf($arg, 'v%d', $this->ssl);
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
    static $versions= [
        HttpConstants::VERSION_1_0 => CURL_HTTP_VERSION_1_0,
        HttpConstants::VERSION_1_1 => CURL_HTTP_VERSION_1_1,
    ];
    $headers= [];
    foreach ($request->headers as $name => $values) {
      foreach ($values as $value) {
        $headers[]= $name.': '.$value;
      }
    }
    $handle= curl_init();

    $options = [
        CURLOPT_HEADER         => true,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_SSL_VERIFYHOST => 2,
        // CURLOPT_SSL_VERIFYPEER => 0, //RP-disabled, enabled is and should be the default
        CURLOPT_URL            => $request->url->getCanonicalURL(),
        CURLOPT_CUSTOMREQUEST  => $request->method,
//        CURLOPT_CUSTOMREQUEST  => $request->getRequestString(), // this is what "send" uses, but its not used here?!?
        CURLOPT_CONNECTTIMEOUT => $connectTimeout,
        CURLOPT_TIMEOUT        => $readTimeout,
        CURLOPT_HTTPHEADER     => $headers,
    ];

    if ($this->ssl) $options[CURLOPT_SSLVERSION] = $this->ssl;
    if (isset($versions[$request->version])) $options[CURLOPT_HTTP_VERSION] = $versions[$request->version];


    curl_setopt_array($handle, $options);

    if ($this->proxy && !$this->proxy->isExcluded($request->getUrl())) {
      curl_setopt_array($handle, [
          CURLOPT_PROXY     => $this->proxy->host,
          CURLOPT_PROXYPORT => $this->proxy->port,
      ]);
      $proxied= true;
    } else {
      $proxied= false;
    }
//    $this->cat && $this->cat->info('>>>', (
//        $request->method.' '.$request->target().' HTTP/'.$request->version."\r\n".
//        implode("\r\n", $headers)
//    ));
    return new CurlHttpOutputStream($handle, $proxied);
  }
  /**
   * Finishes a transfer and returns the response
   *
   * @param  peer.http.HttpOutputStream $stream
   * @return HttpResponse peer.http.HttpResponse
   * @throws IOException
   */
  public function finish($stream) {
    $stream->close();
    if ('' !== $stream->bytes) {
      curl_setopt($stream->handle, CURLOPT_POSTFIELDS, $stream->bytes);
    }
    $transfer= curl_exec($stream->handle);
    if (false === $transfer) {
      $error= curl_errno($stream->handle);
      $message= curl_error($stream->handle);
      curl_close($stream->handle);
      throw new IOException($error.' '.$message);
    }
    // Strip "HTTP/x.x 200 Connection established" which is followed by
    // the real HTTP message: headers and body
    if ($stream->proxied) {
      $transfer= preg_replace('#^HTTP/[0-9]\.[0-9] [0-9]{3} .+\r\n\r\n#', '', $transfer);
    }
    curl_close($stream->handle);
    $response= new HttpResponse(new MemoryInputStream($transfer), false);
//    $this->cat && $this->cat->info('<<<', $response->getHeaderString());
    return $response;
  }
  /**
   * Sends a request
   *
   * @param   peer.http.HttpRequest $request
   * @param   int $timeout default 60
   * @param   float $connecttimeout default 2.0
   * @return  HttpResponse peer.http.HttpResponse response object
   */
  public function send(HttpRequest $request, $timeout= 60, $connecttimeout= 2.0) {
     // dont do this, it doesnt work (at least with the Wirecard Sandbox)
//     $stream = $this->open($request, $timeout, $connecttimeout);
//     $response = $this->finish($stream);
//
//     return $response;

    $curl= curl_init();
    curl_setopt($curl, CURLOPT_URL, $request->url->getCanonicalURL());
    curl_setopt($curl, CURLOPT_CUSTOMREQUEST, $request->getRequestString());
    curl_setopt($curl, CURLOPT_CONNECTTIMEOUT, $connecttimeout);
    curl_setopt($curl, CURLOPT_TIMEOUT, $timeout);
    curl_setopt($curl, CURLOPT_HEADER, true);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 2);

    if ($this->proxy && !$this->proxy->isExcluded($request->getUrl())) {
      curl_setopt($curl, CURLOPT_PROXY, $this->proxy->host);
      curl_setopt($curl, CURLOPT_PROXYPORT, $this->proxy->port);
      $read= function($transfer) {
        if (preg_match('#^HTTP/[0-9]\.[0-9] [0-9]{3} .+\r\n\r\n#', $transfer, $matches)) {
          // Strip "HTTP/x.x 200 Connection established" which is followed by
          // the real HTTP message: headers and body
          return substr($transfer, strlen($matches[0]));
        } else {
          return $transfer;
        }
      };
    } else {
      $read= function($transfer) { return $transfer; };
    }

    $return= curl_exec($curl);
    if (false === $return) {
      $errno= curl_errno($curl);
      $error= curl_error($curl);
      curl_close($curl);
      throw new IOException(sprintf('%d: %s', $errno, $error));
    }
    // ensure handle is closed
    curl_close($curl);
//    $this->cat && $this->cat->info('>>>', $request->getHeaderString());

    $response= new HttpResponse(new MemoryInputStream($read($return)), false);
//    $this->cat && $this->cat->info('<<<', $response->getHeaderString());

    return $response;
  }
}