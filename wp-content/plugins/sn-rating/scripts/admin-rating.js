/**
 * This file would handle AJAX submission of Rating.
 */
$plugin_url = pluginUrl+'/sn_rating';
//$plugin_url = '/wp-content/plugins/sn_rating' ;
var pathname = window.location.pathname;
pathname = pathname+'?page=rating_options&tab=rating_settings&';
jQuery(document).ready(function() { 
  jQuery('#ajax-call a').click(function() {
    jQuery('#ajax-list1').load(pathname+jQuery(this).attr('title'), 
    {}); 
    return false; });
  jQuery(function() {
   dateFormat:'yy-mm-dd',
   jQuery("#datepicker1").datepicker();
  });
  jQuery(function() {
   dateFormat:'yy-mm-dd',
   jQuery("#datepicker2").datepicker();
  });
});

function factor_del_operation(link) {
  if(confirm("Are you sure you want to delete this factor ")) {
    window.location = link;
  }
}



jQuery('.callbacks_factors').live('click', function() {
	jQuery.ajax({
        url: ajax_url,
	    type: 'POST',
	    dataType: 'html',
        data: {
	    	action : 'ajax_factor_assign',
	    	entity_type : jQuery(this).attr('id')
        },       
	    success: function(data) {
            jQuery('.simple_overlay').show();
            jQuery('#manage_forms').html(data);
            //console.error(data);
	    },
	    error: function() {
	    	console.error('Some error occurred.');
	    }
	});	
});

jQuery('.callbacks_theme').live('click', function() {
	jQuery.ajax({
        url: ajax_url,
	    type: 'POST',
	    dataType: 'html',
        data: {
	    	action : 'ajax_theme_assign',
	    	entity_type_theme : jQuery(this).attr('id')
        },      
	    success: function(data) {
          jQuery('.simple_overlay').show();
          jQuery('#manage_forms').html(data);
	    },
	    error: function(data) {
	    	console.error('Some error occurred.');
	    }
	});
	
});

jQuery('.close').live('click', function() {
  jQuery('.simple_overlay').hide();
});
