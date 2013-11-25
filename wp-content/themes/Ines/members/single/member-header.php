<?php do_action( 'bp_before_member_header' ); ?>

<div id="item-header-avatar">
<a href="<?php bp_displayed_user_link(); ?>"><?php bp_displayed_user_avatar( 'type=full' ); ?></a>
</div>

<div id="item-header-content">

<h2>
<a href="<?php bp_displayed_user_link(); ?>"><?php _e('hi, I am', 'Detox') ?> <?php bp_displayed_user_fullname(); ?></a>
<?php if ( $data = bp_get_profile_field_data( 'field=Town ' ) ) : ?>
<br /><span><?php _e('from', 'Detox') ?> <?php echo $data ?></span>
<?php endif ?> 
<?php if ( $data = bp_get_profile_field_data( 'field=Age ' ) ) : ?>
<?php _e('and', 'Detox') ?> <span><?php echo $data ?></span> <?php _e('years old', 'Detox') ?>
<?php endif ?>.
</h2>

<?php if ( $data = bp_get_profile_field_data( 'field=Hobby ' ) ) : ?>
<?php _e('My hobby:', 'Detox') ?> <span><?php echo $data ?></span> 
<?php endif ?> 
    
<?php do_action( 'bp_before_member_header_meta' ); ?>

	<div id="item-meta">

		<?php if ( bp_is_active( 'activity' ) ) : ?>

<div id="latest-update"><?php bp_activity_latest_update( bp_displayed_user_id() ); ?></div>

		<?php endif; ?>

		<div id="item-buttons">

			<?php do_action( 'bp_member_header_actions' ); ?>

		</div><!-- #item-buttons -->

		<?php
		/***
		 * If you'd like to show specific profile fields here use:
		 * bp_member_profile_data( 'field=About Me' ); -- Pass the name of the field
		 */
		 do_action( 'bp_profile_header_meta' );

		 ?>

	</div><!-- #item-meta -->

</div><!-- #item-header-content -->

<?php if ( $location = bp_get_profile_field_data( 'field=Town&user_id=' . bp_displayed_user_id() ) ) : ?>
		<div class="wmap hc5">
			<h2><?php _e('My town:', 'Detox') ?></h2>
		    <script src="http://maps.google.com/maps?file=api&v=2.x&key=AIzaSyA8swFjz5yg4Yovy7duWeRgujP6p0FI6oI" type="text/javascript"></script>
		    <script type="text/javascript">
		    var map = null;
		    var geocoder = null;
		    function initialize() {
		      if (GBrowserIsCompatible()) {
		        map = new GMap2(document.getElementById("map_canvas"));
		        map.setCenter(new GLatLng(0, 0), 35);
		        geocoder = new GClientGeocoder();
		      }
		    }
		    function showAddress(address) {
		      if (geocoder) {
		        geocoder.getLatLng(
		          address,
		          function(point) {
		            if (point) {
		              map.setCenter(point, 11);
		              var marker = new GMarker(point);
		              map.addOverlay(marker);
					  map.addControl(new GSmallMapControl());
		            }
		          }
		        );
		      }
		    }
			jQuery(document).ready( function() { initialize(); showAddress('<?php echo $location ?>'); } );
		    </script>

			<div id="map_canvas" style="width:100%;height:340px;overflow: hidden;"></div>
		</div>
		<?php endif; ?>
    
<?php do_action( 'bp_after_member_header' ); ?>

<?php do_action( 'template_notices' ); ?>