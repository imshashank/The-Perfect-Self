<?php
/*
 * Plugin Name: Rating
 * Author: Divesh Kumar, Paritosh Gautam, Ramanpreet Singh
 * Version: 1.4.6
 * Description: A new Plugin to add and customize rating per post type with geo location tracking.
 */
global $rating_db_version;

require_once (dirname( __FILE__ ) . '/rating.php');
require_once (dirname( __FILE__ ) . '/data_access.php');
require_once (dirname( __FILE__ ) . '/rating_html.php');
require_once (dirname( __FILE__ ) . '/rating_settings.php');
require_once (dirname( __FILE__ ) . '/widget.php');
require_once (dirname( __FILE__ ) . '/ajax-rating.php');

global $rating_db_version;
$rating_db_version = "1.4.4";


// Actions to add script and css to admin pages
@wp_enqueue_style( 'rating-styles', plugins_url( '/css/rating-styles.css', __FILE__ ) );
@wp_enqueue_script( 'rating', plugins_url( '/scripts/rating.js', __FILE__ ), array( "jquery" ), '', true );
@wp_enqueue_style( 'jquery-ui-lightness', plugins_url( 'css/ui-lightness/jquery-ui-1.10.2.custom.css', __FILE__ ) );
@wp_enqueue_script( 'jquery-ui-datepicker' );

//allow redirection, even if my theme starts to send output to the browser
add_action( 'init', 'do_output_buffer' );
add_action( 'wp_head', 'custom_scripts' );

function do_output_buffer()
{
    ob_start();
}

/**
 * Function to add custom script
 */
function custom_scripts()
{
    ?>
    <script type='text/javascript'>
        ajax_url = '<?php echo admin_url( 'admin-ajax.php' ); ?>';
    </script>
    <?php
}

/**
 * @method
 * This method is for the rating box integration with content.
 */
function add_rating_to_content( $content )
{
    $p = $GLOBALS[ 'post' ];
    // checking if rating is enabled
    $ratingEnabled = true; // TODO: Admin Config
    global $post;
    $data = new DataAccess();
    $entity = $data->get_entity_id_by_name( $post->post_type );
    $content = "<div class='content-wrap'>" . $content . "</div>";
    $rating_activation = $data->db->get_row( "SELECT entity_enable as is_enabled, most_rated_flag from " . $data->db->prefix . "admin_rating_config WHERE id = '$entity->id'" );
    if ( $rating_activation->most_rated_flag > 0 )
    {
        $is_most_rated_content = $data->getMostRatedContent( $post );

        if ( $is_most_rated_content === true )
        {
            $content = "<div class='most-rated-content'>" . __( "Most Rated" ) . "</div>" . $content;
        }
    }
    if ( $rating_activation->is_enabled > 0 )
    {
        // Rating position
        $rating_box_position = 'bottom'; // TODO: Admin Config (Top, Bottom, Both)
        // getting rating widget
        $rating = new Rating();
        switch ( $rating_box_position )
        {
            case 'top':
                $content = $rating->generateRatingBox( $post ) . $content;
                break;
            case 'bottom':
                $content = $content . $rating->generateRatingBox( $post );
                break;
            case 'both':
                $content = $rating->generateRatingBox( $post ) . $content . $rating->generateRatingBox( $post );
                break;
        }
        return $content;
    }
    else
        return $content;
}

/**
 *
 * This method would hook into comment content.
 * @param unknown_type $content
 */
function add_rating_to_comment( $content )
{
    // checking if rating is enabled
    $data = new DataAccess();
    $rating_activation = $data->db->get_row( "SELECT entity_enable as is_enabled from " . $data->db->prefix . "admin_rating_config WHERE entity_type_name = 'comment'" );
    $comment = get_comment( get_comment_ID() );

    if ( $rating_activation->is_enabled > 0 )
    {
        // Rating position
        $rating_box_position = 'bottom'; // TODO: Admin Config (Top, Bottom, Both)
        // getting rating widget
        $rating = new Rating();
        switch ( $rating_box_position )
        {
            case 'top':
                $content = $rating->generateRatingBox( $comment ) . $content;
                break;
            case 'bottom':
                $content = $content . $rating->generateRatingBox( $comment );
                break;
            case 'both':
                $content = $rating->generateRatingBox( $comment ) . $content . $rating->generateRatingBox( $comment );
                break;
        }
        return $content;
    }
}

function addRatingToBPMediaContent(){
    $data = new DataAccess();
    $bpActivity = new BP_Activity_Activity(bp_Get_Activity_Id());
    $post = get_Post($bpActivity->item_id);
    $ratingConfig = $data->db->get_Row(sPrintF("
        SELECT entity_enable isEnabled,
               most_rated_flag isMostRatedEnabled
        FROM %sadmin_rating_config
        WHERE entity_type_name='%s'",
        $data->db->prefix, $post->post_type
    ));
    if (isset($ratingConfig->isMostRatedEnabled) && $ratingConfig->isMostRatedEnabled == 1 && $data->getMostRatedContent($post)){
        echo '<div class="most-rated-content snRatingBuddyPress">'.__('Most Rated').'</div>';
    }
    
    if (isset($ratingConfig->isEnabled) && $ratingConfig->isEnabled == 1){
        $rating = new Rating();
        echo $rating->generateRatingBox($post);
    }
}

// Calling actions for rating display
add_action( 'the_content', 'add_rating_to_content' );
add_action( 'comment_text', 'add_rating_to_comment' );
add_action('bp_activity_entry_content', 'addRatingToBPMediaContent');

/**
 * Rating db install hook
 * @global type $wpdb
 * @global string $rating_db_version
 */
function Rating_install()
{
    global $wpdb;
    if ( !isset( $wpdb ) )
        $wpdb = $GLOBALS[ 'wpdb' ];
    //global $rating_db_version;
    require_once(ABSPATH . 'wp-admin/includes/upgrade.php');

    $t_admin_rating_config = $wpdb->prefix . 'admin_rating_config';
    $sql_admin_rating_config = "CREATE TABLE IF NOT EXISTS $t_admin_rating_config (
  id mediumint(9) NOT NULL AUTO_INCREMENT,
  entity_type_name VARCHAR(50) NOT NULL,
  most_rated_flag boolean NOT NULL DEFAULT 0,
  entity_factors_mode boolean NOT NULL DEFAULT 0,
  entity_enable boolean NOT NULL DEFAULT 1,
  theme VARCHAR(255) NOT NULL,
  rating_scale int(5) NOT NULL DEFAULT 5, 
  
    PRIMARY KEY id (id)
  );";


    $t_rating_factors = $wpdb->prefix . 'rating_factors';
    $sql_rating_factors = "CREATE TABLE IF NOT EXISTS $t_rating_factors (
  id mediumint(9) NOT NULL AUTO_INCREMENT,
  factor VARCHAR(50) NOT NULL,
  enable_factor boolean NOT NULL DEFAULT 1,
   
    PRIMARY KEY id (id)
  );";

    $t_entity_rating_factors = $wpdb->prefix . 'entity_rating_factors';
    $sql_entity_rating_factors = "CREATE TABLE IF NOT EXISTS $t_entity_rating_factors (
  id int(11) NOT NULL AUTO_INCREMENT,
  entity_id mediumint(9) NOT NULL DEFAULT 0,
  factor_name VARCHAR(50) NULL DEFAULT NULL,
  weight FLOAT NOT NULL,
   
    PRIMARY KEY id (id)
  );";

    $t_rating_score = $wpdb->prefix . 'rating_scores';
    $sql_rating_score = "CREATE TABLE IF NOT EXISTS $t_rating_score (
  id int(11) NOT NULL AUTO_INCREMENT,
  entity_content_id int(11) NOT NULL DEFAULT 0,
  entity_type_name VARCHAR(50) NOT NULL,
  factor_name VARCHAR(50) NULL DEFAULT NULL,
  rating_score int(11) NOT NULL DEFAULT 0,
  country VARCHAR(50) NULL DEFAULT NULL,
  state VARCHAR(50) NULL DEFAULT NULL,
  time TIMESTAMP NOT NULL,
  
    PRIMARY KEY id (id)
  );";


    $t_rating_meta = $wpdb->prefix . 'rating_meta';
    $sql_rating_meta = "CREATE TABLE IF NOT EXISTS $t_rating_meta (
  id int(11) NOT NULL AUTO_INCREMENT,
  entity_content_id int(11) NOT NULL DEFAULT 0,
  user_id int(11) NOT NULL DEFAULT 0,
  entity_type_name VARCHAR(50) NOT NULL,
  factor_name VARCHAR(50) NULL DEFAULT NULL,
  rating_score int(11) NOT NULL DEFAULT 0,
  rating_stars_count int(11) NOT NULL DEFAULT 0,
  rating_weight int(11) NOT NULL DEFAULT 0,
  ip VARCHAR(255) NOT NULL,
  country VARCHAR(50) NULL DEFAULT NULL,
  state VARCHAR(50) NULL DEFAULT NULL,
  time TIMESTAMP NOT NULL,
  
    PRIMARY KEY id (id)
  );";


    dbDelta( $sql_admin_rating_config );
    dbDelta( $sql_rating_factors );
    dbDelta( $sql_entity_rating_factors );
    dbDelta( $sql_rating_score );
    dbDelta( $sql_rating_meta );

    global $rating_db_version;
    add_option( "rating_db_version", $rating_db_version );

    $table = $wpdb->prefix . 'posts';
    $sql_get_post_type = $wpdb->get_results( " SELECT post_type FROM $table GROUP BY post_type" );

    foreach ( $sql_get_post_type as $type )
    {
        $post_type[ $type->post_type ] = $type->post_type;
        $post_type[ 'comment' ] = 'comment';
    }
    unset( $post_type[ 'revision' ] );

    $ar_custom_post_types = get_post_types( array( 'public' => true, '_builtin' => false ) );

    if ( count( $ar_custom_post_types ) > 0 )
    {
        $post_type = array_merge( $post_type, $ar_custom_post_types );
    }

    foreach ( $post_type as $val )
    {
        $data_access = new DataAccess();
        $cond = '"' . $val . '"';
        $sql_entity_theme = $data_access->get_table_values( $wpdb->prefix . 'admin_rating_config', ' * ', " WHERE entity_type_name = $cond " );
        if ( count( $sql_entity_theme ) == 0 )
        {
            $wpdb->insert( $wpdb->prefix . 'admin_rating_config', array( 'entity_type_name' => $val,
                'most_rated_flag' => 0,
                'entity_factors_mode' => 1,
                'entity_enable' => 0,
                'theme' => 'default' ), array( '%s', '%d', '%d', '%d', '%s' )
            );
        }
    }
}

/* Delete table */

function Rating_uninstall()
{
    global $wpdb;
    $wpdb->query( "DROP TABLE IF EXISTS " . $wpdb->prefix . 'admin_rating_config' );
    $wpdb->query( "DROP TABLE IF EXISTS " . $wpdb->prefix . 'rating_factors' );
    $wpdb->query( "DROP TABLE IF EXISTS " . $wpdb->prefix . 'entity_rating_factors' );
    $wpdb->query( "DROP TABLE IF EXISTS " . $wpdb->prefix . 'rating_meta' );
    delete_option( 'rating_theme_settings' );
    delete_option( 'advanced_rating_settings' );
}

register_activation_hook( __FILE__, 'Rating_install' );
register_uninstall_hook( __FILE__, 'Rating_uninstall' );


// Initialize the plugin
add_action( 'plugins_loaded', create_function( '', '$rating_settings_tabs  = new RatingSettings();' ) );

/**
 * This method would check database update.
 * Adding up a new column for rating_scale
 */
function db_upgrade()
{
    global $wpdb;
    $upd_version = '1.4.1';
    $current_version = get_option( 'rating_db_version' );

    if ( $current_version < $upd_version || $upd_version == '' )
    {
        require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
        $t_admin_rating_config = $wpdb->prefix . 'admin_rating_config';
        $sql_admin_rating_config = "ALTER TABLE `" . $t_admin_rating_config . "` ADD COLUMN `rating_scale` INT(5) DEFAULT 5;";
        $wpdb->query( $sql_admin_rating_config );
        update_option( 'rating_db_version', $upd_version );
    }
}

add_action( 'init', 'db_upgrade' );

/**
 * Method to create share links
 * @param object $content
 * @return object
 */
function add_share_post_content( $content )
{
    $advancedOptions = get_option( 'advanced_rating_settings' );
    if ( isset( $advancedOptions[ 'enable_share' ] ) && $advancedOptions[ 'enable_share' ] == 1 )
    {
        $rating_settings = new RatingSettings;
        if ( !is_feed() && !is_home() )
        {
            $content .= $rating_settings->share_rating( $content );
        }
    }
    return $content;
}

add_filter( 'the_content', 'add_share_post_content' );

/**
 * Function to add custom js
 */
function my_custom_js()
{
    ?>
    <script type="text/javascript">
        var pluginUrl = '<?php echo plugins_url(); ?>';
        var ajax_url = '<?php echo admin_url( 'admin-ajax.php' ); ?>';
        var adminRatingTaburl = '<?php echo admin_url() . 'options-general.php?page=rating_options&tab=rating_settings' ?>';
    </script>
    <?php
}

// Add hook for admin <head></head>
add_action( 'admin_head', 'my_custom_js' );

// Add hook for front-end <head></head>
add_action( 'wp_head', 'my_custom_js' );

// Action to add analytics page
add_action( 'admin_menu', 'register_rating_analytics_page' );

function register_rating_analytics_page()
{
    add_menu_page( 'SN Rating Analytics', 'Rating Analytics', 'manage_options', 'sn_rating_analytics', 'rating_analytics_report_page', plugins_url( 'sn-rating/images/analytics.png' ));
}

/**
 * Function for creating analytics forms
 * @global type $wpdb
 */
function rating_analytics_report_page()
{
    if (file_exists(plugin_dir_path(__FILE__) . 'GeoLiteCity.dat') || file_exists(get_theme_root() . '/library/GeoLiteCity.dat')) {        
        global $wpdb;
        $data_access = new DataAccess();
        include_once('geoip.inc');
        include_once("geoipcity.inc");
        include_once("geoipregionvars.php");
        $data = array();
        if(file_exists(get_theme_root() . '/library/GeoLiteCity.dat')) 
        {
          $gi = geoip_open(get_theme_root() . '/library/GeoLiteCity.dat', GEOIP_STANDARD);
        }
        elseif(file_exists(plugin_dir_path(__FILE__) . 'GeoLiteCity.dat')) 
        {
          $gi = geoip_open(plugin_dir_path(__FILE__) . 'GeoLiteCity.dat', GEOIP_STANDARD);
        }

        if ( isset( $_POST[ 'entity_type' ] ) || isset( $_POST[ 'country' ] ) || isset( $_POST[ 'order_by' ] ) || isset( $_POST[ 'records_perpage' ] ) )
        {
            $entity_type = $_POST[ 'entity_type' ];
            $country = $_POST[ 'country' ];
            $order_by = $_POST[ 'order_by' ];
            $records_perpage = $_POST[ 'records_perpage' ];
            $from_date = $_POST[ 'datepicker1' ];
            $to_date = $_POST[ 'datepicker2' ];
            wp_redirect( $_SERVER[ "REQUEST_URI" ] . '&entity_type=' . $entity_type . '&country=' . $country . '&order_by=' . $order_by . '&records_perpage=' . $records_perpage . '&from_date=' . $from_date . '&to_date=' . $to_date);
        }
        ?>

        <!--  Form for filtering analytics report-->
        <div class="wrap" id="analytics-report">
            <h2><?php print __( 'Rating Analytics' ); ?></h2>
            <div class='description'><p>
                    <?php print __( 'Choose appropriate filters to get an anlytics report.' ) ?>

                </p></div>
            <div class='filter-label'><label><?php print __( 'Filters: ' ) ?></label></div>
            <div class = 'wrapper'>
                <form method="post" name="rating_report" enctype="multipart/form-data" action="<?php print admin_url() . 'admin.php?page=sn_rating_analytics' ?>">
                    <div class='form-element'>   
                        <label><?php print __( 'Entity Type: ' ) ?></label> 
                        <?php
                        $sql_get_post_type = $data_access->get_table_values( $wpdb->prefix . 'posts', " post_type ", 'GROUP BY post_type' );
                        foreach ( $sql_get_post_type as $type )
                        {
                            $post_type[ $type->post_type ] = $type->post_type;
                            $post_type[ 'comment' ] = 'comment';
                        }
                        $ar_custom_post_types = get_post_types( array( 'public' => true, '_builtin' => false ) );

                        if ( count( $ar_custom_post_types ) > 0 )
                        {
                            $post_type = array_merge( $post_type, $ar_custom_post_types );
                        }
                        ?>
                        <select id="entity_type" name="entity_type" >      
                            <option value="all"><?php print __( 'Select Entity' ); ?></option>
                            <?php
                            foreach ( $post_type as $key => $value )
                            {
                                if ( $value != 'revision' )
                                {
                                    ?>
                                    <option value="<?php print $key; ?>" <?php print (isset( $_GET[ 'entity_type' ] ) && $_GET[ 'entity_type' ] != '' && $_GET[ 'entity_type' ] != 'all' && $_GET[ 'entity_type' ] == $key) ? ' selected="selected"' : ''  ?>><?php print $value; ?></option>
                                    <?php
                                }
                            }
                            ?>
                        </select>
                    </div>

                    <div class='form-element'>    
                        <label><?php print __( 'Country: ' ) ?></label> 
                        <select id="country" name="country">  
                            <option value="all"><?php print __( 'Select Country' ); ?></option>
                            <?php
                            unset( $gi->GEOIP_COUNTRY_NAMES[ 0 ] );
                            foreach ( $gi->GEOIP_COUNTRY_NAMES as $k => $v )
                            {
                                ?>
                                <option value="<?php print $k; ?>" <?php print (isset( $_GET[ 'country' ] ) && is_numeric( $_GET[ 'country' ] ) && $_GET[ 'country' ] != '' && $_GET[ 'country' ] != 'all' && $_GET[ 'country' ] == $k) ? ' selected="selected"' : ''  ?>><?php print $v; ?></option>
                            <?php } ?>
                        </select>
                    </div> 

                    <div class='form-element'>    
                        <label class='no-left'><?php print __( 'Sort By: ' ) ?></label> 
                        <select id="order_by" name="order_by">
                            <option value="0"><?php print __( 'Sort By' ); ?></option>
                            <option value ="max_vote" <?php print (isset( $_GET[ 'order_by' ] ) && is_string( $_GET[ 'order_by' ] ) && $_GET[ 'order_by' ] == 'max_vote') ? ' selected="selected"' : ''  ?>><?php print __( 'Max Vote' ); ?></option>
                            <option value ="min_vote" <?php print (isset( $_GET[ 'order_by' ] ) && is_string( $_GET[ 'order_by' ] ) && $_GET[ 'order_by' ] == 'min_vote') ? ' selected="selected"' : ''  ?>><?php print __( 'Min Vote' ); ?></option>
                        </select>
                    </div>
                    <?php $order = array( 5, 10, 15, 20 ); ?>
                    <div class='form-element'>   
                        <label><?php print __( 'Records Per Page: ' ) ?></label>  
                        <select id="records_perpage" name="records_perpage" >
                            <?php
                            foreach ( $order as $va )
                            {
                                if ( !isset( $_GET[ 'records_perpage' ] ) )
                                {
                                    $_GET[ 'records_perpage' ] = 10;
                                }
                                ?>
                                <option value="<?php print $va; ?>" <?php print (isset( $_GET[ 'records_perpage' ] ) && is_numeric( $_GET[ 'records_perpage' ] ) && $_GET[ 'records_perpage' ] != '' && $_GET[ 'records_perpage' ] == $va) ? ' selected="selected"' : ''  ?>><?php print $va; ?></option>
                            <?php }
                            ?>
                        </select>
                    </div>
                    <?php
                    $records_per_page = isset( $_GET[ 'records_perpage' ] ) ? $_GET[ 'records_perpage' ] : 10;
                    $offset = (isset( $_GET[ 'pg' ] ) ? $_GET[ 'pg' ] : 1 - 1) * $records_per_page;
                    ?>

                    <div class='form-element'>
                        <label>From Date:</label>
                        <input type="text" id="datepicker1" name="datepicker1" <?php print(isset( $_GET[ 'from_date' ] ) && $_GET[ 'from_date' ] != '') ? ' value=' . $_GET[ 'from_date' ] : ''  ?>>
                    </div>

                    <div class='form-element'>
                        <label>To Date:</label>
                        <input type="text" id="datepicker2" name="datepicker2" <?php print(isset( $_GET[ 'to_date' ] ) && $_GET[ 'to_date' ] != '') ? ' value=' . $_GET[ 'to_date' ] : ''  ?>>
                    </div>

                    <div class='form-element'>    
                        <input type="submit" value="Apply Filter" class="button button-primary" id="submit" name="submit">
                    </div>       
                </form>
                <div class='form-element'>    
                    <a href="<?php print admin_url() . 'admin.php?page=sn_rating_analytics' ?>"><input type="button" value="Reset" class="button button-primary" id="submit" name="submit"></a>
                </div>   
            </div>

            <!--  filters for query-->
            <?php
            $cond = '';
            if ( isset( $_GET[ 'entity_type' ] ) && $_GET[ 'entity_type' ] != 'all' )
            {
                $entity_type_name = '"' . trim( $_GET[ 'entity_type' ] ) . '"';
                $cond1 = " wrs.`entity_type_name`=" . $entity_type_name . " ";
            }
            if ( isset( $_GET[ 'country' ] ) && $_GET[ 'country' ] != 'all' && is_numeric( $_GET[ 'country' ] ) )
            {
                $coun = '"' . $gi->GEOIP_COUNTRY_CODES[ $_GET[ 'country' ] ] . '"';
                $cond2 = " wrs.`country`=" . $coun . " ";
            }

            //Country & Entity type condition
            if ( isset( $cond1 ) && isset( $cond2 ) )
            {
                $cond = " WHERE " . $cond1 . " AND " . $cond2;
            }
            else if ( isset( $cond1 ) && !isset( $cond2 ) )
            {
                $cond = " WHERE " . $cond1;
            }
            else if ( !isset( $cond1 ) && isset( $cond2 ) )
            {
                $cond = " WHERE " . $cond2;
            }

            // order By condition
            if ( isset( $_GET[ 'order_by' ] ) && $_GET[ 'order_by' ] != '0' )
            {
                if ( $_GET[ 'order_by' ] == 'max_vote' )
                {
                    $order_by_cond = " ORDER BY vote DESC ";
                }
                elseif ( $_GET[ 'order_by' ] == 'min_vote' )
                {
                    $order_by_cond = " ORDER BY vote ASC ";
                }
                else
                {
                    $order_by_cond = '';
                }
            }
            else
            {
                $order_by_cond = '';
            }

            $time_cond = '';
            if ( isset( $_GET[ 'from_date' ] ) && isset( $_GET[ 'to_date' ] ) && $_GET[ 'to_date' ] != '' && $_GET[ 'from_date' ] != '' )
            {
                $from_raw = explode( '/', $_GET[ 'from_date' ] );
                $from_date_final = " '" . $from_raw[ 2 ] . '-' . $from_raw[ 0 ] . '-' . $from_raw[ 1 ] . " 00:00:00' ";

                $to_raw = explode( '/', $_GET[ 'to_date' ] );
                $to_date_final = " '" . $to_raw[ 2 ] . '-' . $to_raw[ 0 ] . '-' . $to_raw[ 1 ] . " 23:59:59' ";
                if ( isset( $cond ) && $cond != '' )
                {
                    $time_cond = " AND wrs.`time` BETWEEN $from_date_final AND $to_date_final";
                }
                else
                {
                    $time_cond = " WHERE wrs.`time` BETWEEN $from_date_final AND $to_date_final";
                }
            }

            //main Query
            $query = $wpdb->get_results( "SELECT count(wrs.`entity_content_id`) as vote, po.`ID` as id, po.post_title, wrs.`entity_type_name`, wrs.`rating_score`, wrs.`country` , wrs.`state`, wrs.`factor_name`
                                      FROM `" . $wpdb->prefix . "rating_scores` wrs
                                      INNER JOIN " . $wpdb->prefix . "posts po ON wrs.`entity_content_id` = po.`ID`
                                      $cond $time_cond
                                      GROUP BY wrs.`country`, wrs.`state` , wrs.`entity_content_id`, wrs.`entity_type_name`
                                      $order_by_cond
                                      LIMIT $offset, $records_per_page" );


            //count query
            $q_count = $wpdb->get_results( "SELECT count(wrs.`entity_content_id`) as vote, po.`ID` as id, po.post_title, wrs.`entity_type_name`, wrs.`rating_score`, wrs.`country` , wrs.`state`, wrs.`factor_name`
                                      FROM `" . $wpdb->prefix . "rating_scores` wrs
                                      INNER JOIN " . $wpdb->prefix . "posts po ON wrs.`entity_content_id` = po.`ID`
                                      $cond $time_cond
                                      GROUP BY wrs.`country`, wrs.`state` , wrs.`entity_content_id`, wrs.`entity_type_name`
                                      $order_by_cond" );
            
            $query_count = $num_pages = 0;
            $query_c = array( );
            
            foreach ( $q_count as $count_val )
            {
                $query_c[ ] = $count_val->id;
            }
            $query_count = count( $query_c );
            $num_pages = ceil($query_count/$records_per_page);
            
            //Table header and data for report
            if ( $query_count > 0 )
            {
                ?>
                    <table class='table table-striped table-hover'>
                        <th><?php print __( 'S.No.' ); ?></th>
                        <th><?php print __( 'Entity Type' ); ?></th>
                        <th><?php print __( 'Post Title' ); ?></th>
                        <th><?php print __( 'Vote' ); ?></th>
                        <th><?php print __( 'Avg Rating' ); ?></th>
                        <th><?php print __( 'Country' ); ?></th>

                        <?php
                        $i = ($offset + 1);
                        foreach ( $query as $val )
                        {
                            $post_data = get_post( $val->id );
                            $rating = $data_access->getRating( $post_data );
                            $max_count = $rating[ 'max_count' ];
                            foreach ( $rating[ 'options' ] as $rate )
                            {
                                if ( isset( $val->factor_name ) && ($val->factor_name != NULL || $val->factor_name != '') && $rate[ 'title' ] == $val->factor_name )
                                {
                                    $rate[ 'aggregate' ] = ($max_count * $rate[ 'aggregate' ] ) / 100;
                                    break;
                                }
                                else
                                {
                                    $rate[ 'aggregate' ] = ($max_count * $rate[ 'aggregate' ] ) / 100;
                                }
                            }
                            ?>
                            <tr>
                                <td><?php print $i; ?></td>
                                <td><?php print $val->entity_type_name; ?></td>

                                <?php
                                $limit = 50;
                                $title = (strlen( $val->post_title ) > 50) ? substr( $val->post_title, 0, strrpos( substr( $val->post_title, 0, $limit ), ' ' ) ) . '....' : $val->post_title;
                                ?>
                                <td><a href="<?php print site_url(); ?>/index.php?p=<?php print $val->id; ?>" target="_blank"><?php print $title; ?></a></td>
                                <td><?php print $val->vote; ?></td>
                                <td><?php print round( $rate[ 'aggregate' ], 2 ) . ' / ' . $max_count; ?></td>
                                <?php $region = $GEOIP_REGION_NAME[ $val->country ][ $val->state ] ? $GEOIP_REGION_NAME[ $val->country ][ $val->state ] : __( 'Unknown' ); ?>
                                <td><?php print $data_access->get_country( $val->country ) . ' (' . $region . ')'; ?></td>
                            </tr>
                            <?php
                            $i++;
                        }
                        ?>
                    </table>
                    
                    <?php 
                        @$current = $_GET[ 'pg' ] ? $_GET[ 'pg' ] : 0;
                        @$prev = $current - 1;
                        @$next = $current + 1; 
                        @$start_index = ( ($current - 2) > 0 ) ? ($current - 2) : 0;
                        @$end_index = ( $start_index > 0 ) ? ($current + 3) : 5;
                        @$display_first = ($current * $records_per_page + 1);
                        @$temp_index = ($display_first+$records_per_page-1);
                        @$display_last = ($temp_index > $query_count) ? $query_count : $temp_index; ?>
                    <?php if( !empty($num_pages) && ($num_pages > 1) ) { ?>
                        <div class="row-fluid">
                            <div class="span12"> 
                                <div class="pagination pagination-centered">
                                    <ul>
                                        <?php
                                        if ( $prev >= 0 )
                                        {
                                           $prev_url = str_replace('pg=' .$_GET['pg'], 'pg='.$prev, $_SERVER["REQUEST_URI"]);
                                            ?>
                                            <li><a href="<?php print $prev_url ?>">&laquo;</a></li>
                                        <?php } ?>


                                        <?php
                                        for ( $k = $start_index; $k < min( $num_pages, $end_index ); $k++ )
                                        {
                                            $active = ($current == $k) ? 1 : 0;
                                            if(isset($_GET['pg'])) {
                                              $page_url = str_replace('&pg=' .$_GET['pg'], '&pg='.$k, $_SERVER["REQUEST_URI"]);           
                                            }
                                            else {
                                              $page_url = $_SERVER["REQUEST_URI"] . '&pg='.$k;
                                            }

                                            if ( !empty( $active ) )
                                            {
                                                echo '<li class="active"><span>' . ($k + 1) . '</span></li>';
                                            }
                                            else
                                            {
                                                echo '<li><a href="' . $page_url . '">' . ($k + 1) . '</a></li>';
                                            }
                                        }
                                        ?>

                                        <?php
                                        if ( $num_pages > $next )
                                        {
                                            if(isset($_GET['pg'])) {
                                              $next_url = str_replace('&pg=' .$_GET['pg'], '&pg='.$next, $_SERVER["REQUEST_URI"]);           
                                            }
                                            else {
                                              $next_url = $_SERVER["REQUEST_URI"] . '&pg='.$next;
                                            }
                                            ?>
                                            <li><a href="<?php print $next_url; ?>">&raquo;</a></li>
                                        <?php } ?>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    <?php } ?>
            
                    <div class="row-fluid">
                        <div class="span12">
                            <p>Displaying <strong><?php echo $display_first; ?> to  <?php echo $display_last; ?></strong> of total <strong><?php echo $query_count; ?> records</strong></p>
                        </div>
                    </div>
            <?php
        }
        else
        {
            ?><div class='no-result-found'><?php Print __( "No Result Found" ); ?></div><?php
        }
    }
    else
    {
        ?>
        <div class="update-nag">
            <?php print __( "To enable Rating Analytics and location tracking for rating please create folder 'library' under 'wp-content/themes' and download <b><a href='http://geolite.maxmind.com/download/geoip/database/GeoLiteCity.dat.gz'>Gzip File</a></b> and extract in 'library' folder."); ?>
        </div>
        <?php
    }
}
?>
