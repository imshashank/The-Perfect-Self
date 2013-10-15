<?php if ( is_user_logged_in() ) : ?>
<a href="<?php echo bp_loggedin_user_domain(); ?>"><?php bp_loggedin_user_avatar( 'type=full&width=90&height=90' ); ?></a>			
<h1 class="entry-title"><?php _e( 'Welcome', 'Detox' ); ?> <?php echo bp_core_get_userlink( bp_loggedin_user_id() ); ?></h1>

<div class="item-list-tabs no-ajax" id="object-nav" role="navigation">
<ul>
<li><a href="<?php echo bp_loggedin_user_domain(); ?>activity/"><?php _e( 'Your activity', 'buddypress' ); ?></a></li>
<li><a href="<?php echo bp_loggedin_user_domain(); ?>profile/"><?php _e( 'Your profile', 'buddypress' ); ?></a></li>
<li><a href="<?php echo bp_loggedin_user_domain(); ?>friends/"><?php _e( 'Your friends', 'buddypress' ); ?></a></li>
<li><a href="<?php echo bp_loggedin_user_domain(); ?>settings/"><?php _e( 'Your settings', 'buddypress' ); ?></a></li>
<li><a class="button logout" href="<?php echo wp_logout_url( wp_guess_url() ); ?>"><?php _e( 'Log Out', 'buddypress' ); ?></a></li>
</ul>
</div>
<?php else : ?>
<div class="des"><?php bloginfo('description'); ?></div>
<?php endif; ?>