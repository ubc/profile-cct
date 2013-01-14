var Profile_CCT_PROFILE_PAGE = {
	onReady: function() {
		jQuery( ".sort" ).sortable({
			connectWith: ".connectedSortable",
		}).disableSelection();
	}
};

jQuery(document).ready(Profile_CCT_PROFILE_PAGE.onReady);