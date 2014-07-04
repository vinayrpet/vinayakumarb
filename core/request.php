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

/**
 * Class to handle all requests
 * @author vinay
 */
class RequestHandler {
  const ATTRS_KEY_CTRL = 'controller';
  const ATTRS_KEY_ACTION = 'action';
  const ATTRS_KEY_VARLIST = 'params';

  private static $instance;

  private $dispatchAttrs;

  private function __construct() {
  }

  /**
   * Get instance of RequestHandler
   * @return RequestHandler
   */
  public static function getInstance() {
    if (self::$instance === null) {
      self::$instance = new self ();
    }
    return self::$instance;
  }

  /**
   * Set Dispatch attributes for the request
   * @param array $dispatchAttrs
   */
  public function setDispatchAttrs(array $dispatchAttrs) {
    if (empty ( $attributes [OW_Route::DISPATCH_ATTRS_CTRL] )) {
      throw new Redirect404Exception ();
    }
    $this->dispatchAttrs = array (
        self::ATTRS_KEY_CTRL => trim ( $attributes [Route::DISPATCH_ATTRS_CTRL] ),
        self::ATTRS_KEY_ACTION => (empty ( $attributes [Route::DISPATCH_ATTRS_ACTION] ) ? null : trim ( $attributes [Route::DISPATCH_ATTRS_ACTION] )),
        self::ATTRS_KEY_VARLIST => (empty ( $attributes [Route::DISPATCH_ATTRS_VARLIST] ) ? array () : $attributes [Route::DISPATCH_ATTRS_VARLIST]) 
    );
  }

  /**
   * Get dispatchAttributes
   * @return array
   */
  public function getDispatchAttrs() {
    return $this->dispatchAttrs;
  }

  /**
   * Dispatch the current request handlers
   */
  public function dispatch() {
    if (empty ( $this->dispatchAttrs [self::ATTRS_KEY_CTRL] )) {
      throw new InvalidArgumentException ( "Can't dispatch request! Empty or invalid controller class provided!" );
    }
    if (empty ( $this->dispatchAttrs [self::ATTRS_KEY_ACTION] )) {
      throw new InvalidArgumentException ( "Can't dispatch request! Empty or invalid action provided!" );
    }
    // TODO handle the params
    
    // Findout the controllet class to be invoked
    try {
      $reflectionClass = new ReflectionClass ( $this->handlerAttributes [self::ATTRS_KEY_CTRL] );
    } catch ( ReflectionException $e ) {
      throw new Redirect404Exception ();
    }
    /* @var $controller OW_ActionController */
    $controller = $reflectionClass->newInstance ();
    
    // check if controller exists and is instance of base action controller class
    if ($controller === null || ! $controller instanceof OW_ActionController) {
      throw new LogicException ( "Can't dispatch request! Please provide valid controller class!" );
    }
    
    // Findout the action on controller class
    try {
      $action = $reflectionClass->getMethod ( $this->handlerAttributes [self::ATTRS_KEY_ACTION] );
    } catch ( Exception $e ) {
      throw new Redirect404Exception ();
    }
    
    $actionParams = array (
        self::ATTRS_KEY_VARLIST => (empty ( $this->handlerAttributes [self::ATTRS_KEY_VARLIST] ) ? array () : $this->handlerAttributes [self::ATTRS_KEY_VARLIST]) 
    );
    
    // Invoke the action method on controller
    $action->invokeArgs ( $controller, $actionParams );

    // set default template for controller action if template wasn't set
    if ($controller->getTemplate () === null) {
      $controller->setTemplate ( $this->getControllerActionDefaultTemplate ( $controller ) );
    }

    //TODO
    OW::getDocument ()->setBody ( $controller->render () );
  }
}
?>