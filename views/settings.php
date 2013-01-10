<?php

	/** 
	*  The current default settings
	*/
	$note = '';
	$profile = Profile_CCT::get_object();
    error_log(print_r($profile, TRUE));	
	
	if( !empty($_POST) && wp_verify_nonce( $_POST['update_settings_nonce_field'], 'update_settings_nonce' ) ):
		
		//Validate pic options
		$width = intval( $_POST['picture_width'] );
		$height = intval( $_POST['picture_height'] );
		if( $width >= 100 && $width <= 560 && $height >= 100 && $height <= 560):
			$picture_options = array ( 'width'=>$width, 'height'=>$height );
			$profile->settings['picture'] = $picture_options;
		else:
			$note = '<div class="error settings-error"><p>Picture dimensions should be between 100x100 and 560x560</p></div>';
		endif;
		
		$slug = trim( $_POST['slug'] );
		if( !empty( $slug ) ):
			$profile->settings['slug'] = sanitize_title( trim( $_POST['slug'] ) );
		else:
			$profile->settings['slug'] = 'person';
		endif;
		
		$order_by = $_POST['sort_order'];
		if(in_array($order_by, array("manual", "first_name", "last_name", "date"))):
			$profile->settings['sort_order'] = $order_by;
		endif;
		
		
		$archive = $_POST['archive'];
		$profile->settings['archive'] = $archive;
		
		// lets deal with permissions	
		$post_permissions = $_POST['options']['permissions'];
		
		foreach($profile->settings['permissions'] as $user => $permission_array):
			if($user != 'administrator'): // don't want people changing the permissions of the admin
				
				$role = get_role( $user );
				
				foreach($permission_array as $permission => $can):
					if( isset( $profile->settings['permissions'][$user][$permission] ) ): // does the permission exist in the settings
						$profile->settings['permissions'][$user][$permission] = (bool)$post_permissions[$user][$permission];
						// add the new capability
						if( (bool)$post_permissions[$user][$permission] ): 
							$role->add_cap( $permission );
						else:
  							$role->remove_cap(  $permission );
  							
  						endif;
					endif;
				endforeach;
				
			else: 
				// admin role you can't change the default permissions for the administater
				$role = get_role( 'administrator' );
				// the admin gets the best permissions
				foreach($profile->settings['permissions']['administrator'] as $permission => $can):
						$role->add_cap( $permission );
				endforeach;
				
			endif;
			
		endforeach;
		
		
		//Store updated options
		update_option( 'Profile_CCT_settings', $profile->settings );

		$note = '<div class="updated below-h2"><p> Settings saved.</p></div>';
		// lets flush the rules again
		$profile->register_cpt_profile_cct();
		flush_rewrite_rules();
	endif;


?>
<h2>General Settings</h2>
<?php echo $note; ?>
<form method="post" action="">
	<h3>Picture Dimensions</h3>
	<?php wp_nonce_field( 'update_settings_nonce','update_settings_nonce_field' ); ?>	
	<table class="form-table">
	<tbody>
	<tr valign="top">
		<th scope="row"><label for="picture_width">Width</label></th>
		<td><input type="text" size="3" name="picture_width" id="picture_width" value="<?php echo esc_attr($profile->settings['picture']['width']); ?>" /> pixels</td>
	</tr>
	<tr valign="top">
		<th scope="row"><label for="picture_height">Height</label></th>
		<td><input type="text" size="3" name="picture_height" id="picture_height" value="<?php echo esc_attr($profile->settings['picture']['height']); ?>" /> pixels</td>
	</tr>
	</tbody></table>
	
	
	<h3>Sort Order</h3>
	<table class="form-table">
	<tbody>
	<tr valign="top">
		<th scope="row"><label for="slug">Order by</label></th>
		<td>
			<select name="sort_order" id="sort_order">
				<option value="manual" <?php selected("manual", $profile->settings['sort_order']); ?>>Manually</option>
				<option value="first_name" <?php selected("first_name", $profile->settings['sort_order']); ?>>First Name</option>
				<option value="last_name" <?php selected("last_name", $profile->settings['sort_order']); ?>>Last Name</option>
				<option value="date" <?php selected("date", $profile->settings['sort_order']); ?>>Date Added</option>
			</select><br />
			if using manual sorting, go to <a href="<?php echo admin_url('edit.php?post_type=profile_cct&page=order_profiles'); ?>" title="Order Profiles">Order Profiles</a> to set the order.
		</td>
	</tr>

	</tbody></table>
	
	
	
	<h3>Profile Archive Navigation Form</h3>
	<p>Which navigation to display on profile listing page</p>
	<table class="form-table">
        <tbody>
            <tr valign="top">
                <th scope="row"><label for="archive_display_searchbox">Show Search Box</label></th>
                <td>
                    <input type="checkbox" name="archive[display_searchbox]" id="archive_display_searchbox" <?php checked($profile->settings['archive']['display_searchbox'], 'on'); ?> />
                </td>
            </tr>
            
            <tr valign="top">
                <th scope="row"><label for="archive_display_alphabet">Show Alphabet Listing</label></th>
                <td>
                    <input type="checkbox" name="archive[display_alphabet]" id="archive_display_alphabet" <?php checked($profile->settings['archive']['display_alphabet'], 'on'); ?> />
                </td>
            </tr>
            
            <tr valign="top">
                <th scope="row"><label for="archive_display_orderby">Show Order By</label></th>
                <td>
                    <input type="checkbox" name="archive[display_orderby]" id="archive_display_orderby" <?php checked($profile->settings['archive']['display_orderby'], 'on'); ?> />
                </td>
            </tr>
            
            <tr valign="top">
                <th scope="row">Show Taxonomies</th>
                <td>
                    <?php
                        foreach( get_object_taxonomies('profile_cct') as $tax): ?>
                            <input type="checkbox" name="archive[display_tax][<?php echo $tax; ?>]" id="archive_display_tax_<?php echo $tax; ?>" <?php checked($profile->settings['archive']['display_tax'][$tax], 'on'); ?> /><label style="padding-left:6px;"for="archive_display_tax_<?php echo $tax; ?>"><?php echo substr($tax, 12); ?></label><br />
                        <?php endforeach;
                    ?>
                </td>
            </tr>
        </tbody>
    </table>
	
	
	
	
	<h3>Permalink</h3>
	<table class="form-table">
        <tbody>
            <tr valign="top">
                <th scope="row"><label for="slug">Slug</label></th>
                <td><input type="text"  name="slug" id="slug" value="<?php echo esc_attr($profile->settings['slug']); ?>" /><br />
                    By default it is set to 'person'
                </td>
            </tr>
        </tbody>
    </table>
	

	<h3>Profile Permissions</h3>

	<table class="wp-list-table widefat fixed posts ">
		<thead>
			<tr>
				<th>Role</th>
				<th>Enable public profile</th>
				<th>Manage own profiles</th>
				<th>Manage all profiles</th>
				<th>Publish profile</th>
				<th>Read private profile</th>
				<th>Delete own profile</th>
				<th>Delete all profiles</th>
			</tr>
		</thead>		
		<tbody id="the-list">
				<?php 
				$count = 0;
				foreach( $profile->settings['permissions'] as $user => $permission ):
					Profile_CCT_Admin::permissions_table( $user, ($count%2), $profile->settings ); $count++;
				endforeach; ?>
		</tbody>
	</table>
	<br />
	<input type="submit" class="button-primary" value="<?php _e('Save Changes') ?>" />
</form>	
