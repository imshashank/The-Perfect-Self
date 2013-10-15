<?php

/**
 *
 * This class is responsible to handle database related actions.
 * @author paritosh gautam
 *
 */
class DataAccess {

  public $db;

  public function __construct() {
    global $wpdb;
    $this->db = $wpdb;
  }

  /**
   *
   * This method would return rating of a particular entity.
   * @param RatingEntity $entity
   *
   * @return $rating;
   */
  public function getRating($entity) {
    get_currentuserinfo();
    global $current_user;
    $factor_id = 0;
    $multiple = true;
    $themeOptions = get_option('advanced_rating_setting');
    $id = isset($entity->comment_ID)?$entity->comment_ID:$entity->ID;
    $type = isset($entity->comment_ID)?'comment':$entity->post_type;
    // if type is revision then convert it to its parent id.
      
    if ($type == 'revision' ) {
      $type = 'post';
    }
    
    
    // getting rating factors
    $rating_scale = $this->get_rating_scale($type);
    $rating_options = get_option('advanced_rating_settings');
    $rating_scale = isset($rating_scale->rating_scale)?$rating_scale->rating_scale:(isset($rating_options['star_disp_count'])?$rating_options['star_disp_count']:5);
    
    // getting factors of an entity.
    $entity_id = $this->get_entity_id_by_name($type);
    $entity_id = $entity_id->id;

    $factors = $this->_get_factors_by_entity_id($entity_id);

    // current user id.
    $user_id = isset($current_user)?$current_user->ID:0;
    // rating score of a particular id.
    $user_can_rate = true;
    // getting if user has provided this rating.
    $user_data = $this->db->get_row("Select count(rating_score) as record_count from " . $this->db->prefix . "rating_meta where entity_content_id = '$id' AND entity_type_name = '$type' AND user_id='$user_id'");
    // If user has provided the rating on content then restrict.
    if ($user_data->record_count > 0 || $current_user->ID < 1) {
      $user_can_rate = false;
    }
    // OLD ONE: $is_multiple = $this->db->get_row("SELECT entity_factors_mode FROM ".$this->db->prefix."admin_rating_config where entity_type_name ='$type'");
    $is_multiple = $this->db->get_row("SELECT count(*) as entity_factors_mode FROM ".$this->db->prefix."entity_rating_factors where entity_id ='$entity_id'");
    if ( $is_multiple->entity_factors_mode == 0) {
      $factors = array();
    }

    if (empty($factors)) {
      $rating = $this->db->get_row("Select Avg(rating_score) as avg_score, factor_name from " . $this->db->prefix . "rating_scores where entity_content_id = '$id'");
      $multiple = false;
      $rating_content[] = array(
          'aggregate' => $rating->avg_score,
          'title' => '',
      );
    } else {
      foreach ($factors as $record) {
        $sql_assigned_factor = $this->db->get_row("SELECT erf.id FROM " . $this->db->prefix . "entity_rating_factors erf INNER JOIN " . $this->db->prefix . "rating_factors rf ON erf.factor_name = rf.factor WHERE erf.entity_id =" .$record->entity_id . " AND erf.factor_name = '$record->factor_name' AND rf.enable_factor = 1");
        if(isset($sql_assigned_factor)) {
          $user_data = $this->db->get_row("Select count(rating_score) as count from " . $this->db->prefix . "rating_meta where entity_content_id = '$id' AND entity_type_name = '$type' AND user_id='$user_id' AND factor_name ='$record->factor_name'");
          $rating = $this->db->get_row("Select Avg(rating_score) as avg_score, factor_name from " . $this->db->prefix . "rating_meta where entity_content_id = '$id' AND entity_type_name = '$type' AND factor_name='$record->factor_name'");
          $rating_content[] = array(
              'factor_id' => $record->id,
              'aggregate' => $rating->avg_score,
              'title' => $record->factor_name,
              'can_rate_this' => $user_id && !($user_data->count)?true:false,

          );
        }
      }
      $multiple = true;
    }
    // getting theme of the current type.

    return array(
        'user_can_rate' => $user_can_rate,
        'type' => $type,
        'content_id' => isset($entity->comment_ID)?$entity->comment_ID:$entity->ID,
        'entity_type' => $type,
        'max_count' => $rating_scale,
        'multiple' => $multiple,
        'options' => $rating_content,
        'theme' => $this->_get_theme_by_entity_type($type)
    );
  }

  /**
   * Metod to save admin configuration for rating plugin
   */
  public function save_rating_options() {
    if(isset($_POST['option_page'])) {
      switch ($_POST['option_page']) {
        case 'rating_settings':
          $advancedOptions = get_option('advanced_rating_settings');
          foreach($_POST['entity_type'] as $val) {
            $str_entity_config = " * ";
            $cond = " WHERE entity_type_name =" . "'". $val . "'";
            $sql_entity_config = $this->get_table_values($this->db->prefix . 'admin_rating_config', $str_entity_config, $cond);
            foreach($sql_entity_config as $value) {
              $entity_id = $value->id;
              $theme_name = $value->theme;
            }

            if(!isset($theme_name)) {
              $theme_name = $advancedOptions['theme_name'];
            }
            if(!isset($theme_name)) {
              $theme_name = 'default';
            }

            if(isset($entity_id) && $entity_id != '') {
              $this->update_rating_options($entity_id);
            }
            else {
              $this->db->insert($this->db->prefix . 'admin_rating_config', array('entity_type_name' => $val,
                  'most_rated_flag' => isset($_POST['most_rated_flag'][$val]) ? $_POST['most_rated_flag'][$val] : 0,
                  'entity_factors_mode' => isset($_POST['entity_factors_mode'][$val]) ? $_POST['entity_factors_mode'][$val] : 1,
                  'entity_enable' => isset($_POST['entity_enable'][$val]) ? $_POST['entity_enable'][$val] : 0,
                  'theme' => $theme_name), array('%s', '%d', '%d', '%d', '%s')
              );
            }
          }

          $url = admin_url().'options-general.php?page=rating_options&tab=rating_settings';
          $this->set_message(array('status' => TRUE, 'message' => __('Rating Configuration Updated Successfully')));
          //wp_redirect($url);

          break;

        case 'rating_factors':
          if(isset($_POST['factor']) && trim($_POST['factor']) !='') {
            if (!preg_match("^\d*[a-zA-Z][a-zA-Z\d]*$^", $_POST['factor'])){
              $this->set_message(array('status' => TRUE, 'message' => __('Rating Parameter name contains Special characters')));
              break;
            }
            else{
              $cond = "'". trim($_POST['factor']) ."'";
              $sql_rating = $this->get_table_values($this->db->prefix . 'rating_factors', 'id, enable_factor' , " WHERE factor = $cond");
              if(!isset($sql_rating[0]->id)) {
                $this->db->insert($this->db->prefix . 'rating_factors', array('factor' => $_POST['factor'], 'enable_factor' => 1), array('%s', '%d')
                );
              }
              else if(isset($sql_rating[0]->id) && $sql_rating[0]->enable_factor == 0) {
                $this->update_rating_options($sql_rating[0]->id);
              }
              else {
                $this->set_message(array('status' => TRUE, 'message' => __('Rating Parameter Already Exists')));
                break;
              }
            }
          }

          if (isset($this->db->insert_id) && $this->db->insert_id != 0) {
            $this->set_message(array('status' => TRUE, 'message' => __('Rating Parameter Added Successfully')));
            break;
          } else {
            $this->set_message(array('status' => TRUE, 'message' => __('Rating Parameter can not be added')));
          }
          break;

        case 'assign_entity_factors' :
          $i = 0;
          foreach($_POST as $key=>$value) {
            $pos = strpos($key, 'assign_fact--');
            if ($pos !== FALSE) {
              $entity_id = explode('assign_fact--', $key);
              $this->delete_rating_options('assign_entity_factors', $entity_id[1]);
              foreach($value as $val) {
                $sql_assigned_factor = $this->get_table_values($this->db->prefix . 'entity_rating_factors', ' id ', "WHERE entity_id = $entity_id[1] AND factor_id = $val");
                foreach ($sql_assigned_factor as $assigned_val) {
                  $value_assigned = $assigned_val->id;
                }
                if($value_assigned != $entity_id[1]) {
                  $this->db->insert($this->db->prefix . 'entity_rating_factors', array('entity_id' => $entity_id[1],
                      'factor_name' => $val,
                      'weight' => 0,), array('%d', '%s', '%d')
                  );
                }
                unset($value_assigned);
              }

              if (isset($this->db->insert_id) && $this->db->insert_id != 0) {
                $this->set_message(array('status' => TRUE, 'message' => __('Rating Parameter assigned Successfuly')));
                wp_redirect($_SERVER["REQUEST_URI"]);
              } else {
                $this->set_message(array('status' => TRUE, 'message' => __('Rating Parameter can not be assigned')));
              }
            }
            else {
              if($i<=1) {
                $this->delete_rating_options('assign_entity_factors', $_POST['entity_type_name']);
              }
            }
            $i++;
          }
          break;

        case 'rating_advanced_settings':
          if(isset($_POST['star_disp_count']) && !is_numeric($_POST['star_disp_count'])) {
            $this->set_message(array('status' => TRUE, 'message' => __('Please Enter Rating Scale in Numeric format')));
            break;
          }
          if(isset($_POST['star_disp_count']) && is_numeric($_POST['star_disp_count']) && $_POST['star_disp_count'] < 2 ){
            $this->set_message(array('status' => TRUE, 'message' => __('Please Enter Rating Scale Count Greater Then 1')));
            break;
          }
          $advancedOptions = array('theme_name' => $_POST['theme_name'],
              'star_disp_count' => isset($_POST['star_disp_count'])? $_POST['star_disp_count'] : 5,
              'demographic_mode' => isset($_POST['demographic_mode']) ? $_POST['demographic_mode'] : 0,
              'ip_blockage_time' => isset($_POST['ip_blockage']) ? $_POST['ip_blockage'] : 15,
              'enable_share' => isset($_POST['enable_share']) ? $_POST['enable_share'] : 0,
              'share_text' => isset($_POST['share_text']) ? "'" . $_POST['share_text'] ."'" : "This Post Has Rating Score of %d");

          add_option('advanced_rating_settings', $advancedOptions);
          $this->set_message(array('status' => TRUE, 'message' => __('Settings Added Successfully')));
          break;

        default:
          break;
      }
    }
  }

  /**
   * Metod to update admin configuration for rating plugin
   */
  public function update_rating_options($id = '') {
    if(isset($_POST['option_page']) && $_POST['option_page'] !='') {
      switch ($_POST['option_page']) {
        case 'rating_settings':
          $str_entity_config = " * ";
          $cond = " WHERE id =" . "'". $id . "'";
          $sql_entity_config = $this->get_table_values($this->db->prefix . 'admin_rating_config', $str_entity_config, $cond);
          foreach($sql_entity_config as $value) {
            $val = $value->entity_type_name;
            $theme = $value->theme;
          }
          $most_rated_flag = isset($_POST['most_rated_flag'][$val]) ? $_POST['most_rated_flag'][$val] : 0;
          $entity_factors_mode = isset($_POST['entity_factors_mode'][$val]) ? $_POST['entity_factors_mode'][$val] : 1;
          $entity_enable = isset($_POST['entity_enable'][$val]) ? $_POST['entity_enable'][$val] : 0;
          $table_admin_rating_config = $this->db->prefix . 'admin_rating_config';
          $theme = isset($theme) ? $theme : 'default';

          $this->db->query("UPDATE $table_admin_rating_config SET most_rated_flag = '" . $most_rated_flag . "',
              entity_factors_mode = '" . $entity_factors_mode . "', entity_enable = '" . $entity_enable . "', theme = '" . $theme . "' WHERE id =$id");

          $url = admin_url().'options-general.php?page=rating_options&tab=rating_settings';
          $this->set_message(array('status' => TRUE, 'message' => __('Rating Configuration Updated Successfully')));
         // wp_redirect($url);

          break;

        case 'entity_theme_settings':
          $str_entity_config = " * ";
          $cond = " WHERE entity_type_name =" . "'". $_POST['entity_type_name'] . "'";
          $sql_entity_config = $this->get_table_values($this->db->prefix . 'admin_rating_config', $str_entity_config, $cond);
          $rating_scale = 5; // default rating scale;
          foreach($sql_entity_config as $value) {
            $val = $value->entity_type_name;
            $most_rated_flag = $value->most_rated_flag;
            $entity_factors_mode = $value->entity_factors_mode;
            $entity_enable = $value->entity_enable;
            $theme = isset($_POST['theme']) ? $_POST['theme'] : $value->theme;
            $rating_scale = isset($_POST['rating_scale'])?$_POST['rating_scale']:5;
          }
          $most_rated_flag = isset($most_rated_flag) ? $most_rated_flag : 0;
          $entity_factors_mode = isset($entity_factors_mode) ? $entity_factors_mode : 0;
          $entity_enable = isset($entity_enable) ? $entity_enable : 0;
          $theme = isset($theme) ? $theme : 'default';
          $table_admin_rating_config = $this->db->prefix . 'admin_rating_config';

          $this->db->query("UPDATE $table_admin_rating_config SET most_rated_flag = '" . $most_rated_flag . "',
              entity_factors_mode = '" . $entity_factors_mode . "', entity_enable = '" . $entity_enable . "',
              theme = '" . $theme . "', rating_scale = '" . $rating_scale . "' WHERE entity_type_name ='" .$val. "'");


          $this->set_message(array('status' => TRUE, 'message' => __('Theme updated Successfully')));
          wp_redirect($_SERVER["REQUEST_URI"]);

          break;

        case 'rating_factors':
          $entity_factor = $_POST['factor'];
          if (!preg_match("^\d*[a-zA-Z][a-zA-Z\d]*$^", $_POST['factor'])){
            $this->set_message(array('status' => TRUE, 'message' => __('Rating Parameter contains Special characters')));
          }
          else {
            if(is_numeric($id)) {
              $table_rating_factors = $this->db->prefix . 'rating_factors';
              $this->db->query("UPDATE $table_rating_factors SET factor = '" . $entity_factor . "', enable_factor = 1 WHERE id =$id");

              $url = admin_url().'options-general.php?page=rating_options&tab=rating_factors';
              $this->set_message(array('status' => TRUE, 'message' => __('Rating Parameter updated Successfully')));
              wp_redirect($url  . '&update=true');
            }
            else {
              $this->set_message(array('status' => TRUE, 'message' => __('Invalid Rating Parameter Entry')));
            }
          }
          break;

        case 'assign_entity_factors' :
          $table_entity_rating_factors = $this->db->prefix . 'entity_rating_factors';
          $this->db->query("UPDATE $table_entity_rating_factors SET factor = '" . $entity_factor . "' WHERE id =$id");

          if (isset($_GET['edit_factor_id'])) {
            $str = explode('&edit_factor_id', $_SERVER["REQUEST_URI"]);
            $url = $str[0];
          }
          $this->set_message(array('status' => TRUE, 'message' => __('Rating Parameter updated Successfully')));
          wp_redirect($url);

        case 'rating_theme_settings_key':
          $themeOptions = array(
          'theme_name' => $_POST['theme_name'],
          'star_disp_count' => $_POST['star_disp_count']
          );
          update_option('rating_theme_settings', $themeOptions);
          $this->set_message(array('status' => TRUE, 'message' => __('Theme Settings Updated Successfully')));
          header("location: " .$_SERVER['HTTP_REFERER']);
          break;

        case 'rating_advanced_settings':
          $star_count = isset($_POST['star_disp_count'])? $_POST['star_disp_count'] : 5;
          if(!is_numeric($star_count)) {
            $this->set_message(array('status' => TRUE, 'message' => __('Please Enter Rating Scale in Numeric format')));
            wp_redirect($_SERVER["REQUEST_URI"] . '&update=true');
            break;
          }
          if(is_numeric($star_count) && $star_count < 2){
            $this->set_message(array('status' => TRUE, 'message' => __('Please Enter Rating Scale Count Greater Then 1')));
            break;
          }
          $advancedOptions = array('theme_name' => $_POST['theme_name'],
              'star_disp_count' => $star_count,
              'demographic_mode' => isset($_POST['demographic_mode']) ? $_POST['demographic_mode'] : 0,
              'enable_share' => isset($_POST['enable_share']) ? $_POST['enable_share'] : 0,
              'share_text' => isset($_POST['share_text']) ? "'". $_POST['share_text']."'" : "This Post Has Rating Score of %d");

          update_option('advanced_rating_settings', $advancedOptions);
          $this->set_message(array('status' => TRUE, 'message' => __('Settings Updated Successfully')));
          wp_redirect($_SERVER["REQUEST_URI"] . '&update=true');
          break;

        default:
          break;
      }
    }
  }

  /**
   * Metod to update admin configuration for rating plugin
   */
  public function delete_rating_options($page, $id = '') {
    switch ($page) {
      case 'rating_settings':
        break;

      case 'rating_factors':
        $table_rating_factors = $this->db->prefix . 'rating_factors';
        $table_rating_meta = $this->db->prefix . 'rating_meta';
        $table_entity_rating_factors = $this->db->prefix . 'entity_rating_factors';
        $this->db->query("UPDATE $table_rating_factors SET enable_factor = 0 WHERE id = $id");

        $this->db->query("DELETE FROM $table_rating_meta WHERE factor_id = $id" );

        $this->db->query("DELETE FROM $table_entity_rating_factors WHERE factor_id = $id");


        if (isset($_GET['delete_factor_id'])) {
          $str = explode('&delete_factor_id', $_SERVER["REQUEST_URI"]);
          $url = $str[0];
        }
        $this->set_message(array('status' => TRUE, 'message' => __('Factor deleted Successfully')));
        wp_redirect($url . '&delete=true');
        break;

      case 'rating_theme_settings_key':
        break;

      case 'assign_entity_factors' :
        $table_entity_rating_factors = $this->db->prefix . 'entity_rating_factors';
        $this->db->query("DELETE FROM $table_entity_rating_factors WHERE entity_id = $id");
        break;

      default:
        break;
    }
  }

  /**
   * Method to save rating scores
   */
  public function set_rating($rating) {
    $geo_location_data =array();  
    if (file_exists(plugin_dir_path(__FILE__) . 'GeoLiteCity.dat') || file_exists(get_theme_root() . '/library/GeoLiteCity.dat')) {
      $geo_location_data = $this->get_geolocation($rating['ip']);
      $geo_location_data['country'] = trim($geo_location_data['country']);
      $geo_location_data['state'] = trim($geo_location_data['state']);
      // If no country from ip
      if(isset($geo_location_data['country']) && $geo_location_data['country'] == '') {
        $geo_location_data['country'] = 'US';
        $geo_location_data['state'] = 'NY';
      }
    }
    
    $rating_options = get_option('advanced_rating_settings');
    
    // getting rating factors
    $rating_scale = $this->get_rating_scale($rating['entity_type_name']);
    $rating_scale = isset($rating_scale->rating_scale)?$rating_scale->rating_scale:(isset($rating_options['star_disp_count'])?$rating_options['star_disp_count']:5);
    $star_count = $rating_scale;
    //todo: Changed
    $rating['rating_score'] = ($rating['rating_score']/$star_count)*100;

    $is_multiple_rated = $this->db->get_row("Select entity_factors_mode from " . ($this->db->prefix . 'admin_rating_config') . " WHERE entity_type_name = '$rating[entity_type_name]'");
    if($is_multiple_rated->entity_factors_mode == 1) {
      $is_already_rated = $this->db->get_row("Select count(user_id) as cnt from " . ($this->db->prefix . 'rating_meta') . " WHERE user_id='$rating[user_id]' AND entity_type_name ='$rating[entity_type_name]' AND entity_content_id = '$rating[entity_content_id]' AND factor_name = '$rating[factor_name]'" );
    }
    else {
      $is_already_rated = $this->db->get_row("Select count(user_id) as cnt from " . ($this->db->prefix . 'rating_meta') . " WHERE user_id='$rating[user_id]' AND entity_type_name ='$rating[entity_type_name]' AND entity_content_id = '$rating[entity_content_id]'" );
    }

    if ($is_already_rated->cnt > 0) {
     // return;
    }

    $this->db->insert($this->db->prefix . 'rating_meta', array(
        'entity_content_id' => $rating['entity_content_id'],
        'user_id' => $rating['user_id'],
        'entity_type_name' => $rating['entity_type_name'],
        'factor_name' => $rating['factor_name'],
        'rating_score' => $rating['rating_score'],
        'rating_stars_count' => $star_count,
        'rating_weight' => isset($rating['rating_weight']) ? $rating['rating_weight'] : 0,
        'ip' => $rating['ip'],
        'country' => isset($geo_location_data['country']) ? $geo_location_data['country'] : '',
        'state' => isset($geo_location_data['state']) ? $geo_location_data['state'] : ''), array('%d', '%d', '%s', '%s', '%d', '%d', '%d', '%s', '%s', '%s')
    );

    $this->db->insert($this->db->prefix . 'rating_scores', array(
        'entity_content_id' => $rating['entity_content_id'],
        'entity_type_name' => $rating['entity_type_name'],
        'factor_name' => $rating['factor_name'],
        'rating_score' => $rating['rating_score'],
        'country' => isset($geo_location_data['country']) ? $geo_location_data['country'] : '',
        'state' => isset($geo_location_data['state']) ? $geo_location_data['state'] : ''), array('%d', '%s', '%s', '%d', '%s', '%s')
    );
  }

  /**
   * Method to set message after save/update/delete option
   * @param string $value
   */
  public function set_message($value) {
    $_SESSION['rating_message'] = $value;
  }

  /**
   * Method to fetch value from table
   * @param string $table
   * @param string $str
   * @param string $cond
   */
  public function get_table_values($table, $str, $cond = '') {
    $sql = $this->db->get_results(
        " SELECT $str
        FROM $table $cond
        "
    );

    return $sql;
  }

  /**
   * Method to find geo location using ip
   * @param string $ip
   * @return string
   */
  public function get_geolocation($ip) {
    include_once('geoip.inc');
    include_once("geoipcity.inc");
    include_once("geoipregionvars.php");
    $gi = array();
    
    if (file_exists(plugin_dir_path(__FILE__) . 'GeoLiteCity.dat') || file_exists(get_theme_root() . '/library/GeoLiteCity.dat')) {
      if(file_exists(get_theme_root() . '/library/GeoLiteCity.dat')) 
      {
        $gi = geoip_open(get_theme_root() . '/library/GeoLiteCity.dat', GEOIP_STANDARD);
      }
      elseif(file_exists(plugin_dir_path(__FILE__) . 'GeoLiteCity.dat')) 
      {
        $gi = geoip_open(plugin_dir_path(__FILE__) . 'GeoLiteCity.dat', GEOIP_STANDARD);
      }
    }

    $record = geoip_record_by_addr($gi, $ip);
    $country = isset($record->country_code) ? $record->country_code : '';
    $region = isset($record->region) ? $record->region : '';
    geoip_close($gi);

    $data = array('country' => $country, 'state' => $region);
    return $data;
  }

  /**
   * Method to find country name
   * @param string $country_code
   * @return string country name
   */
  function get_country($country_code) {
    include_once('geoip.inc');
    include_once("geoipcity.inc");
    include_once("geoipregionvars.php");

    $gi = array();
    
    if (file_exists(plugin_dir_path(__FILE__) . 'GeoLiteCity.dat') || file_exists(get_theme_root() . '/library/GeoLiteCity.dat')) {
      if(file_exists(get_theme_root() . '/library/GeoLiteCity.dat')) 
      {
        $gi = geoip_open(get_theme_root() . '/library/GeoLiteCity.dat', GEOIP_STANDARD);
      }
      elseif(file_exists(plugin_dir_path(__FILE__) . 'GeoLiteCity.dat')) 
      {
        $gi = geoip_open(plugin_dir_path(__FILE__) . 'GeoLiteCity.dat', GEOIP_STANDARD);
      }
    }
    
    $country_code = trim($country_code);
    $count = strlen($country_code);
    $country_id = ($count==2) ? $gi->GEOIP_COUNTRY_CODE_TO_NUMBER[$country_code] : $gi->GEOIP_COUNTRY_CODE_TO_NUMBER[$country_code];
    $country_name = $gi->GEOIP_COUNTRY_NAMES[$country_id];
    return $country_name;
  }


  /**
   * Function to return entity_id by entity_name.
   * @param string $name
   * @return string Name of entity.
   */
  public function get_entity_id_by_name($name='') {
    $result = $this->db->get_row("SELECT id FROM " . $this->db->prefix . "admin_rating_config WHERE entity_type_name = '$name'");
    return $result;
  }
  
  /**
   * Function to return post type information based on entity id
   * @param integer $id
   * @return string of post type
   */
  public function get_revision_parent_type($id) {
    $result = $this->db->get_row("SELECT post_type FROM " . $this->db->prefix . "_posts WHERE post_type='revision' AND post_parent = $id");
    return $result;
  }

  /**
   * Function to returns factors info based on entity id
   * @param integer $id
   * @return array of factors
   */
  public function _get_factors_by_entity_id($id) {
    $result = $this->db->get_results("SELECT * from " . $this->db->prefix . "entity_rating_factors WHERE entity_id ='$id' order by id ASC");
    return $result;
  }
  
  /**
   * Function to get factors name based on entity type
   * @param string $type
   * @return string theme name
   */
  private function _get_factors_by_entity($type) {
    $result = $this->db->get_row("SELECT entity_factors_mode as is_entity FROM ".$this->db->prefix."admin_rating_config WHERE entity_type_name='$type'");
    return $result;
  }
  
  /**
   * Function to get theme name based on entity type
   * @param string $type
   * @return string theme name
   */
  private function _get_theme_by_entity_type($type) {
    $result = $this->db->get_row("SELECT theme FROM " . $this->db->prefix . "admin_rating_config WHERE entity_type_name = '$type'");
    return $result->theme;
  }
  
  /**
   * Function returns array of anlytics result based on demography
   */
  public function get_demographic_information($entity) {
    $id = $entity->ID;
    $result = $this->db->get_results("SELECT country, state, count(id) as votes from " . $this->db->prefix . "_rating_scores WHERE entity_content_id='$id' GROUP BY country, state LIMIT 0, 10");
    $output = array();
    foreach ($result as $row) {
      $output[] = array('country' => $row->country, 'state' => $row->state, 'votes' => $row->votes);
    }
    return $output;
  }
  
  /**
   * Method to get most rated content information
   */
  public function getMostRatedContent($entity) {
    $id = $entity->ID;
    $result = $this->db->get_row("SELECT entity_content_id as id, avg( rating_score ) 'rating'
        FROM ".$this->db->prefix."rating_scores
        WHERE entity_type_name='".$entity->post_type."'
        GROUP BY entity_content_id
        ORDER BY rating DESC
        LIMIT 0 , 1");

    if ($id == $result->id) {
      return true;
    }
    else {
      return false;
    }
  }
  
  /**
   * This method would fetch the star count of a particular entity type. 
   */
  public function get_rating_scale($type) {
    
    $result = $this->db->get_row("SELECT rating_scale 
        FROM ".$this->db->prefix."admin_rating_config
        WHERE entity_type_name='".$type."'
        ");
    
    return $result;
  }
}