<?php
// plugins.php

/**
 * Class to manage all plugins
 * @author vb
 */
class PluginManager {

  private static $instance;

  /**
   * Array of active plugin names
   * @var array
   */
  private $activePlugins = array ();

  private function __construct() {
    //TODO read from the database about the active plugins
  }

  /**
   * Get instance of PluginManager
   * @return PluginManager
   */
  public static function getInstance() {
    if (self::$instance === null) {
      self::$instance = new self ();
    }
    return self::$instance;
  }

  /**
   * Initialize all the active plugins
   */
  public function initPlugins() {
    //TODO
  }
}