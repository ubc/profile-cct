var Profile_CCT_PAGE = {
	tabs_shell : 0,
	
	onReady : function() {
		
		// tabs
		Profile_CCT_PAGE.tabs_shell = jQuery( "#tabs" );
		Profile_CCT_PAGE.startTabs();
		
		//jQuery('.hide-if-js',Profile_CCT_PAGE.tabs_shell).removeClass('hide-if-js'); // this helps with showing the meta boxes 
		jQuery('.add-multiple').click(Profile_CCT_PAGE.addFields);
		jQuery('.remove-fields').live('click',Profile_CCT_PAGE.removeFields);
		jQuery(".wrap-social-fields select").live('change', Profile_CCT_PAGE.updateSocialLabel);
		jQuery(".add-multiple").live('click', Profile_CCT_PAGE.clearSocialLabel);
		
		// placed right after tb_show call
		if(typeof window.tb_remove == 'function') {
			window.tb_remove = function() {
				// replace the previous function with the new one
				jQuery("#TB_window").fadeOut("fast",function(){jQuery('#TB_window,#TB_overlay,#TB_HideSelect').unload("#TB_ajaxContent").unbind().remove();});
			}
		}
		 

	},
	startTabs: function (){
		if(Profile_CCT_PAGE.tabs_shell)
			Profile_CCT_PAGE.tabs_shell = jQuery( "#tabs" );
		
		Profile_CCT_PAGE.tabs_shell.tabs();
		
	},
	
	addFields : function(e){
		e.preventDefault();
		var link = jQuery(this);
		//
		if(link.prev().children('div').hasClass('days'))
			var days_case = true;
		
		var count = link.prev().data('count');
		
		if (days_case)
			link.prev().children('hr').remove();
		
		var copy = link.prev().clone();
		
		if (days_case)
			link.prev().append('<hr />');
			
		copy.insertBefore( link );
				
		// add the remove link unless there is none
		if( !link.prev().children('a.remove-fields').length ){
			link.prev().append('<a href="#" class="remove-fields button">Remove</a>');
		}
		
		if (days_case)
				link.prev().append('<hr />');
		
		link.prev().data('count',count+1);
		
		link.prev().find('input,select,textarea').each(function(index, value){
			var new_count = jQuery(this).parent().parent().data('count');
			
			var name = jQuery(this).attr('name');
		
			var new_name = name.replace(count, new_count);
		
			if( jQuery(this).attr('type') == 'checkbox' ) {
				jQuery(this).attr('name',new_name).attr('checked',false);
			}
			else
				jQuery(this).attr('name',new_name).val('');
			
		});
		//if(link.prev().find('.remove-fields'))
		
	},
	
	removeFields: function(e) {
		e.preventDefault();
		var link = jQuery(this);
		link.parent().remove();
		//link.prev().children('hr').remove();
	},
	
	
	updateSocialLabel: function(e){
		var value = jQuery(this).val();
		jQuery(this).parent().next().children("label").text(socialArray[value].user_url);
	},
	
	clearSocialLabel: function(e){
		jQuery(this).prev().children(".username").children("label").text("");
	}
	
};

jQuery(document).ready(Profile_CCT_PAGE.onReady);

var socialOptions = profileCCTSocialArray;
var socialArray=new Array();
for(var i = 0; i < socialOptions.length; i++){
	 socialArray[socialOptions[i].label] = socialOptions[i];
}