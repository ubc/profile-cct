var Profile_CCT_PAGE = {
	
	onReady : function() {
		
		// tabs
		var tabs_shell = jQuery( "#tabs" );
		tabs_shell.tabs();
		
		jQuery('.hide-if-js',tabs_shell).removeClass('hide-if-js'); // this helps with showing the meta boxes 
		
		jQuery('.add-fields').click(Profile_CCT_PAGE.addFields)
		jQuery('.remove-fields').live('click',Profile_CCT_PAGE.removeFields)
	},
	addFields : function(e){
		e.preventDefault();
		var link = jQuery(this);
		link.prev().clone().insertBefore( link );
		// add the remove link unless there is non
		if( !link.prev().children('a.remove-fields').length ){
			link.prev().append('<a href="#" class="remove-fields button">Remove</a>');
		}
		
		//if(link.prev().find('.remove-fields'))
		
	},
	removeFields: function(e) {
		e.preventDefault();
		var link = jQuery(this);
		link.parent().remove();
	}
};

jQuery(document).ready(Profile_CCT_PAGE.onReady);