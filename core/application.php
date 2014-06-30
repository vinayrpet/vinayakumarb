<?php
// application.php

/*
 * Application, The starting point of the application
 */

/**
 * Class to handle the application
 * @author vb
 *
 */
class Application {
  
  private static $instance = null;

  private function __construct(){
   
  }

  /**
   * Get the instance of the Application
   */
  public static function getInstance(){
    if(self::$instance == null){
      self::$instance = new self();
    }
    return self::$instance;
  }

  public function init() {
  }

  public function display() {
  }
}
?>