<?php if ( (is_home())  ) { ?><?php } else { ?><?php get_template_part('ff'); ?><?php } ?>

<div id="footer">

<div class="finner">

<div class="hc1">
<?php if ( !function_exists('dynamic_sidebar')
		        || !dynamic_sidebar('footer-column1') ) : ?>

<h3><?php _e( 'Topi', 'Detox') ?><span><?php _e( 'cs', 'Detox') ?></span></h3>
<div class="cats">
<ul>
<?php wp_list_categories('orderby=name&show_count=0&title_li=&number=8'); ?>
</ul>
</div>

<?php endif; ?>
</div>

<div class="hc1">

<?php if ( !function_exists('dynamic_sidebar')
		        || !dynamic_sidebar('footer-column2') ) : ?>

<h3><?php _e( 'Page', 'Detox') ?><span><?php _e( 's', 'Detox') ?></span></h3>
<ul>
  <?php wp_list_pages('sort_column=menu_order&title_li=&number=8'); ?>
</ul>

<?php endif; ?>

</div>

<div class="hc1">
<?php if ( !function_exists('dynamic_sidebar')
		        || !dynamic_sidebar('footer-column3') ) : ?>

<h3><?php _e( 'Pop', 'Detox') ?><span><?php _e( 'ular', 'Detox') ?></span></h3>

<ul id="popular-comments">
<?php
$pc = new WP_Query('orderby=comment_count&posts_per_page=6&cat=');
?>
<?php while ($pc->have_posts()) : $pc->the_post(); ?>

<li>
<a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>"><?php the_title(); ?> <small>&#38; <?php comments_popup_link('No Comments;', '1 Comment', '% Comments'); ?></small></a>
</li>

<?php endwhile; ?>
</ul>


<?php endif; ?>
</div>

<div class="hc2">
<?php if ( !function_exists('dynamic_sidebar')
		        || !dynamic_sidebar('footer-column4') ) : ?>

<h3><?php _e( 'Archi', 'Detox') ?><span><?php _e( 'ves', 'Detox') ?></span></h3>
<div class="cats">
<ul>
<?php wp_get_archives('type=monthly&show_post_count=0&limit=8'); ?>
</ul>
</div>

<?php endif; ?>
</div>

<div class="copy">

<div class="f1">
<?php if ( !function_exists('dynamic_sidebar')
		        || !dynamic_sidebar('footer-column5') ) : ?>

<ul id="f-nav">
<?php wp_list_pages('depth=4&sort_column=menu_order&title_li=&number=4'); ?>
</ul> 

<?php endif; ?>
</div>

<div class="f2">
<?php if ( !function_exists('dynamic_sidebar')
		        || !dynamic_sidebar('footer-column6') ) : ?>

<div class="socials">
<?php 
	global $user_identity, $user_ID;	
if (is_user_logged_in()) { 
?>
<ul>
<li><a href=""><?php echo $user_identity ?></a></li>
<li><a href="<?php echo wp_logout_url(site_url()); ?>" rel="nofollow" title="<?php _e('Logout', 'Detox') ?>"><?php _e('Logout', 'Detox') ?></a></li>
</ul>
<?php 
	} else {	
?>
<ul>
<li><a rel="nofollow" href="/login/"><?php _e('Login', 'Detox') ?></a></li>
<li><a rel="nofollow" href="/wp-login.php?action=register"><?php _e('Register', 'Detox') ?></a></li>
</ul>
<?php } ?>
</div> 

<?php endif; ?>
</div>

</div>

<div class="clearfix"></div><hr class="clear" />
<p class="milo"><?php _e( 'Copyright', 'Detox') ?> &copy; <?php echo date("Y"); ?> <?php bloginfo('name'); ?><br /><a title="Design by milo" href="http://3oneseven.com/">Design by milo</a></p>
<div class="f-img">
<img src="<?php header_image(); ?>" height="<?php echo get_custom_header()->height; ?>" width="<?php echo get_custom_header()->width; ?>" alt="<?php bloginfo('name'); ?>" />
</div>

</div>

</div>
</div>
</div>
</div>

<?php /* "Mumbling around" */ ?> 
<?php wp_footer(); ?>

</body>
</html>