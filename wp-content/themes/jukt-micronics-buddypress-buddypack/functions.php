<?php
define( 'BP_DTHEME_DISABLE_CUSTOM_HEADER', true );

add_filter( 'wp_nav_menu_items', 'my_nav_menu_profile_link' );
function my_nav_menu_profile_link($menu) {      
        if (!is_user_logged_in())
                return $menu;
        else
                $profilelink = '<li><a href="' . bp_loggedin_user_domain( '/' ) . '">' . __('Visit your Awesome Profile') . '</a></li>';
                $menu = $menu . $profilelink;
                return $menu;
}

?>
