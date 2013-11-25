<?php

/**
 * BuddyPress - Blogs Loop
 *
 * Querystring is set via AJAX in _inc/ajax.php - bp_dtheme_object_filter()
 *
 * @package BuddyPress
 * @subpackage bp-default
 */

?>

<?php do_action( 'bp_before_blogs_loop' ); ?>

<?php if ( bp_has_blogs( bp_ajax_querystring( 'blogs' ) ) ) : ?>

	<div id="pag-top" class="pagination">

		<div class="pag-count" id="blog-dir-count-top">
			<?php bp_blogs_pagination_count(); ?>
		</div>

		<div class="pagination-links" id="blog-dir-pag-top">
			<?php bp_blogs_pagination_links(); ?>
		</div>

	</div>

	<?php do_action( 'bp_before_directory_blogs_list' ); ?>


<?php /* variable for alternating post styles */
$style_classes = array('hc5','hc7','hc6','hc8');
$styles_count = count($style_classes);
$style_index = 0;
?>

	<?php while ( bp_blogs() ) : bp_the_blog(); ?>

		<div class="<?php echo $style_classes[$style_index++ % $styles_count]; ?>">
			<div class="hp">
				<a href="<?php bp_blog_permalink(); ?>"><?php bp_blog_avatar( 'type=thumb' ); ?></a>
			<div class="x"></div>
      </div>

			<div class="item">
				<div class="item-title"><a href="<?php bp_blog_permalink(); ?>"><?php bp_blog_name(); ?></a></div>
				<div class="item-meta"><span class="activity"><?php bp_blog_last_active(); ?></span></div>

				<?php do_action( 'bp_directory_blogs_item' ); ?>
			</div>

			<div class="action">

				<?php do_action( 'bp_directory_blogs_actions' ); ?>

				<div class="meta">

					<?php bp_blog_latest_post(); ?>

				</div>

			</div>

			</div>

	<?php endwhile; ?>

	<?php do_action( 'bp_after_directory_blogs_list' ); ?>

	<?php bp_blog_hidden_fields(); ?>

	<div id="pag-bottom" class="pagination">

		<div class="pag-count" id="blog-dir-count-bottom">

			<?php bp_blogs_pagination_count(); ?>

		</div>

		<div class="pagination-links" id="blog-dir-pag-bottom">

			<?php bp_blogs_pagination_links(); ?>

		</div>

	</div>

<?php else: ?>

	<div id="message" class="info">
		<p><?php _e( 'Sorry, there were no sites found.', 'buddypress' ); ?></p>
	</div>

<?php endif; ?>

<?php do_action( 'bp_after_blogs_loop' ); ?>
