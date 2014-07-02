<?php
// session.php

/*
 * This will take care of the session for the application
 */


class AppSession {

  private static $instance = null;

  private function __construct(){

  }

  /**
   * Return the instance of the AppSession to maintain the details of the 
   session
   */
  public static function getInstance() {
    if( self::$instance === null ){
      self::$instance = new self();
    }
    return self::$instance;
  }

  function getName(){
    return md5(APP_URL_HOME);
  }

  function getId(){
    return session_id();
  }

  public function start(){

    session_name($this->getName());
    // Set cookies accessible only via HTTP
    $cookie = session_get_cookie_params();
    $cookie['httponly'] = true;
    session_set_cookie_params($cookie['lifetime'], $cookie['path'], 
    $cookie['domain'], $cookie['secure'], $cookie['httponly']);
    session_start();

    //Check whether its the same session?
    if ( !isset($_SESSION['session.home_url']) )
    {
      $_SESSION['session.home_url'] = OW_URL_HOME;
    }
    else if ( strcmp($_SESSION['session.home_url'], OW_URL_HOME) )
    {
      $this->recreate();
    }
  }

  private function recreate(){
    session_regenerate_id();

    $_SESSION = array();

    if ( isset($_COOKIE[$this->getName()]) ){
      $_COOKIE[$this->getName()] = $this->getId();
    }
  }
}
?>
