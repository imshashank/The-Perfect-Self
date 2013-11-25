<?php

/**
 * BuddyPress - Groups Loop
 *
 * Querystring is set via AJAX in _inc/ajax.php - bp_dtheme_object_filter()
 *
 * @package BuddyPress
 * @subpackage bp-default
 */

?>

<?php do_action( 'bp_before_groups_loop' ); ?>

<?php if ( bp_has_groups( bp_ajax_querystring( 'groups' ) ) ) : ?>


	<?php do_action( 'bp_before_directory_groups_list' ); ?>

	<?php /* variable for alternating post styles */
$style_classes = array('hc5','hc7','hc8','hc8');
$styles_count = count($style_classes);
$style_index = 0;
?>

	<?php while ( bp_groups() ) : bp_the_group(); ?>

		<div class="<?php echo $style_classes[$style_index++ % $styles_count]; ?> view view-first">
			<div class="hp">
				<a href="<?php bp_group_permalink(); ?>"><?php bp_group_avatar( 'type=thumb&width=125&height=125' ); ?></a>
			<div class="x"></div>
      </div>

			<div class="item">
				<div class="item-title">
        <h2><a href="<?php bp_group_permalink(); ?>"><?php bp_group_name(); ?></a></h2>
        </div>
				<div class="item-meta">
        <span class="activity">
        <?php
echo bp_get_total_member_count();
?>
        </span></div>

				<div class="item-desc"><?php bp_group_description_excerpt(); ?></div>

				<?php do_action( 'bp_directory_groups_item' ); ?>

			</div>

			<div class="action">

				<?php do_action( 'bp_directory_groups_actions' ); ?>

			</div>
      <div class="mask">
<h3><a href="<?php bp_group_permalink(); ?>"><?php bp_group_name(); ?></a></h3>
<a href="<?php bp_group_permalink(); ?>" class="info"><?php _e('View group', 'Detox') ?></a>
</div>
			</div>

	<?php endwhile; ?>

	<?php do_action( 'bp_after_directory_groups_list' ); ?>

<?php else: ?>

	<div id="message" class="info">
		<p><?php _e( 'There were no groups found.', 'buddypress' ); ?></p>
	</div>

<?php endif; ?>

<?php do_action( 'bp_after_groups_loop' ); ?>
