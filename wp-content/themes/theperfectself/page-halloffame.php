<?php
/*
Template Name: Hall of Fame
*/
?>	
	

	<?php get_header(); ?>

	<div id="content">
		<div class="padder">

		<?php do_action( 'bp_before_blog_page' ); ?>

		<div class="page" id="blog-page" role="main">

<?php /* Widgetized sidebar */
    if ( !function_exists('dynamic_sidebar') || !dynamic_sidebar('my_mega_menu') ) : ?><?php endif; 
wp_gdsr_render_rating_results(array('template_id' => 4, 'rows' => 30, 'select' => 'post', 'hide_empty' => false, 'min_votes' => 0, 'min_count' => 1));
?>
		</div><!-- .page -->

		<?php do_action( 'bp_after_blog_page' ); ?>

		</div><!-- .padder -->
	</div><!-- #content -->

	<?php get_sidebar(); ?>

<?php get_footer(); ?>
