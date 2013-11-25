<?php
// File responsible for creating admin widget
class RatingWidget extends WP_Widget {
  /**
   * @method
   * Implementing constructor.
   */
  public function __construct() {
    parent::__construct('rating_widget', 'Detailed Rating Info', array('name' => 'Rating Details', 'description' => __('This widget shows detailed view of rating.')));
  }

  /**
   * @method
   * Implementing widgets hook to create widget.
   */
  public function widget($args, $instance) {
    echo $args['before_widget'];
    if ( ! empty( $instance['title'] ) ) {
      echo $args['before_title'];
      echo esc_html( $instance['title'] );
      echo $args['after_title'];
    }
    $this->getRatingDetails($instance);
    echo $args['after_widget'];
  }

  /**
   * This method would return related posts for currently viewed post.
   * @param mixed $instance
   * This variable will hold plugin's admin variables.
   */

  function getRatingDetails($instance) {
    get_currentuserinfo();
    global $current_user;
    if($current_user->roles[0] == 'administrator') {
    global $post;
    if (is_single($post) || $post->post_type=='page') {
      // getting current post's categories.
      $data = new DataAccess();
      // getting factors availability.
      $entity = $data->get_entity_id_by_name($post->post_type);
      
      $result = $data->db->get_row("SELECT count(rating_score) as cnt FROM wp_rating_scores where entity_content_id ='$post->ID'");
      $is_multiple = $data->db->get_row("SELECT count(*) as entity_factors_mode FROM ".$data->db->prefix."entity_rating_factors where entity_id ='$entity->id'");
      $is_multiple = $is_multiple->entity_factors_mode;
      if ($result->cnt > 0) {
        $themeOptions = get_option('advanced_rating_settings');
        $max_count = !empty($themeOptions['star_disp_count'])?$themeOptions['star_disp_count']:5;
        $rating_scale = $data->get_rating_scale($post->post_type);
        
        if (isset($rating_scale->rating_scale)) {
          $max_count = $rating_scale->rating_scale;
        }
        
        $rating = new Rating();
        if (!$is_multiple) {
          $output = $rating->generateRatingBox($post);
          $results = $data->db->get_results("SELECT rating_score, rating_stars_count, count(rating_score) as cnt FROM ".$data->db->prefix. "rating_meta where entity_content_id ='$post->ID' group by rating_score");
          
          foreach ($results as $r) {
            $stars[ceil(($r->rating_score/100) * $r->rating_stars_count)] = $r->cnt;  
          }
          // getting total number of records in rating table
          $total_records = count($stars);                   
          
          for ($i=1; $i<=$max_count; $i++) {
            $avg = 0;
            // If there is a record for current index in fetched records then update average.
            if (isset($stars[$i])) {
              $avg = ($stars[$i] /$max_count) * 100;
            }
            $output .= "<div class='rating-vertical'><div class='vertical-rating-label'>$i Stars</div><div class='rating-vertical-back'><div class='rating-vertical-front' style='width:$avg%;'></div></div><div class='rating-count-label'>(".(isset($stars[$i])?$stars[$i]:0).")</div></div>";
          }
        }
        else {
          // getting factors of an entity.
          $entity_id = $data->get_entity_id_by_name($post->post_type);
          $entity_id = $entity_id->id;
          $factors = $data->_get_factors_by_entity_id($entity_id);
          $output .= "<div class='rating-vertical'><div class='multiple-factors-bar'>(".__('Average Ratings').")</div>";
          foreach($factors as $factor) {
            $factor_name = $factor->factor_name;
            $result_inner = $data->db->get_row("SELECT avg(rating_score) as avg FROM wp_rating_scores where entity_content_id ='$post->ID' AND factor_name = '$factor_name'");
            $avg = ceil($result_inner->avg/100 * $max_count);
            $output .= "<div class='rating-vertical'><div class='multiple-factors-bar'><strong>$factor_name</strong>: (".number_format($avg,2).")</div></div>";
          }

        }

        $settings = get_option('advanced_rating_settings');
        if (isset($settings['demographic_mode']) && $settings['demographic_mode']) {
          // calculating demographic information.
          $output .= "<h2>" . __('Rating Location Information').'</h2>';

          $locations = $data->get_demographic_information($post);

          foreach ($locations as $location) {
            $code = trim($location['country']);
            $state = isset($location['state'])? ', ' . $location['state'] : '';
            $image_path =  plugins_url("/images/flags/" . strtolower($code) . ".gif", __FILE__ );
            $country = $data->get_country($code);
            if (empty($country)) {
              $country = 'Anonymous';
              $image_path = plugins_url("/images/flags/anonymous.gif", __FILE__ );
            }
            $output .= "<div class='country-flag $code' style='background:url($image_path) no-repeat left 7px;'>" . $country . $state . " (" . $location['votes'] . ")</div>";
          }
          $output .="<div class='rating-vertical' style='margin-bottom:10px;'></div>";
        }
        
        print '<h3 class="widget-title">Rating Details</h3>' . $output;         
      }
    }
   }
  }

  /**
   * @method
   * This method would register widget.
   */
  public function register_my_widget() {
    register_widget('RatingWidget');
  }
}



// Registering the widget using inbuild register_hook for widget.
add_action('widgets_init',  array('RatingWidget', 'register_my_widget'));