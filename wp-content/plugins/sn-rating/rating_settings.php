<?php

/**
 * The main plugin class, holds everything this plugin does,
 * initialized right after declaration
 * @author paritosh gautam
 */
class RatingSettings {
  /**
   * For easier overriding we declared the keys
   * here as well as our tabs array which is populated
   * when registering settings
   */
  private $ratings_general_settings_key = 'rating_settings';
  private $ratings_entity_factors_key = 'rating_factors';
  private $assign_entity_factors_key = 'assign_entity_factors';
  private $rating_theme_settings_key = 'rating_theme_settings_key';
  private $advanced_settings_key = 'rating_advanced_settings';
  private $plugin_options_key = 'rating_options';
  private $plugin_settings_tabs = array();
  public $demo_graphic_enable = FALSE;
  public $dat_file_msg = '';
  
  /**
   * Fired during plugins_loaded (very very early),
   * so don't miss-use this, only actions and filters,
   * current ones speak for themselves.
   */
  function __construct() {
    global $wpdb;
    $this->db = $wpdb;
    add_action('init', array(&$this, 'load_settings'));
    add_action('admin_init', array(&$this, 'register_general_settings'));
    add_action('admin_init', array(&$this, 'register_entity_factors'));
    add_action('admin_init', array(&$this, 'register_advanced_settings'));
    add_action('admin_menu', array(&$this, 'add_admin_menus'));
    @wp_enqueue_style('sn_rating', plugins_url('/css/admin-styles.css', __FILE__));
    @wp_enqueue_style('sn_rating', plugins_url('/css/colorbox.css', __FILE__));
    @wp_enqueue_script('sn_rating', plugins_url('/scripts/admin-rating.js', __FILE__), array("jquery"), '', true);
    @wp_enqueue_script('sn_rating', plugins_url('/scripts/jquery.tools.min.js', __FILE__), array("jquery"), '', true);
    
    
    if (file_exists(plugin_dir_path(__FILE__) . 'GeoLiteCity.dat') || file_exists(get_theme_root() . '/library/GeoLiteCity.dat')) {
      $this->demo_graphic_enable = TRUE;
    } else {
      $this->demo_graphic_enable = FALSE;
      $this->dat_file_msg = __("To enable Rating Analytics and location tracking for rating please create folder 'library' under 'wp-content/themes' and download <b><a href='http://geolite.maxmind.com/download/geoip/database/GeoLiteCity.dat.gz'>Gzip File</a></b> and extract in 'library' folder.");
    }
  }

  /**
   * Loads all settings from the database into their respective arrays.
   */
  function load_settings() {
    $this->general_settings = (array) get_option($this->ratings_general_settings_key);
    $this->entity_factors_settings = (array) get_option($this->ratings_entity_factors_key);
    $this->assign_factors = (array) get_option($this->assign_entity_factors_key);
    $this->rating_theme = (array) get_option($this->rating_theme_settings_key);
    $this->advanced_settings = (array) get_option($this->advanced_settings_key);

    // Merge with defaults
    $this->general_settings = array_merge(array(
        'general_option' => 'General value'
            ), $this->general_settings);

    $this->entity_factors_settings = array_merge(
            array('add_factors' => 'Factor Value'
            ), $this->entity_factors_settings);

    $this->assign_factors = array_merge(
            array('assign_factor_settings' => 'Assign Factors'
            ), $this->assign_factors);

    $this->rating_theme = array_merge(
            array('theme_settings' => 'Rating Theme'
            ), $this->rating_theme);


    $this->advanced_settings = array_merge(array(
        'advanced_option' => 'Advanced value'
            ), $this->advanced_settings);
  }

  /**
   * Ratings General settings operations
   */
  function register_general_settings() {
    $this->plugin_settings_tabs[$this->ratings_general_settings_key] = __('Rating Configuration');
    add_settings_field('general_option', '', array(&$this, 'field_general_option'), $this->ratings_general_settings_key, 'section_general');
  }

  /**
   * Entity factors tab operations
   */
  function register_entity_factors() {
    $this->plugin_settings_tabs[$this->ratings_entity_factors_key] = __('Rating Parameters Configuration');
    add_settings_field('add_factors', '', array(&$this, 'field_entity_factors'), $this->ratings_entity_factors_key, 'section_general');
  }

  /**
   * Rating theme settings operations
   */
  function rating_theme_settings() {
    $this->plugin_settings_tabs[$this->rating_theme_settings_key] = __('Theme Settings');
    add_settings_field('theme_settings', '', array(&$this, 'field_theme_settings'), $this->rating_theme_settings_key, 'section_general');
  }

  /**
   * Assigning Entity facator operations 
   */
  function assign_entity_factors() {
    $this->plugin_settings_tabs[$this->assign_entity_factors_key] = __('Assign Factors');
    add_settings_field('assign_factor_settings', '', array(&$this, 'field_assign_factor_settings'), $this->assign_entity_factors_key, 'section_general');
  }

  /**
   * Registers the advanced settings operations
   */
  function register_advanced_settings() {
    $this->plugin_settings_tabs[$this->advanced_settings_key] = __('Analytics & Sharing Configuration');
    add_settings_field('advanced_option', '', array(&$this, 'field_advanced_option'), $this->advanced_settings_key, 'section_advanced');
  }

  /**
   * General Option field callback, renders a
   * text input, note the name and value.
   */
  function field_general_option() {
    $data_access = new DataAccess();
    
    if (isset($_POST['option_page']) && $_POST['option_page'] == 'rating_settings') {
      $data_access->save_rating_options();
      unset($_POST);
    }

    if (isset($_POST['option_page']) && $_POST['option_page'] == 'assign_entity_factors') {
      $data_access->save_rating_options();
      unset($_POST);
    }

    if (isset($_POST['option_page']) && $_POST['option_page'] == 'entity_theme_settings') {
      $data_access->update_rating_options();
      unset($_POST);
    }

    $sql_get_post_type = $data_access->get_table_values($this->db->prefix . 'posts', " post_type ", 'GROUP BY post_type');
    foreach ($sql_get_post_type as $type) {
      $post_type[$type->post_type] = $type->post_type;
      $post_type['comment'] = 'comment';
    }
    $ar_custom_post_types = get_post_types(array( 'public' => true, '_builtin' => false ));
  
    if(count($ar_custom_post_types)> 0) {
     $post_type = array_merge($post_type, $ar_custom_post_types);
    }
    
    $tab = isset($_GET['tab']) ? $_GET['tab'] : $this->ratings_general_settings_key;

    $dirs = array_filter(glob(__DIR__ . '\images\themes' . '/*'), 'is_dir');
    $i = 0;
    foreach ($dirs as $value) {
      $directory_temp = explode('/', $value);
      $directory[$i++] = $directory_temp[1];
    }

    // Message for demographic file presence
    if ($this->demo_graphic_enable == FALSE) { ?>
     <div class="update-nag"><?php print $this->dat_file_msg; ?></div>
     <?php
    }  
    
   // Form for Manage Rating  under options page
    ?>
    <div class="wrap">
      <h2> Manage Rating </h2>
      <div class="mainform">
        <form method="post" action="#">
          <?php wp_nonce_field('update-options'); ?>
          <?php settings_fields($tab); ?>

          <?php if (isset($_SESSION['rating_message'])) { ?>
            <div class="updated" id="message"><?php print $_SESSION['rating_message']['message'] ?></div>
    <?php
    }
    if(isset($_SESSION['rating_message'])) {
      unset($_SESSION['rating_message']);
    }
    ?>

          <table class="table_class_70">
            <tr class="tr_class">
              <td class="td_class"><?php print __('Enable Rating'); ?></td>
              <td class="td_class"><?php print __('Post Type'); ?></td>
              <td class="td_class"><?php print __('Enable Most Rated Flag'); ?></td>  
              <td class="td_class"><?php print __('Actions'); ?></td>
            </tr>

            <?php
            $i = 1;
            $exclude_types = array('revision', 'nav_menu_item');
            foreach ($post_type as $type_option) {
              if (in_array($type_option, $exclude_types)) {
                continue;
              }

              $str_entity_config = " * ";
              $cond = " WHERE entity_type_name =" . "'" . $type_option . "'";
              $sql_entity_config = $data_access->get_table_values($this->db->prefix . 'admin_rating_config', $str_entity_config, $cond);
              foreach ($sql_entity_config as $value) {
                $entity_id = $value->id;
                $entity_type_name = $value->entity_type_name;
                $most_rated_flag = $value->most_rated_flag;
                $entity_factors_mode = $value->entity_factors_mode;
                $entity_enable = $value->entity_enable;
                $theme_name = $value->theme;
              }
              $advancedOptions = get_option('advanced_rating_settings');
              ?>
              <tr>
                <td class="td_class factors_checkbox">
                  <input type="checkbox" name="<?php print 'entity_enable[' . $type_option . ']'; ?>" value="1" <?php print (isset($entity_enable) && $entity_enable == 1) ? ' checked="checked"' : ''  ?>/>
                </td>
                <td class="td_class">
                  <p><?php
              if (!isset($theme_name)) {
                $theme_name = $advancedOptions['theme_name'];
              }
              if (!isset($theme_name)) {
                $theme_name = 'default';
              }
              $path = plugins_url("images/themes/$theme_name/active.png", __FILE__);
              for ($j = 1; $j <= 3; $j++) {
                ?>
                      <img src="<?php print $path; ?>"/>
      <?php } ?>
                  </p>
                  <p><?php print $type_option === 'attachment' && class_Exists('BuddyPressMedia') ? 'BuddyPress Media' : $type_option; ?></p>
                  <input type="hidden" name="entity_type[]" value="<?php print $type_option ?>">
                </td>        
                <td class="td_class factors_checkbox">
                  <input type="checkbox" name="<?php print 'most_rated_flag[' . $type_option . ']'; ?>" value="1" <?php print (isset($most_rated_flag) && $most_rated_flag == 1 ) ? ' checked="checked"' : ''  ?> <?php if (!strcasecmp($type_option, "comment")): print " disabled='disabled'";
            endif; ?>/></td>

                <td class="td_class factors_checkbox">      
                  <?php if (isset($value->theme) && $value->theme != '') { ?> 
                    <a class="callbacks_theme" id="<?php print $type_option ?>" href="#" rel="#manage_forms"><?php print __('Edit Theme'); ?></a>
                  <?php
                  } else {
                    print __('Edit Theme');
                  }
                  ?>
                <?php
                print ' | ';
                $url = admin_url() . 'options-general.php?page=rating_options&tab=rating_settings&entity_type=' . $type_option;
                ?>
              <?php if (isset($entity_factors_mode) && $entity_factors_mode == 1) { ?>
                    <a class="callbacks_factors" id="<?php print $type_option ?>" rel="#manage_forms" href="#"><?php print __('Assign Rating Parameters'); ?></a></td>
      <?php } else {
        ?>
        <?php print __('Assign Rating Parameters'); ?>
      <?php } ?>
              </tr>
      <?php }
    ?>
          </table>
          <p class="submit"><input type="submit" value="Save Changes" class="button button-primary" id="submit" name="submit"></p>
        </form>
      </div>
      <div id="manage_forms" class="simple_overlay"></div>
    </div>  
    <?php
  }

  /**
   * General Option field callback, renders a
   * text input, note the name and value.
   */
  function field_entity_factors() {
    $data_access = new DataAccess();
    if (isset($_POST) && !isset($_GET['edit_factor_id']) && !isset($_GET['delete_factor_id'])) {
      $data_access->save_rating_options();
      unset($_POST);
    }

    if (isset($_POST) && isset($_GET['edit_factor_id'])) {
      $data_access->update_rating_options($_GET['edit_factor_id']);
      unset($_POST);
    }

    if (isset($_GET['delete_factor_id'])) {
      $data_access->delete_rating_options('rating_factors', $_GET['delete_factor_id']);
    }

    if (isset($_GET['edit_factor_id'])) {
      $str_entity_factor = " * ";
      $cond = " WHERE id=" . $_GET['edit_factor_id'];
      $sql_entity_factor = $data_access->get_table_values($this->db->prefix . 'rating_factors', $str_entity_factor, $cond);
      foreach ($sql_entity_factor as $val) {
        $factor_name = $val->factor;
      }
    }


    // Message for demographic file presence
    if ($this->demo_graphic_enable == FALSE) { ?>
     <div class="update-nag"><?php print $this->dat_file_msg; ?></div>
     <?php
    }

    if (isset($_GET['update']) && $_GET['update'] == 'true') {
      $data_access->set_message(array('status' => TRUE, 'message' => __('Rating Parameter updated Successfully')));
    }

    if (isset($_GET['delete']) && $_GET['delete'] == 'true') {
      $data_access->set_message(array('status' => TRUE, 'message' => __('Rating Parameter deleted Successfully')));
    }

    if (isset($_SESSION['rating_message'])) {
      ?>
      <div class="updated" id="message"><?php print $_SESSION['rating_message']['message'] ?></div>
          <?php
          }
          if(isset($_SESSION['rating_message'])) {
            unset($_SESSION['rating_message']); 
          }
          ?>
    <div class="wrap">
      <div class="form1">
        <form method="post" name="entity_factors" action="#">
          <?php wp_nonce_field('update-options');
          if (isset($_SESSION['rating_message'])) { ?>
            <div class="updated" id="message"><?php print $_SESSION['rating_message']['message'] ?></div>
          <?php
          }
          
          if(isset($_SESSION['rating_message'])) {
            unset($_SESSION['rating_message']);
          }
          ?>
          <table> 
            <tr>
            <?php $operation = isset($_GET['edit_factor_id']) ? 'Edit ' : 'Add '; ?>        
              <td><label style="font-weight: bold;"><?php print $operation; ?> Rating Parameter:</label></td>
              <td><input type="text" name="factor" <?php print (isset($factor_name)) ? ' value=' . $factor_name : ''; ?>>
                <input type="hidden" name="option_page" value="rating_factors">
              </td>
            </tr>
          </table>
            
          <div class="add_factor_submit">
            <p class="submit"><input type="submit" value="Save Changes" class="button button-primary" id="submit" name="submit"></p>
          </div>
          <tr>
            <td></td>
            <td><p class="description">Add/Edit rating parameter</p></td>
          </tr>
        </form>
        <?php
        print $this->section_add_factors_desc();
        ?> 
      </div>
    </div>
    <?php
  }

  /**
   * Function to create factors table with edit/delete operation
   */
  function section_add_factors_desc() {
    $data_access = new DataAccess();
    $str_entity_config = " * ";
    $sql_entity_factor = $data_access->get_table_values($this->db->prefix . 'rating_factors', $str_entity_config, " WHERE enable_factor = 1 ");
    $i = 1;
    ?>
      <?php if (count($sql_entity_factor) >= 1) { ?>
      <h2> Manage Rating Parameters </h2>
      <table class="table_class">
        <tr class="tr_class">
          <td class="td_class"><?php print __('SNO.'); ?></th></td>
          <td class="td_class"><?php print __('Parameter Name'); ?></td>
          <td class="td_class"><?php print __('Edit'); ?></td>
          <td class="td_class"><?php print __('Delete'); ?></td>
        </tr> 
          <?php foreach ($sql_entity_factor as $val) { ?>

          <tr>
            <td class="td_class"><?php print $i++; ?></td>
            <td class="td_class"><?php print $val->factor; ?></td>
            <?php
            if (isset($_GET['edit_factor_id'])) {
              $str = explode('&edit_factor_id', $_SERVER["REQUEST_URI"]);
              $edit_url = $str[0] . '&edit_factor_id=' . $val->id;
            } else if (isset($_GET['delete_factor_id'])) {
              $str = explode('&delete_factor_id', $_SERVER["REQUEST_URI"]);
              $edit_url = $str[0] . '&edit_factor_id=' . $val->id;
            } else {
              $edit_url = $_SERVER["REQUEST_URI"] . '&edit_factor_id=' . $val->id;
            }
            ?>
            <td class="td_class"><a href="<?php echo $edit_url; ?>"><?php print __('Edit'); ?></a></td>
            <?php
            if (isset($_GET['delete_factor_id'])) {
              $str = explode('&delete_factor_id', $_SERVER["REQUEST_URI"]);
              $delete_url = $str[0] . '&delete_factor_id=' . $val->id;
            } else if (isset($_GET['edit_factor_id'])) {
              $str = explode('&edit_factor_id', $_SERVER["REQUEST_URI"]);
              $delete_url = $str[0] . '&delete_factor_id=' . $val->id;
            } else {
              $delete_url = $_SERVER["REQUEST_URI"] . '&delete_factor_id=' . $val->id;
            }
            ?>
            <td class="td_class"><a href="#" onclick="return factor_del_operation(<?php echo "'" . $delete_url . "'"; ?>)" class="delete_fact"><?php print __('Delete'); ?></a></td>
          </tr>
        <?php }
    }
    ?>
    </table>
    <?php
  }

  /**
   * Advanced Option tab callback, same as above.
   */
  function field_advanced_option() {
    $advancedOptions = get_option('advanced_rating_settings');
    $data_access = new DataAccess();
    if (isset($_POST) && !isset($advancedOptions)) {
      $data_access->save_rating_options();
      unset($_POST);
    }

    if (isset($_POST) && isset($advancedOptions)) {
      $data_access->update_rating_options();
      unset($_POST);
    }

    $theme_name = $advancedOptions['theme_name'];
    $star_count = $advancedOptions['star_disp_count'];
    $share_text = $advancedOptions['share_text'];

    $dirs = array_filter(glob(__DIR__ . '\images\themes' . '/*'), 'is_dir');
    $i = 0;
    foreach ($dirs as $value) {
      $directory_temp = explode('/', $value);
      $directory[$i++] = $directory_temp[1];
    }

    // Message for demographic file presence
    if ($this->demo_graphic_enable == FALSE) { ?>
     <div class="update-nag"><?php print $this->dat_file_msg; ?></div>
     <?php
    }  ?>


    <?php
    if (isset($_GET['update']) && $_GET['update'] == 'true') {
      $data_access->set_message(array('status' => TRUE, 'message' => __('Settings Updated Successfully')));
    }
    if (isset($_SESSION['rating_message'])) {
      ?>
      <div class="updated" id="message"><?php print $_SESSION['rating_message']['message'] ?></div>
    <?php
    }
    if(isset($_SESSION['rating_message'])) {
      unset($_SESSION['rating_message']);
    }
    ?>


    <form method="post" name="advanced_settings" action="#">
      <table>
        <input type="hidden" name="option_page" value="rating_advanced_settings">
        <tr class="tr_class_nb">
          <td><label>Rating Scale:</label></td>
          <td>
            <input type="text" name="star_disp_count" value=<?php print (isset($star_count)) ? $star_count : 5  ?>>
          </td>
        </tr> 
        <tr><td></td><td><p class="description"><?php print __("Rating Scale (Greater then 1) for rating. Global for all entities"); ?></p></td></tr>   
        <tr class="tr_class_nb">
          <td><label><?php print __('Location Tracking:') ?></label></td>
          <td class="factors_checkbox">
            <input type="checkbox" name="demographic_mode" value="1" <?php print (isset($advancedOptions['demographic_mode']) && $advancedOptions['demographic_mode'] == 1) ? ' checked="checked"' : ''  ?> <?php if ($this->demo_graphic_enable == FALSE) : print " disabled='disabled'";
          endif; ?>/>
          </td>
        </tr>
        <tr><td></td><td><p class="description">
              <?php
              if ($this->demo_graphic_enable == FALSE) {
                print $this->dat_file_msg;
              } else {
                print __("This will enable location tracking for rating and user can see ratings from different geographies");
              }
              ?>
            </p>
          </td>
        </tr>


        <tr class="tr_class_nb">
          <td><label><?php print __('Share Feature:'); ?></label></td>
          <td class="factors_checkbox">
            <input type="checkbox" name="enable_share" value="1" <?php print (isset($advancedOptions['enable_share']) && $advancedOptions['enable_share'] == 1) ? ' checked="checked"' : ''  ?>>
          </td>
        </tr> 
        <tr><td></td><td><p class="description"><?php print __("This will enable share rating on Facebook/Twitter.") ?></p></td></tr>
        <tr class="tr_class_nb">
          <td><label>Share Text:</label></td>
          <td>
            <input type="text" name="share_text" value=<?php print (isset($share_text)) ? $share_text : __("'This Post Has Rating Score of %d'"); ?>/>
          </td>
        </tr> 
        <tr><td></td><td><p class="description"><?php print __("Share text is common for all the Posts (Use keyword '%d' for rating score count)"); ?></p></td></tr>
      </table>
      <p class="submit"><input type="submit" value="Save Changes" class="button button-primary" id="submit" name="submit"></p>
    </form>
    <?php
  }

  /*
   * Called during admin_menu, adds an options
   * page under Settings called My Settings, rendered
   * using the plugin_options_page method.
   */

  function add_admin_menus() {
    add_options_page('Rating Plugin Settings', 'Rating Settings', 'manage_options', $this->plugin_options_key, array(&$this, 'plugin_options_page'));
  }

  /*
   * Plugin Options page rendering goes here, checks
   * for active tab and replaces key with the related
   * settings key. Uses the plugin_options_tabs method
   * to render the tabs.
   */

  function plugin_options_page() {
    $tab = isset($_GET['tab']) ? $_GET['tab'] : $this->ratings_general_settings_key;
    $this->rating_settings_tabs();

    if ($tab == 'rating_settings') {
      $this->field_general_option();
    }
    if ($tab == 'rating_factors') {
      $this->field_entity_factors();
    }
    if ($tab == 'rating_advanced_settings') {
      $this->field_advanced_option();
    }
  }

  /*
   * Renders our tabs in the plugin options page,
   * walks through the object's tabs array and prints
   * them one by one. Provides the heading for the
   * plugin_options_page method.
   */

  function rating_settings_tabs() {
    $current_tab = isset($_GET['tab']) ? $_GET['tab'] : $this->ratings_general_settings_key;
    screen_icon();
    echo '<h2 class="nav-tab-wrapper">';
    foreach ($this->plugin_settings_tabs as $tab_key => $tab_caption) {
      $active = $current_tab == $tab_key ? 'nav-tab-active' : '';
      echo '<a class="rating-tab nav-tab ' . $active . '" href="?page=' . $this->plugin_options_key . '&tab=' . $tab_key . '">' . $tab_caption . '</a>';
    }
    echo '</h2>';
  }

  /**
   * Function to create social sharing feature
   * @global array $post
   * @return string 
   */
  function share_rating() {
    global $post;
    $data_access = new DataAccess();
    $advancedOptions = get_option('advanced_rating_settings');
    $stars = isset($advancedOptions['star_disp_count']) ? $advancedOptions['star_disp_count'] : 5;
    $share_text_raw = (isset($share_text_raw)) ? $advancedOptions['share_text'] : "This Post Has Rating Score of %d";

    $rating_score_arr = array();
    $sql_rating_score = $data_access->get_table_values($this->db->prefix . 'rating_scores', 'rating_score', "WHERE entity_content_id = $post->ID");
    foreach ($sql_rating_score as $score) {
      $rating_score_arr[] = isset($score->rating_score) ? ($score->rating_score / 100) * $stars : 0;
    }
    $count = count($rating_score_arr);
    if ($count == 0) {
      $count = 1;
    }
    $rating_score = array_sum($rating_score_arr) / $count;
    if (!isset($rating_score)) {
      $rating_score = 0;
    }

    $share_text = str_replace('%d', $rating_score, str_replace("'", '', trim($share_text_raw)));

    $path = trim($post->guid);
    $title = trim($post->post_title);
    $limit = 100;
    $summary = strip_tags($post->post_content);

    $content_data = (strlen($summary) > 100) ? substr($summary, 0, strrpos(substr($summary, 0, $limit), ' ')) . '....' : $summary;
    $content_data .= ' -- ' . $share_text;

    $img_path = plugins_url('images/facebook_counter.png', __FILE__);
    $url = "<a class='fb_share' title='Share this post/page'
      href='http://www.facebook.com/sharer.php?s=100&p[url]=$path&p[title]=$title&p[summary]=$content_data' target='_blank'>
      <img src=$img_path alt='Share on Facebook' /></a>";

    $url2 = "<a href='https://twitter.com/share' class='twitter-share-button' data-url='$path' data-text='$content_data' data-count='none'>Tweet</a>
<script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0];if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src='//platform.twitter.com/widgets.js';fjs.parentNode.insertBefore(js,fjs);}}(document,'script','twitter-wjs');</script>";
    $share = "<div class='share_block'>" . $url . $url2 . "</div>";
    return $share;
  }

}