<?php 




function profile_cct_form_shell_tabs($action){
	
	profile_cct_show_tabs($action,'form');
}

function profile_cct_page_shell_tabs($action,$user_data = null){

	profile_cct_show_tabs($action,'page',$user_data);
}

$profile_cct_tabs = 0;
function profile_cct_show_tabs($action,$type, $user_data = null) {
	
	$act = ($action == 'edit'?  true: false);
	$profile = Profile_CCT::get_object();
	
	$tabs = $profile->get_option($type,'tabs');
	
	if($act): ?>
		<div id="tabs">
			<span class="description-shell">tabs</span>
	<?php 
	else: 
		$profile_cct_tabs++;
		?><div id="<?php echo "tab-id-".$profile_cct_tabs; ?>" ><?php 
	
	endif; 
	?><ul><?php 
			$count = 1;
			foreach( $tabs as $tab) : 
				?><li><a href="#tabs-<?php echo $count; ?>" class="tab-link"><?php echo $tab; ?></a><?php 
			if($act): ?>
				<span class="remove-tab">Remove Tab</span> <span class="edit-tab">Edit</span><input type="text" class="edit-tab-input" value="<?php echo esc_attr($tab); ?>" />
			<?php endif;
				?></li><?php
				$count++;
			endforeach;
			if($act): ?>
			<li id="add-tab-shell"><a href="#add-tabshell" id="add-tab" title="Add Tab">Add Tab</a></li>
			<?php 
			endif; 
			?></ul><?php 
		$count = 1;
		foreach( $tabs as $tab) :
		?><div id="tabs-<?php echo $count?>"><?php 
			if($act): ?>
				<input type="hidden" name="form_field[tabs][]" value="<?php echo esc_attr($tab); ?>" />
				<ul class="form-builder sort" id="tabbed-<?php echo $count?>">
				<?php 
			endif;
			unset($fields);
			$fields = $profile->get_option($type,'fields','tabbed-'.$count);
			$i =0;
			
			if(is_array($fields)):
				foreach( $fields as $field):
						if($type == 'page')
							call_user_func('profile_cct_'.$field['type'].'_display_shell',$action,$field,$user_data[ $field['type']]);
						else
							call_user_func('profile_cct_'.$field['type'].'_field_shell',$action,$field);
				endforeach;
			endif;
			if($act): ?>
				</ul>
			<?php 
			endif; 
				?></div><?php 
			$count++;
		endforeach; ?>
		<?php if($act): ?>
			<div id="add-tabshell"></div>
		<?php else: ?>
			<script type="text/javascript">
				jQuery(document).ready(function($) {
				$("#<?php echo "tab-id-".$profile_cct_tabs; ?>").tabs();
			});
			</script>
		<?php endif; ?></div><?php 
}

