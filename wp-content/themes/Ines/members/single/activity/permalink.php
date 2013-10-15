<?php get_header( 'buddypress' ); ?>
<div id="smain">
<div id="content">
<div class="padder">
<?php do_action( 'template_notices' ); ?>
<div class="entry">
<div class="activity no-ajax" role="main">
	<?php if ( bp_has_activities( 'display_comments=threaded&show_hidden=true&include=' . bp_current_action() ) ) : ?>

		<div class="breaks">
		<?php while ( bp_activities() ) : bp_the_activity(); ?>

			<?php locate_template( array( 'activity/entry.php' ), true ); ?>

		<?php endwhile; ?>
		</div>

	<?php endif; ?>

</div>
</div>
<div id="navigation">
<?php _e('You are here', 'Detox') ?>: <?php if (function_exists('dimox_breadcrumbs')) dimox_breadcrumbs(); ?>
</div>  
</div>
</div>
</div>
<?php get_footer( 'buddypress' ); ?>