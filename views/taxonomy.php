<?php 
	$taxonomys = get_option( 'Profile_CCT_taxonomy');
	if(!is_array($taxonomys))
		$taxonomys = array();
	
// remove Taxonomy 
if( wp_verify_nonce($_GET['_wpnonce'], 'profile_cct_remove_taxonomy'.$_GET['remove'])){
	if(isset($taxonomys[$_GET['remove']]))
		unset($taxonomys[$_GET['remove']]);
	

	update_option( 'Profile_CCT_taxonomy', $taxonomys );
	flush_rewrite_rules();
	
}
// Add taxonomy
if ( !empty($_POST) && check_admin_referer( 'add_profile_taxonomy','add_profile_taxonomy_field' ) ) :
	
	//delete_option( 'Profile_CCT_taxonomy');
		
	$error = array();
	
	$hierarchical = (is_numeric($_POST['hierarchical'])? $_POST['hierarchical'] : 0 );
	
	$plural = trim(strip_tags ($_POST['plural-name']));
	if(empty($plural))
		$error['plural'] = "Please Fill out the Plural Name";
	
	$single = trim(strip_tags ($_POST['single-name']));
	if(empty($single))
		$error['single'] = "Please Fill out the Single Name";
	
	
	if(empty($error)):
		// since there are no errors add the taxonomy 
		$new_taxonomy = array( 'plural'=>$plural, 'single' => $single, 'hierarchical' => $hierarchical);
		foreach($taxonomys as $taxonomy):
			if($taxonomy == $new_taxonomy):
				$error['duplicate'] = "taxonomy already exists";
				break 1;
			endif;
		endforeach;
		
		
		if(empty($error)):
			$taxonomys[] = $new_taxonomy;
	   		update_option( 'Profile_CCT_taxonomy', $taxonomys );
	   		
			profile_cct_register_taxonomy($new_taxonomy);
	   		flush_rewrite_rules();
	   		
	   		$note = "<p class='info'>Now you can add ".esc_attr($_POST['single-name'])." to the <a href=\"".admin_url('edit.php?post_type=profile_cct&page=profile-cct/profile-custom-content-type.php&view=page')."\">person page</a> or the <a href=\"".admin_url('edit.php?post_type=profile_cct&page=profile-cct/profile-custom-content-type.php&view=list')."\">list view</a></p>" ;
   		endif;
   		
   	endif;
endif;

?>
<h2>Taxonomy Builder</h2>
	<p><strong>Tax&middot;on&middot;o&middot;my</strong> - The classification of something, a way to group things and be able to filter them later.</p>
	<?php echo $note; ?>
	<h3>Current Taxonomies </h3>
	<?php if(is_array($taxonomys) && !empty($taxonomys)) : ?>
	<table class="widefat">
		<thead>
		<tr>
			<th class="row-title">Name</th>
			<th>Hierarchical</th>
		</tr>
		</thead>
		
		<tbody>
		<?php 
			  $count = 0;
			  foreach($taxonomys as $taxonomy): ?>
		<tr <?php if($count%2) echo 'class="alternate"'; ?>>
		<td ><?php echo $taxonomy['single']; ?> / <?php echo $taxonomy['plural']; ?>
		<div class="row-actions">
			<span class="trash"><a href="?post_type=profile_cct&page=profile-cct/profile-custom-content-type.php&view=taxonomy&remove=<?php echo $count."&_wpnonce=".wp_create_nonce('profile_cct_remove_taxonomy'.$count); ?> " class="submitdelete">Delete</a>
		</div>
		</td>
		<td><?php echo ($taxonomy['hierarchical']? "Yes": "No"); ?></td>
		</tr>
		<?php 
			  $count++;
			  endforeach; ?>
		</tbody>
		
		<tfoot>
			<tr>
			<th class="row-title">Name</th>
			<th>Hierarchical</th>
		</tr>
		</tfoot>
	</table>
	<?php else: ?>
	<p>There are currently no Taxonomies defined</p>
	<?php endif; ?>
	<?php echo (isset($error['duplicate'])? "<br /><div class='error below-h2'><p>The <strong>".$single."</strong> ".$error['duplicate']."</p></div>": ""); ?>
	<h3>Add Taxonomy </h3>
	<form method="post" action="<?php echo admin_url('edit.php?post_type=profile_cct&page=profile-cct/profile-custom-content-type.php&view=taxonomy'); ?>">
		<?php wp_nonce_field( 'add_profile_taxonomy','add_profile_taxonomy_field' ); ?>
		
		<table class="form-table">
		
		<tr valign="top">
			<th scope="row"><label for="single-name">Singular Name</label><span class="required">*</span>
			</th>
			<td>
			<input type="text" value="" id="single-name" name="single-name" class="all-options" /> <span class="description">For example: Research Interest</span>
			<br /><?php echo (isset($error['single'])? "<span class='form-invalid' style='padding:2px;'>".$error['single']."</span>": ""); ?>
			</td>
		</tr>
		
		<tr valign="top">
			<th scope="row"><label for="plural-name">Plural Name</label><span class="required">*</span>
			</th>
			<td>
				<input type="text" value="" id="plural-name" name="plural-name" class="all-options" /> <span class="description">For example: Research Interests</span>	
				<br /><?php echo (isset($error['plural'])? "<span class='form-invalid' style='padding:2px;'>".$error['plural']."</span>": ""); ?>
			</td>
		</tr>
		<tr valign="top">
			<th scope="row">Hierarchical</th>
			<td>
				<fieldset><legend class="screen-reader-text"><span>Hierarchical</span></legend>
					<label title='g:i a'><input type="radio"  name="hierarchical" value="1"  /> <span>Yes - Works like Post Categories</span></label> <br />
					<label title='g:i a'><input type="radio" name="hierarchical" value="0" checked="checked" /> <span>No - Works like Post Tags</span></label>
				</fieldset>
			</td>
			</tr>
		</table>
		<input class="button-primary" type="submit" name="Example" value="<?php _e( 'Add Profile Taxonomy' ); ?>" />
	
	<script type="text/javascript">
		//<![CDATA[
	jQuery('document').ready(function($){
		$('#single-name').keypress(function(){
			// 
			var name = $(this);
			setTimeout( function(){
			
			$('#plural-name').val(name.val()+'s');
			}, 10);
		});
	});
	//]]>
	</script> 
</form>
