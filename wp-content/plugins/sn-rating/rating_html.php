<?php

/**
 *
 * Responsibility of this class would be only to create renderable HTML content .
 * @author dkum32
 *
 */
class RatingHTML {
  public static function getRatingBox($rating) {
    $output = '';
    if(isset($rating)) {
      $output = self::_get_rating_box_output($rating);
    }
    return $output;
  }

  /**
   *
   * This method would return rating boxes if there is multiple rating factors
   * @param RatingObject $rating
   */
  private function _get_rating_box_output($rating) {
    $output = '';
    $data = new DataAccess();
    // getting default rating theme for current entity type.
    $theme = $rating['theme'];
    $max_count = $rating['max_count'];
    $final_count = 0;
    $final_aggregation = 0;
    $class = ' single';
    if ($rating['multiple'] === true) {
      $class = ' multiple';
    }

    
    $output .= '<div class="rating-container ' . $class . '" id="entity-content-' . $rating['type'] . '-' .$rating['content_id'].'" rel="'.$rating['content_id'].'">';
    foreach ($rating['options'] as $rate) { 
      $complete_stars = 0;
      $final_count++;
      $half_stars = 0;
      $title = $rate['title'];
      $rate['aggregate'] = ($max_count * $rate['aggregate'] ) / 100;
      $aggregate = $rate['aggregate'];
      $final_aggregation += $aggregate;
      $complete_stars = intval($rate['aggregate']);
      $half_stars = $rate['aggregate'] - $complete_stars;
      
      
      if ($rating['multiple'] === false) {
        $output .= '<div class="rating-box clearfix" rel="' . $rating['type'] . '">';
      }
      else {
        $output .= '<div title =' . "'" . $rate['title'] .  "'"  . ' class="rating-box" rel="' . $rating['type'] . '">';
      }
      $output .= '<div class="rating-label"><label>' . $title . '</label></div>';
      if ($rating['multiple'] === false)
        $output .='<div class="rating-area '. (($rating['user_can_rate']===true)? " enable":"disable" ). '">';
      else
        $output .='<div class="rating-area '.$rate['title'] . ' ' . (($rate['can_rate_this']===true)? "enable":"disable" ). '">';
      for($i = 1 ; $i <= $max_count; $i++) {


        if ($i <= $complete_stars) {
          $output .= '<span class="rating-image active-rating-' . $theme . '" rel="' . $i . '" id="'.$rating['type'].'x'. $rating['content_id'] . 'x'. $i . '"></span>';
        }
        elseif (!empty($half_stars)) {
          $output .= '<span class="rating-image active-rating-' . $theme . ' no-rating" rel="' . $i .'" id="'.$rating['type'].'x'. $rating['content_id'] . 'x'. $i . '"><span class="active-rating-' . $theme . '" style="width:' . ($half_stars * 100) . '%;"></span></span>';
          $half_stars = '';
        }
        else {
          $output .= '<span class="rating-image active-rating-' . $theme . ' no-rating" rel="'.$i.'" id="'.$rating['type'].'x'. $rating['content_id'] . 'x'. $i . '"></span>';
        }
      }
      $output .= "</div><span class='rating-single-avg'>   (" . round($rate['aggregate'], 1) . "/" . $max_count . ")</span>";
      $output .= "</div>";
    }
    if ($rating['multiple'] === true) {
      $final_average = round($final_aggregation / ($final_count),1);
      // $output .= "<input type='hidden' id='multiple-rating-" . $rating['type'] . '-' .$rating['content_id']. "' value='" . $max_count . "'/><div class='multiple-rate-button'>" . __('Rate this') . '</div>';
    }
    else {
      $final_average = round($final_aggregation / ($final_count),1);
    }
    $output .="</div>";
    return $output;
  }
}