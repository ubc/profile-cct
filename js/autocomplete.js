jQuery(document).ready( function($) {
	var ajaxurl = profile_cct_autocomple.admin_url;
	var ajaxaction = 'profile_cct_autocomplete';
	
	$(".profile-cct-search-form input.profile-cct-search").each( function() {
		jQuery(this).autocomplete( {
			delay: 0,
			minLength: 0,
			source: function( req, response ) {  
				$.getJSON( ajaxurl+'?callback=?&action='+ajaxaction, req, response );  
			},
			select: function( event, ui ) {
				window.location.href = ui.item.link;
			},
		} );
		jQuery(this).data( "ui-autocomplete" )._renderItem = function( ul, item ) {
			return $( "<li>" )
			.append( "<a>" + item.img + " " + item.label + "</a>" )
			.appendTo( ul );
		};
	} );
} );
