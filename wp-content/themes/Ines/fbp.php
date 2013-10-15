<form action="" method="post" id="members-directory-form" class="dir-form">

<?php if ( bp_has_members( 'type=random&per_page optional=8&max=8' ) ) : ?>

<?php /* variable for alternating post styles */
$style_classes = array('hc5','hc7','hc8');
$styles_count = count($style_classes);
$style_index = 0;
?>
	<?php while ( bp_members() ) : bp_the_member(); ?>

		<div class="<?php echo $style_classes[$style_index++ % $styles_count]; ?> view view-first">
			<div class="hp">
				<a href="<?php bp_member_permalink(); ?>"><?php bp_member_avatar('type=full&amp;width=125&amp;height=125') ?></a>
			<div class="x"></div>
      </div>

			<div class="item">
				<div class="item-title">
					<h2><a href="<?php bp_member_permalink(); ?>"><?php bp_member_name(); ?></a></h2>
				</div>

				<div class="item-meta">
        <span class="activity">
<span><?php _e('from', 'Detox') ?> <?php				 
				  bp_member_profile_data( 'field=Town' );				  
				?></span>
<?php _e('and', 'Detox') ?> <span><?php				 
				  bp_member_profile_data( 'field=Age' );				  
				?></span> <?php _e('years old', 'Detox') ?>.
        </span></div>

				<?php do_action( 'bp_directory_members_item' ); ?>

			</div>

			<div class="action">

				<?php do_action( 'bp_directory_members_actions' ); ?>

			</div>

<div class="mask">
<h3><a href="<?php bp_member_permalink(); ?>"><?php bp_member_name(); ?></a></h3>
<a href="<?php bp_member_permalink(); ?>" class="info"><?php _e('View profile', 'Detox') ?></a>
</div>
		</div>

	<?php endwhile; ?>

	<?php do_action( 'bp_after_directory_members_list' ); ?>

	<?php bp_member_hidden_fields(); ?>

<?php else: ?>

	<div id="message" class="info">
		<p><?php _e( "Sorry, no members were found.", 'buddypress' ); ?></p>
	</div>

<?php endif; ?>

<?php do_action( 'bp_after_members_loop' ); ?>

</form>
