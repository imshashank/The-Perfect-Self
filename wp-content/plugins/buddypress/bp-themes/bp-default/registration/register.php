<html xmlns="http://www.w3.org/1999/xhtml" <?php language_attributes(); ?>>
	<head profile="http://gmpg.org/xfn/11">
		<meta http-equiv="Content-Type" content="<?php bloginfo( 'html_type' ); ?>; charset=<?php bloginfo( 'charset' ); ?>" />
		<?php if ( current_theme_supports( 'bp-default-responsive' ) ) : ?><meta name="viewport" content="width=device-width, initial-scale=1.0" /><?php endif; ?>
		<title><?php wp_title( '|', true, 'right' ); bloginfo( 'name' ); ?></title>
		
<!--Mine-->
  <meta charset="utf-8" />

  <!-- Set the viewport width to device width for mobile -->
  <meta name="viewport" content="width=device-width" />

<link rel="stylesheet" href="http://server17.ies.cse.ohio-state.edu/test/TPS/css/normalize.css">
<link rel="stylesheet" href="http://server17.ies.cse.ohio-state.edu/test/TPS/css/foundation.css">
<link rel="stylesheet" href="http://server17.ies.cse.ohio-state.edu/test/TPS/css/main.css">
<script src="http://server17.ies.cse.ohio-state.edu/test/TPS/js/vendor/custom.modernizr.js"></script>

  
<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>" />

		<?php bp_head(); ?>
		<?php wp_head(); ?>

	</head>
	
	
<body <?php body_class(); ?> id="bp-default">

		<?php do_action( 'bp_before_header' ); ?>


  <!-- Header and Nav -->
  
  
  <div class="large-12 columns">
  <div class="row">

    <div class="large-5 columns">
      <!--<h1><img src="Logo.png" ></h1>-->
      <br />
      <span class="logo">The Perfect Self</span>
    </div>
    <div class="large-6 columns">
    

  
		<div class="row">

			 <div class="large-12 columns ">
	 
		<form name="login-form" id="sidebar-login-form" class="standard-form" action="<?php echo site_url( 'wp-login.php', 'login_post' ); ?>" method="post">

<div class="large-5 columns">
<div class="row">
					<label><?php _e( 'Username', 'buddypress' ); ?><br />
					<input type="text" name="log" id="sidebar-user-login" class="input" value="<?php if ( isset( $user_login) ) echo esc_attr(stripslashes($user_login)); ?>" tabindex="97" /></label>
</div>
<div class="row">

<p class="forgetmenot"><label><input name="rememberme" type="checkbox" id="sidebar-rememberme" value="forever" tabindex="99" /> <?php _e( 'Remember Me', 'buddypress' ); ?></label></p>
</div>
</div>
		<div class="large-5 columns">

<div class="row">
					<label><?php _e( 'Password', 'buddypress' ); ?><br />
					<input type="password" name="pwd" id="sidebar-user-pass" class="input" value="" tabindex="98" /></label>
</div>
		
					<div class="row">
					<input type="submit" class="login-submit" name="wp-submit" id="sidebar-wp-submit" value="<?php _e( 'Log In', 'buddypress' ); ?>" tabindex="100" />
					</form>
</div>
</div>
</form>
		
					<?php do_action( 'bp_sidebar_login_form' ); ?>
		
			   </div>
			</div>
 
  </div>
</div>


  <!-- End Header and Nav -->

  <!-- First Band (Image) -->
<!--<hr />-->
  <div class="row secondRow">
  <br /><br />
    <div class="large-12 columns">
      <!--<img src="http://placehold.it/1000x400&text=[img]">-->
      
      
		<div class="large-12 columns"> 
		<div class="large-12 columns left">
		
		<div class="row">
		      <span class="empText">Create An Account</span>
		</div>
		

<div id="content">
<div class="noBorder">

		<div class="padder">

		<?php do_action( 'bp_before_register_page' ); ?>

		<div class="page" id="register-page">

			<form action="" name="signup_form" id="signup_form" class="standard-form" method="post" enctype="multipart/form-data">

			<?php if ( 'registration-disabled' == bp_get_current_signup_step() ) : ?>
				<?php do_action( 'template_notices' ); ?>
				<?php do_action( 'bp_before_registration_disabled' ); ?>

					<p><?php _e( 'User registration is currently not allowed.', 'buddypress' ); ?></p>

				<?php do_action( 'bp_after_registration_disabled' ); ?>
			<?php endif; // registration-disabled signup setp ?>

			<?php if ( 'request-details' == bp_get_current_signup_step() ) : ?>

				<?php do_action( 'template_notices' ); ?>

				<?php do_action( 'bp_before_account_details_fields' ); ?>

				<div class="register-section" id="basic-details-section">

					<?php /***** Basic Account Details ******/ ?>
					<h4><?php _e( 'Account Details', 'buddypress' ); ?></h4>

					<label for="signup_username"><?php _e( 'Username', 'buddypress' ); ?> <?php _e( '(required)', 'buddypress' ); ?></label>
					<?php do_action( 'bp_signup_username_errors' ); ?>
					<input type="text" name="signup_username" id="signup_username" value="<?php bp_signup_username_value(); ?>" />

					<label for="signup_email"><?php _e( 'Email Address', 'buddypress' ); ?> <?php _e( '(required)', 'buddypress' ); ?></label>
					<?php do_action( 'bp_signup_email_errors' ); ?>
					<input type="text" name="signup_email" id="signup_email" value="<?php bp_signup_email_value(); ?>" />

					<label for="signup_password"><?php _e( 'Choose a Password', 'buddypress' ); ?> <?php _e( '(required)', 'buddypress' ); ?></label>
					<?php do_action( 'bp_signup_password_errors' ); ?>
					<input type="password" name="signup_password" id="signup_password" value="" />

					<label for="signup_password_confirm"><?php _e( 'Confirm Password', 'buddypress' ); ?> <?php _e( '(required)', 'buddypress' ); ?></label>
					<?php do_action( 'bp_signup_password_confirm_errors' ); ?>
					<input type="password" name="signup_password_confirm" id="signup_password_confirm" value="" />
					
					<?php do_action( 'bp_before_registration_submit_buttons' ); ?>
					<div class="row left">
									<div class="submit">
										<input type="submit" name="signup_submit" id="signup_submit" value="<?php _e( 'Complete Sign Up', 'buddypress' ); ?>" />
									</div>
</div>
					
									<?php do_action( 'bp_after_registration_submit_buttons' ); ?>
					
									<?php wp_nonce_field( 'bp_new_signup' ); ?>
					

				</div><!-- #basic-details-section -->

				<?php do_action( 'bp_after_account_details_fields' ); ?>

				<?php /***** Extra Profile Details ******/ ?>

				<?php if ( bp_is_active( 'xprofile' ) ) : ?>

					<?php do_action( 'bp_before_signup_profile_fields' ); ?>

					<div class="register-section" id="profile-details-section">

						<h4><?php _e( 'Profile Details', 'buddypress' ); ?></h4>

						<?php /* Use the profile field loop to render input fields for the 'base' profile field group */ ?>
						<?php if ( bp_is_active( 'xprofile' ) ) : if ( bp_has_profile( 'profile_group_id=1' ) ) : while ( bp_profile_groups() ) : bp_the_profile_group(); ?>

						<?php while ( bp_profile_fields() ) : bp_the_profile_field(); ?>

							<div class="editfield">

								<?php if ( 'textbox' == bp_get_the_profile_field_type() ) : ?>

									<label for="<?php bp_the_profile_field_input_name(); ?>"><?php bp_the_profile_field_name(); ?> <?php if ( bp_get_the_profile_field_is_required() ) : ?><?php _e( '(required)', 'buddypress' ); ?><?php endif; ?></label>
									<?php do_action( bp_get_the_profile_field_errors_action() ); ?>
									<input type="text" name="<?php bp_the_profile_field_input_name(); ?>" id="<?php bp_the_profile_field_input_name(); ?>" value="<?php bp_the_profile_field_edit_value(); ?>" />

								<?php endif; ?>

								<?php if ( 'textarea' == bp_get_the_profile_field_type() ) : ?>

									<label for="<?php bp_the_profile_field_input_name(); ?>"><?php bp_the_profile_field_name(); ?> <?php if ( bp_get_the_profile_field_is_required() ) : ?><?php _e( '(required)', 'buddypress' ); ?><?php endif; ?></label>
									<?php do_action( bp_get_the_profile_field_errors_action() ); ?>
									<textarea rows="5" cols="40" name="<?php bp_the_profile_field_input_name(); ?>" id="<?php bp_the_profile_field_input_name(); ?>"><?php bp_the_profile_field_edit_value(); ?></textarea>

								<?php endif; ?>

								<?php if ( 'selectbox' == bp_get_the_profile_field_type() ) : ?>

									<label for="<?php bp_the_profile_field_input_name(); ?>"><?php bp_the_profile_field_name(); ?> <?php if ( bp_get_the_profile_field_is_required() ) : ?><?php _e( '(required)', 'buddypress' ); ?><?php endif; ?></label>
									<?php do_action( bp_get_the_profile_field_errors_action() ); ?>
									<select name="<?php bp_the_profile_field_input_name(); ?>" id="<?php bp_the_profile_field_input_name(); ?>">
										<?php bp_the_profile_field_options(); ?>
									</select>

								<?php endif; ?>

								<?php if ( 'multiselectbox' == bp_get_the_profile_field_type() ) : ?>

									<label for="<?php bp_the_profile_field_input_name(); ?>"><?php bp_the_profile_field_name(); ?> <?php if ( bp_get_the_profile_field_is_required() ) : ?><?php _e( '(required)', 'buddypress' ); ?><?php endif; ?></label>
									<?php do_action( bp_get_the_profile_field_errors_action() ); ?>
									<select name="<?php bp_the_profile_field_input_name(); ?>" id="<?php bp_the_profile_field_input_name(); ?>" multiple="multiple">
										<?php bp_the_profile_field_options(); ?>
									</select>

								<?php endif; ?>

								<?php if ( 'radio' == bp_get_the_profile_field_type() ) : ?>

									<div class="radio">
										<span class="label"><?php bp_the_profile_field_name(); ?> <?php if ( bp_get_the_profile_field_is_required() ) : ?><?php _e( '(required)', 'buddypress' ); ?><?php endif; ?></span>

										<?php do_action( bp_get_the_profile_field_errors_action() ); ?>
										<?php bp_the_profile_field_options(); ?>

										<?php if ( !bp_get_the_profile_field_is_required() ) : ?>
											<a class="clear-value" href="javascript:clear( '<?php bp_the_profile_field_input_name(); ?>' );"><?php _e( 'Clear', 'buddypress' ); ?></a>
										<?php endif; ?>
									</div>

								<?php endif; ?>

								<?php if ( 'checkbox' == bp_get_the_profile_field_type() ) : ?>

									<div class="checkbox">
										<span class="label"><?php bp_the_profile_field_name(); ?> <?php if ( bp_get_the_profile_field_is_required() ) : ?><?php _e( '(required)', 'buddypress' ); ?><?php endif; ?></span>

										<?php do_action( bp_get_the_profile_field_errors_action() ); ?>
										<?php bp_the_profile_field_options(); ?>
									</div>

								<?php endif; ?>

								<?php if ( 'datebox' == bp_get_the_profile_field_type() ) : ?>

									<div class="datebox">
										<label for="<?php bp_the_profile_field_input_name(); ?>_day"><?php bp_the_profile_field_name(); ?> <?php if ( bp_get_the_profile_field_is_required() ) : ?><?php _e( '(required)', 'buddypress' ); ?><?php endif; ?></label>
										<?php do_action( bp_get_the_profile_field_errors_action() ); ?>

										<select name="<?php bp_the_profile_field_input_name(); ?>_day" id="<?php bp_the_profile_field_input_name(); ?>_day">
											<?php bp_the_profile_field_options( 'type=day' ); ?>
										</select>

										<select name="<?php bp_the_profile_field_input_name(); ?>_month" id="<?php bp_the_profile_field_input_name(); ?>_month">
											<?php bp_the_profile_field_options( 'type=month' ); ?>
										</select>

										<select name="<?php bp_the_profile_field_input_name(); ?>_year" id="<?php bp_the_profile_field_input_name(); ?>_year">
											<?php bp_the_profile_field_options( 'type=year' ); ?>
										</select>
									</div>

								<?php endif; ?>

								<?php do_action( 'bp_custom_profile_edit_fields_pre_visibility' ); ?>

								<?php if ( bp_current_user_can( 'bp_xprofile_change_field_visibility' ) ) : ?>
									<p class="field-visibility-settings-toggle" id="field-visibility-settings-toggle-<?php bp_the_profile_field_id() ?>">
										<?php printf( __( 'This field can be seen by: <span class="current-visibility-level">%s</span>', 'buddypress' ), bp_get_the_profile_field_visibility_level_label() ) ?> <a href="#" class="visibility-toggle-link"><?php _ex( 'Change', 'Change profile field visibility level', 'buddypress' ); ?></a>
									</p>

									<div class="field-visibility-settings" id="field-visibility-settings-<?php bp_the_profile_field_id() ?>">
										<fieldset>
											<legend><?php _e( 'Who can see this field?', 'buddypress' ) ?></legend>

											<?php bp_profile_visibility_radio_buttons() ?>

										</fieldset>
										<a class="field-visibility-settings-close" href="#"><?php _e( 'Close', 'buddypress' ) ?></a>

									</div>
								<?php else : ?>
									<p class="field-visibility-settings-notoggle" id="field-visibility-settings-toggle-<?php bp_the_profile_field_id() ?>">
										<?php printf( __( 'This field can be seen by: <span class="current-visibility-level">%s</span>', 'buddypress' ), bp_get_the_profile_field_visibility_level_label() ) ?>
									</p>
								<?php endif ?>

								<?php do_action( 'bp_custom_profile_edit_fields' ); ?>

								<p class="description"><?php bp_the_profile_field_description(); ?></p>

							</div>

						<?php endwhile; ?>

						<input type="hidden" name="signup_profile_field_ids" id="signup_profile_field_ids" value="<?php bp_the_profile_group_field_ids(); ?>" />

						<?php endwhile; endif; endif; ?>

					</div><!-- #profile-details-section -->

					<?php do_action( 'bp_after_signup_profile_fields' ); ?>

				<?php endif; ?>

				<?php if ( bp_get_blog_signup_allowed() ) : ?>

					<?php do_action( 'bp_before_blog_details_fields' ); ?>

					<?php /***** Blog Creation Details ******/ ?>

					<div class="register-section" id="blog-details-section">

						<h4><?php _e( 'Blog Details', 'buddypress' ); ?></h4>

						<p><input type="checkbox" name="signup_with_blog" id="signup_with_blog" value="1"<?php if ( (int) bp_get_signup_with_blog_value() ) : ?> checked="checked"<?php endif; ?> /> <?php _e( 'Yes, I\'d like to create a new site', 'buddypress' ); ?></p>

						<div id="blog-details"<?php if ( (int) bp_get_signup_with_blog_value() ) : ?>class="show"<?php endif; ?>>

							<label for="signup_blog_url"><?php _e( 'Blog URL', 'buddypress' ); ?> <?php _e( '(required)', 'buddypress' ); ?></label>
							<?php do_action( 'bp_signup_blog_url_errors' ); ?>

							<?php if ( is_subdomain_install() ) : ?>
								http:// <input type="text" name="signup_blog_url" id="signup_blog_url" value="<?php bp_signup_blog_url_value(); ?>" /> .<?php bp_blogs_subdomain_base(); ?>
							<?php else : ?>
								<?php echo home_url( '/' ); ?> <input type="text" name="signup_blog_url" id="signup_blog_url" value="<?php bp_signup_blog_url_value(); ?>" />
							<?php endif; ?>

							<label for="signup_blog_title"><?php _e( 'Site Title', 'buddypress' ); ?> <?php _e( '(required)', 'buddypress' ); ?></label>
							<?php do_action( 'bp_signup_blog_title_errors' ); ?>
							<input type="text" name="signup_blog_title" id="signup_blog_title" value="<?php bp_signup_blog_title_value(); ?>" />

							<span class="label"><?php _e( 'I would like my site to appear in search engines, and in public listings around this network.', 'buddypress' ); ?>:</span>
							<?php do_action( 'bp_signup_blog_privacy_errors' ); ?>

							<label><input type="radio" name="signup_blog_privacy" id="signup_blog_privacy_public" value="public"<?php if ( 'public' == bp_get_signup_blog_privacy_value() || !bp_get_signup_blog_privacy_value() ) : ?> checked="checked"<?php endif; ?> /> <?php _e( 'Yes', 'buddypress' ); ?></label>
							<label><input type="radio" name="signup_blog_privacy" id="signup_blog_privacy_private" value="private"<?php if ( 'private' == bp_get_signup_blog_privacy_value() ) : ?> checked="checked"<?php endif; ?> /> <?php _e( 'No', 'buddypress' ); ?></label>

						</div>

					</div><!-- #blog-details-section -->

					<?php do_action( 'bp_after_blog_details_fields' ); ?>

				<?php endif; ?>

				
			<?php endif; // request-details signup step ?>

			<?php if ( 'completed-confirmation' == bp_get_current_signup_step() ) : ?>

				<h2><?php _e( 'Check Your Email To Activate Your Account!', 'buddypress' ); ?></h2>

				<?php do_action( 'template_notices' ); ?>
				<?php do_action( 'bp_before_registration_confirmed' ); ?>

				<?php if ( bp_registration_needs_activation() ) : ?>
					<p><?php _e( 'You have successfully created your account! To begin using this site you will need to activate your account via the email we have just sent to your address.', 'buddypress' ); ?></p>
				<?php else : ?>
					<p><?php _e( 'You have successfully created your account! Please log in using the username and password you have just created.', 'buddypress' ); ?></p>
				<?php endif; ?>

				<?php do_action( 'bp_after_registration_confirmed' ); ?>

			<?php endif; // completed-confirmation signup step ?>

			<?php do_action( 'bp_custom_signup_steps' ); ?>

			</form>

		</div>

			<?php do_action( 'bp_after_register_page' ); ?>

		</div><!-- .padder -->
		</div><!-- .noBorder -->
		
	</div><!-- #content -->

	<?php do_action( 'bp_after_register_page' ); ?>

			<div class="large-2 columns">&nbsp;</div>
	 </div>	
      
    </div>
  </div>
  
  <!-- Second Band (Image Left with Text) -->


  <!-- Third Band (Image Right with Text) -->
</div><!-- Second Row-->

  <div class="row">
  
  <br /><br />
  <div class="large-2 columns"></div>
  
    <div class="large-5 columns">
       <span class="empText">What is The Perfect Self?</span>
      
      <p>A socially conscious and conscientious networking site, with a goal to celebrate The Perfect Self in each of us through a helpful online society.</p>

      
    </div>
    <div class="large-5 columns">
      <img src="http://server17.ies.cse.ohio-state.edu/test/TPS/values.png" class="homeImg">
    </div>
  </div>


  <!-- Footer -->

  <footer class="row">
    <div class="large-12 columns">
      <hr />
      <div class="row">
        <div class="large-6 columns">
          <p>&copy; The Perfect Self. 2013.</p>
        </div>
        <div class="large-6 columns">
          <!--<ul class="inline-list right">
            <li><a href="#">Link 1</a></li>
            <li><a href="#">Link 2</a></li>
            <li><a href="#">Link 3</a></li>
            <li><a href="#">Link 4</a></li>
          </ul>-->
        </div>
      </div>
    </div>
   
  </footer>
  </div>
  
  	<script type="text/javascript">
  		jQuery(document).ready( function() {
  			if ( jQuery('div#blog-details').length && !jQuery('div#blog-details').hasClass('show') )
  				jQuery('div#blog-details').toggle();
  
  			jQuery( 'input#signup_with_blog' ).click( function() {
  				jQuery('div#blog-details').fadeOut().toggle();
  			});
  		});
  	</script>
  
  <script>
document.write('<script src=http://server17.ies.cse.ohio-state.edu/test/TPS/js/vendor/' +
('__proto__' in {} ? 'zepto' : 'jquery') +
'.js><\/script>')
</script>
<script src="http://server17.ies.cse.ohio-state.edu/test/TPS/js/foundation.min.js"></script>
 <script src="http://server17.ies.cse.ohio-state.edu/test/TPS/js/foundation/foundation.section.js"></script>
<script>
  $(document).foundation();
  $('#myModal').on('opened', function () {
    $(this).foundation('section', 'reflow');
  });
</script>
	</body>

</html>
