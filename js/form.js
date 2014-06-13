var Profile_CCT_FORM = {
	onReady: function() {
		var tab_shell = jQuery( "#tabs" );

		jQuery( ".sort" ).sortable({
            placeholder: "ui-state-highlight",
            handle:      "label.field-title", 
            update:      Profile_CCT_FORM.updateSortCallback,
            connectWith: '.sort',
            tolerance:   'pointer',
            forcePlaceholderSize: true,
		});
		
		jQuery(document).on('click', '.form-builder .edit', 				Profile_CCT_FORM.editField			);
		jQuery(document).on('click', '.form-builder .field-label', 			Profile_CCT_FORM.updateLabel		);
		jQuery(document).on('click', '.form-builder .field-description', 	Profile_CCT_FORM.updateDescription	);
		jQuery(document).on('click', '.form-builder .field-url-prefix', 	Profile_CCT_FORM.updateUrlPrefix	);
		jQuery(document).on('click', '.form-builder .field-show', 			Profile_CCT_FORM.updateShow			);
		jQuery(document).on('click', '.form-builder .field-multiple', 		Profile_CCT_FORM.multipleShow		);
		jQuery(document).on('click', '.form-builder .save-field-settings', 	Profile_CCT_FORM.updateField		);
		jQuery(document).on('click', '.form-builder .field-textarea', 		Profile_CCT_FORM.updateTextarea		);
		jQuery(document).on('click', '.form-builder .field-text', 			Profile_CCT_FORM.updateText			);
		
		jQuery(".edit", "#form-name").click(Profile_CCT_FORM.editField);
	},
	
	moveUp: function(element) {
		element = element.parent().parent('.field-item');
		var previous = element.prev('.field-item');
		element.insertBefore(previous);
		var ul = element.parent();
		Profile_CCT_FORM.updateSort( ul );
	},
	
	moveDown: function(element) {
		element = element.parent().parent('.field-item');
		var next = element.next('.field-item');
		element.insertAfter(next);
		var ul = element.parent();
		Profile_CCT_FORM.updateSort( ul );
	},
	
	updateSortCallback: function( event, ui ) {
		Profile_CCT_FORM.updateSort( jQuery(this) );
	},
    
	updateSort: function( element ) {

		if( element == undefined || !element.hasOwnProperty( 'context' ) ){
			console.log( 'Error: element is possibly undefined in form.js - ' + element );
			return;
		}

		Profile_CCT_FORM.showSpinner();
		
		var data = new Array();
		element.find('.field-item').each( function(index, value) {
			data[index] = jQuery(this).data('options');
		} );
		
		var context = element.attr('id');
		
		var data_set = {	
            action: 'cct_update_fields',
            method: 'sort',
            context: context,
            data: data,
            where: ProfileCCT.page,
        };
		
		jQuery.post(ajaxurl, data_set, function(response) {		
            if ( response == 'sorted' ) {
                Profile_CCT_FORM.hideSpinner();
                Profile_CCT_Admin.show_refresh();
            } else {
				alert( "Failed to save. Please refresh and try again." );
				console.log( 'data_set: ' );
				console.log( data_set );
				console.log( 'ajaxurl: ' );
				console.log( ajaxurl );
				console.log( 'response: ' );
				console.log( response );
				console.log( 'context: ' );
				console.log( context );
				console.log( 'element: ' );
				console.log( element );
				console.log( 'data: ' );
				console.log( data );
			}
		});
	},
    
	updateLabel: function(e) {
		var el = jQuery(this);
		
		el.parent().parent().addClass('changed');
		
		setTimeout( function() {
            var text_label = el.val();
            if ( text_label.length+1 > 0 ) {
                el.parents().siblings(".field-title").text(text_label);
            } else {
                el.parents().siblings(".field-title").text(el.attr('title'));
            }
		}, 10);
	},
    
	updateDescription: function(e) {
		var el = jQuery(this);
		
		el.parent().parent().addClass('changed');
		setTimeout( function() {		
			var text_label = el.val();
			if ( text_label.length + 1 > 0 ) {
				jQuery(".description", el.parent().parent().parent()).text(text_label);
			} else {
				jQuery(".description", el.parent().parent().parent()).text(el.attr('title'));
			}
		}, 10);
	},
	
	updateUrlPrefix: function(e) {
		var el = jQuery(this);
		el.parent().parent().addClass('changed');
		setTimeout( function() {		
			var text_label = el.val();
			el.parent().parent().parent().find(".url label").text("Website - " + text_label);
		}, 10);
	},
	
	updateTextarea: function(e) {
		jQuery(this).parent().parent().addClass('changed');
	},
    
	updateText: function(e) {
		var el = jQuery(this);
		el.parent().parent().addClass('changed');
		setTimeout(function() {		
			var text_label = el.val();
			
			if ( text_label.length + 1 > 0 ) {
				jQuery( ".text-input", el.parent().parent().parent() ).text(text_label);
			} else {
				jQuery( ".text-input", el.parent().parent().parent() ).text(el.attr('title'));
			}
		}, 10);
	},
    
	updateShow: function(e) {
		var el = jQuery(this);
		el.parent().parent().parent().addClass('changed');
		
		var el_class = jQuery.trim(el.parent().text());
		if (el.attr('checked')) {
			jQuery( '.'+el_class, el.parent().parent().parent().parent() ).show();
			jQuery( '.'+el_class+'-separator', el.parent().parent().parent().parent() ).show();
		} else {
			jQuery( '.'+el_class, el.parent().parent().parent().parent() ).hide();
			jQuery( '.'+el_class+'-separator', el.parent().parent().parent().parent() ).hide();
		}
	},
    
	multipleShow: function(e) {
		var el = jQuery(this);
		el.parent().parent().parent().addClass('changed');
		
		var el_class = jQuery.trim(el.parent().text());
		if (el.attr('checked')) {
			jQuery( '.add-multiple', el.parent().parent().parent().parent() ).show();
		} else {
			jQuery( '.add-multiple', el.parent().parent().parent().parent() ).hide();
		}
	},
    
	updateField: function(e) {
		e.preventDefault();
		
		var element = jQuery(this);
		var parent = element.parent();
		parent.wrap('<form />');
		var serialize = element.parent().parent().serialize();
		parent.unwrap();
		
		var context = parent.parent().parent().attr('id');
		var field_index = jQuery( ".field-item", parent.parent().parent() ).index( parent.parent() );
		var data_set = {	
			action: 'cct_update_fields',
			method: 'update',
			context: context,
			field_index: field_index,
			where: ProfileCCT.page,
		};
		data_set = jQuery.param(data_set)+"&"+serialize;
		
		element.siblings('.spinner-shell').show();		
     	
     	parent.parent().data('options', serialize); // update the serialized data 
		
		// Ajax updating of the field options
     	jQuery.post(ajaxurl, data_set, function(response) {
			if (response == 'updated') {
				parent.removeClass('changed');
			 	element.siblings('.spinner-shell').hide();
				element.parent().siblings('.action-shell').children('.edit').trigger("click");
			 	Profile_CCT_Admin.show_refresh();
			}
		});
    },
    
	editField: function(e) {
		e.preventDefault();
		var el = jQuery(this);
		var edit_shell = el.parent().siblings(".edit-shell");
		
		if ( el.text() == 'Edit' ) {
			el.text('Close');
		} else {
			if (edit_shell.hasClass('changed')) {
				if ( confirm("There are some unsaved chages.\nWould you like to save them?") ) {
					edit_shell.parent().toggleClass('hover-expanded');
					edit_shell.find('.save-field-settings').trigger('click');
					return;
				}
			}
			el.text('Edit');
		}
		
		edit_shell.toggle();
	},
    
	showSpinner: function() {
		jQuery('#spinner').show();
	},
    
	hideSpinner: function() {
		jQuery('#spinner').hide();
	},
};

jQuery(document).ready(Profile_CCT_FORM.onReady);