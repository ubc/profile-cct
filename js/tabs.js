var Profile_CCT_TABS ={
	selected_tab : 0,
    $tabs: 0,
    tab_counter:0,
	onReady :function() {
		
		// tabs
		jQuery( "#add-tab" ).click( Profile_CCT_TABS.addTab );
		var tab_shell = jQuery( "#tabs" );
		Profile_CCT_TABS.$tabs = tab_shell.tabs({ tabTemplate: '<li><a href="#{href}" class="tab-link">#{label}</a>  <span class="remove-tab">Remove Tab</span> <span class="edit-tab">Edit</span><input type="text" class="edit-tab-input" value="#{label}" /></li>'});
		
		Profile_CCT_TABS.selected_tab = jQuery(".tab-link:first",Profile_CCT_TABS.$tabs); 
		Profile_CCT_TABS.$tabs.bind( "tabsselect", Profile_CCT_TABS.selectTab);
		Profile_CCT_TABS.tab_counter = Profile_CCT_TABS.$tabs.tabs('length'); // count the tabs 
		Profile_CCT_TABS.$tabs.tabs( "disable" , Profile_CCT_TABS.tab_counter-1 ); // disable the 'add tab' as a tab
		
		jQuery( ".remove-tab" ).live( "click", Profile_CCT_TABS.removeTab );
		jQuery( ".edit-tab" ).live( "click", Profile_CCT_TABS.editTab );
		jQuery( ".edit-tab-input" ).live( "keypress", Profile_CCT_TABS.updateTab );
	},
	addTab : function(e) {
			e.preventDefault();
			var tab_title = prompt("name your tab");
			
			if(tab_title){
				var index = jQuery( "li",Profile_CCT_TABS.$tabs ).index( jQuery( this ).parent() );
				Profile_CCT_TABS.showSpinner();
				// remove tab from form
				var data = {
						type:   ProfileCCT.type,
						action: 'cct_update_tabs',
						method: 'add',
						title: tab_title,
						i:  index
					};
				jQuery.post(ajaxurl, data, function(response) {
					
					if(response == "added"){
						Profile_CCT_TABS.$tabs.append('<div id="tabs-'+Profile_CCT_TABS.tab_counter+'"><ul class="connectedSortable sortable ui-helper-reset form-builder"></ul></div>');
						// add tab to form
						Profile_CCT_TABS.$tabs.tabs( "add" , '#tabs-'+Profile_CCT_TABS.tab_counter , tab_title , index );
						Profile_CCT_TABS.$tabs.tabs('select',index);
						Profile_CCT_TABS.tab_counter++;
						
						// make sure that the newly added ul will be sortable
						jQuery('.sortable', Profile_CCT_TABS.$tabs)
						.sortable({
							placeholder: "ui-state-highlight",
							forcePlaceholderSize: true,
							handle:"label.desc", 
							update:Profile_CCT_TABS.updateSort, 
						});
						Profile_CCT_TABS.hideSpinner();
						
					}
				});			
			}
			return false;
			
	},
	editTab : function (){
		var tab_title = jQuery(this).siblings('a').text();
		var index = jQuery( "li",Profile_CCT_TABS.$tabs ).index( jQuery( this ).parent() );
		jQuery( this ).parent().addClass('editing');
		
	},
	updateTab : function (e){
		
		if(e.keyCode == 13) {
			Profile_CCT_TABS.showSpinner();
			 // you pressed enter
			var el = jQuery( this )
			var tab_title = jQuery( this ).val();
			var index = jQuery( "li",Profile_CCT_TABS.$tabs ).index( jQuery( this ).parent() );
			var data = {
					type:   ProfileCCT.type,
					action: 'cct_update_tabs',
					method: 'update',
					title:  tab_title,
					i:      index
				};
			// don't submit the vall 
			jQuery( "#form-builder").submit(function(e){ e.preventDefault(); });

			jQuery.post(ajaxurl, data, function(response) {	
					
				if(response == "updated") {	
					el.siblings('a').text( tab_title );
         			el.parent().removeClass('editing'); 
         			Profile_CCT_TABS.hideSpinner();
         		}
			});
		 }
	},
	removeTab : function(e) {
			var $tablist = jQuery( ".tab-link",Profile_CCT_TABS.$tabs );
			
			if($tablist.length <= 1 ) {
				alert("Sorry, but you can't remove the last tab");
				return false;
			}
			var tab_title = jQuery(this).siblings('a').text();
			if(!confirm("Are you sure you want to DELETE '"+tab_title+"' tab?"))
				return false;
			
			Profile_CCT_TABS.showSpinner();
			var index = jQuery( this ).parent().index();
			// remove tab from form
			var data = {
					type:   ProfileCCT.type,
					action: 'cct_update_tabs',
					method: 'remove',
					i:  index
				};
			
			jQuery.post(ajaxurl, data, function(response) {
				if(response == "removed")
					Profile_CCT_TABS.$tabs.tabs( "remove", index );
					Profile_CCT_TABS.hideSpinner();
			});
	},
	selectTab : function(e, ui) {
		Profile_CCT_TABS.selected_tab = jQuery(ui.tab);
		// Profile_CCT_TABS.selected_tab = (this);
	},
	showSpinner: function(){
		jQuery('#spinner').show();
	
	},
	hideSpinner: function(){
		jQuery('#spinner').hide();
	}
};

jQuery(document).ready(Profile_CCT_TABS.onReady);