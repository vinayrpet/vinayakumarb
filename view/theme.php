<?php
// theme.php

/**
 * This file is to handle all things related to theme service
 */

/**
 * Class to provide the service of themes
 * @author vinay
 */
class ThemeService {

  private static $instance = null;

  //themes
  private $defaultTheme;
  private $currentTheme;

  function __construct() {
  }

  /**
   * Get instance
   * @return ThemeService
   */
  public static function getInstance() {
    if (self::$instance === null) {
      self::$instance = new self ();
    }
    return self::$instance;
  }

  function getDefaultTheme() {
  }

  /**
   * Initialize the default theme
   */
  function initDefaultTheme() {
    //TODO
  }
}

//TODO
class Theme {

  function __construct() {
  }
}
?>