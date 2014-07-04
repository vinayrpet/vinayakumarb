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

  /**
   * Route the request to corresponding controller
   */
  public function handleRequest() {
    try {
      RequestHandler::getInstance ()->setDispatchAttrs ( Router::getInstance ()->route () );
    } catch ( RedirectException $e ) {
      $this->redirect ( $e->getUrl (), $e->getRedirectCode () );
    } catch ( InterceptException $e ) {
      RequestHandler::getInstance ()->setHandlerAttributes ( $e->getHandlerAttrs () );
    }
    
    try {
      RequestHandler::getInstance()->dispatch ();
    } catch ( RedirectException $e ) {
      $this->redirect ( $e->getUrl (), $e->getRedirectCode () );
    } catch ( InterceptException $e ) {
      OW::getRequestHandler ()->setHandlerAttributes ( $e->getHandlerAttrs () );
      $this->handleRequest ();
    }
  }

  public function respond() {
  }

  /**
   * Redirect the request to another URI
   * @param string $redirectTo
   * @param integer $redirectCode
   */
  public function redirect($redirectTo, $redirectCode) {
    URL_Util::redirect ( $redirectTo, $redirectCode );
  }
}
?>