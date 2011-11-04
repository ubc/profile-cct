<?php 




function profile_cct_form_shell_tabs($action){
	
	profile_cct_show_tabs($action,'form');
}

function profile_cct_show_page_builder_tabs($action){

	profile_cct_show_tabs($action,'page');
}


function profile_cct_show_tabs($action,$type) {
	
	$act = ($action == 'edit'?  true: false);
	$profile = Profile_CCT::get_object();
	
	$tabs = $profile->get_option($type,'tabs');
	
	?>
		<div id="tabs">
		<ul>
			<?php 
			$count = 1;
			foreach( $tabs as $tab) : ?>
				<li><a href="#tabs-<?php echo $count; ?>" class="tab-link"><?php echo $tab; ?></a>
				<?php if($act): ?>
				<span class="remove-tab">Remove Tab</span> <span class="edit-tab">Edit</span><input type="text" class="edit-tab-input" value="<?php echo esc_attr($tab); ?>" /></li>
			<?php 
				endif;
				$count++;
			endforeach; ?>
			<?php if($act): ?>
			<li id="add-tab-shell"><a href="#add-tabshell" id="add-tab" title="Add Tab">Add Tab</a></li>
			<?php endif; ?>
		</ul>
		<?php 
		$count = 1;
		foreach( $tabs as $tab) :
		?>
			<div id="tabs-<?php echo $count?>">
				<input type="hidden" name="form_field[tabs][]" value="<?php echo esc_attr($tab); ?>" />
				<ul class="form-builder sort" id="tabbed-<?php echo $count?>">
				<?php 
				unset($fields);
				$fields = $profile->get_option($type,'fields','tabbed-'.$count);
				$i =0;
				if(is_array($fields)):
					foreach( $fields as $field):
							call_user_func('profile_cct_'.$field['type'].'_field_shell',$action,$field);
					endforeach;
				endif;
				?>
				</ul>
			</div>
			<?php 
			$count++;
		endforeach; ?>
		<?php if($act): ?>
		<div id="add-tabshell"></div>
		<?php endif; ?>
		</div>
		<?php 
}

