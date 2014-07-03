<?php
// autoload.php
/**
 * This class is responsible for loading of all classes registered here whenever needed.
 * This is a singleton class, classes has to be registered mapped with the php filepath which has the class.
 * @author vb
 */
class Autoload {

  private static $instance;

  private $classArray = array ();

  private function __construct() {
  }

  /**
   * Get instance of the AutoLoad
   * @return Autoload
   */
  public static function getInstance() {
    if (self::$instance === null) {
      self::$instance = new self ();
    }
    return self::$instance;
  }

  /**
   * Register classes to autoload
   * @param array $classes
   *          Array of class->filepath
   * @throws LogicException throws when already class with same name is registered
   */
  public function addClasses($classes) {
    foreach ( $classes as $className => $filePath ) {
      $this->addClass ( $className, $filePath );
    }
  }

  /**
   * Register class to autoload
   * @param string $class
   *          Class name
   * @param string $filePath
   *          PHP file path containing the Class.
   * @throws LogicException throws when already class with same name is registered
   */
  public function addClass($className, $filePath) {
    $className = trim ( $className );
    if (isset ( $this->classArray [$className] )) {
      throw new LogicException ( "Can't register `" . $className . "` in autoloader. Duplicated class name!" );
    }
    $classArray [$className] = $filePath;
  }

  /**
   * Get the path of the class from registered classes.
   * @param string $className          
   */
  private function getClasspath($className) {
    if (isset ( $this->classArray [$className] )) {
      return $this->classArray [$className];
    }
    throw new InvalidArgumentException ( $className . " is not registered to load automatically!" );
  }

  /**
   * Function to be called by system to load class automatically whenever needed.
   * @param string $className          
   */
  public static function autoload($className) {
    $thisObj = self::getInstance ();
    try {
      $path = $thisObj->getClassPath ( $className );
    } catch ( Exception $e ) {
      return;
    }
    include $path;
  }
}