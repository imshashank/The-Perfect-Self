<?php
if ( !defined('ABSPATH')) exit;
load_theme_textdomain('Detox', get_template_directory().'/languages');
$locale = get_locale();
$locale_file = get_template_directory().'/languages/$locale.php';
if (is_readable( $locale_file))
require_once( $locale_file);
function Detox_category_rel_removal ($output) {
    $output = str_replace(' rel="category tag"', '', $output);
    return $output;
}
add_filter('wp_list_categories', 'Detox_category_rel_removal');
add_filter('the_category', 'Detox_category_rel_removal');  
	
$args = array(
	'default-color' => 'ffffff',
);
add_theme_support( 'custom-background', $args );

$args = array(
	'flex-width'    => false,
	'width'         => 200,
	'flex-height'    => false,
	'height'        => 120,
	'default-image' => get_template_directory_uri() . '/images/dtox.jpg',
);
add_theme_support( 'custom-header', $args );
			
if ( ! isset( $content_width ) ) $content_width = 980;
add_filter( 'show_admin_bar', '__return_false' );
add_theme_support( 'automatic-feed-links' );
remove_action('wp_head', 'wp_generator');
add_editor_style('custom-editor-style.css');
function custom_excerpt_length( $length ) {
	return 20;
}
add_filter( 'excerpt_length', 'custom_excerpt_length', 999 );

add_action( 'after_setup_theme', 'regMyMenus' );
function regMyMenus() {
// This theme uses wp_nav_menu() in four locations.
register_nav_menus( array(
'top-nav' => __( 'Top-Level Navigation', 'Detox' ),
'secnav' => __( 'Second-Level Navigation', 'Detox' ),
) );
}

function topnav_fallback() {
?>

<ul id="top-nav" >
<li class="<?php if ( is_home() or is_single() or is_paged() or is_search() or (function_exists('is_tag') and is_tag()) ) { ?>current_page_item<?php } else { ?>page_item<?php } ?>">
<a href="<?php home_url(); ?>/"><?php _e( 'Home', 'Detox' ) ?></a></li>
<?php wp_list_pages('depth=4&sort_column=menu_order&title_li='); ?>
</ul>  
<?php
}

function secnav_fallback() {
?>
<ul>
<?php wp_list_categories('orderby=id&show_count=0&sort_column=name&title_li=&depth=4'); ?>
</ul>
<?php
}

add_theme_support( 'post-thumbnails' );
add_image_size( 'c1', 700, 700 );
add_image_size( 'c2', 300, 350 );
add_image_size( 'fteaser', 690, 690 );
add_image_size( 'sbrowse', 255, 155 );
add_image_size( 'player', 280, 250, true );
add_image_size( 'covers', 180, 150, true );
add_image_size( 'gallerie', 1290, 890, true );

remove_action('init', 'wpsc_enqueue_user_script_and_css');

add_filter('gallery_style',
	create_function(
		'$css',
		'return preg_replace("#<style type=\'text/css\'>(.*?)</style>#s", "", $css);'
		)
	);

function milo_remove_recent_comments_style() {
	global $wp_widget_factory;
	remove_action( 'wp_head', array( $wp_widget_factory->widgets['WP_Widget_Recent_Comments'], 'recent_comments_style' ) );
}
add_action( 'widgets_init', 'milo_remove_recent_comments_style' );

register_sidebars( 1,
	array(
    'name' => __('Post widget', 'Detox'),
		'id' => 'sidebar',
		'description' => __('The single bar widget area for your single posts.', 'Detox'),
		'before_widget' => '<div id="%1$s" class="widget %2$s">',
        'after_widget' => '</div>',
        'before_title' => '<h3>',
        'after_title' => '</h3>'
	) 
);
register_sidebars( 1,
	array( 
    'name' => __('Page widget', 'Detox'),
		'id' => 'pagebar',
		'description' => __('The page bar widget area for your paged sites.', 'Detox'),
		'before_widget' => '<div id="%1$s" class="widget %2$s">',
        'after_widget' => '</div>',
        'before_title' => '<h2 class="widgettitle">',
        'after_title' => '</h2>'
	) 
);
register_sidebars( 1,
	array( 
    'name' => __('Single post Widget bar', 'Detox'),
		'id' => 'widgetbar',
		'description' => __('The widget area for videos etc.', 'Detox'),
		'before_widget' => '<div id="%1$s" class="widget %2$s">',
        'after_widget' => '</div>',
        'before_title' => '<h3 class="widgettitle">',
        'after_title' => '</h3>'
	) 
);
register_sidebars( 1, 
	array( 
		'name' => __('Footer Box 1', 'Detox'),
		'id' => 'footer-column1',
		'description' => __('The 1st column footer widget area.', 'Detox'),
    'before_widget' => '<div id="%1$s" class="widget %2$s">',
        'after_widget' => '</div>',
        'before_title' => '<h3 class="widgettitle">',
        'after_title' => '</h3>'
	) 
);
register_sidebars( 1,
	array( 
		'name' => __('Footer Box 2', 'Detox'),
		'id' => 'footer-column2',
		'description' => __('The 2nd column footer widget area.', 'Detox'),
    'before_widget' => '<div id="%1$s" class="widget %2$s">',
        'after_widget' => '</div>',
        'before_title' => '<h3 class="widgettitle">',
        'after_title' => '</h3>'
	) 
);
register_sidebars( 1,
	array( 
		'name' => __('Footer Box 3', 'Detox'),
		'id' => 'footer-column3',
		'description' => __('The 3rd column footer widget area.', 'Detox'),
    'before_widget' => '<div id="%1$s" class="widget %2$s">',
        'after_widget' => '</div>',
        'before_title' => '<h3 class="widgettitle">',
        'after_title' => '</h3>'
	) 
);
register_sidebars( 1,
	array( 
		'name' => __('Footer Box 4', 'Detox'),
		'id' => 'footer-column4',
		'description' => __('The 4th column footer widget area.', 'Detox'),
    'before_widget' => '<div id="%1$s" class="widget %2$s">',
        'after_widget' => '</div>',
        'before_title' => '<h3 class="widgettitle">',
        'after_title' => '</h3>'
	) 
);
register_sidebars( 1, 
	array( 
		'name' => __('Footer widget left', 'Detox'),
		'id' => 'footer-column5',
		'description' => __('The 1st column footer widget area.', 'Detox'),
    'before_widget' => '<div id="%1$s" class="widget %2$s">',
        'after_widget' => '</div>',
        'before_title' => '<h3 class="widgettitle">',
        'after_title' => '</h3>'
	) 
);
register_sidebars( 1,
	array( 
		'name' => __('Footer widget right', 'Detox'),
		'id' => 'footer-column6',
		'description' => __('The 2nd column footer widget area.', 'Detox'),
    'before_widget' => '<div id="%1$s" class="widget %2$s">',
        'after_widget' => '</div>',
        'before_title' => '<h3 class="widgettitle">',
        'after_title' => '</h3>'
	) 
);
register_sidebars( 1, 
	array( 
		'name' => __('BuddyPress Box 1', 'Detox'),
		'id' => 'buddybar-left',
		'description' => __('The 1st column BuddyPress widget area.', 'Detox'),
    'before_widget' => '<div id="%1$s" class="widget %2$s">',
        'after_widget' => '</div>',
        'before_title' => '<h3 class="widgettitle">',
        'after_title' => '</h3>'
	) 
);
register_sidebars( 1,
	array( 
		'name' => __('BuddyPress Box 2', 'Detox'),
		'id' => 'buddybar-right',
		'description' => __('The 2nd column BuddyPress widget area.', 'Detox'),
    'before_widget' => '<div id="%1$s" class="widget %2$s">',
        'after_widget' => '</div>',
        'before_title' => '<h3 class="widgettitle">',
        'after_title' => '</h3>'
	) 
);
add_filter('excerpt_length', 'my_excerpt_length');
function my_excerpt_length($length) {
return 60; }
function new_excerpt_more($more) {
	return '...';
}
add_filter('excerpt_more', 'new_excerpt_more'); 

function custom_colors() {
   echo '<style type="text/css">#wphead{background:#fff !important;border-bottom:5px solid #ccc;color:#333;text-shadow:#fff 0 1px 1px;}#message{display:none !important;}#footer{background:#fff !important;border-top:5px solid #ccc;color:#333;}#user_info p,#user_info p a,#wphead a{color:#900 !important;}</style>';
}
add_action('admin_head', 'custom_colors');
function remove_footer_admin () {
    echo "Thank you for creating with <a href='http://3oneseven.com'>milo</a>.";
} 
add_filter('admin_footer_text', 'remove_footer_admin'); 

function custom_login() { 
echo '<link rel="stylesheet" type="text/css" href="' . get_template_directory_uri() . '/log/log.css" />'; 
}   
add_action('login_head', 'custom_login');

function fb_login_headerurl() {
	return home_url();
}
add_filter( 'login_headerurl', 'fb_login_headerurl' );
function fb_login_headertitle() {
	return get_option('blogname');
}
add_filter( 'login_headertitle', 'fb_login_headertitle' );

add_filter('disable_captions', create_function('$a','return true;'));

function wp_pagenavi($before = '', $after = '', $prelabel = '', $nxtlabel = '', $pages_to_show = 5, $always_show = false) {
	global $request, $posts_per_page, $wpdb, $paged;
	if(empty($prelabel)) {
		$prelabel  = '<strong>&laquo;</strong>';
	}
	if(empty($nxtlabel)) {
		$nxtlabel = '<strong>&raquo;</strong>';
	}
	$half_pages_to_show = round($pages_to_show/2);
	if (!is_single()) {
		if(!is_category()) {
			preg_match('#FROM\s(.*)\sORDER BY#siU', $request, $matches);		
		} else {
			preg_match('#FROM\s(.*)\sGROUP BY#siU', $request, $matches);		
		}
		$fromwhere = $matches[1];
		$numposts = $wpdb->get_var("SELECT COUNT(DISTINCT ID) FROM $fromwhere");
		$max_page = ceil($numposts /$posts_per_page);
		if(empty($paged)) {
			$paged = 1;
		}
		if($max_page > 1 || $always_show) {
			echo "$before <div class='Nav'><span>Pages ($max_page): </span>";
			if ($paged >= ($pages_to_show-1)) {
				echo '<a href="'.get_pagenum_link().'">&laquo; Erste Seite</a> ... ';
			}
			previous_posts_link($prelabel);
			for($i = $paged - $half_pages_to_show; $i  <= $paged + $half_pages_to_show; $i++) {
				if ($i >= 1 && $i <= $max_page) {
					if($i == $paged) {
						echo "<strong class='on'>$i</strong>";
					} else {
						echo ' <a href="'.get_pagenum_link($i).'">'.$i.'</a> ';
					}
				}
			}
			next_posts_link($nxtlabel, $max_page);
			if (($paged+$half_pages_to_show) < ($max_page)) {
				echo ' ... <a href="'.get_pagenum_link($max_page).'">Last Page &raquo;</a>';
			}
			echo "</div> $after";
		}
	}
}
class Recentposts_thumbnail extends WP_Widget {
    function Recentposts_thumbnail() {
        parent::WP_Widget(false, $name = 'Recent Posts');
    }
    function widget($args, $instance) {
        extract( $args );
        $title = apply_filters('widget_title', $instance['title']);
        ?>
            <?php echo $before_widget; ?>
            <?php if ( $title ) echo $before_title . $title . $after_title;  else echo '<div class="widget-body clear">'; ?>
            <?php
                global $post;
                if (get_option('rpthumb_qty')) $rpthumb_qty = get_option('rpthumb_qty'); else $rpthumb_qty = 5;
                $q_args = array(
                    'numberposts' => $rpthumb_qty,
                );
                $rpthumb_posts = get_posts($q_args);
                foreach ( $rpthumb_posts as $post ) :
                    setup_postdata($post);
            ?>
                <a href="<?php the_permalink(); ?>" class="rpthumb clear">
                    <?php if ( has_post_thumbnail() && !get_option('rpthumb_thumb') ) {
                        the_post_thumbnail('mini-thumbnail');
                        $offset = 'style="padding-left: 65px;"';
                    }
                    ?>
                    <span class="rpthumb-title" <?php echo $offset; ?>><?php the_title(); ?></span>
                    <span class="rpthumb-date" <?php echo $offset; unset($offset); ?>><?php the_time(__('M j, Y', 'Detox')) ?></span>
                </a>
            <?php endforeach; ?>
            <?php echo $after_widget; ?>
        <?php
    }
    function update($new_instance, $old_instance) {
        $instance = $old_instance;
        $instance['title'] = strip_tags($new_instance['title']);
        update_option('rpthumb_qty', $_POST['rpthumb_qty']);
        update_option('rpthumb_thumb', $_POST['rpthumb_thumb']);
        return $instance;
    }
    function form($instance) {
        $title = esc_attr($instance['title']);
        ?>
            <p><label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:', 'Detox'); ?> <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo $title; ?>" /></label></p>
            <p><label for="rpthumb_qty">Number of posts:  </label><input type="text" name="rpthumb_qty" id="rpthumb_qty" size="2" value="<?php echo get_option('rpthumb_qty'); ?>"/></p>
            <p><label for="rpthumb_thumb">Hide thumbnails:  </label><input type="checkbox" name="rpthumb_thumb" id="rpthumb_thumb" <?php echo (get_option('rpthumb_thumb'))? 'checked="checked"' : ''; ?>/></p>
        <?php
    }

}
add_action('widgets_init', create_function('', 'return register_widget("Recentposts_thumbnail");'));

function Bruce_remove_recent_comments_style() {
	global $wp_widget_factory;
	remove_action( 'wp_head', array( $wp_widget_factory->widgets['WP_Widget_Recent_Comments'], 'recent_comments_style' ) );
}
add_action( 'widgets_init', 'Bruce_remove_recent_comments_style' );

add_filter('get_comments_number', 'comment_count', 0);
function comment_count( $count ) {
        if ( ! is_admin() ) {
                global $id;
                $comments_by_type = &separate_comments(get_comments('status=approve&post_id=' . $id));
                return count($comments_by_type['comment']);
        } else {
                return $count;
        }
}
function dimox_breadcrumbs() { 
  $delimiter = '&raquo;';
  $home = 'Home'; // text for the 'Home' link
  $before = '<span class="current">'; // tag before the current crumb
  $after = '</span>'; // tag after the current crumb 
  if ( !is_home() && !is_front_page() || is_paged() ) {
    echo '<div id="crumbs">'; 
    global $post;
    $homeLink = home_url();
    echo '<a href="' . $homeLink . '">' . $home . '</a> ' . $delimiter . ' '; 
    if ( is_category() ) {
      global $wp_query;
      $cat_obj = $wp_query->get_queried_object();
      $thisCat = $cat_obj->term_id;
      $thisCat = get_category($thisCat);
      $parentCat = get_category($thisCat->parent);
      if ($thisCat->parent != 0) echo(get_category_parents($parentCat, TRUE, ' ' . $delimiter . ' '));
      echo $before . 'Archive by category "' . single_cat_title('', false) . '"' . $after; 
    } elseif ( is_day() ) {
      echo '<a href="' . get_year_link(get_the_time('Y')) . '">' . get_the_time('Y') . '</a> ' . $delimiter . ' ';
      echo '<a href="' . get_month_link(get_the_time('Y'),get_the_time('m')) . '">' . get_the_time('F') . '</a> ' . $delimiter . ' ';
      echo $before . get_the_time('d') . $after; 
    } elseif ( is_month() ) {
      echo '<a href="' . get_year_link(get_the_time('Y')) . '">' . get_the_time('Y') . '</a> ' . $delimiter . ' ';
      echo $before . get_the_time('F') . $after; 
    } elseif ( is_year() ) {
      echo $before . get_the_time('Y') . $after; 
    } elseif ( is_single() && !is_attachment() ) {
      if ( get_post_type() != 'post' ) {
        $post_type = get_post_type_object(get_post_type());
        $slug = $post_type->rewrite;
        echo '<a href="' . $homeLink . '/' . $slug['slug'] . '/">' . $post_type->labels->singular_name . '</a> ' . $delimiter . ' ';
        echo $before . get_the_title() . $after;
      } else {
        $cat = get_the_category(); $cat = $cat[0];
        echo get_category_parents($cat, TRUE, ' ' . $delimiter . ' ');
        echo $before . get_the_title() . $after;
      } 
    } elseif ( !is_single() && !is_page() && get_post_type() != 'post' && !is_404() ) {
      $post_type = get_post_type_object(get_post_type());
      echo $before . $post_type->labels->singular_name . $after; 
    } elseif ( is_attachment() ) {
      $parent = get_post($post->post_parent);
      $cat = get_the_category($parent->ID); $cat = $cat[0];
      echo get_category_parents($cat, TRUE, ' ' . $delimiter . ' ');
      echo '<a href="' . get_permalink($parent) . '">' . $parent->post_title . '</a> ' . $delimiter . ' ';
      echo $before . get_the_title() . $after; 
    } elseif ( is_page() && !$post->post_parent ) {
      echo $before . get_the_title() . $after; 
    } elseif ( is_page() && $post->post_parent ) {
      $parent_id  = $post->post_parent;
      $breadcrumbs = array();
      while ($parent_id) {
        $page = get_page($parent_id);
        $breadcrumbs[] = '<a href="' . get_permalink($page->ID) . '">' . get_the_title($page->ID) . '</a>';
        $parent_id  = $page->post_parent;
      }
      $breadcrumbs = array_reverse($breadcrumbs);
      foreach ($breadcrumbs as $crumb) echo $crumb . ' ' . $delimiter . ' ';
      echo $before . get_the_title() . $after; 
    } elseif ( is_search() ) {
      echo $before . 'Search results for "' . get_search_query() . '"' . $after; 
    } elseif ( is_tag() ) {
      echo $before . 'Posts tagged "' . single_tag_title('', false) . '"' . $after; 
    } elseif ( is_author() ) {
       global $author;
      $userdata = get_userdata($author);
      echo $before . 'Articles posted by ' . $userdata->display_name . $after;
    } elseif ( is_404() ) {
      echo $before . 'Error 404' . $after;
    }
    if ( get_query_var('paged') ) {
      if ( is_category() || is_day() || is_month() || is_year() || is_search() || is_tag() || is_author() ) echo ' (';
      echo __('Page', 'Detox') . ' ' . get_query_var('paged');
      if ( is_category() || is_day() || is_month() || is_year() || is_search() || is_tag() || is_author() ) echo ')';
    } 
    echo '</div>'; 
  }
} // end dimox_breadcrumbs()
function myavatar_add_default_avatar( $url )
{
return get_stylesheet_directory_uri() .'/images/members.jpg';
}
add_filter( 'bp_core_mysteryman_src', 'myavatar_add_default_avatar' );
 
function my_default_get_group_avatar($avatar) {
global $bp, $groups_template;
if( strpos($avatar,'group-avatars') ) {
return $avatar;
}
else {
$custom_avatar = get_stylesheet_directory_uri() .'/images/groups.jpg';
if($bp->current_action == "")
return '<img width="'.BP_AVATAR_THUMB_WIDTH.'" height="'.BP_AVATAR_THUMB_HEIGHT.'" src="'.$custom_avatar.'" class="avatar" alt="' . esc_attr( $groups_template->group->name ) . '" />';
else
return '<img width="'.BP_AVATAR_FULL_WIDTH.'" height="'.BP_AVATAR_FULL_HEIGHT.'" src="'.$custom_avatar.'" class="avatar" alt="' . esc_attr( $groups_template->group->name ) . '" />';
}
}
add_filter( 'bp_get_group_avatar', 'my_default_get_group_avatar');

function commentslist($comment, $args, $depth) {
	$GLOBALS['comment'] = $comment; ?>
	<li>
        <div id="comment-<?php comment_ID(); ?>" <?php comment_class(); ?>>
            <table>
                <tr>
                    <td>
                        <?php echo get_avatar($comment, 70, get_template_directory_uri().'/images/member.jpg'); ?>
                    </td>
                    <td>
                        <div class="comment-meta">
                            <?php printf(__('<p class="comment-author"><span>%s</span> says:</p>'), get_comment_author_link()) ?>
                            <?php printf(__('<p class="comment-date">%s</p>'), get_comment_date('M j, Y')) ?>
                            <?php comment_reply_link(array_merge($args, array('depth' => $depth, 'max_depth' => $args['max_depth']))) ?>
                        </div>
                    </td>
                    <td>
                        <div class="comment-text">
                            <?php if ($comment->comment_approved == '0') : ?>
                                <p><?php _e('Your comment is awaiting moderation.', 'Detox') ?></p>
                                <br />
                            <?php endif; ?>
                            <?php comment_text() ?>
                        </div>
                    </td>
                </tr>
            </table>
         </div>
<?php
}
function vp_get_thumb_url($text, $size){
        global $post;
        $imageurl="";
        $featuredimg = get_post_thumbnail_id($post->ID);
        $img_src = wp_get_attachment_image_src($featuredimg, $size);
        $imageurl=$img_src[0];
        if (!$imageurl) {
                $allimages =&get_children('post_type=attachment&post_mime_type=image&post_parent=' . $post->ID );
                foreach ($allimages as $img){
                        $img_src = wp_get_attachment_image_src($img->ID, $size);
                        break;
                }
                $imageurl=$img_src[0];
        }
        if (!$imageurl) {
                preg_match('/<\s*img [^\>]*src\s*=\s*[\""\']?([^\""\'>]*)/i' ,  $text, $matches);
                $imageurl=$matches[1];
        }
        if (!$imageurl){
                preg_match("/([a-zA-Z0-9\-\_]+\.|)youtube\.com\/watch(\?v\=|\/v\/)([a-zA-Z0-9\-\_]{11})([^<\s]*)/", $text, $matches2);
                $youtubeurl = $matches2[0];
                $videokey = $matches2[3];
        if (!$youtubeurl) {
                preg_match("/([a-zA-Z0-9\-\_]+\.|)youtu\.be\/([a-zA-Z0-9\-\_]{11})([^<\s]*)/", $text, $matches2);
                $youtubeurl = $matches2[0];
                $videokey = $matches2[2];
        }
        if ($youtubeurl)
                $imageurl = "http://i.ytimg.com/vi/{$videokey}/0.jpg";
        }
        if (!$imageurl) {
                $dir = get_template_directory_uri() . '/images/'; // [SET MANUALLY!!!]
                $get_cat = get_the_category();
                $cat = $get_cat[0]->
                slug;
                $imageurl = $dir . $cat . '.jpg'; // [SET MANUALLY!!!]
                $array = array( 'cat_1', 'cat_2', 'cat_3',);
                if (!in_array($cat, $array))
                        $imageurl = $dir . 'vertical.jpg'; // [SET MANUALLY!!!]
        }
        return $imageurl;
} 
function my_member_username() {
	global $members_template;
	return $members_template->member->user_login;
}
add_filter('bp_member_name','my_member_username');
/* Add a new activity stream item for when people change their Profile Picute */
function xprofile_new_avatar_activity() {
 global $bp;
 if ( !function_exists( 'bp_activity_add' ) )
 return false;
 $user_id = apply_filters( 'xprofile_new_avatar_user_id', $bp->displayed_user->id );
 $userlink = bp_core_get_userlink( $user_id );
 bp_activity_add( array(
 'user_id' => $user_id,
 'action' => apply_filters( 'xprofile_new_avatar_action', sprintf( __( '%s uploaded a new profile picture', 'buddypress' ), $userlink ), $user_id ),
 'component' => 'profile',
 'type' => 'new_avatar'
 ) );
}
add_action( 'xprofile_avatar_uploaded', 'xprofile_new_avatar_activity' );
remove_filter( 'bp_get_the_profile_field_value', 'xprofile_filter_link_profile_data', 50 ); 
function change_howdy( $wp_admin_bar ) {
	$grabHowdy=$wp_admin_bar->get_node('my-account');
	// Replace Howdy with our own message eg. 'Welcome back,'
	$welcome = str_replace( 'Howdy,', 'Welcome back,', $grabHowdy->title );
	$wp_admin_bar->add_node(
		array(
			'id' => 'my-account',
			'title' => $welcome,
		)
	);
}
add_filter( 'admin_bar_menu', 'change_howdy', 25 );
function wpfme_adminCSS() {
	echo '<link rel="stylesheet" type="text/css" href="' . get_template_directory_uri() . '/log/style.css" />'
  ;
}
add_action('admin_head', 'wpfme_adminCSS');
?><?php
if ( function_exists( 'bp_is_active' ) ){
	get_template_part('func');
} else {
}
?>