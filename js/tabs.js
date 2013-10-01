var Profile_CCT_TABS = {
	selected_tab: 0,
    tabs: 0,
    tab_counter: 0,
    tabs_global_template: '<li><a href="#{href}" class="tab-link">#{label}</a>  <span class="remove-tab">Remove Tab</span> <span class="edit-tab">Edit</span></li>',

	onReady: function() {
		// tabs
		jQuery( "#add-tab" ).click( Profile_CCT_TABS.addTab );
		var tab_shell = jQuery( "#tabs" );
		Profile_CCT_TABS.$tabs = tab_shell.tabs();
		
		Profile_CCT_TABS.selected_tab = jQuery(".tab-link:first", Profile_CCT_TABS.$tabs); 
		Profile_CCT_TABS.$tabs.bind( "tabsselect", Profile_CCT_TABS.selectTab);
		Profile_CCT_TABS.tab_counter = Profile_CCT_TABS.$tabs.find('ul.nav li').length; // count the tabs 
		Profile_CCT_TABS.$tabs.tabs( "disable", Profile_CCT_TABS.tab_counter - 1 ); // disable the 'add tab' as a tab

		jQuery( ".remove-tab" ).live( "click",    Profile_CCT_TABS.removeTab );
		jQuery( ".edit-tab"   ).live( "click",    Profile_CCT_TABS.editTab   );
		jQuery( ".tab-link"   ).live( "dblclick", Profile_CCT_TABS.editTab   );
	},
    
	addTab: function(e) {
        e.preventDefault();
        var tab_title = prompt("Name your tab");
        
        if (tab_title) {
            var index = jQuery( "li", Profile_CCT_TABS.$tabs ).index( jQuery( this ).parent() );
            Profile_CCT_FORM.showSpinner();
            // remove tab from form
            var data = {
				where:  ProfileCCT.page,
				action: 'cct_update_tabs',
				method: 'add',
				title:  tab_title,
				index:  index,
			};
            
            jQuery.post(ajaxurl, data, function(response) {
                if (response == "added") {
                    //add tab content
                    Profile_CCT_TABS.$tabs.append('<div id="tabs-'+Profile_CCT_TABS.tab_counter+'"><ul id="tabbed-'+Profile_CCT_TABS.tab_counter+'"class="sort form-builder"></ul></div>');
                    // add tab to form
                    jQuery("'"+Profile_CCT_TABS._getTabTemplate('#tabs-'+Profile_CCT_TABS.tab_counter, tab_title)+"'").insertBefore('li#add-tab-shell');
                    Profile_CCT_TABS.$tabs.tabs("refresh");
                    Profile_CCT_TABS.$tabs.tabs("option", "active", index );
                    Profile_CCT_TABS.tab_counter++;
                    
                    // make sure that the newly added ul will be sortable
                    jQuery( ".sort" ).sortable({
						placeholder: "ui-state-highlight",
						forcePlaceholderSize: true,
						handle: "label.field-title", 
						update: Profile_CCT_FORM.updateSort,
						connectWith: '.sort',
						tolerance: 'pointer',
                    });
                    Profile_CCT_FORM.hideSpinner();
                    Profile_CCT_Admin.show_refresh();
                }
            });			
        }
        return false;
	},
    
	editTab: function() {
        var tab_title = prompt("Rename your tab");
        
		if (tab_title) {
			Profile_CCT_FORM.showSpinner();
			
			var element = jQuery(this);
			var index = jQuery( "li", Profile_CCT_TABS.$tabs ).index( element.parent() );
			var data = {
				where:  ProfileCCT.page,
				action: 'cct_update_tabs',
				method: 'update',
				title:  tab_title,
				index:  index,
			};
			jQuery.post(ajaxurl, data, function(response) {
				if (response == "updated") {	
					element.parent().children('a').text( tab_title );
					Profile_CCT_FORM.hideSpinner();
					
					Profile_CCT_Admin.show_refresh();
				}
			});
		}
	},
    
	removeTab: function(e) {
		var $tablist = jQuery( ".tab-link", Profile_CCT_TABS.$tabs );
		
        var tab_title = jQuery(this).siblings('a').text();
        if ( ! confirm("Are you sure you want to DELETE '"+tab_title+"' tab?")) {
            return false;
        }
        
        Profile_CCT_FORM.showSpinner();
        var index = jQuery( this ).parent().index();
        
        // remove tab from form
        var data = {
            where:   ProfileCCT.page,
            action: 'cct_update_tabs',
            method: 'remove',
            index:  index,
        };
        
        jQuery.post(ajaxurl, data, function(response) {
            if (response == "removed") {
                var html_list = jQuery("#tabs div.ui-tabs-panel").eq( index ).find('ul').html();
                jQuery("#bench").append(html_list);
                
                Profile_CCT_TABS.$tabs.find('ul.nav li:eq('+index+')').remove();
                Profile_CCT_TABS.$tabs.tabs('refresh');
                Profile_CCT_FORM.hideSpinner();
                Profile_CCT_Admin.show_refresh();
            }
        });
		
		return true;
	},
    
	selectTab: function(e, ui) {
		Profile_CCT_TABS.selected_tab = jQuery(ui.tab);
	},

	_getTabTemplate: function(url, title) {
		return Profile_CCT_TABS.tabs_global_template.replace('#{href}', url).replace('#{label}', title);
	}
};

jQuery(document).ready(Profile_CCT_TABS.onReady);