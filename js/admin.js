var Profile_CCT_Admin = {
	ready: function() {
		jQuery('#refresh-profiles').on('click', function () {
			Profile_CCT_Admin.update_profiles( 1 );
		}).parent().after('<div id="update-profiles-slider"><div class="slider-shell"><div></div></div> 0% </div>');
	},
    
	prep: function( where ) {
		var html = 'The profile '+where+' layout has been changed. <a href="#nogo" id="refresh-profiles" class="button">Update All Profiles</a>';
		if ( jQuery('#update-profile-shell').length ) { //ie. #update-profile-shell already exists.
			jQuery('#update-profile-shell').html(html);
		} else { 
			jQuery('#profile-setting').after('<div id="update-profile-shell" class="update-profiles info">'+html+'</div>');
		}
	},
	
	show_refresh: function() {
		if ( ProfileCCT.page != 'form' ){
			Profile_CCT_Admin.prep(ProfileCCT.page);
			Profile_CCT_Admin.ready();
			
			jQuery.post(ajaxurl, {
				action: 'cct_needs_refresh',
				where: ProfileCCT.page,
				needs_refresh: 1,
			});
		}
	},
    
	update_profiles: function( page ) {
		var data = {
			action: 'cct_update_profiles',
			page: page
		};	
		
		jQuery('#update-profile-shell').fadeOut('slow', function() {
			jQuery(this).remove();
		});
		
		jQuery.post(ajaxurl, data, function(result) {
			var percent = Math.floor( ( result['page']/result['max'] ) * 100 );
			Profile_CCT_Admin.update_slider( percent, percent+'% Profiles updated.' );
			
			if ( result['max'] > result['page'] ) {
				Profile_CCT_Admin.update_profiles( result['page'] + 1 );
				// Warn about leaving this page and interrupting the process. 
				jQuery(window).bind('beforeunload', function() {
    				return "Warning! If you leave this page the profile updating process will be interrupted. Do yo want to continue?";
    			});
			} else {
				jQuery.post(ajaxurl, {
					action: 'cct_needs_refresh',
					needs_refresh: 0,
				});
				
				Profile_CCT_Admin.update_slider( percent, '<strong>Done!</strong> All profiles are updated!' );
				jQuery(window).unbind("beforeunload");
				
				// When the mouse is moved, then hide the completion message.
				jQuery(window).mousemove(function(event) {
					jQuery(window).unbind('mousemove');
					jQuery('#update-profiles-slider').delay(2000).fadeOut('slow', function() {
    					jQuery(this).remove();
 					});
 				});	
			}
		}, 'json' );	
	},
    
	update_slider: function( percent, message ) {
		jQuery('#update-profiles-slider').addClass('active-slider').html("<div class='slider-shell'><div style='width:"+percent+"%'>&nbsp;</div></div>"+message+"</div>");
	},
	
	confirm_redirect: function( url, message ) {
		if ( confirm( message ) ) window.location = url;
	}
}

jQuery( Profile_CCT_Admin.ready );