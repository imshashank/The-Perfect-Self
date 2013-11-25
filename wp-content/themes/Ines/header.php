<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" <?php language_attributes(); ?>>
<head profile="http://gmpg.org/xfn/11">
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<meta name="viewport" content="initial-scale=1.0, maximum-scale=1.0, user-scalable=no, width=device-width" />	

<title><?php wp_title( '|', true, 'right' ); bloginfo( 'name' ); ?> | <?php bloginfo('description'); ?></title>

<link rel="stylesheet" href="<?php bloginfo('stylesheet_url'); ?>" type="text/css" media="screen" />
<link href='http://fonts.googleapis.com/css?family=Abril+Fatface|Montserrat:700' rel='stylesheet' type='text/css' />
<?php
if ( function_exists( 'bp_is_active' ) ){
	get_template_part('style');
} else {
}
?>
<link rel="shortcut icon" type="image/ico" href="<?php echo get_template_directory_uri(); ?>/images/favicon.png" />
<link rel="stylesheet" href="<?php echo get_template_directory_uri(); ?>/mobi.css" type="text/css" media="handheld" />
<link rel="stylesheet" href="<?php echo get_template_directory_uri(); ?>/tab.css" type="text/css" media="only screen and (max-width: 920px), only screen and (max-device-width: 920px)" />
<link rel="stylesheet" href="<?php echo get_template_directory_uri(); ?>/mobi.css" type="text/css" media="only screen and (max-width: 720px), only screen and (max-device-width: 720px)" />
<link href='http://fonts.googleapis.com/css?family=Monda:400,700' rel='stylesheet' type='text/css' />
<script src="<?php echo get_template_directory_uri(); ?>/js/contentslider.js" type="text/javascript"></script>
<script src="<?php echo get_template_directory_uri(); ?>/js/ibox.js" type="text/javascript"></script>
<?php do_action( 'bp_head' ) ?>
<?php wp_head(); ?>
<?php if ( is_singular() ) wp_enqueue_script( "comment-reply" ); ?>
</head>
<body <?php body_class(); ?> id="ines_theme_by_milo317">

<div id="wrapper">

<div id="main">
<div class="wrap">

<div id="fheader">
<div id="header">

<div class="head">
<div id="logo"><h1><a href="<?php home_url(); ?>/"><?php bloginfo('name'); ?></a></h1></div>
</div>

<div class="mainmenu">
<div class="menu-header">

<?php
if(function_exists('wp_nav_menu')) {
wp_nav_menu(array(
'theme_location' => 'top-nav',
'container' => '',
'container_id' => 'logo-inner',
'menu_id' => 'top-nav',
'fallback_cb' => 'topnav_fallback',
));
} else {
?>
<?php
}
?>

</div>
</div>

<div class="soc">
<div class="hsoc">
<form id="searchform" method="get" action="<?php echo esc_url( home_url( '/' ) ); ?>">
<input type="text" value="<?php _e( 'Search...', 'Detox') ?>" name="s" id="s" onfocus="if (this.value == '<?php _e( 'Search...', 'Detox') ?>') {this.value = '';}" onblur="if (this.value == '') {this.value = '<?php _e( 'Search more', 'Detox') ?>';}" />
</form>
</div>
</div>

<div class="cimg">
<a href=""><img src="<?php echo get_stylesheet_directory_uri() ?>/images/fb.png" alt="social activity" /></a>
<a href=""><img src="<?php echo get_stylesheet_directory_uri() ?>/images/twi.png" alt="social activity" /></a>
<a href=""><img src="<?php echo get_stylesheet_directory_uri() ?>/images/utube.png" alt="social activity" /></a>
<a href=""><img src="<?php echo get_stylesheet_directory_uri() ?>/images/vimeo.png" alt="social activity" /></a>
</div>

</div>
</div>

<div id="lhead">
<?php
if ( function_exists( 'bp_is_active' ) ){
	get_template_part('bphead');
} else {
}
?>
</div>