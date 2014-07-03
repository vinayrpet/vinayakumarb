<?php
// application.php

/*
 * Application, The starting point of the application
 */

/**
 * Class to handle the application
 * @author vb
 */
class Application {

  private static $instance = null;

  private $document = null;

  private function __construct() {
  }

  /**
   * Get the instance of the Application
   */
  public static function getInstance() {
    if (self::$instance == null) {
      self::$instance = new self ();
    }
    return self::$instance;
  }

  public function init() {
    // router init - need to set current page uri and base url
    $router = Router::getInstance ();
    $router->setBaseUrl ( APP_URL_HOME );
    $uri = Request::getInstance ()->getRequestUri ();
    $uri = StringUtil::removeGetParamsFromURI ( $uri );
    $router->setCurrentUri ( $uri );
    
    // init plugins
    $pluginManager = PluginManager::getInstance ();
    $pluginManager->initPlugins ();
  }

  public function display() {
  }
}
?>