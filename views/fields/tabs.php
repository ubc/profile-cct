<?php 


add_action('profile_cct_form','profile_cct_show_form_tabs',10,1);
add_action('profile_cct_page_builder','profile_cct_show_page_builder_tabs',10,1);

function profile_cct_show_form_tabs($action){
	
	$profile = Profile_CCT::set(); // prints "Creating new instance."
	$fields = $profile->form_fields;
	
	if( !$fields['tabs'] ) 
		$fields['tabs'] 	= $profile->default_tabs("form");
	
	if( !$fields['fields'] ) 
		$fields['fields'] 	= $profile->default_fields("form");
	
	profile_cct_show_tabs($fields,$action);
}

function profile_cct_show_page_builder_tabs($action){
	
	$profile = Profile_CCT::set(); // prints "Creating new instance."
	$fields = $profile->page_fields;
	
	if( !$fields['tabs'] ) 
		$fields['tabs'] 	= $profile->default_tabs("page");
	
	if( !$fields['fields'] ) 
		$fields['fields'] 	= $profile->default_fields("page");
	
	profile_cct_show_tabs($fields,$action);
}


function profile_cct_show_tabs($fields,$action) {
	
	$act = ($action == 'edit'?  true: false);
	$profile = Profile_CCT::set();
	?>
		<div id="tabs">
		<ul>
			<?php 
			$count = 1;
			foreach( $fields['tabs'] as $tab) : ?>
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
		foreach( $fields['tabs'] as $tab) :
		?>
			<div id="tabs-<?php echo $count?>">
				<input type="hidden" name="form_field[tabs][]" value="<?php echo esc_attr($tab); ?>" />
				<ul class="connectedSortable sortable ui-helper-reset form-builder sort dropzone ">
				<?php 
				$i =0;
				if(is_array($fields['fields']) && is_array($fields['fields'][$count-1])):
					foreach( $fields['fields'][$count-1] as $field):
					 	$profile->show_field($type, $field['type'], $field['label'],$field['options'], "edit", $i ); $i++;
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
