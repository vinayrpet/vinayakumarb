<?php
// view.php

/**
 * This file is responsible for the rendering the view of an action
 */
require_once LIB_DIR . 'smarty3' . DS . 'lib' . DS . 'Smarty.class.php';

class View {

  private static $instance;

  /**
   * smarty instance to render the view
   */
  private $smarty;

  function __construct(){
    $this->smarty = new MySmarty();
  }

  /**
   * Gets the instance of the View
   */
  public static function getInstance(){
    if( self::$instance === null ){
      self::$instance = new self();
    }
    return self::$instance;
  }

  /**
   *
   * Assign the template variables to smarty by reference
   * @param $vars - key=>value pairs
   */
  public function assignVars($vars){
    foreach ( $vars as $key => $value ){
      $this->assignVar($key, $vars[$key]);
    }
  }

  /**
   * Assign the template variable
   * @param unknown_type $key
   * @param unknown_type $value
   */
  public function assignVar($key, $value){
    $this->smarty->assignByRef($key, $value);
  }


  /**
   * Returns assigned var value for provided var name.
   *
   * @param string $varName
   * @return mixed
   */
  public function getAssignedVar( $varName ){
    return $this->smarty->getTemplateVars($varName);
  }

  /**
   * Returns list of assigned var values.
   *
   * @return array
   */
  public function getAllAssignedVars(){
    return $this->smarty->getTemplateVars();
  }

  /**
   * Deletes all assigned template vars.
   */
  public function clearAssignedVars(){
    $this->smarty->clearAllAssign();
  }

  /**
   *
   * @param string $varName
   */
  public function clearAssignedVar( $varName ){
    $this->smarty->clearAssign($varName);
  }

  /**
   * This will render the template by replacing all template variables.
   * rendered content will be returned.
   * @param $template The full path of the template file.
   */
  public function renderTemplate($template){
    return $this->smarty->fetch($template);
  }

  /**
   * Clears compiled templates.
   */
  public function clearCompiledTemplates(){
    $this->smarty->clearCompiledTemplate();
  }
}

class MySmarty extends Smarty{

  function __construct(){
    parent::__construct();
    $smartyDir = LIB_DIR . 'smarty3'. DS;
    $compileDir = $smartyDir . 'templates_c' . DS;
    $this->setCompileDir($compile_dir);
  }

}

?>