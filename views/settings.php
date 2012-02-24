<?php

/* the current default settings
*/
$default_options = $this->default_options( 'settings' );
if(	empty($this->settings_options['picture'] ) )
	$this->settings_options['picture'] = $default_options['picture'];

if(	empty($this->settings_options['slug'] ) )
	$this->settings_options['slug'] = $default_options['slug'];

if(	empty($this->settings_options['permissions'] ) )
	$this->settings_options['permissions'] = $default_options['permissions'];
	
if( !empty($_POST) ):
	if(wp_verify_nonce($_POST['update_settings_nonce_field'], 'update_settings_nonce')):
		
		
	
		//Validate pic options
		$width = intval($_POST['picture_width']);
		$height = intval($_POST['picture_height']);
		if( $width >= 100 && $width <= 560 && $height >= 100 && $height <= 560):
			$picture_options = array ( 'width'=>$width, 'height'=>$height );
			$this->settings_options['picture'] = $picture_options;
		else:
			echo '<div class="error settings-error"><p>Picture dimensions should be between 100x100 and 560x560</p></div>';
		endif;
		
		$slug = trim( $_POST['slug'] );
		if( !empty( $slug ) ):
			$this->settings_options['slug'] = sanitize_title( trim( $_POST['slug'] ) );
		else:
			$this->settings_options['slug'] = 'person';
		endif;
	
		// lets deal with permissions	
		$post_permissions = $_POST['options']['permissions'];
		
		foreach($this->settings_options['permissions'] as $user=>$permission_array):
			if($user != 'administrator'): // don't want people changing the permissions of the admin
				
				$role = get_role( $user );
				
				foreach($permission_array as $permission => $can):
					if( isset( $this->settings_options['permissions'][$user][$permission] ) ):
						$this->settings_options['permissions'][$user][$permission] = (bool)$post_permissions[$user][$permission];
						// add the new capability
						if( (bool)$post_permissions[$user][$permission] ):
							$role->add_cap( $permission );
							
						else:
  							$role->remove_cap(  $permission );
  							
  						endif;
					endif;
				endforeach;
				
			endif;
			
		endforeach;
		
		
		//Store updated options
		update_option('Profile_CCT_settings', $this->settings_options);
		
		// lets flush the rules again
		$this->register_cpt_profile_cct();
		flush_rewrite_rules();
	else:	//if nonce failed
		echo '<div class="error settings-error"><p>Verification error. Try again.</p></div>';
	endif;
endif;


?>

<form method="post" action="">
	<h3>Picture Dimensions</h3>
	<?php wp_nonce_field( 'update_settings_nonce','update_settings_nonce_field' ); ?>	
	<table class="form-table">
	<tbody>
	<tr valign="top">
		<th scope="row"><label for="picture_width">Width</label></th>
		<td><input type="text" size="3" name="picture_width" id="picture_width" value="<?php echo esc_attr($this->settings_options['picture']['width']); ?>" /> pixels</td>
	</tr>
	<tr valign="top">
		<th scope="row"><label for="picture_height">Height</label></th>
		<td><input type="text" size="3" name="picture_height" id="picture_height" value="<?php echo esc_attr($this->settings_options['picture']['height']); ?>" /> pixels</td>
	</tr>
	</tbody></table>
	
	
	<h3>Permalink</h3>
	<table class="form-table">
	<tbody>
	<tr valign="top">
		<th scope="row"><label for="slug">Slug</label></th>
		<td><input type="text"  name="slug" id="slug" value="<?php echo esc_attr($this->settings_options['slug']); ?>" /><br />
			By default it is set to 'person'
		</td>
	</tr>

	</tbody></table>
	
	
	<h3>Permissions</h3>
	Set permissions for profile 
	<table class="wp-list-table widefat fixed posts ">
		<thead>
			<tr>
				<th>Role</th>
				<th>Edit profile</th>
				<th>Edit profiles </th>
				<th>Edit others profile</th>
				<th>Publish profile</th>
				<th>Read private profile</th>
				<th>Delete profile</th>
				<th>Delete others profiles</th>
			</tr>
		</thead>		
		<tbody id="the-list">
				<?php 
				$count = 0;
				foreach($this->settings_options['permissions'] as $user=>$permission):
					$this->permissions_table($user, ($count%2)); $count++;
				endforeach; ?>
		</tbody>
	</table>
	<br />
	<input type="submit" class="button-primary" value="<?php _e('Save Settings') ?>" />
</form>	


<!--
<table class="form-table">
	<tbody><tr valign="top">
	<th scope="row">...</th>
	<td><fieldset><legend class="screen-reader-text"><span>...</span></legend>
	<label for="default_pingback_flag">
	<input type="checkbox" checked="checked" value="1" id="default_pingback_flag" name="default_pingback_flag"> Allow UBC directory integration</label>
	<br>
	<label for="default_ping_status">
	<input type="checkbox" checked="checked" value="open" id="default_ping_status" name="default_ping_status"> Allow subscribers to manage profile</label>
	<br>
	<label for="default_comment_status">
	<input type="checkbox" checked="checked" value="open" id="default_comment_status" name="default_comment_status"> Allow multiple profiles</label>
	<br>
	<label for="default_comment_status">
	<input type="checkbox" checked="checked" value="open" id="default_comment_status" name="default_comment_status"> Allow someone else to edit profile</label>
	</fieldset></td>
	</tr>
</tbody></table>
</form>

<h3>Export</h3>
<pre> export string goes here</pre>

<h3>Import</h3>
<form>
<table class="form-table">
	<tbody><tr valign="top">
	<th scope="row">Import</th>
	<td><fieldset><legend class="screen-reader-text"><span>Import</span></legend>
	<label for="default_pingback_flag"></label><br />
	<textarea type="checkbox" checked="checked" value="1" id="default_pingback_flag" name="default_pingback_flag"></textarea>
	
	</tr>
</tbody></table>
<p class="submit">
		<input type="submit" class="button-primary" value="<?php _e('Import Changes') ?>" />
		<em><span>copy and paste</span></em>
	</p>
</form>
have options for how you want to list the view. 

Have options on how many person you want to list.
<form>
<table class="form-table">
	<tbody><tr valign="top">
		<th scope="row">ID</th>
		<td><input type="text" /></td>
	</tr>
	<tr>
		<td>label</td>
		<td><input type="text" /></td>
	</tr>
	<tr>
		<td>service url</td>
		<td><input type="text" /></td>
	</tr>
	<tr>
		<td>user url</td>
		<td><input type="text" /></td>
	</tr>
	<tr>
		<td>icon url</td>
		<td><input type="text" /></td>
	</tr>
</tbody></table>
<p class="submit">
		<input type="submit" class="button-primary" value="<?php _e('Add') ?>" />
	</p>
</form>


-->