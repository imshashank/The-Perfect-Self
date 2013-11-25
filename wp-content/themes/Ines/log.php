<?php do_action( 'bp_inside_before_sidebar' ); ?>

<?php if ( is_user_logged_in() ) : ?>
<?php else : ?>

		<?php do_action( 'bp_before_sidebar_login_form' ); ?>

		<?php if ( bp_get_signup_allowed() ) : ?>
		
		<?php endif; ?>

		<form name="login-form" id="sidebar-login-form" class="standard-form" action="<?php echo site_url( 'wp-login.php', 'login_post' ); ?>" method="post">
			<label class="hch"><?php _e( 'Username', 'buddypress' ); ?><br />
			<input type="text" name="log" id="sidebar-user-login" class="input" value="<?php if ( isset( $user_login) ) echo esc_attr(stripslashes($user_login)); ?>" tabindex="97" />
      </label>

			<label class="hch"><?php _e( 'Password', 'buddypress' ); ?><br />
			<input type="password" name="pwd" id="sidebar-user-pass" class="input" value="" tabindex="98" />
      </label>

			<?php do_action( 'bp_sidebar_login_form' ); ?>
			<input type="submit" name="wp-submit" id="sidebar-wp-submit" value="<?php _e( 'Log In', 'buddypress' ); ?>" tabindex="100" />
			<input type="hidden" name="testcookie" value="1" />
		
    </form>
    <?php if ( bp_get_signup_allowed() ) : ?>
    <div id="crear">
				<?php printf( __( '<a href="%s" title="Create an account">Create an account</a>', 'buddypress' ), bp_get_signup_page() ); ?>
			</div>
    <?php endif; ?>
    
		<?php do_action( 'bp_after_sidebar_login_form' ); ?>

	<?php endif; ?>