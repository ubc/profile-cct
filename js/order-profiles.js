/* Order Profiles */
var Profile_CCT_ORDER = {
	order: 'ASC',
	
	onReady: function() {
		jQuery( "#profile-items" ).sortable({
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
		
		if ( Profile_CCT_ORDER.order == "ASC" ) {
			Profile_CCT_ORDER.order = "DESC";
		} else {
			Profile_CCT_ORDER.order = "ASC";
		}
	},
	
	drawArrow: function(el){
		jQuery("#order-arrow").remove();
		
		if ( Profile_CCT_ORDER.order == "ASC" ) {
			jQuery(el).after('<span id="order-arrow">&uArr;<span>');
		} else {
			jQuery(el).after('<span id="order-arrow">&dArr;<span>');
		}
	},
	
	sortFirst: function() {
		Profile_CCT_ORDER.drawArrow(this);
		
		var mylist = jQuery('#profile-items');
		var listitems = mylist.children('.profile-item').get();
		
		listitems.sort( function( a, b ) {
   			var compA = jQuery(a).find('a.post-edit-link').text().toUpperCase();
   			var compB = jQuery(b).find('a.post-edit-link').text().toUpperCase();
   			
   			return Profile_CCT_ORDER.sort_AB( compA, compB );
		})
		
		jQuery.each( listitems, function(idx, itm) { mylist.append(itm); } );
		
		Profile_CCT_ORDER.rewriteOrder();
	},
	
	sort_AB: function( A, B ){
		if ( Profile_CCT_ORDER.order == "ASC" ) {
			return (A < B) ? -1 : (A > B) ? 1 : 0;
		} else {
			return (A > B) ? -1 : (A < B) ? 1 : 0;
		}
	},
	
	sortLast: function() {
		Profile_CCT_ORDER.drawArrow(this);
		
		var mylist = jQuery('#profile-items');
		var listitems = mylist.children('.profile-item').get();
		
		listitems.sort( function( a, b ) {
   			var compAArray = jQuery(a).find('a.post-edit-link').text().toUpperCase().split(" ");
   			var compA = compAArray.pop();
   			var compBArray = jQuery(b).find('a.post-edit-link').text().toUpperCase().split(" ");
   			var compB = compBArray.pop();
   			
   			return Profile_CCT_ORDER.sort_AB( compA, compB );
   		})
		
		jQuery.each( listitems, function(idx, itm) { mylist.append(itm); } );
		
		Profile_CCT_ORDER.rewriteOrder();
	}
}

jQuery(document).ready(Profile_CCT_ORDER.onReady);