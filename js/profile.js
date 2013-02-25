var Profile_CCT_PROFILE ={
	onReady: function() {	
		jQuery('.field-item').mouseenter(function() {
			jQuery(this).addClass('hover');
		}).mouseleave(function() {
			jQuery(this).removeClass('hover');
		});
		
		jQuery('.edit').click(function() {
			jQuery(this).parent().parent().toggleClass('hover-expanded');
		});
		
		jQuery('select[name="width"]').change(function() {
			var el = jQuery(this);
			var value = el.val();
			jQuery(this).parent().parent().addClass('changed');
			jQuery(this).parent().parent().parent().removeClass("full half one-third two-third").addClass(value);
		})
	}
};

jQuery(document).ready(Profile_CCT_PROFILE.onReady);
