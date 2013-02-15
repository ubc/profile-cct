<h2>List View Builder</h2>
<div id="notify">
	<span id="spinner" class="update"><img src="<?php echo admin_url(); ?>/images/wpspin_light.gif" alt="spinner" /> thinking...</span>
</div>
<p class="info">Drag and drop the fields into place, just the way you want to. Don't forget to click the edit button for some customization. Also you don't have to click a save button. Enjoy</p>

<div id="col-container">
	<div id="col-right">
		<div class="col-wrap">
			<h3>Inactive Fields</h3>
			<?php Profile_CCT_Admin::generate_profile( 'bench' ); ?>
			<p class="info"><em>Place fields that you don't want to display above.</em> &uarr;</p>
		</div>
	</div>
	
	<div id="col-middle">
		<div class="col-wrap">
			<h3>Page Content</h3>
			<?php Profile_CCT_Admin::generate_profile( 'preview' ); ?>
		</div>
	</div>
</div>