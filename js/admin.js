var Profile_CCT_Admin = {
	ready : function () {
		//console.log('show admin');
		
		jQuery('#refresh-profiles').click( function () {
			//console.log('clicked');
			Profile_CCT_Admin.update_profiles( 1 );
		} ).parent().after('<div id="update-profiles-slider"> 0% </div>');
		
	},
	
	update_profiles : function( page ) {
		// console.log('update profiles', page);
		
		var data = {
			action: 'cct_update_profiles',
			page: page
		};
		
		
					
		jQuery.post(ajaxurl, data, function(result) {
			
			var percent = Math.floor( ( result['page']/result['max'] ) * 100 );
				
				Profile_CCT_Admin.update_slider( percent, percent+'% Profiles updated.' );
				
			if( result['max'] > result['page'] +1  ) {
				Profile_CCT_Admin.update_profiles( result['page'] + 1 );
				// make it hard to leave this page and inturup the process 
				jQuery(window).bind('beforeunload', function(){
    				return "If you leave this page the profile updating process will be interupted. Do yo want to continou? ";
    				});
			}
			else{
				Profile_CCT_Admin.update_slider( percent, '100% Done! All profiles are updated!' );
				
				jQuery(window).unbind("beforeunload");
			}
			
		}, 'json' );
		
	},
	
	update_slider : function( percent, message ) {
		
		jQuery('#update-profiles-slider').addClass('active-slider').html("<div style='width:"+percent+"%'>"+message+"</div>");
	
	}
}

jQuery( Profile_CCT_Admin.ready );