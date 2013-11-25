<?php
/**
 * 
 * This would be a singleton class having all kinds of admin stuff required at various places
 * like when we store the data to dbase.
 * @author dkum32
 *
 */
class RatingEntity {
  public $type;
  public $id;
  
  public function __construct($type, $id) {
    $this->type = $type;
    $this->id = $id;
  }
  
  /**
   * 
   * This method would return an entity object based on type and id provided.
   */
  public function getEntity() {
    $output = '';
    // filtering out the entity.
    switch (strtolower($this->type)) {
      case 'comment':
        $output = get_comment($this->id);
        break;
      default: // considering default as post. or it may be configurable.
        $output = get_post($this->id);
        break;
    }
    return $output;
  }
  
}