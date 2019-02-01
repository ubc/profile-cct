<?php
	/** 
	*  The current default settings
	*/
	$note = '';
	$profile = Profile_CCT::get_object();
    global $blog_id;
    
    
    /**
     * IMPORT SETTINGS
     * 
     *
     **/
    if ( isset( $_POST['import_settings_nonce_field'] ) && wp_verify_nonce( $_POST['import_settings_nonce_field'], 'import_settings_nonce' ) && isset( $_POST['agree'] ) && 'iagree' == $_POST['agree'] ):
    	$import_url = trim( $_POST['import-url'] );
    	// $import_url = 'http://local.dev/profile/wp-admin/admin-ajax.php?action=profile-cct-export';
    	
    	if( !empty( $import_url ) ):
    	
    		$remote = wp_remote_get($import_url);
    		if ( is_wp_error($remote) ):
    			
    			echo '<div class="update-nag"><p>An error cccurred and settings were not updated, make sure that your url is valid!</p></div>';
    		else:
    			$settings = json_decode( $remote['body'], true );
    		
	    		$profile->remove_all_global_fields();
	    		if( is_array( $settings) ):
	    		foreach( $settings as $setting_name => $setting_value ) {
	    			
	    			if(  'settings' == $setting_name) :
	    				$profile->settings = $setting_value;
	    				foreach ($setting_value['clone_fields'] as $field_type => $field):
	    					$profile->add_global_field( $field, null, 'skip' );
	    				endforeach;
	    			endif;
	    			
	    			update_option( 'Profile_CCT_'.$setting_name, $setting_value );
	    		}
	    			echo '<div id="message" class="updated below-h2"><p>Settings Were Update!</p></div>';
	    			flush_rewrite_rules();
	    		else:
	    			echo '<div id="message" class="error below-h2"><p>Settings were not updated</p></div>';
	    		endif;
    			
    		endif;
    		
    		
    	else:
    		echo '<div id="message" class="error below-h2"><p>Please enter an import url</p></div>';
    	endif;
    
    elseif( isset( $_POST['import_settings_nonce_field'] ) && wp_verify_nonce( $_POST['import_settings_nonce_field'], 'import_settings_nonce' ) ):
    	echo '<div id="message" class="error below-h2"><p>Please check the box that says that you understand how the settings will be changed.</p></div>';
    	
    endif;
    
    /**
     * SAVE SETTINGS 
     *
     *
     **/
	if ( ! empty($_POST) && isset($_POST['update_settings_nonce_field']) && wp_verify_nonce( $_POST['update_settings_nonce_field'], 'update_settings_nonce' ) ):
		//Validate pic options
		$width = intval( $_POST['picture_width'] );
		$height = intval( $_POST['picture_height'] );
		if ( $width >= 100 && $width <= 560 && $height >= 100 && $height <= 560 ):
			$picture_options = array( 'width' => $width, 'height' => $height );
			$profile->settings['picture'] = $picture_options;
		else:
			$note = '<div class="error settings-error"><p>Picture dimensions should be between 100x100 and 560x560</p></div>';
		endif;
		
		$slug = trim( $_POST['slug'] );
		if ( ! empty( $slug ) ):
			$profile->settings['slug'] = trim( sanitize_title( $_POST['slug'] ) );
		else:
			$profile->settings['slug'] = 'person';
		endif;
		
		$order_by = $_POST['sort_order_by'];
		$order = in_array( $_POST['sort_order'], array( 'ASC', 'DESC' ) ) ? $_POST['sort_order'] : null ;
		if ( in_array( $order_by, array( "manual", "first_name", "last_name", "date" ) ) ):
			$profile->settings['sort_order_by'] = $order_by;
			$profile->settings['sort_order'] = $order;
		endif;
		
		$widget_title = $_POST['widget_title'];
		$profile->settings['widget_title'] = $widget_title;
		
		$archive = $_POST['archive'];
		$profile->settings['archive'] = $archive;
		
		// Lets deal with permissions	
		$post_permissions = $_POST['options']['permissions'];
		
		foreach ( $profile->settings['permissions'] as $user => $permission_array ):
			if ( $user != 'administrator' ): // Don't want people changing the permissions of the admin
				$role = get_role($user);
				
				foreach ( $permission_array as $permission => $can ):
					if ( isset( $profile->settings['permissions'][$user][$permission] ) ): // Does the permission exist in the settings
						$profile->settings['permissions'][$user][$permission] = (bool) $post_permissions[$user][$permission];
						// Add the new capability
						if ( (bool) $post_permissions[$user][$permission] ): 
							$role->add_cap( $permission );
						else:
  							$role->remove_cap( $permission );
  						endif;
					endif;
				endforeach;
			else: 
				// Admin role. You can't change the default permissions for the administater
				$role = get_role( 'administrator' );
				// The admin gets the best permissions (all of them)
				foreach ( $profile->settings['permissions']['administrator'] as $permission => $can ):
					$role->add_cap( $permission );
				endforeach;
			endif;
		endforeach;

		// Lets save the editor options
		
		$wp_editor = array(
				'media_buttons' => ( isset( $_POST['wp_editor']['media_buttons'] ) ? true : false),
				'advanced' => ( isset( $_POST['wp_editor']['advanced'] ) ? true : false),
				 );
		
		$profile->settings['wp_editor'] = $wp_editor;
		
		// Store updated options
        update_option( 'Profile_CCT_settings', $profile->settings );
		$note = '<div class="updated below-h2"><p> Settings saved.</p></div>';
		
		// Lets flush the rules again
		$profile->register_profiles();
		flush_rewrite_rules();
	endif;
	
	if ( ! isset( $profile->settings['picture'] ) )           $profile->settings['picture'] = array( 'width' => 150, 'height' => 150 );
	if ( ! isset( $profile->settings['picture']['width'] ) )  $profile->settings['picture']['width'] = 150;
	if ( ! isset( $profile->settings['picture']['height'] ) ) $profile->settings['picture']['height'] = 150;
	if ( ! isset( $profile->settings['slug'] ) )              $profile->settings['slug'] = 'person';
	if ( ! isset( $profile->settings['sort_order_by'] ) )     $profile->settings['sort_order_by'] = 'first_name';
	if ( ! isset( $profile->settings['sort_order'] ) )        $profile->settings['sort_order'] = 'ASC';
	
	$global_settings = get_site_option( PROFILE_CCT_SETTING_GLOBAL, array() );
	
?>
<h2>General Settings</h2>
<?php echo $note; ?>
<form method="post" action="">
	<h3>Picture Dimensions</h3>
	<?php wp_nonce_field( 'update_settings_nonce', 'update_settings_nonce_field' ); ?>	
	<table class="form-table">
        <tbody>
			<tr valign="top">
				<th scope="row">
					<label for="picture_width">Width</label>
				</th>
				<td>
					<input type="text" size="3" name="picture_width" id="picture_width" value="<?php echo esc_attr( $profile->settings['picture']['width'] ); ?>" /> pixels
				</td>
			</tr>
			<tr valign="top">
				<th scope="row">
					<label for="picture_height">Height</label>
				</th>
				<td>
					<input type="text" size="3" name="picture_height" id="picture_height" value="<?php echo esc_attr( $profile->settings['picture']['height'] ); ?>" /> pixels
				</td>
			</tr>
        </tbody>
    </table>
	
	<h3>Sort Order</h3>
	<table class="form-table">
		<tbody>
			<tr valign="top">
				<th scope="row"><label for="slug">Order by</label></th>
				<td>
					<?php
						$sort_order_by = $profile->settings['sort_order_by'];
						$sort_order = $profile->settings['sort_order'];
					?>
					<select name="sort_order_by" id="sort_order_by" onchange="update_sort_order_dropdown(jQuery(this).val());">
						<option value="manual" <?php selected( "manual", $sort_order_by ); ?>>Manually</option>
						<option value="first_name" <?php selected( "first_name", $sort_order_by ); ?>>First Name</option>
						<option value="last_name" <?php selected( "last_name", $sort_order_by ); ?>>Last Name</option>
						<option value="date" <?php selected( "date", $sort_order_by ); ?>>Date Added</option>
					</select>
					<select name="sort_order" id="sort_order" <?php if ( $sort_order_by == "manual" ) echo 'style="display:none"'; ?>>
						<option value="ASC" <?php selected( "ASC", $sort_order ); ?>>Ascending A - Z</option>
						<option value="DESC" <?php selected( "DESC", $sort_order ); ?>>Descending Z - A</option>
					</select>
					<span id="sort_order_info" <?php if ( $sort_order_by != "manual" ) echo 'style="display:none"'; ?>>
						 Go to <a href="<?php echo admin_url('edit.php?post_type=profile_cct&page=order_profiles'); ?>" title="Order Profiles">Order Profiles</a> to set the order.
					</span>
					<script>
						function update_sort_order_dropdown( order_by ) {
							switch ( order_by ) {
							case "manual":
								jQuery('#sort_order').hide();
								jQuery('#sort_order_info').show();
								break;
							case "date":
								jQuery('#sort_order').val('DESC');
								jQuery('#sort_order').show();
								jQuery('#sort_order_info').hide();
								break;
							default:
								jQuery('#sort_order').val('ASC');
								jQuery('#sort_order').show();
								jQuery('#sort_order_info').hide();
								break;
							}
						}
					</script>
				</td>
			</tr>
		</tbody>
	</table>
	
	<h3>Profile Archive Navigation Form</h3>
	<p>Which navigation to display on profile listing page</p>
	<table class="form-table">
        <tbody>
            <tr valign="top">
                <th scope="row"><label for="widget-title">Widget Title</label></th>
                <td>
					<?php
					if ( empty( $profile->settings['widget_title'] ) ) $profile->settings['widget_title'] = "Profile Navigation";
					?>
                    <input type="text" name="widget_title" id="widget-title" value="<?php echo esc_attr($profile->settings['widget_title']); ?>" />
                </td>
            </tr>
            <tr valign="top">
                <th scope="row"><label for="archive_display_searchbox">Show Search Box</label></th>
                <td>
                    <input type="checkbox" name="archive[display_searchbox]" id="archive_display_searchbox" value="true" <?php checked( !empty( $profile->settings['archive']['display_searchbox']) ); ?> />
                </td>
            </tr>
            <tr valign="top">
                <th scope="row"><label for="archive_display_alphabet">Show Alphabet Listing</label></th>
                <td>
                    <input type="checkbox" name="archive[display_alphabet]" id="archive_display_alphabet" value="true" <?php checked( !empty($profile->settings['archive']['display_alphabet'])); ?> />
                </td>
            </tr>
            <tr valign="top">
                <th scope="row"><label for="archive_display_orderby">Show Order By</label></th>
                <td>
                    <input type="checkbox" name="archive[display_orderby]" id="archive_display_orderby" value="true" <?php checked(!empty($profile->settings['archive']['display_orderby'])); ?> />
                </td>
            </tr>
            <tr valign="top">
                <th scope="row">Show Taxonomies</th>
                <td>
                    <?php foreach ( $profile->taxonomies as $taxonomy ): ?>
						<?php $taxonomy_id = Profile_CCT_Taxonomy::id( $taxonomy['single'] ); ?>
						<input type="checkbox" name="archive[display_tax][<?php echo $taxonomy_id; ?>]" id="archive_display_tax_<?php echo $taxonomy_id; ?>" value="true" <?php checked($profile->settings['archive']['display_tax'][$taxonomy_id], 'true'); ?> />
						<label style="padding-left:6px;" for="archive_display_tax_<?php echo $taxonomy_id; ?>"><?php echo $taxonomy['plural']; ?></label>
						<br />
					<?php endforeach; ?>
                </td>
            </tr>
        </tbody>
    </table>
	
	<h3>Permalink</h3>
	<table class="form-table">
        <tbody>
            <tr valign="top">
                <th scope="row"><label for="slug">Slug</label></th>
                <td>
					<input type="text" name="slug" id="slug" value="<?php echo esc_attr($profile->settings['slug']); ?>" />
					<br />
                    By default this is set to 'person'
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
				<th>Create multiple profiles</th>
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
					Profile_CCT_Admin::permissions_table( $user, ($count % 2), $profile->settings ); $count++;
				endforeach;
			?>
		</tbody>
	</table>
	
	<h3><abbr title="What You See Is What You Get">WYSIWYG</abbr> Editor Options </h3>
	<table class="form-table">
        <tbody>
            <tr valign="top">
                <th scope="row"><label for="wp_editor_mediabutton">Enable Media Upload</label></th>
                <td>
                    <input type="checkbox" name="wp_editor[media_buttons]" id="wp_editor_mediabutton" value="true" <?php checked( $profile->settings['wp_editor']['media_buttons'], true); ?> /> 
                    <small>Allow users that can <strong>publish</strong> thier profile to upload media files</small>.
                </td>
            </tr>
            <tr valign="top">
                <th scope="row"><label for="wp_editor_advanced">Advanced Editor Options</label></th>
                <td>
                    <input type="checkbox" name="wp_editor[advanced]" id="wp_editor_simple" value="true" <?php checked($profile->settings['wp_editor']['advanced'], true); ?> />
                    <small>The <abbr title="What You See Is What You Get">WYSIWYG</abbr> editor will have more formating options available for the profile builder.</small>
                </td>
            </tr>
     
        </tbody>
    </table>
	<br/>
	<input type="submit" class="button-primary" value="<?php _e('Save Changes') ?>" />
</form>	
<h3>Import Settings</h3>
	<table class="form-table">
        <tbody>
            <tr valign="top">
                <th scope="row"><label for="wp_editor_mediabutton">Export Settings URL</label></th>
                <td>
                	<?php echo admin_url('admin-ajax.php?action=profile-cct-export'); ?>&s=<?php echo md5($blog_id); ?>
                </td>
            </tr>
            <tr valign="top">
                <th scope="row"><label for="wp_editor_advanced">Import Settings URL</label></th>
                <td>
                	<form action="" method="post">
                    <input type="text" name="import-url" size="70" /><br />
                    
                    <label><input type="checkbox" name="agree" class="checkbox" value="iagree" /> I understand that this will <strong>overwrite ALL my current settings</strong>, such as <em>taxonomies, forms,  page and list view as well as fields</em>.<br /> <strong>There is no UNDO. PROCEED   WITH CAUTION</strong> </label><br />
                    <input type="submit" value="Import Settings" class="button" />
                    <?php wp_nonce_field( 'import_settings_nonce', 'import_settings_nonce_field' ); ?>
                    </form>
                </td>
            </tr>
     
        </tbody>
    </table>