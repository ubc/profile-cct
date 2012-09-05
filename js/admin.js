var Profile_CCT_Admin = {
	ready : function () {
		
		jQuery('#refresh-profiles').on('click', function () {
			
			Profile_CCT_Admin.update_profiles( 1 );
		} ).parent().after('<div id="update-profiles-slider"> 0% </div>');
	},
	prep : function( where ) {
		
		var html = 'The profile '+where+' has changed. <a href="#nogo" id="refresh-profiles" class="button">Update All Profiles</a>';
		if( !jQuery('#update-profile-shell').length ){
			jQuery('#profile-setting').after('<div id="update-profile-shell" class="update-profiles info">'+html+'</div>');
			
		} else { 
			jQuery('#update-profile-shell').html(html);
		}
	},
	
	show_refresh: function() {
		
		if( ProfileCCT.page != 'form' ){
			Profile_CCT_Admin.prep(ProfileCCT.page);
			Profile_CCT_Admin.ready();		
		}
	},
	update_profiles : function( page ) {
		var data = {
			action: 'cct_update_profiles',
			page: page
		};
		
		jQuery.post(ajaxurl, data, function(result) {
			
			
			var percent = Math.floor( ( result['page']/result['max'] ) * 100 );
				
				Profile_CCT_Admin.update_slider( percent, percent+'% Profiles updated.' );
				
			if( result['max'] > result['page']  ) {
				
				
				Profile_CCT_Admin.update_profiles( result['page'] + 1 );
				// make it hard to leave this page and inturup the process 
				jQuery(window).bind('beforeunload', function(){
    				return "If you leave this page the profile updating process will be interupted. Do yo want to continou? ";
    				});
			}
			else{
				Profile_CCT_Admin.update_slider( percent, '<strong>Done!</strong> All profiles are updated!' );
				
				jQuery(window).unbind("beforeunload");
				// when we move the mouse we can hide the stuff
				jQuery(window).mousemove(function(event) {
					jQuery(window).unbind('mousemove');
					jQuery('#update-profiles-slider,#update-profile-shell').delay(2000).fadeOut('slow', function() {
    					// Animation complete.
    					jQuery(this).remove();
    					
 					});
 				});	
			}
		}, 'json' );	
	},
	update_slider : function( percent, message ) {
		jQuery('#update-profiles-slider').addClass('active-slider').html("<div style='width:"+percent+"%'>"+message+"</div>");
	}
}

jQuery( Profile_CCT_Admin.ready );