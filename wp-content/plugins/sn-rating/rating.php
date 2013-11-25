<?php
include_once 'rating_admin.php';
include_once 'rating_entity.php';

/**
 * 
 * This class would be responsible to handle rating functionality.
 * @author pgautam
 *
 */
class Rating {
  public $user;
  public $entity;
  public $dataaccess; 
  
  public function __construct() {
    $this->dataaccess = new DataAccess();  
  }
  
  /**
   * 
   * This method give rating.
   */
  public function giveRating() {
    
  }
  
  /**
   * 
   * This method would generate HTML based rating widget.
   * @param mixed $args
   * Args would have a structured array which would generate an HTML based output for rating.
   * 
   */
  public function generateRatingBox($args) {
    $rating = $this->dataaccess->getRating($args);
    $rating_html = RatingHTML::getRatingBox($rating);
    return $rating_html;
  }
 
}


