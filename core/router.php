<?php
// router.php
/**
 * Class responsible for routing requests correct Controller and actions.
 * @author vinay
 */
class Router {

  /**
   * Current request URI
   */
  private $currentUri;

  /**
   * Static routes
   * @var Route
   */
  private $staticRoutes = array ();

  /**
   * Routes
   * @var Route
   */
  private $routes = array ();

  /**
   * Singleton Instance
   */
  private static $instance = null;

  /**
   * Default route.
   * Used for default url generation strategy.
   * @var DefaultRoute
   */
  private $defaultRoute;

  /**
   * Base url is added to all generated URIs.
   * @var string
   */
  private $baseUrl;

  /**
   * Last Used route
   * @var Route
   */
  private $usedRoute;

  private function __construct() {
  }

  /**
   * Get Instance
   * @return Router
   */
  public static function getInstance() {
    if (self::$instance === null) {
      self::$instance = new self ();
    }
    return self::$instance;
  }

  /**
   * Set the current request URI
   * @param string $uri
   */
  public function setCurrentUri($uri) {
    $this->currentUri = $uri;
  }

  /**
   * Return the Current request uri
   * @return string
   */
  public function getCurrentUri() {
    return $this->currentUri;
  }

  /**
   * Set the base url for all the urls generated
   * @param String $baseUrl
   */
  public function setBaseUrl($baseUrl) {
    $this->baseUrl = $baseUrl;
  }

  /**
   * Get the baseUrl
   * @return string
   */
  public function getBaseUrl() {
    return $this->baseUrl;
  }

  /**
   * Adds route object to router.
   * All routes should by added before routing process starts.
   * If route with provided name exists exception will be thrown.
   * @param Route $route
   * @return Router
   */
  public function addRoute(Route $route) {
    $routeName = $route->getRouteName ();
    
    if (isset ( $this->staticRoutes [$routeName] ) || isset ( $this->routes [$routeName] )) {
      // TODO "Can't add route! Route `" . $routeName . "` already added!");
    }
    else {
      if ($route->isStatic ()) {
        $this->staticRoutes [$routeName] = $route;
      }
      else {
        $this->routes [$routeName] = $route;
      }
    }
    
    return $this;
  }

  /**
   * Removes route object from router.
   * Routes should be removed before routing process starts.
   * @param string $routeName
   * @return Router
   */
  public function removeRoute($routeName) {
    $routeName = trim ( $routeName );
    
    if (isset ( $this->staticRoutes [$routeName] )) {
      unset ( $this->staticRoutes [$routeName] );
    }
    
    if (isset ( $this->routes [$routeName] )) {
      unset ( $this->routes [$routeName] );
    }
    
    return $this;
  }

  /**
   * Set the defaultRoute for the current request
   * @param Route $defaultRoute
   */
  public function setDefaultRoute($defaultRoute) {
    $this->defaultRoute = $defaultRoute;
  }

  /**
   * Returns route with provided name.
   * @param string $routeName
   * @return Route
   */
  public function getRoute($routeName) {
    $routeName = trim ( $routeName );
    
    if (isset ( $this->staticRoutes [$routeName] )) {
      return $this->staticRoutes [$routeName];
    }
    
    if (isset ( $this->routes [$routeName] )) {
      return $this->routes [$routeName];
    }
    
    return null;
  }

  /**
   * Generates uri for route using provided params.
   * @param string $routeName
   * @param array $params
   * @return string
   */
  public function uriForRoute($routeName, array $params = array()) {
    $routeName = trim ( $routeName );
    
    if (isset ( $this->staticRoutes [$routeName] )) {
      return $this->staticRoutes [$routeName]->generateUri ( $params );
    }
    
    if (isset ( $this->routes [$routeName] )) {
      return $this->routes [$routeName]->generateUri ( $params );
    }
    
    trigger_error ( "Can't generate URI! Route `" . $routeName . "` not found!", E_USER_WARNING );
    
    return 'INVALID_URI';
  }

  /**
   * Returns routing result - array with params (module, controller, action).
   * Tries to match requested URI with all added routes.
   * If matches weren't found default route is used.
   * @throws Redirect404Exception
   * @return array
   */
  public function route() {
    foreach ( $this->staticRoutes as $route ) {
      if ($route->match ( $this->uri )) {
        $this->usedRoute = $route;
        return $route->getDispatchAttrs ();
      }
    }
    
    foreach ( $this->routes as $route ) {
      if ($route->match ( $this->uri )) {
        $this->usedRoute = $route;
        return $route->getDispatchAttrs ();
      }
    }
    
    return $this->defaultRoute->getDispatchAttrs ( $this->uri );
  }
}

/**
 * Route for an action
 * @author vinay
 */
class Route {
  const PARAM_OPTION_DEFAULT_VALUE = 'default';
  const PARAM_OPTION_HIDDEN_VAR = 'var';
  const PARAM_OPTION_VALUE_REGEXP = 'regexp';
  const DISPATCH_ATTRS_CTRL = 'controller';
  const DISPATCH_ATTRS_ACTION = 'action';
  const DISPATCH_ATTRS_VARLIST = 'vars';
  
  // Name of the Route
  private $name;
  // path of the route
  private $path;
  // Name of the controller class
  private $controller;
  // function name to handle the current action
  private $action;
  // parameters passed to the action
  private $params;
  // Whether the route is static?
  private $static = false;
  // Result attributes for dispatching process.
  private $dispatchAttrs = array ();

  public function __construct($name, $path, $controller, $action, array $params = array()) {
    // TODO trimming and validation
    $this->name = $name;
    $this->path = trim ( trim ( $path ), '/' );
    $this->controller = $controller;
    $this->action = $action;
    $this->dispatchAttrs [self::DISPATCH_ATTRS_CTRL] = trim ( $controller );
    $this->dispatchAttrs [self::DISPATCH_ATTRS_ACTION] = trim ( $action );
    $this->params = $params;
  }

  /**
   * Get the name of the route
   */
  public function getName() {
    return $this->name;
  }

  /**
   * Get the path of the route
   */
  public function getPath() {
    return $this->path;
  }

  /**
   * Get the Controller class name of the Route
   */
  public function getController() {
    return $this->controller;
  }

  /**
   * Get the method name for the action
   */
  public function getAction() {
    return $this->action;
  }

  /**
   * Get the uri params
   */
  public function getParams() {
    return $this->params;
  }

  /**
   * Generate the URI for the route with provided options
   * @param string $params
   * @return string
   */
  public function generateUri($params = array()) {
    if ($this->static) {
      return $this->path;
    }
    // TODO Need to process the dynamic values
    return '';
  }

  /**
   * Compare the provided uri with the path of the route.
   * @param string $uri
   * @return boolean Returns true if uri matches, else return false;
   */
  public function isUriMathes($uri) {
    $uri = trim ( trim ( $uri ), '/' );
    
    if ($this->isStatic) {
      $pathArray = explode ( '/', $this->path );
      $pathArray = array_map ( 'urlencode', $pathArray );
      $tempPath = implode ( '/', $pathArray );
      
      return (mb_strtoupper ( $uri ) === mb_strtoupper ( $tempPath ));
    }
    // TODO handle dynamic paths
  }

  /**
   * Get dispatch attributes
   * @return multitype:
   */
  public function getDispatchAttrs() {
    return $this->dispatchAttrs;
  }
}
?>