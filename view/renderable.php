<?php
// renderable.php
abstract class Renderable {

  /**
   * Template path.
   * @var string
   */
  protected $template;

  /**
   * List of assigned vars.
   * @var array
   */
  protected $assignedVars = array ();

  /**
   * Whether the component is visible
   * @var boolean
   */
  private $isVisible = true;

  /**
   * Assigns variable.
   * @param string $name
   * @param mixed $value
   */
  public function assign($name, $value) {
    $this->assignedVars [$name] = $value;
  }

  /**
   * Clear the assigned value
   * @param string $varName
   */
  public function clearAssign($varName) {
    if (isset ( $this->assignedVars [$varName] )) {
      unset ( $this->assignedVars [$varName] );
    }
  }

  /**
   * Get the template
   * @return string
   */
  public function getTemplate() {
    return $template;
  }

  public function setTemplate($template) {
    $this->template = $template;
  }

  /**
   * Get whether visible?
   * @return boolean
   */
  public function isVisible() {
    return $this->isVisible;
  }

  /**
   * Set the visibility
   * @param boolean $isVisible
   */
  public function setVisible($isVisible) {
    $this->isVisible = $isVisible;
  }

  /**
   * Render the view
   */
  public function render() {
    if (! $this->isVisible) {
      return '';
    }
    
    if ($this->template === null) {
      throw new LogicException ( 'No template was provided for render! Class `' . get_class ( $this ) . '`.' );
    }
    
    $view = View::getInstance ();
    $prevVars = $view->getAllAssignedVars ();
    
    $view->assignVars ( $this->assignedVars );
    
    $renderedMarkup = $view->renderTemplate ( $this->template );
    
    $view->clearAssignedVars ();
    
    $view->assignVars ( $prevVars );
    
    return $renderedMarkup;
  }
}
?>