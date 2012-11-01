<?php 




function profile_cct_form_shell_tabs($action){
	
	profile_cct_show_tabs($action,'form');
}

function profile_cct_page_shell_tabs($action,$user_data = null){

	profile_cct_show_tabs($action,'page',$user_data);
}

$profile_cct_tabs = 0;
function profile_cct_show_tabs($action,$type, $user_data = null ) {
	
	$act = ($action == 'edit'?  true: false);
	$profile = Profile_CCT::get_object();
	$profile->action = $action;
	$tabs = $profile->get_option($type,'tabs');

	// check if we even want to display the tabs. 
	$display_tabs = true;
	$count = 1;
	if(!$act):
		foreach( $tabs as $tab) :
			$fields[$count] = $profile->get_option($type,'fields','tabbed-'.$count);
		
			$count++;
		endforeach;
		
	endif;
	
	if($display_tabs):
	
		if($act): ?>
			<div id="tabs">
				<span class="description-shell">tabs</span>
		<?php 
		else: 
			$profile_cct_tabs++;
			?><div id="<?php echo "tab-id-".$profile_cct_tabs; ?>" class="profile-cct-shell" ><?php 
		
		endif; 
		?><ul><?php 
		$count = 1;
		if(is_array($tabs)):
			foreach( $tabs as $tab) : 
				?><li><a href="#tabs-<?php echo $count; ?>" class="tab-link"><?php echo $tab; ?></a><?php 
			if($act): ?>
				<span class="remove-tab">Remove Tab</span> <span class="edit-tab">Edit</span><input type="text" class="edit-tab-input" value="<?php echo esc_attr($tab); ?>" /><input type="button" class="edit-tab-save button" value="Save" />
			<?php endif;
				?></li><?php
				$count++;
			endforeach;
		endif;
		if($act): ?>
		<li id="add-tab-shell"><a href="#add-tabshell" id="add-tab" title="Add Tab">Add Tab</a></li>
		<?php 
		endif; 
		?></ul><?php 
		
		$count = 1;
		if(is_array($tabs)):
			foreach( $tabs as $tab) :
			?><div id="tabs-<?php echo $count?>"><?php 
				if($act): ?>
					<input type="hidden" name="form_field[tabs][]" value="<?php echo esc_attr($tab); ?>" />
					<ul class="form-builder sort" id="tabbed-<?php echo $count?>">
					<?php 
				endif;
				
				unset($fields);
				
				$fields = $profile->get_option($type,'fields','tabbed-'.$count);
				
				
				$i = 0;
				
				if(is_array($fields)):
					foreach( $fields as $field):
							if($type == 'page')
								if( function_exists('profile_cct_'.$field['type'].'_display_shell') ):
					 				call_user_func('profile_cct_'.$field['type'].'_display_shell',$action,$field,$user_data[ $field['type']]);
					 			else:
					 				do_action( 'profile_cct_display_shell_'.$field['type'], $action, $field, $user_data[ $field['type'] ] );
					 			endif;
							else
								if( function_exists('profile_cct_'.$field['type'].'_shell') ):
					 				call_user_func('profile_cct_'.$field['type'].'_shell',$action,$field);
					 			else:
					 				do_action( 'profile_cct_shell_'.$field['type'], $action, $field, $user_data[ $field['type'] ] );
					 			endif;
					endforeach;
				endif;
				if($act): ?>
					</ul>
				<?php 
				endif; 
					?></div><?php 
				$count++;
			endforeach; 
		endif;
			
			if($act): ?>
				<div id="add-tabshell"></div>
			<?php else: ?>
				<script type="text/javascript">
					/* <![CDATA[ */
					jQuery(document).ready(function() {
						jQuery("#<?php echo "tab-id-".$profile_cct_tabs; ?>").tabs();
					});
					/* ]]> */
				</script>
			<?php endif; ?></div><?php 
		
	endif;
}

