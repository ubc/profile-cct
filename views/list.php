<div id="col-container">
	<div id="notify">
		<span id="spinner" class="update"><img src="<?php echo admin_url(); ?>/images/wpspin_light.gif" alt="spinner" /> thinking...</span>
		
	</div>


<div id="col-right">
	<div class="col-wrap">
	
	<h3>Inactive Fields</h3>
	<ul id="banch" class="sort">
		<?php 
		$action = 'edit';
		$fields = $this->get_option('list','fields','banch');		 				
 		if( is_array( $fields  ) ):
	 		foreach($fields  as $field):
	 			call_user_func('profile_cct_'.$field['type'].'_display_shell',$action,$field);
	 		endforeach;
 		endif;
		?>
	</ul>
	<p><em>drag and drop the fields that you want to be displayed in the page</em></p>
		
	</div>
</div><!-- /col-right -->

<div id="col-middle">
	<div class="col-wrap">
	<h3>List Content</h3>
	<?php
		$data = array();
		do_action('profile_cct_list',$action,$data);
	?>
	</div>
</div><!-- /col-left -->

</div>
