<?php
Class Profile_CCT_Tabs {
	/**
	 * shell function.
	 * 
	 * @access public
	 * @static
	 * @return void
	 */
	public static function shell( $data ) {
		$editing = ( 'edit' == Profile_CCT_Admin::$action ?  true : false );
		$tabs = Profile_CCT_Admin::get_option( Profile_CCT_Admin::$page, 'tabs' ); // get back all the tabs for that page
		
		// check if we even want to display the tabs. 
		$display_tabs = true;
		$count = 1;
		if ( is_array($tabs) ):
			foreach ( $tabs as $tab ) :
				$fields[$count] = Profile_CCT_Admin::get_option( Profile_CCT_Admin::$page, 'fields', 'tabbed-'.$count);
				$count++;
			endforeach;
		endif;
		
		if ( $display_tabs ):
			if ( $editing ):
				?>
				<div id="tabs">
					<span class="description-shell">tabs</span>
				<?php 
			else: 
				$profile_cct_tabs++;
				?>
				<div id="<?php echo "tab-id-".$profile_cct_tabs; ?>" class="profile-cct-shell" >
				<?php
			endif; 
			?>
			<ul>
				<?php 
				$count = 1;
				if ( is_array( $tabs ) ):
					foreach( $tabs as $tab ): 
						?>
						<li>
							<a href="#tabs-<?php echo $count; ?>" class="tab-link"><?php echo $tab; ?></a>
							<?php if ( $editing ): ?>
								<span class="remove-tab">Remove Tab</span> <span class="edit-tab">Edit</span><input type="text" class="edit-tab-input" value="<?php echo esc_attr($tab); ?>" /><input type="button" class="edit-tab-save button" value="Save" />
							<?php endif; ?>
						</li>
						<?php
						$count++;
					endforeach;
				endif;
				?>
				
				<?php if ( $editing ): ?>
					<li id="add-tab-shell"><a href="#add-tabshell" id="add-tab" title="Add Tab">Add Tab</a></li>
				<?php endif; ?>
			</ul>
			<?php 
			
			$count = 1;
			if ( is_array($tabs) ):
				foreach ( $tabs as $tab ) :
					?>
					<div id="tabs-<?php echo $count?>">
					<?php if ($editing): ?>
						<input type="hidden" name="form_field[tabs][]" value="<?php echo esc_attr($tab); ?>" />
					<?php endif; ?>
						<?php
						unset($fields);
						Profile_CCT_Admin::render_context( 'tabbed-'.$count, $data );
						?>
					</div>
					<?php
					$count++;
				endforeach; 
			endif;
			
			if ( $editing ): ?>
				<div id="add-tabshell"></div>
			<?php else: ?>
				<script type="text/javascript">
					/* <![CDATA[ */
					jQuery(document).ready(function() {
						jQuery("#<?php echo "tab-id-".$profile_cct_tabs; ?>").tabs();
					});
					/* ]]> */
				</script>
			<?php endif; ?>
			</div>
			<?php 
		endif;
	}
}

/**
 * profile_cct_tabs_shell function.
 * 
 * @access public
 * @return void
 */
function profile_cct_tabs_shell( $data ) {
	Profile_CCT_Tabs::shell( $data );
}