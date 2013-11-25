<?php
/**
 * File contains form for assign theme form
 */

/**
 * Function called from ajax for rating submission
 * @global array $current_user
 */
function ajax_rating_submission() {
  get_currentuserinfo();
  global $current_user;

  $db = new DataAccess();
  $params = $_REQUEST;

  switch($params['request']) {
    case 'save_rating':
      $request = array(
      'entity_content_id' => $params['content_id'], 
      'user_id' => $current_user->data->ID,
      'entity_type_name' => $params['entity_type'],
      'factor_name' => isset($params['factor_name']) ? $params['factor_name']: '', //changed
      'rating_score' => $params['score'],
      'ip' => $_SERVER['REMOTE_ADDR'],  
      'country' => '',
      'state' => ''
      );

      $db->set_rating($request);

      if ($params['entity_type'] != 'comment') {
        $entity = get_post($params['content_id']);
      }
      else {
        $entity = get_comment($params['content_id']);
      }
      $rating = new Rating();
      $output = $rating->generateRatingBox($entity);
      break;

  }

  print $output;exit;
}
// action using wordpress ajax callbacks
add_action('wp_ajax_ajax_rating_submission', 'ajax_rating_submission');
add_action('wp_ajax_nopriv_ajax_rating_submission', 'ajax_rating_submission');

add_action('wp_ajax_ajax_theme_assign', 'ajax_theme_assign');
add_action('wp_ajax_nopriv_ajax_theme_assign', 'ajax_theme_assign');

add_action('wp_ajax_ajax_factor_assign', 'ajax_factor_assign');
add_action('wp_ajax_nopriv_ajax_factor_assign', 'ajax_factor_assign');


/**
 * Function for creating form for assigin rating factors
 * @global array $wpdb
 */
function ajax_factor_assign() {
  $data_access = new DataAccess();
  global $wpdb;
 ?>
 <a href ="#" class="close"></a>

<?php
if (isset($_REQUEST['entity_type'])) {
  $cond = "'" . trim($_REQUEST['entity_type']) . "'";
  // check for entity type existance
  $sql_entity_config = array();
  $sql_entity_config = $data_access->get_table_values($wpdb->prefix . 'admin_rating_config', ' * ', " WHERE entity_type_name = $cond ");
  if(count($sql_entity_config) == 0) {
      $wpdb->insert($wpdb->prefix . 'admin_rating_config', array('entity_type_name' => $_REQUEST['entity_type'],
                'most_rated_flag' => 0,
                'entity_factors_mode' => 1,
                'entity_enable' => 0,
                'theme' => 'default'), array('%s', '%d', '%d', '%d', '%s')
        );  
    }
  $sql_entity_config = $data_access->get_table_values($wpdb->prefix . 'admin_rating_config', ' * ', " WHERE entity_type_name = $cond ");
  $sql_entity_factors = array();
  $sql_entity_factors = $data_access->get_table_values($wpdb->prefix . 'rating_factors', ' * ', ' WHERE enable_factor = 1 ');

  foreach ($sql_entity_config as $count_con) {
    $count_arr[] = $count_con->id;
  }
  foreach ($sql_entity_factors as $count_factor) {
    $count_fac_arr[] = $count_factor->id;
  }

  if (isset($count_fac_arr) && count($count_fac_arr) >= 1 && count($count_arr) >= 1) {

    $count_arr = array();

    $sql_fact_mode = array();
    $cond = " WHERE entity_factors_mode = 1 ";
    $sql_fact_mode = $data_access->get_table_values($wpdb->prefix . 'admin_rating_config', ' id ', $cond);

    $fact_mode_array = array();
    foreach ($sql_fact_mode as $fact) {
      $fact_mode_array[] = $fact->id;
    }
    foreach ($sql_entity_config as $count_con) {
      if ($count_con->entity_factors_mode == 1) {
        $count_arr[] = $count_con->id;
      }
    }
    foreach ($sql_entity_factors as $count_factor) {
      $count_fac_arr[] = $count_factor->id;
    }
    
    //check for rating factor and rating configuration for factor assignment
    $con_count = count($count_arr);
    $con_fact = count($count_fac_arr);
    ?>
    
    <?php
    // Form for rating factors assigment appears in popup
    if (count($count_arr) >= 1) { ?>
      <form method="post" name="entity_factors" action="<?php print admin_url() . 'options-general.php?page=rating_options&tab=rating_settings'?>">
        <?php
        wp_nonce_field('update-options');

        if (isset($_POST['option_page']) && $_POST['option_page'] == 'assign_entity_factors') {
          $data_access->save_rating_options();
          unset($_POST);
        }
        ?>
        <?php if (isset($_SESSION['rating_message'])) { ?>
          <div class="updated" id="message"><?php print $_SESSION['rating_message']['message'] ?></div>
          <?php
        }
        if(isset($_SESSION['rating_message'])) {
          unset($_SESSION['rating_message']);
        }
        ?>
        <table class="table_class_70_med">
          <input type="hidden" name="option_page" value="assign_entity_factors">
          <tr class="tr_class">
            <td class="td_class">Post Type</td>
            <td class="td_class">Rating Parameter</td>
          </tr>
          <?php
          if ($con_fact >= 1 && $con_count >= 1) {
            foreach ($sql_entity_config as $val) {
              if (in_array($val->id, $fact_mode_array)) {
                ?> 
                <tr class="tr_class_nb">
                  <td class="td_class_label">
                    <label><?php print $val->entity_type_name; ?></label>
                  </td>        
                  <td class="td_class factors_checkbox"> 
                    <input type="hidden" name="entity_type_name" value="<?php echo $val->id; ?>">
                    <div>
                    <?php
                    foreach ($sql_entity_factors as $value) {
                      $name_fac = "'" . $value->factor . "'";
                      $sql_assigned_factor = $data_access->get_table_values($wpdb->prefix . 'entity_rating_factors', ' factor_name, weight ', "WHERE entity_id = $val->id AND factor_name = $name_fac");
                      foreach ($sql_assigned_factor as $assigned_val) {
                        $value_assigned = $assigned_val->factor_name;
                      }
                      ?>
                      <div class="factors_checkbox">    
                        <input type="checkbox" name="<?php print 'assign_fact--' . $val->id; ?>[]" value="<?php print $value->factor; ?>" <?php print (isset($value_assigned) && $value_assigned == $value->factor) ? ' checked="checked"' : ''  ?>><?php print $value->factor; ?>
                      </div>
                      <?php
                      unset($value_assigned);
                    }
                    ?>
                    </div>
                  </td>    
                </tr>
                <?php
              }
            }
          }
          ?><br>
        </table>    
        <p class="submit"><input type="submit" value="Save Changes" class="button button-primary" id="submit" name="submit"></p>

        <?php
        $url = admin_url() . 'options-general.php?page=rating_options&tab=rating_settings';
        ?>
        <p class="cancel_btn"><a href="<?php print $url; ?>"><input type="button" value="Cancel" class="button button-primary" id="submit" name="submit"></a></p>
      </form>
      <?php
    }
  }
  else {
      $url = admin_url() . 'options-general.php?page=rating_options&tab=rating_factors';?>
    <p><?php print "Please add some Rating Parameters using link "; ?><a href="<?php print $url; ?>"><?php print "Rating Parameters Configuration" ?></a></p>
    <?php
  }
}
die;
}



/**
 * Function for form creation for theme setting and rating parameter
 * @global array $wpdb
 */
function ajax_theme_assign() {
global $wpdb;

$data_access = new DataAccess();
$count_arr = array();
 if(isset($_REQUEST['entity_type_theme'])){           
    $cond_theme = "'" . trim($_REQUEST['entity_type_theme']) . "'";

    $sql_entity_theme = array(); 
    $sql_entity_theme = $data_access->get_table_values($wpdb->prefix . 'admin_rating_config', ' * ', " WHERE entity_type_name = $cond_theme ");
    if(count($sql_entity_theme) == 0) {
      $wpdb->insert($wpdb->prefix . 'admin_rating_config', array('entity_type_name' => $_REQUEST['entity_type_theme'],
                'most_rated_flag' => 0,
                'entity_factors_mode' => 1,
                'entity_enable' => 0,
                'theme' => 'default', 'rating_scale' => 5), array('%s', '%d', '%d', '%d', '%s', '%d')
        );  
    }
    $sql_entity_theme = $data_access->get_table_values($wpdb->prefix . 'admin_rating_config', ' * ', " WHERE entity_type_name = $cond_theme ");
    foreach($sql_entity_theme as $count_con) {
      $count_arr[] = $count_con->id;
    }
    
    if(count($count_arr) >=1) {
      $dirs = glob(__DIR__ . DIRECTORY_SEPARATOR . 'images' . DIRECTORY_SEPARATOR . 'themes' . DIRECTORY_SEPARATOR . '*');  
      $i = 0;
      foreach($dirs as $value) {
        $directory_temp = explode(DIRECTORY_SEPARATOR, $value);
        $revparts = array_reverse($directory_temp);   
        $directory[$i++] = $revparts[0];
      } ?>
    <script>
      jQuery('#entity_theme_form').submit(function() {
          if (jQuery('#rating_scale').val().length < 1) {
            jQuery('#error').html('Rating Scale Can Not Be Blank').show();
            return false;
          }
          if (jQuery('#rating_scale').val()< 2) {
            jQuery('#error').html('Please Enter Rating Scale Greater Than 1').show();
            return false;
          }
          if (jQuery.isNumeric(jQuery('#rating_scale').val()) == false) {
            jQuery('#error').html('Please Enter Numeric Value for Rating Scale').show();
            return false;
          }
      });
    </script>
      <?php  // Form for theme and rating scale settings appears in popup ?>
      <a class="close"></a>  
      <form method="post" id="entity_theme_form" name="entity_theme" action="<?php print admin_url() . 'options-general.php?page=rating_options&tab=rating_settings'?>">
        <?php wp_nonce_field('update-options'); ?>
        <input type="hidden" name="option_page" value="entity_theme_settings">
      <?php if(isset($_SESSION['rating_message'])) { ?>
        <div class="updated" id="message"><?php print $_SESSION['rating_message']['message'] ?></div>
      <?php }
      if(isset($_SESSION['rating_message'])) {
       unset($_SESSION['rating_message']);
      }
      ?><div id="error"></div>
        <table class="table_class_70_med">   
          <tr class="tr_class">
            <td class="td_class">Entity Name</td>
            <td class="td_class">Theme</td>
          </tr>
       <?php
         foreach($sql_entity_theme as $value) {
           ?> 
              <tr class="tr_class_nb">
              <td class="td_class_label">
                <input type="hidden" name="entity_type_name" value="<?php print $value->entity_type_name; ?>"> 
                <p><?php print $value->entity_type_name; ?></p></td>        
                <td class="td_class factors_checkbox">
                  <div>
                  <?php 
                  $theme_name = $value->theme;
                   if(!isset($theme_name)) {
                     $theme_name = 'default';
                    }
                    if(isset($directory) && !empty($directory)) {
                      foreach($directory as $key => $val) {?>
                        <?php $path = plugins_url("images/themes/$val/active.png", __FILE__); ?>
                         <p><input type="radio" class ="star_theme" name="theme" class="factors_checkbox" value="<?php print $val; ?>" <?php print ($theme_name != '' && $theme_name == $val) ? ' checked="checked"':'' ?>>
                        <?php
                       for($i=1; $i<=3; $i++) { ?>
                        <img src="<?php print $path; ?>">
                      <?php  }?></p>
                      <?php }          
                    }
                   ?>
                  </div>
                </td> 
                </tr>
          <?php
           } 
            ?>
            
					<tr class="tr_class_nb">
            <td class="td_class">Rating Scale</td>
            <td class="td_class_label"><input type='text' id='rating_scale' name='rating_scale' size=3 value="<?php print $value->rating_scale;?>"/><br><small><?php print __('Count of stars/images');?></small></td>
          </tr>
           </table>    
         <p class="submit"><input type="submit" value="Save Changes" class="button button-primary" id="submit" name="submit"></p>
        </form>
        <?php
          $url = admin_url().'options-general.php?page=rating_options&tab=rating_settings';
        ?>
        <p class="cancel_btn"><a href="<?php print $url; ?>"><input type="button" value="Cancel" class="button button-primary" id="submit" name="submit"></a></p>
      <?php } 
      }
      die;
}
