/*!
 * jQuery TextChange Plugin
 * http://www.zurb.com/playground/jquery-text-change-custom-event
 *
 * Copyright 2010, ZURB
 * Released under the MIT License
 */
(function ($) {
	
	$.event.special.textchange = {
		
		setup: function (data, namespaces) {
		  $(this).data('lastValue', this.contentEditable === 'true' ? $(this).html() : $(this).val());
			$(this).bind('keyup.textchange', $.event.special.textchange.handler);
			$(this).bind('cut.textchange paste.textchange input.textchange', $.event.special.textchange.delayedHandler);
		},
		
		teardown: function (namespaces) {
			$(this).unbind('.textchange');
		},
		
		handler: function (event) {
			$.event.special.textchange.triggerIfChanged($(this));
		},
		
		delayedHandler: function (event) {
			var element = $(this);
			setTimeout(function () {
				$.event.special.textchange.triggerIfChanged(element);
			}, 25);
		},
		
		triggerIfChanged: function (element) {
		  var current = element[0].contentEditable === 'true' ? element.html() : element.val();
			if (current !== element.data('lastValue')) {
				element.trigger('textchange',  [element.data('lastValue')]);
				element.data('lastValue', current);
			}
		}
	};
	
	$.event.special.hastext = {
		
		setup: function (data, namespaces) {
			$(this).bind('textchange', $.event.special.hastext.handler);
		},
		
		teardown: function (namespaces) {
			$(this).unbind('textchange', $.event.special.hastext.handler);
		},
		
		handler: function (event, lastValue) {
			if ((lastValue === '') && lastValue !== $(this).val()) {
				$(this).trigger('hastext');
			}
		}
	};
	
	$.event.special.notext = {
		
		setup: function (data, namespaces) {
			$(this).bind('textchange', $.event.special.notext.handler);
		},
		
		teardown: function (namespaces) {
			$(this).unbind('textchange', $.event.special.notext.handler);
		},
		
		handler: function (event, lastValue) {
			if ($(this).val() === '' && $(this).val() !== lastValue) {
				$(this).trigger('notext');
			}
		}
	};	

})(jQuery);

var Profile_CCT_FORM ={
	onReady :function() {
		var tab_shell = jQuery( "#tabs" );
		// add fields 
		jQuery( "#cct-fields").find("a").click(Profile_CCT_FORM.addField);
		
		// sort fields 
		var form_sortable = jQuery(".sortable",tab_shell);
		var formB = jQuery(".form-builder");
		form_sortable.sortable( {
				placeholder: "ui-state-highlight",
				forcePlaceholderSize: true,
				handle: "label.field-title", 
				update: Profile_CCT_FORM.updateSort, 
			});
			
		formB.find(".edit").live("click", Profile_CCT_FORM.editField);
		formB.find(".remove").live("click", Profile_CCT_FORM.removeField);
		formB.find(".field-label").live("keypress", Profile_CCT_FORM.updateLabel);
		//formB.find(".field-label").bind("textchange", Profile_CCT_FORM.updateLabel);
		formB.find(".field-description").live("keypress", Profile_CCT_FORM.updateDescription);
		//formB.find(".field-description").bind("textchange", Profile_CCT_FORM.updateDescription);
		formB.find(".field-show").live("click", Profile_CCT_FORM.updateShow);
		formB.find(".field-multiple").live("click", Profile_CCT_FORM.multipleShow);
		formB.find(".save-field-settings").live("click", Profile_CCT_FORM.updateField);
		// name field
		jQuery(".edit","#form-name").click(Profile_CCT_FORM.editField);
	},
	addField : function(e) {
		e.preventDefault();
		Profile_CCT_TABS.showSpinner();
		var tab_index = jQuery( "li", Profile_CCT_TABS.$tabs ).index( Profile_CCT_TABS.selected_tab.parent() );
		var data = {	
				action: 'cct_update_fields',
				method: 'add',
				type : jQuery(this).attr('id'),
				tab_index : tab_index
			};
		// since 2.8 ajaxurl is always defined in the admin header and points to admin-ajax.php
		jQuery.post(ajaxurl, data, function(response) {
		
			jQuery(".sortable", Profile_CCT_TABS.selected_tab.attr("href") ).append(response);
			
			Profile_CCT_TABS.hideSpinner();
			
			
		});

	},
	removeField : function(e) {
		e.preventDefault();
		
		if(confirm("Are you you want to remove this field?"))
		{
			Profile_CCT_TABS.showSpinner();
			var tab_index = jQuery( "li",Profile_CCT_TABS.$tabs ).index( Profile_CCT_TABS.selected_tab.parent() );
			var parent = jQuery(this).parent();
			var field_index = jQuery( ".field-item", Profile_CCT_TABS.selected_tab.attr("href") ).index(  parent );
			var data = {	
					action: 'cct_update_fields',
					method: 'remove',
					tab_index: tab_index, 
					field_index: field_index
				};
			jQuery.post(ajaxurl, data, function(response) {
				 parent.slideUp().remove();
				 Profile_CCT_TABS.hideSpinner();
			});
		}		
	},
	updateSort: function(event, ui) { 
		Profile_CCT_TABS.showSpinner();
		
		var data = new Array();
		jQuery('.field-item',jQuery(this)).each(function(index, value){
			data[index] = jQuery(this).data('options');
		});
		
		
		console.log(data);
		
		var tab_index = jQuery( "li",Profile_CCT_TABS.$tabs ).index( Profile_CCT_TABS.selected_tab.parent() );
		var datas = {	
					action: 'cct_update_fields',
					method: 'sort',
					tab_index: tab_index, 
					data: data
				};
			jQuery.post(ajaxurl, datas, function(response) {
				Profile_CCT_TABS.hideSpinner();
			});
	 },
	updateLabel : function(e){
		var el = jQuery(this);
		var text_label = el.val();
		
		if(text_label.length > 0 ) {
			el.parents().siblings(".field-title").text(text_label);
		} else {
			text_label = el.attr('title');
			el.parents().siblings(".field-title").text(el.attr('title'));
		}
		// update the database
	},
	updateDescription : function(e){
		var el = jQuery(this);
		var text_label = el.val();
		
		setTimeout( 
		function () {
			if(text_label.length > 0 ) {
				el.parents().siblings(".description").text(text_label);
			} else {
				text_label = el.attr('title');
				el.parents().siblings(".description").text(el.attr('title'));
			}
		},75);

	},
	updateShow : function(e){
		
		var el = jQuery(this);
		var el_class = jQuery.trim(el.parent().text());
		if(el.attr('checked'))
		{
			jQuery('.'+el_class,el.parent().parent().parent().parent()).show();
		}else{
			jQuery('.'+el_class,el.parent().parent().parent().parent()).hide();
		}
		
	},
	multipleShow : function(e){
		
		var el = jQuery(this);
		var el_class = jQuery.trim(el.parent().text());
		if(el.attr('checked'))
		{
			jQuery('.add-multiple',el.parent().parent().parent().parent()).show();
		}else{
			jQuery('.add-multiple',el.parent().parent().parent().parent()).hide();
		}
		
	},
	updateField : function(e){
		 e.preventDefault();
		 
		 var el = jQuery(this);
		 var parent = el.parent();
		 parent.wrap('<form />');
		 var serialize = el.parent().parent().serialize();
		 parent.unwrap();
		 var tab_index = jQuery( "li", Profile_CCT_TABS.$tabs ).index( Profile_CCT_TABS.selected_tab.parent() );
		 
		 
		 var field_index = jQuery( ".field-item", Profile_CCT_TABS.selected_tab.attr("href") ).index( parent.parent() );
		 if(field_index == -1)
		 tab_index = 'name';
		 var data = 'action=cct_update_fields&method=update&'+serialize+'&tab_index='+tab_index+'&field_index='+field_index;
			 el.siblings('.spinner').show();		
     	 
     	 
     	 parent.parent().data('options', serialize); // update the serealized data 
     	 // ajax updating of the field options
     	 jQuery.post(ajaxurl, data, function(response) {
			 
			 if(response == 'updated'){
			 	 el.siblings('.spinner').hide();
			 }
			
		 });
     	},
	editField : function(e) {
		
		e.preventDefault();
		var el = jQuery(this);
		var parent = el.parent();
		
		if( el.text()	== 'Edit') {
			el.text('Close'); 
		} else {
			
			el.text('Edit'); 
			/* 
			var text_label = jQuery( ".field-label", parent).val();
			// lets update the db
			var tab_index = jQuery( "li",Profile_CCT_TABS.$tabs ).index( Profile_CCT_TABS.selected_tab.parent() );
			var field_index = jQuery( ".field-item", Profile_CCT_TABS.selected_tab.attr("href") ).index( parent );
			var data = {	
					action: 'cct_update_fields',
					method: 'update',
					id: tab_index, 
					index: field_index,
					label: text_label
				};
			jQuery.post(ajaxurl, data, function(response) {
			 Profile_CCT_TABS.hideSpinner();
			 });
			*/
		}
		
		el.siblings("div.edit-shell").toggle();	
	}
};

jQuery(document).ready(Profile_CCT_FORM.onReady);


