<?php 

?>
<div id="col-container">
	<div id="notify">
		<span id="spinner" class="update"><img src="<?php echo admin_url(); ?>/images/wpspin_light.gif" alt="spinner" /> thinking...</span>
		
	</div>


<div id="col-right">
	<div class="col-wrap">
	
	<h3>Form fields to add</h3>
	<ul id="banch" class="sort">
		<?php 
		$action = 'edit';
		$fields = $this->get_option('form','fields','banch');		 				
 		if( is_array( $fields  ) ):
	 		foreach($fields  as $field):
	 			call_user_func('profile_cct_'.$field['type'].'_field_shell',$action,$field);
	 		endforeach;
 		endif;
		?>
	</ul>
		
	</div>
</div><!-- /col-right -->

<div id="col-middle">
	<div class="col-wrap">
	<h3>Preview</h3>
	
	<?php
		do_action('profile_cct_form',$action);
	?>
	</div>
</div><!-- /col-left -->

</div>
