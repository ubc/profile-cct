<?php 
global $blog_id;

$global_settings = get_site_option('Profile_CCT_global_settings', array());

// for local 
if( !empty($this->settings_options['clone_fields']) ):
	foreach( $this->settings_options['clone_fields'] as $local_clone_field):
		$clone_fields[] = $local_clone_field['type'];
	endforeach;
else:
	$clone_fields = array();
endif;
	
// ADD FIELDS 
if ( !empty($_POST) && check_admin_referer( 'add_profile_field','add_profile_fields_field' ) ) :

	$error = array();
	
	$field_label = trim(strip_tags($_POST['label']));
	if(empty($field_label))
		$error['label'] = "Please Fill out the Field Name";
	
	$field_clone = trim(strip_tags($_POST['field_clone']));
	if(empty($field_clone))
		$error['field_clone'] = "Please Select a Field To Duplicate";
	
	$field_description = trim(strip_tags($_POST['description']));
	if(empty($field_description))
		$error['description'] = "Please enter a Description for the Field";
	
	$new_type = trim(strip_tags($_POST['field_type']));

	// we want to eather add a completly new type or add one to the local array
	if(empty($error) || !empty($new_type) ):
		
		$type = "clone_".strtolower(preg_replace('/[^A-Za-z0-9]+/', '_', $field_label));
		$field_type = $type;
		$field_count = 1;
		$global_clone_fields = array();
		
		if(is_array($global_settings['clone_fields'])):
			
			$global_count = 0;
			// for global 
			foreach( $global_settings['clone_fields'] as $clone_field):
				$global_clone_fields[] = $clone_field['type'];
				
				// just adding one to the local array
				if($new_type == $clone_field['type'] || ($type == $clone_field['type'] && $clone_field['field_clone'] == $field_clone) ) {
					$copy_to_local = $clone_field;
					$new_type = $clone_field['type'];
					unset($copy_to_local['blogs']); // local array doesn't need to worry about 
					$global_to_change_count = $global_count;
				}
				$global_count++;
				
			endforeach;
			
			
			if(empty($copy_to_local)): 
				// name can't clash with the global socope
				while( in_array( $field_type, $global_clone_fields ) ):
				
					$field_type = $type."_".$field_count;
					$field_count++;
					
				endwhile;
			
			endif; // 
		
		endif;
		
		// create a new 
		if(empty($copy_to_local)):
			$new_field = array(
				'type'=>$field_type,
				'label'=>$field_label,
				'field_clone'=>$field_clone,
				'description'=>$field_description
			);
		else:
			if( !in_array( $field['type'], $clone_fields ) ):
				// add a copy of the to the local field
				(array)$this->settings_options['clone_fields'][] 	= $copy_to_local;
				
				update_option( 'Profile_CCT_settings', $this->settings_options );
				$global_settings['clone_fields'][$global_to_change_count]['blogs'] .= ",".$blog_id;
				// also update the blogs field in the particular 
				update_site_option( 'Profile_CCT_global_settings',$global_settings );
				
				// remove the errors from the 
				unset($error);
				// make sure that the new clone fields is added to the clone_fields
				$clone_fields[] = $copy_to_local['type'];
				
				$note = "<p class='info'>Now you can add ". $copy_to_local['label']." Field to the <a href=\"".admin_url('edit.php?post_type=profile_cct&page=profile-cct/profile-custom-content-type.php&view=form')."\">form</a>, <a href=\"".admin_url('edit.php?post_type=profile_cct&page=profile-cct/profile-custom-content-type.php&view=page')."\">person page</a> or the <a href=\"".admin_url('edit.php?post_type=profile_cct&page=profile-cct/profile-custom-content-type.php&view=list')."\">list view</a></p>";
			endif;
		endif;
		
		// create a new field add it to global as well as local 
		if(empty($error) && !empty($new_field)):
			
			// add completely new field to the site and global scope
			(array)$this->settings_options['clone_fields'][] 	= $new_field;
			$new_field['blogs'] = $blog_id;
			(array)$global_settings['clone_fields'][] 			= $new_field;
	   		update_option( 'Profile_CCT_settings', $this->settings_options );
	   		update_site_option( 'Profile_CCT_global_settings',$global_settings );
	   		$clone_fields[] = $new_field['type'];
			
	   		$note =  
	"<p class='info'>Now you can add ".$new_field['label']." Field to the <a href=\"".admin_url('edit.php?post_type=profile_cct&page=profile-cct/profile-custom-content-type.php&view=form')."\">form</a>, <a href=\"".admin_url('edit.php?post_type=profile_cct&page=profile-cct/profile-custom-content-type.php&view=page')."\">person page</a> or the <a href=\"".admin_url('edit.php?post_type=profile_cct&page=profile-cct/profile-custom-content-type.php&view=list')."\">list view</a></p>";
;
   		endif;
   		
   	endif;
endif;
// Remove Fields 
if( is_numeric($_GET['remove']) ):
	$global_field = $global_settings['clone_fields'][$_GET['remove']];
	
	if( wp_verify_nonce($_GET['_wpnonce'], 'profile_cct_remove_field'.$global_field['type']) ) :
		$count = 0;
		unset($count_set);
		unset($clone_fields); // we will recreate this later
		$clone_fields = array();
		foreach($this->settings_options['clone_fields'] as $field):
			
			if( $global_field['type'] == $field['type'] ):
				$count_set = $count;
			else:
			
				$clone_fields[] = $field['type'];
			endif;
			
			$count++;
		endforeach;
		// remove the fields
		if(is_numeric($count_set)):
			unset($this->settings_options['clone_fields'][$count_set]);
			
			// also remove the site from the blogs global array
			$blogs = str_replace($blog_id, "", $global_field['blogs']);
			$blogs = str_replace(",,", "", $blogs);
			$blogs = ( substr($blogs,-1) == "," ? substr($blogs,0,-1) : $blogs );
			 
			$global_settings['clone_fields'][$_GET['remove']]['blogs'] = $blogs;
			
			update_option( 'Profile_CCT_settings', $this->settings_options );
			update_site_option( 'Profile_CCT_global_settings', $global_settings );
		endif;
		
	endif;

	
endif; // remove fields localy 
			
	
?>

<?php echo $note; ?>

<h3>Available Fields</h3>
<?php if(is_array($global_settings['clone_fields']) && !empty($global_settings['clone_fields'])) : ?>
<table class="widefat">
	<thead>
	<tr>
		<th class="row-title">Name</th>
		<th>Description</th>
		<th>Based on </th>
	</tr>
	</thead>
	
	<tbody>
	<?php 
		  $count = 0;
		  foreach($global_settings['clone_fields'] as $field): 
		  
		  ?>
			<tr <?php if($count%2) echo 'class="alternate"'; ?>>
			<td ><?php echo $field['label']; ?>
			<?php if( !in_array( $field['type'], $clone_fields ) ): ?>
				
				<form action="<?php echo admin_url('edit.php?post_type=profile_cct&page=profile-cct/profile-custom-content-type.php&view=fields'); ?>" method="POST">
				<?php wp_nonce_field( 'add_profile_field','add_profile_fields_field' ); ?>
				<input type="hidden" name="field_type" value="<?php echo esc_attr($field['type']); ?>" />
				<input type="submit" value="Add" class="button-primary" />
				</form>
			<?php else: ?>
			<div class="row-actions">
				<span class="trash"><a href="?post_type=profile_cct&page=profile-cct/profile-custom-content-type.php&view=fields&remove=<?php echo $count."&_wpnonce=".wp_create_nonce('profile_cct_remove_field'.$field['type']); ?> " class="submitdelete">Delete</a>
			</div>
			<?php endif; ?>
			</td>
			<td><?php echo $field['description']; ?></td>
			<td>the <em><?php echo $field['field_clone']; ?></em> field</td>
			
			</tr>
		  <?php 
		  $count++;
		  endforeach; ?>
	</tbody>
	
	<tfoot>
		<tr>
		<th class="row-title">Name</th>
		<th>Description</th>
		<th>Based on</th>
		</tr>
	</tfoot>
</table>
<?php else: ?>
<p>There are no duplicated fields</p>
<?php endif; ?>
	
<h3>Create a new Field</h3>
<form method="post" action="<?php echo admin_url('edit.php?post_type=profile_cct&page=profile-cct/profile-custom-content-type.php&view=fields'); ?>">
<?php wp_nonce_field( 'add_profile_field','add_profile_fields_field' ); ?>

	<table class="form-table">
			
			<tr valign="top">
				<th scope="row"><label for="label">Name</label><span class="required">*</span>
				</th>
				<td>
				<input type="text" value="<?php echo esc_attr($field_label); ?>" id="label" name="label" class="all-options"  /> <span class="description">For example: Lab Phone</span>
				<br /><?php echo (isset($error['label'])? "<span class='form-invalid' style='padding:2px;'>".$error['label']."</span>": ""); ?>
				</td>
			</tr>
			
			<tr valign="top">
				<th scope="row"><label for="field_clone">Field To Duplicate</label><span class="required">*</span>
				</th>
				<td>
					
					<select name="field_clone" id="field_clone" class="all-options">
						<?php foreach($this->fields_to_clone() as $field_to_clone): ?>
						<option value="<?php echo esc_attr($field_to_clone['type']);?>" <?php selected($field_clone,$field_to_clone['type']); ?>><?php echo esc_attr($field_to_clone['type']);?></option>
						<?php endforeach; ?>
					</select>
					<span class="description">Select the field that you want to mimic in functionality</span>	
					<br />
				</td>
			</tr>
			<tr valign="top">
				<th scope="row"><label for="description">Description</label><span class="required">*</span>
				</th>
				<td>
					<textarea name="description" id="description" class="large-text" cols="30" rows="5"><?php echo esc_textarea($field_description); ?></textarea>
					<br /><span class="description">Describe what this field is used for.</span>
					<?php echo (isset($error['description'])? "<span class='form-invalid' style='padding:2px;'>".$error['description']."</span>": ""); ?>
				</td>
			</tr>
	</table>

	<input class="button-primary" type="submit" name="Example" value="<?php _e( 'Add Field' ); ?>" /> 
</form>


