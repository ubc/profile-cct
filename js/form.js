var Profile_CCT_FORM = {
	onReady: function() {
		var tab_shell = jQuery( "#tabs" );
		var formB = jQuery(".form-builder");
		
		jQuery( ".sort" ).sortable({
            placeholder: "ui-state-highlight",
            handle:      "label.field-title", 
            update:      Profile_CCT_FORM.updateSortCallback,
            connectWith: '.sort',
            tolerance:   'pointer',
            forcePlaceholderSize: true,
		});
		
		formB.find( ".edit"                ).live( 'click', Profile_CCT_FORM.editField         );
		formB.find( ".field-label"         ).live( 'keyup', Profile_CCT_FORM.updateLabel       );
		formB.find( ".field-description"   ).live( 'keyup', Profile_CCT_FORM.updateDescription );
		formB.find( ".field-url-prefix"    ).live( 'keyup', Profile_CCT_FORM.updateUrlPrefix   );
		formB.find( ".field-show"          ).live( 'click', Profile_CCT_FORM.updateShow        );
		formB.find( ".field-multiple"      ).live( 'click', Profile_CCT_FORM.multipleShow      );
		formB.find( ".save-field-settings" ).live( 'click', Profile_CCT_FORM.updateField       );
		formB.find( ".field-textarea"      ).live( 'keyup', Profile_CCT_FORM.updateTextarea    );
		formB.find( ".field-text"          ).live( 'keyup', Profile_CCT_FORM.updateText        );
		
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
				alert("Failed to save.");
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
		
		element.siblings('.spinner').show();		
     	
     	parent.parent().data('options', serialize); // update the serialized data 
		
		// Ajax updating of the field options
     	jQuery.post(ajaxurl, data_set, function(response) {
			if (response == 'updated') {
				parent.removeClass('changed');
			 	element.siblings('.spinner').hide();
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