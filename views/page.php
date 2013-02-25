<?php
	if ( isset( $_REQUEST['reset'] ) && isset( $_REQUEST['nonce'] ) && wp_verify_nonce( $_REQUEST['nonce'], 'profile_cct_reset_fields' ) ):
		Profile_CCT_Admin::delete_option( 'page', 'tabs', 'normal', $tabs ); // Reset to defaults.
		foreach( self::get_contexts() as $context ):
			Profile_CCT_Admin::delete_option( 'page', 'fields', $context );
		endforeach;
		
		?>
		<script> window.location = "<?php echo admin_url( 'edit.php?post_type=profile_cct&page='.PROFILE_CCT_BASEADMIN.'&view=page' ); ?>"; </script>
		<?php
	endif;
	
	$reset_url = admin_url( 'edit.php?post_type=profile_cct&page='.PROFILE_CCT_BASEADMIN.'&view=page&reset=1&nonce='.wp_create_nonce( 'profile_cct_reset_fields' ) );
?>
<h2>Profile View Builder</h2>
<div id="notify">
	<span id="spinner" class="update"><img src="<?php echo admin_url(); ?>/images/wpspin_light.gif" alt="spinner" /> thinking...</span>	
</div>
<p class="info">Drag and drop the fields into place, just the way you want to. Don't forget to click the edit button for some customization. Also you don't have to click a save button. Enjoy</p>
<div id="col-container" class="profile-view-builder">
	
	<div id="col-right">
		<div class="col-wrap">
			
			<h3>Inactive Fields</h3>
			<?php Profile_CCT_Admin::generate_profile( 'bench' ); ?>
			<p class="info"><em>Place fields that you don't want to display above .</em> &uarr;</p>
			<button onClick="Profile_CCT_Admin.confirm_redirect('<?php echo $reset_url; ?>', 'Are you sure you want to reset all fields on this page?\n\nThey will be set to their default configuration.');" class="button" style="width: 100%">Reset Fields</button>
			
		</div>
	</div>
		
	<div id="col-middle">
		<div class="col-wrap">
			
			<h3>Page Content</h3>
			<?php Profile_CCT_Admin::generate_profile( 'preview' ); ?>
			
		</div>
	</div>
	
</div>