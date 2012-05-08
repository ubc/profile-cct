
var Profile_CCT_ORDER = {

	onReady :function() {
	
		jQuery( "#profile-items" ).sortable( { 
			axis: 'y', 
			cursor: 'move',
			placeholder: 'sorthelper',
			update: Profile_CCT_ORDER.rewriteOrder
		});
		jQuery("#sort-first").click(Profile_CCT_ORDER.sortFirst);
		jQuery("#sort-last").click(Profile_CCT_ORDER.sortLast);
	},
	
	rewriteOrder: function(){
		jQuery(".menu_order_input").each(function(index, el) {
			jQuery(el).val(index);
		});
	},
	sortFirst: function(){
		var mylist = jQuery('#profile-items');
		var listitems = mylist.children('.profile-item').get();
		
		listitems.sort( function( a, b ) {
   			var compAArray = jQuery(a).find('a.post-edit-link').text().toUpperCase().split(" ");
   			var compA = compAArray.pop();
   			var compBArray = jQuery(b).find('a.post-edit-link').text().toUpperCase().split(" ");
   			var compB = compBArray.pop();
   			
   			return (compA < compB) ? -1 : (compA > compB) ? 1 : 0;
		})
		
		jQuery.each( listitems, function(idx, itm) { mylist.append(itm); } );
		
		Profile_CCT_ORDER.rewriteOrder();
	},
	sortLast: function(){
		var mylist = jQuery('#profile-items');
		var listitems = mylist.children('.profile-item').get();
		
		listitems.sort( function( a, b ) {
   			var compA = jQuery(a).find('a.post-edit-link').text().toUpperCase();
   			var compB = jQuery(b).find('a.post-edit-link').text().toUpperCase();
   			return (compA < compB) ? -1 : (compA > compB) ? 1 : 0;
		})
		
		jQuery.each( listitems, function(idx, itm) { mylist.append(itm); } );
		
		Profile_CCT_ORDER.rewriteOrder();
	}
}

jQuery(document).ready(Profile_CCT_ORDER.onReady);