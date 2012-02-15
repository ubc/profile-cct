<?php 


// Add taxonomy
if ( !empty($_POST) && check_admin_referer( 'add_profile_field','add_profile_fields_field' ) ) :

		
	$error = array();
	
	$field_label = trim(strip_tags($_POST['label']));
	if(empty($field_label))
		$error['label'] = "Please Fill out the Field Name";
	
	$field_clone = trim(strip_tags($_POST['field_clone']));
	if(empty($field_clone))
		$error['field_clone'] = "Please Select a Field To Duplicate";
	
	
	if(empty($error)):
		
		
		$type = "clone_".strtolower(preg_replace('/[^A-Za-z0-9]+/', '_', $field_label));
		$field_type = $type;
		$field_count = 1;
		$clone_fields = array();
		if(is_array($this->settings_options['clone_fields'])):
			
			foreach( $this->settings_options['clone_fields'] as $clone_field):
				$clone_fields[] = $clone_field['type'];
			endforeach;
		
			while( in_array( $field_type, $clone_fields ) ){
			
				$field_type = $type."_".$field_count;
				$field_count++;
			}
			
		
		endif;
		
		$new_field = array(
			'type'=>$field_type,
			'label'=>$field_label,
			'field_clone'=>$field_clone
		);
		
		
		if(empty($error)):
			
			(array)$this->settings_options['clone_fields'][] = $new_field;
	   		update_option( 'Profile_CCT_settings', $this->settings_options );
	   		
			
	   		$note = "<p class='info'>Now you can add ".$field_label." Field to the <a href=\"".admin_url('edit.php?post_type=profile_cct&page=profile-cct/profile-custom-content-type.php&view=form')."\">form</a>, <a href=\"".admin_url('edit.php?post_type=profile_cct&page=profile-cct/profile-custom-content-type.php&view=page')."\">person page</a> or the <a href=\"".admin_url('edit.php?post_type=profile_cct&page=profile-cct/profile-custom-content-type.php&view=list')."\">list view</a></p>" ;
   		endif;
   		
   	endif;
endif;

?>

<?php echo $note; ?>

<h3>Current Duplicated Fields </h3>
	<?php if(is_array($this->settings_options['clone_fields']) && !empty($this->settings_options['clone_fields'])) : ?>
	<table class="widefat">
		<thead>
		<tr>
			<th class="row-title">Field Name</th>
			<th>Based on</th>
		</tr>
		</thead>
		
		<tbody>
		<?php 
			  $count = 0;
			  foreach($this->settings_options['clone_fields'] as $field): ?>
		<tr>
		<td ><?php echo $field['label']; ?> 

		</td>
		<td><?php echo $field['field_clone']; ?></td>
		</tr>
		<?php 
			  $count++;
			  endforeach; ?>
		</tbody>
		
		<tfoot>
			<tr>
			<th class="row-title">Field Name</th>
			<th>Based on</th>
			</tr>
		</tfoot>
	</table>
	<?php else: ?>
	<p>There are no duplicated fields</p>
	<?php endif; ?>
	
<h3>Duplicate a Field</h3>
<form method="post" action="<?php admin_url('edit.php?post_type=profile_cct&page=profile-cct/profile-custom-content-type.php&view=fields'); ?>">
<?php wp_nonce_field( 'add_profile_field','add_profile_fields_field' ); ?>

<table class="form-table">
		
		<tr valign="top">
			<th scope="row"><label for="single-name">Field Name</label><span class="required">*</span>
			</th>
			<td>
			<input type="text" value="" id="label" name="label" class="all-options" /> <span class="description">For example: Lab Phone</span>
			<br /><?php echo (isset($error['label'])? "<span class='form-invalid' style='padding:2px;'>".$error['label']."</span>": ""); ?>
			</td>
		</tr>
		
		<tr valign="top">
			<th scope="row"><label for="plural-name">Field To Duplicate</label><span class="required">*</span>
			</th>
			<td>
				
				<select name="field_clone" id="field_clone" class="all-options">
					<?php foreach($this->fields_to_clone() as $field_to_clone): ?>
					<option value="<?php echo esc_attr($field_to_clone['type']);?>"><?php echo esc_attr($field_to_clone['type']);?></option>
					<?php endforeach; ?>
				</select>
				<span class="description">Select the field that you want to mimic in functionality</span>	
				<br />
			</td>
		</tr>
</table>

<input class="button-primary" type="submit" name="Example" value="<?php _e( 'Add Field' ); ?>" /> 
</form>


