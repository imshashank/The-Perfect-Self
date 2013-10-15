<?php
/*
Plugin Name: TPWP User Properties 
Plugin URI: http://shashank.pw/
Description: Ability to add properties to user profiles
Version: 1.0
Author: Shashank Agarwal
Author URI: http://shashank.pw/
*/

// Our functions will be here

// Function for adding fields
function extra_profile_fields( $user ) { ?>
 <h3><?php _e('Extra Profile Fields', 'frontendprofile'); ?></h3>
 <table class="form-table">
 <tr>
   <th><label for="gplus">Google+</label></th>
   <td>
     <input type="text" name="gplus" id="gplus" value="<?php echo esc_attr( get_the_author_meta( 'gplus', $user->ID ) ); ?>" class="regular-text" /><br />
     <span class="description">Enter the Google+ URL.</span>
   </td>
 </tr>
 <tr>
   <th><label for="twitter">Twitter Username</label></th>
   <td>
     <input type="text" name="twitter" id="twitter" value="<?php echo esc_attr( get_the_author_meta( 'twitter', $user->ID ) ); ?>" class="regular-text" /><br />
     <span class="description">Enter Twitter Username.</span>
   </td>
 </tr>
 <tr>
   <th><label for="facebook">Facebook</label></th>
   <td>
     <input type="text" name="facebook" id="facebook" value="<?php echo esc_attr( get_the_author_meta( 'facebook', $user->ID ) ); ?>" class="regular-text" /><br />
     <span class="description">Enter Facebook URL.</span>
   </td>
 </tr>
 </table>
<?php } // Function body ends

// Adding actions to show and edit the field
add_action( 'show_user_profile', 'extra_profile_fields', 10 );
add_action( 'edit_user_profile', 'extra_profile_fields', 10 );

function save_extra_profile_fields( $user_id ) {

	if ( !current_user_can( 'edit_user', $user_id ) )
		return false;

	/* Edit the following lines according to your set fields */
	update_usermeta( $user_id, 'twitter', $_POST['gplus'] );
	update_usermeta( $user_id, 'twitter', $_POST['twitter'] );
	update_usermeta( $user_id, 'twitter', $_POST['facebook'] );
}

add_action( 'personal_options_update', 'save_extra_profile_fields' );
add_action( 'edit_user_profile_update', 'save_extra_profile_fields' );

/* End of plugin */
