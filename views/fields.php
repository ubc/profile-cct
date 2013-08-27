<?php 
	global $blog_id;
	$global_settings = get_site_option( PROFILE_CCT_SETTING_GLOBAL, array() );
	$profile = Profile_CCT::get_object();
	

	// Add Field
	if ( ! empty($_POST) && check_admin_referer( 'add_profile_field', 'add_profile_fields_field' ) ):
		// Creating a new field.
		$field_label = trim( strip_tags($_POST['label']) );
		$field_clone = trim( strip_tags($_POST['field_clone']) );
		$field_description = trim( strip_tags($_POST['description']) );
		
		// Validate the form input.
		$error = array();
		if ( empty($field_label) ) $error['label'] = "Please fill out the field name.";
		if ( empty($field_clone) ) $error['field_clone'] = "Please select a field to duplicate.";
		if ( empty($field_description) ) $error['description'] = "Please enter a description for the field.";
		
		if ( empty($error) ):
			$field_type = "clone_".strtolower( preg_replace( '/[^A-Za-z0-9]+/', '_', $field_label ) );
			
			$field = array(
				'type'        => $field_type,
				'label'       => $field_label,
				'field_clone' => $field_clone,
				'description' => $field_description,
				'blogs'       => array(),
			);
			
			$global_settings = $profile->add_global_field($field);
			
			// Unset these fields in order to empty the form on this page.
			unset($field_label);
			unset($field_clone);
			unset($field_description);
		endif;
	elseif ( wp_verify_nonce( $_GET['_wpnonce'], 'profile_cct_toggle_field' ) ):
		
		$field_index = (int)$_GET['field'];
		
		$field = $global_settings['clone_fields'][$field_index];
		
		switch ( $_GET['action'] ):
			case 'add':
				$global_settings = $profile->add_global_field( $field, $field_index );
			break;
			case 'remove':
				$global_settings = $profile->remove_global_field( $field, $field_index );
			break;
		endswitch;
		
	endif;
?>
<h2>Fields Builder</h2>
<?php echo $note; ?>

<h3>Available Fields</h3>
<pre>
<?php print_r($local_clone_fields); ?>
</pre>
<?php if ( is_array( $global_settings['clone_fields'] ) && ! empty( $global_settings['clone_fields'] ) ): ?>
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
			foreach ( $global_settings['clone_fields'] as $field ):
				
				$field = stripslashes_deep( $field );
				$enabled = ( isset( $field['blogs'][$blog_id] ) && $field['blogs'][$blog_id] == true );
							
				?>
					<tr class="<?php if ( $count % 2 ) echo 'alternate'; ?> <?php if ( ! $enabled ) echo 'disabled'; ?>">
						<td >
							<?php echo $field['label']; ?>
							<?php if ( $enabled ): ?>
								<div class="row-actions">
									<span class="trash">
										<a href="?post_type=profile_cct&page=<?php echo PROFILE_CCT_BASEADMIN; ?>&view=fields&field=<?php echo $count; ?>&action=remove&_wpnonce=<?php echo wp_create_nonce('profile_cct_toggle_field'); ?>" class="submitdelete">Delete</a>
									</span>
								</div>
							<?php else: ?>
								<div class="row-actions">
									<a href="?post_type=profile_cct&page=<?php echo PROFILE_CCT_BASEADMIN; ?>&view=fields&field=<?php echo $count; ?>&action=add&_wpnonce=<?php echo wp_create_nonce('profile_cct_toggle_field'); ?>" class="submitadd">Enable</a>
								</div>
							<?php endif; ?>
						</td>
						<td><?php echo $field['description']; ?></td>
						<td>the <em><?php echo $field['field_clone']; ?></em> field</td>
					</tr>
				<?php 
				$count++;
			endforeach;
			?>
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
<form method="post" action="<?php echo admin_url( 'edit.php?post_type=profile_cct&page='.PROFILE_CCT_BASEADMIN.'&view=fields' ); ?>">
	<?php wp_nonce_field( 'add_profile_field', 'add_profile_fields_field' ); ?>
	<table class="form-table">
		<tr valign="top">
			<th scope="row"><label for="label">Name</label><span class="required">*</span></th>
			<td>
				<input type="text" value="<?php echo esc_attr($field_label); ?>" id="label" name="label" class="all-options"  /> <span class="description">For example: Lab Phone</span>
				<br />
				<?php if (isset($error['label'])) echo "<span class='form-invalid' style='padding:2px;'>".$error['label']."</span>"; ?>
			</td>
		</tr>
		<tr valign="top">
			<th scope="row"><label for="field_clone">Field To Duplicate</label><span class="required">*</span></th>
			<td>
				<select name="field_clone" id="field_clone" class="all-options">
					<?php foreach(Profile_CCT_Admin::fields_to_clone() as $field_to_clone): ?>
						<option value="<?php echo esc_attr($field_to_clone['type']);?>" <?php selected($field_clone, $field_to_clone['type']); ?>><?php echo esc_attr($field_to_clone['type']);?></option>
					<?php endforeach; ?>
				</select>
				<span class="description">Select the field that you want to mimic in functionality.</span>
				<br />
				<?php if (isset($error['field_clone'])) echo "<span class='form-invalid' style='padding:2px;'>".$error['field_clone']."</span>"; ?>
			</td>
		</tr>
		<tr valign="top">
			<th scope="row"><label for="description">Description</label><span class="required">*</span></th>
			<td>
				<textarea name="description" id="description" class="large-text" cols="30" rows="5"><?php echo esc_textarea($field_description); ?></textarea>
				<br />
				<span class="description">Describe what this field is used for.</span>
				<br />
				<?php if (isset($error['description'])) echo "<span class='form-invalid' style='padding:2px;'>".$error['description']."</span>"; ?>
			</td>
		</tr>
	</table>
	<input class="button-primary" type="submit" name="Example" value="<?php _e( 'Add Field' ); ?>" /> 
</form>