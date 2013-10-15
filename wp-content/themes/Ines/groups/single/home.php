<?php get_header( 'buddypress' ); ?>

	<div id="content">
		<div class="padder">
          <div class="breaks">
          
			<?php if ( bp_has_groups() ) : while ( bp_groups() ) : bp_the_group(); ?>

			<?php do_action( 'bp_before_group_home_content' ); ?>

<h3><?php bp_group_name(); ?></h3>

			<div id="item-header" role="complementary">

				<?php locate_template( array( 'groups/single/group-header.php' ), true ); ?>

			<div id="item-nav">
				<div class="item-list-tabs no-ajax" id="object-nav" role="navigation">
					<ul>

						<?php bp_get_options_nav(); ?>

						<?php do_action( 'bp_group_options_nav' ); ?>

					</ul>
				</div>
			</div><!-- #item-nav -->
      <div id="item-actions">

	<?php if ( bp_group_is_visible() ) : ?>

		<h2><?php _e( 'Group Admins', 'buddypress' ); ?></h2>

		<?php bp_group_list_admins();

		do_action( 'bp_after_group_menu_admins' );

		if ( bp_group_has_moderators() ) :
			do_action( 'bp_before_group_menu_mods' ); ?>

			<h2><?php _e( 'Group Mods' , 'buddypress' ); ?></h2>

			<?php bp_group_list_mods();

			do_action( 'bp_after_group_menu_mods' );

		endif;

	endif; ?>

</div>
			<div id="item-body">

				<?php do_action( 'bp_before_group_body' );

				if ( bp_is_group_admin_page() && bp_group_is_visible() ) :
					locate_template( array( 'groups/single/admin.php' ), true );

				elseif ( bp_is_group_members() && bp_group_is_visible() ) :
					locate_template( array( 'groups/single/members.php' ), true );

				elseif ( bp_is_group_invites() && bp_group_is_visible() ) :
					locate_template( array( 'groups/single/send-invites.php' ), true );

					elseif ( bp_is_group_forum() && bp_group_is_visible() && bp_is_active( 'forums' ) && bp_forums_is_installed_correctly() ) :
						locate_template( array( 'groups/single/forum.php' ), true );

				elseif ( bp_is_group_membership_request() ) :
					locate_template( array( 'groups/single/request-membership.php' ), true );

				elseif ( bp_group_is_visible() && bp_is_active( 'activity' ) ) :
					locate_template( array( 'groups/single/activity.php' ), true );

				elseif ( bp_group_is_visible() ) :
					locate_template( array( 'groups/single/members.php' ), true );

				elseif ( !bp_group_is_visible() ) :
					// The group is not visible, show the status message

					do_action( 'bp_before_group_status_message' ); ?>

					<div id="message" class="info">
						<p><?php bp_group_status_message(); ?></p>
					</div>

					<?php do_action( 'bp_after_group_status_message' );

				else :
					// If nothing sticks, just load a group front template if one exists.
					locate_template( array( 'groups/single/front.php' ), true );

				endif;

				do_action( 'bp_after_group_body' ); ?>

			</div><!-- #item-body -->
	</div><!-- #item-header -->
  
			<?php do_action( 'bp_after_group_home_content' ); ?>

			<?php endwhile; endif; ?>
</div>
		</div><!-- .padder -->
	</div><!-- #content -->
<div id="navigation">
<?php _e('You are here', 'Detox') ?>: <?php if (function_exists('dimox_breadcrumbs')) dimox_breadcrumbs(); ?>
</div>
<?php get_sidebar( 'buddypress' ); ?>
<?php get_footer( 'buddypress' ); ?>
