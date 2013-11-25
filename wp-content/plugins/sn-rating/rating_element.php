<?php
/**
 * This class is responsible to generate rating Element object.
 * @author dkum32
 *
 */
class RatingElement {
  private $type;
  private $heading;
  private $isRounded;
  
  
  public function __construct($type, $heading, $isRounded = false) {
    $this->type = $type;
    $this->heading = $heading;
    $this->isRounded = $isRounded;
  }
  
  /**
   * This method would return rating element.
   */
  public function getRatingElement() {
    
  }
}
 