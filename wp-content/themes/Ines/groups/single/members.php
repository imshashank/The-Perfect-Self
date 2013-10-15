<?php if ( bp_group_has_members( 'exclude_admins_mods=0' ) ) : ?>

	<?php do_action( 'bp_before_group_members_content' ); ?>

	<div id="pag-top" class="pagination no-ajax">

		<div class="pag-count" id="member-count-top">

			<?php bp_members_pagination_count(); ?>

		</div>

		<div class="pagination-links" id="member-pag-top">

			<?php bp_members_pagination_links(); ?>

		</div>

	</div>

	<?php do_action( 'bp_before_group_members_list' ); ?>

<?php /* variable for alternating post styles */
$style_classes = array('hc5','hc7','hc8','hc8');
$styles_count = count($style_classes);
$style_index = 0;
?>

		<?php while ( bp_group_members() ) : bp_group_the_member(); ?>

				<div class="<?php echo $style_classes[$style_index++ % $styles_count]; ?> view view-first">
			<div class="hp">
				<a href="<?php bp_group_member_domain(); ?>"><?php bp_group_member_avatar('type=full&amp;width=125&amp;height=125'); ?></a>
        	<div class="x"></div>
      </div>
      
				<h2><?php bp_group_member_link(); ?></h2>
				
				<?php do_action( 'bp_group_members_list_item' ); ?>

				<?php if ( bp_is_active( 'friends' ) ) : ?>

					<div class="action">

						<?php bp_add_friend_button( bp_get_group_member_id(), bp_get_group_member_is_friend() ); ?>

						<?php do_action( 'bp_group_members_list_item_action' ); ?>

					</div>

				<?php endif; ?>
        <div class="mask">
<h3><a href="<?php bp_member_permalink(); ?>"><?php bp_member_name(); ?></a></h3>
<a href="<?php bp_member_permalink(); ?>" class="info"><?php _e('View profile', 'Detox') ?></a>
</div>
			</div>

		<?php endwhile; ?>

	<?php do_action( 'bp_after_group_members_list' ); ?>

	<div id="pag-bottom" class="pagination no-ajax">

		<div class="pag-count" id="member-count-bottom">

			<?php bp_members_pagination_count(); ?>

		</div>

		<div class="pagination-links" id="member-pag-bottom">

			<?php bp_members_pagination_links(); ?>

		</div>

	</div>

	<?php do_action( 'bp_after_group_members_content' ); ?>

<?php else: ?>

	<div id="message" class="info">
		<p><?php _e( 'This group has no members.', 'buddypress' ); ?></p>
	</div>

<?php endif; ?>
