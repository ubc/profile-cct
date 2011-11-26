var Profile_CCT_FORM ={
	onReady :function() {
		var tab_shell = jQuery( "#tabs" );
		// add fields 
		
		
		var formB = jQuery(".form-builder");
		
		jQuery( ".sort" ).sortable({
				placeholder: "ui-state-highlight",
				forcePlaceholderSize: true,
				handle: "label.field-title", 
				update: Profile_CCT_FORM.updateSort,
				connectWith: '.sort',
				tolerance: 'pointer'
		});
		
			
		formB.find(".edit").live("click", Profile_CCT_FORM.editField);

		formB.find(".field-label").live("keypress", Profile_CCT_FORM.updateLabel);
		formB.find(".field-description").live("keypress", Profile_CCT_FORM.updateDescription );
		formB.find(".field-show").live("click", Profile_CCT_FORM.updateShow);
		formB.find(".field-multiple").live("click", Profile_CCT_FORM.multipleShow);
		formB.find(".save-field-settings").live("click", Profile_CCT_FORM.updateField);
		// name field
		jQuery(".edit","#form-name").click(Profile_CCT_FORM.editField);
	},
	updateSort: function(event, ui) { 
		Profile_CCT_FORM.showSpinner();
		
		var data = new Array();
		jQuery('.field-item',jQuery(this)).each(function(index, value){
			data[index] = jQuery(this).data('options');
		});
		
		
		var context = jQuery(this ).attr('id');
		
		var data_set = {	
					action: 'cct_update_fields',
					method: 'sort',
					context: context, 
					data: data,
					type: ProfileCCT.type
				};
		
		jQuery.post(ajaxurl, data_set, function(response) {
				Profile_CCT_FORM.hideSpinner();
		});
	 },
	updateLabel : function(e){
		var el = jQuery(this);
				
		el.parent().parent().addClass('changed');
		
		setTimeout( function(){
		var text_label = el.val();
		if(text_label.length+1 > 0 ) {
			el.parents().siblings(".field-title").text(text_label);
		} else {
			text_label = el.attr('title');
			el.parents().siblings(".field-title").text(el.attr('title'));
		}
		},10);
	},
	updateDescription : function(e){
		var el = jQuery(this);
		
		el.parent().parent().addClass('changed');
		setTimeout( function () {		
			var text_label = el.val();
			if(text_label.length+1 > 0 ) {
				el.parents().siblings(".description").text(text_label);
			} else {
				text_label = el.attr('title');
				el.parents().siblings(".description").text(el.attr('title'));
			}
		},10);

	},
	updateShow : function(e){
		
		var el = jQuery(this);
		el.parent().parent().parent().addClass('changed');
		
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
		el.parent().parent().addClass('changed');
		
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
		 
		 var context = parent.parent().parent().attr('id');
		 
		 var field_index = jQuery( ".field-item", parent.parent().parent() ).index( parent.parent() );
		 console.log(serialize);
		 var data = 'action=cct_update_fields&method=update&'+serialize+'&context='+context+'&field_index='+field_index+'&type='+ProfileCCT.type;
		 el.siblings('.spinner').show();		
     	 
     	 parent.parent().data('options', serialize); // update the serealized data 
     	 // ajax updating of the field options
     	 jQuery.post(ajaxurl, data, function(response) {
			 parent.removeClass('changed');
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
			if(el.siblings('div.edit-shell').hasClass('changed'))
			if( confirm("There are some un Saved chages \n Would you like to save them?") ){
				el.siblings("div.edit-shell").find('.save-field-settings').trigger('click');
				
			}
			
			
			el.text('Edit'); 
		}
		
		el.siblings("div.edit-shell").toggle();	
	},
	showSpinner: function(){
		jQuery('#spinner').show();
	
	},
	hideSpinner: function(){
		jQuery('#spinner').hide();
	}
};

jQuery(document).ready(Profile_CCT_FORM.onReady);


