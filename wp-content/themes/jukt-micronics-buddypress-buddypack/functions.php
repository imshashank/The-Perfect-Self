<?php
define( 'BP_DTHEME_DISABLE_CUSTOM_HEADER', true );

add_filter( 'wp_nav_menu_items', 'my_nav_menu_profile_link' );
function my_nav_menu_profile_link($menu) {      
        if (!is_user_logged_in())
                return $menu;
        else
                $profilelink = '<li><a href="' . bp_loggedin_user_domain( '/' ) . '">' . __('Portfolio') . '</a></li>';
                $menu = $menu . $profilelink;
                return $menu;
}

add_theme_support( 'post-thumbnails' ); 
set_post_thumbnail_size( 100, 50, true );  

if ( function_exists('register_sidebar') ){
    register_sidebar(array(
        'name' => 'my_mega_menu',
        'before_widget' => '<div id="my-mega-menu-widget">',
        'after_widget' => '</div>',
        'before_title' => '',
        'after_title' => '',
));
}
?>