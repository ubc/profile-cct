<?php 
	$profile = Profile_CCT::get_object();
	$taxonomies = $profile->taxonomies; 

	// Add taxonomy
	if ( ! empty($_POST) && check_admin_referer( 'add_profile_taxonomy', 'add_profile_taxonomy_field' ) ):
		$error = array();
		$hierarchical = ( is_numeric( $_POST['hierarchical'] ) ? $_POST['hierarchical'] : 0 );
		$display = ( ! empty( $_POST['display'] ) ? $_POST['display'] : 'default' );
		$plural = trim( strip_tags( $_POST['plural-name'] ) );
		
		if ( empty($plural) ):
			$error['plural'] = "Please fill out the plural name";
		endif;
		
		$single = trim( strip_tags( $_POST['single-name'] ) );
		if ( empty($single) ):
			$error['single'] = "Please fill out the single name";
		endif;
		
		if ( empty($error) ):
			// Since there are no errors add the taxonomy 
			$new_taxonomy = array(
				'plural'       => $plural,
				'single'       => $single,
				'hierarchical' => $hierarchical,
				'display'      => $display,
			);
			
			foreach ( $taxonomies as $taxonomy ):
				if ( $taxonomy == $new_taxonomy ):
					$error['duplicate'] = "Taxonomy already exists";
					break 1;
				endif;
			endforeach;
			
			if ( empty( $error ) ): // Ready to add the taxonomy
				$taxonomies = Profile_CCT_Taxonomy::add( $new_taxonomy, $taxonomies );
		   		$note = '<p class="info">Now you can add '.esc_attr($_POST['single-name']).' to the <a href="'.admin_url('edit.php?post_type=profile_cct&page='.PROFILE_CCT_BASEADMIN.'&view=page').'">person page</a> or the <a href="'.admin_url('edit.php?post_type=profile_cct&page='.PROFILE_CCT_BASEADMIN.'&view=list').'">list view</a></p>' ;
	   		endif;
	   	endif;
	endif;
?>
<h2>Taxonomy Builder</h2>
<p><strong>Tax&middot;on&middot;o&middot;my</strong> - The classification of something, a way to group things and be able to filter them later.</p>
<?php echo $note; ?>
<h3>Current Taxonomies </h3>
<?php if ( is_array( $taxonomies ) && ! empty( $taxonomies ) ): ?>
	<table class="widefat">
		<thead>
			<tr>
				<th class="row-title">Name</th>
				<th>Hierarchical</th>
				<th>Display</th>
			</tr>
		</thead>
		
		<tbody>
		<?php 
			$count = 0;
			foreach ( $taxonomies as $key => $taxonomy ):
				$taxonomy_id = Profile_CCT_Taxonomy::id( $taxonomy['single'] );
				?>
				<tr <?php if ( $count % 2 ) echo 'class="alternate"'; ?>>
					<td >
						<a href="<?php echo admin_url("/edit-tags.php?taxonomy=".$taxonomy_id."&post_type=profile_cct"); ?>">
							<?php echo $taxonomy['single']; ?> / <?php echo $taxonomy['plural']; ?>
						</a>
						<div class="row-actions">
							<span>
								<a href="<?php echo admin_url("/edit-tags.php?taxonomy=".$taxonomy_id."&post_type=profile_cct"); ?>">Edit</a>
								 | 
								<span class="trash">
									<a href="?post_type=profile_cct&page=<?php echo PROFILE_CCT_BASEADMIN; ?>&view=taxonomy&remove=<?php echo $key."&_wpnonce=".wp_create_nonce( 'profile_cct_remove_taxonomy'.$key ); ?> " class="submitdelete">Delete</a>
								</span>
							</span>
						</div>
					</td>
					<td>
						<?php echo ( $taxonomy['hierarchical'] ? "Yes": "No" ); ?>
					</td>
					<td>
						<?php echo ( $taxonomy['display'] ? $taxonomy['display']: "default" ); ?>
					</td>
				</tr>
				<?php 
				$count++;
			endforeach;
			?>
		</tbody>
		
		<tfoot>
			<tr>
				<th class="row-title">Name</th>
				<th>Hierarchical</th>
				<th>Display</th>
			</tr>
		</tfoot>
	</table>
<?php else: ?>
	<p>There are currently no Taxonomies defined</p>
<?php endif; ?>

<?php if ( isset( $error['duplicate'] ) ): ?>
	<br />
	<div class='error below-h2'>
		<p>The <strong><?php echo $single; ?></strong> <?php echo $error['duplicate']; ?></p>
	</div>
<?php endif; ?>

<h3>Add Taxonomy </h3>
<form method="post" action="<?php echo admin_url('edit.php?post_type=profile_cct&page='.PROFILE_CCT_BASEADMIN.'&view=taxonomy'); ?>">
	<?php wp_nonce_field( 'add_profile_taxonomy', 'add_profile_taxonomy_field' ); ?>
	
	<table class="form-table">
		<tr valign="top">
			<th scope="row">
				<label for="single-name">Singular Name</label>
				<span class="required">*</span>
			</th>
			<td>
				<input type="text" value="" id="single-name" name="single-name" class="all-options" maxlength="20" /> 
				<span class="description">For example: Research Interest</span>
				<br />
				<small>The maximum length is 20 characters.</small>
				<?php if ( isset( $error['single'] ) ): ?>
					<span class='form-invalid' style='padding:2px;'>
						<?php echo $error['single']; ?>
					</span>
				<?php endif; ?>
			</td>
		</tr>
		
		<tr valign="top">
			<th scope="row">
				<label for="plural-name">Plural Name</label>
				<span class="required">*</span>
			</th>
			<td>
				<input type="text" value="" id="plural-name" name="plural-name" class="all-options" /> 
				<span class="description">For example: Research Interests</span>	
				<br />
				<?php if ( isset( $error['plural'] ) ): ?>
					<span class='form-invalid' style='padding:2px;'>
						<?php echo $error['plural']; ?>
					</span>
				<?php endif; ?>
			</td>
		</tr>
		
		<tr valign="top">
			<th scope="row">
				Hierarchical
			</th>
			<td>
				<fieldset>
					<legend class="screen-reader-text"><span>Hierarchical</span></legend>
					<label><input type="radio" name="hierarchical" value="1" /> <span>Yes - Works like Post Categories</span></label>
					<br />
					<label><input type="radio" name="hierarchical" value="0" checked="checked" /> <span>No - Works like Post Tags</span></label>
				</fieldset>
			</td>
		</tr>
		
		<tr valign="top">
			<th scope="row">
				Display Type
			</th>
			<td>
				<fieldset>
					<legend class="screen-reader-text"><span>Display Type</span></legend>
					<select name="display">
						<option value="">Default</option>
						<option value="dropdown">Dropdown</option>
					</select>
				</fieldset>
			</td>
		</tr>
	</table>
	<input class="button-primary" type="submit" name="Example" value="<?php _e( 'Add Profile Taxonomy' ); ?>" />
</form>

<!-- TODO: Move this javascript to a seperate file. -->
<script type="text/javascript">
	jQuery('document').ready( function( $ ) {
		$('#single-name').keydown( function() {
			var name = $(this);
			setTimeout( function() {
				var value = name.val();
				if ( value == '' ) {
					$( '#plural-name' ).val( '' );
				} else {
					$( '#plural-name' ).val( value + 's' );
				}
			}, 10);
		});
	});
</script> 