<?php
// request.php
class Request {

  private static $instance;
  
  /**
   * URI of the request
   * @var string
   */
  private $uri;

  private function __construct() {
  }

  /**
   * Get request instance
   * @return Request
   */
  public static function getInstance() {
    if (self::$instance === null) {
      self::$instance = new self ();
    }
    return self::$instance;
  }

  /**
   * Get the current request URI
   * @return string
   */
  public function getRequestUri() {
    if ($this->uri === null) {
      $urlArray = parse_url ( Router::getInstance ()->getBaseUrl () );
      // parse the request path
      $originalUri = trim ( trim ( $_SERVER ['REQUEST_URI'] ), '/' );
      // parse the basepath
      $originalPath = trim ( trim ( $urlArray ['path'] ), '/' );
      
      if ($originalPath === '') {
        // In case of installation directory under server root $originalPath will be empty
        $this->uri = $originalUri;
      }
      else {
        // In case of installation under subdirectory, originalPath has to be removed from requestPath
        $uri = substr ( $originalUri, (strpos ( $originalUri, $originalPath ) + strlen ( $originalPath )) );
        $uri = trim ( trim ( $uri ), '/' );
        
        $this->uri = $uri ? $uri : '';
      }
    }

    return $this->uri;
  }
}