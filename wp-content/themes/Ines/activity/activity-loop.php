<?php

/**
 * BuddyPress - Activity Loop
 *
 * Querystring is set via AJAX in _inc/ajax.php - bp_dtheme_object_filter()
 *
 * @package BuddyPress
 * @subpackage bp-default
 */

?>

<?php do_action( 'bp_before_activity_loop' ); ?>

<?php if ( bp_has_activities( bp_ajax_querystring( 'activity' ) ) ) : ?>

	<?php if ( empty( $_POST['page'] ) ) : ?>
	
	<?php endif; ?>
  <?php /* variable for alternating post styles */
$style_classes = array('hc5','hc7','hc8','hc8');
$styles_count = count($style_classes);
$style_index = 0;
?>
	<?php while ( bp_activities() ) : bp_the_activity(); ?>
    <div class="<?php echo $style_classes[$style_index++ % $styles_count]; ?> view view-first">
		<?php locate_template( array( 'activity/entry.php' ), true, false ); ?>
    </div>
	<?php endwhile; ?>

	<?php if ( bp_activity_has_more_items() ) : ?>

    
	<?php endif; ?>

	<?php if ( empty( $_POST['page'] ) ) : ?>

	<?php endif; ?>

<?php else : ?>

	<div id="message" class="info">
		<p><?php _e( 'Sorry, there was no activity found. Please try a different filter.', 'buddypress' ); ?></p>
	</div>

<?php endif; ?>

<?php do_action( 'bp_after_activity_loop' ); ?>

<form action="" name="activity-loop-form" id="activity-loop-form" method="post">

	<?php wp_nonce_field( 'activity_filter', '_wpnonce_activity_filter' ); ?>

</form>