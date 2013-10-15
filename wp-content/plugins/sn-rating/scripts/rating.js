/**
 * This file would handle AJAX submission of Rating.
 */


$plugin_url = pluginUrl+'/sn_rating' ;

//$plugin_url = '/wp-content/plugins/sn_rating' ;

jQuery('.rating-container.single .rating-area.enable .rating-image').live('click', function() {
    $ele = jQuery(this);
    current_id = '#' + jQuery(this).parent().parent().parent().attr('id');
    type = jQuery(this).parent().parent().attr('rel');
    jQuery($ele).closest('.rating-container').html('Please wait...');
	jQuery.ajax({
	    url: ajax_url,
	    type: 'POST',
	    dataType: 'html',
	    data: {
	    	action : 'ajax_rating_submission',
	    	request: 'save_rating',
	    	entity_type : type,
	    	content_id : jQuery(current_id).attr('rel'),
	    	score : jQuery(this).attr('rel')
	    },
	    success: function(data) {
	    	jQuery(current_id).replaceWith(data);
	    },
	    error: function(data) {
	    	console.error('Some error occurred.');
	    }
	});
	
});

jQuery('.rating-container.multiple .rating-area.enable .rating-image').live('click', function() {
    $ele = jQuery(this);
    current_id = '#' + jQuery(this).parent().parent().parent().attr('id');
    type = jQuery(this).parent().parent().attr('rel');
    current_hidden_element = '#multiple-rating-' + type + '-' + jQuery(current_id).attr('rel');
    rating_provided = jQuery(current_hidden_element).val();
    jQuery(current_hidden_element).val(rating_provided + jQuery(this).attr('id'));
       jQuery($ele).closest('.rating-container').html('Please wait...');
	jQuery.ajax({
	    url: ajax_url,
	    type: 'POST',
	    dataType: 'html',
	    data: {
	    	action : 'ajax_rating_submission',
	    	request: 'save_rating',
	    	entity_type : type,
	    	content_id : jQuery(current_id).attr('rel'),
	    	score : jQuery(this).attr('rel'),
            factor_name : jQuery(this).parent().parent().attr('title')
	    },
	    success: function(data) {
	    	jQuery(current_id).replaceWith(data);
	    },
	    error: function(data) {
	    	console.error('Some error occurred.');
	    }
	});
	
});
