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
		global $post;
		$post_id = ( isset( $post->ID) ? $post->ID : 0 ); 
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
				<div id="tabs" class="tabs context-shell">
				<?php 
			else: 
				$profile_cct_tabs++;
				?>
				<div id="<?php echo "tab-id-".$profile_cct_tabs; ?>" class="profile-cct-shell profile-cct-shell-tabs" >
				<?php
			endif; 
			?>

			<div class="nav-tabs-wrapper">
				<ul class="nav nav-tabs">
					<?php 
					$count = 1;
					if ( is_array( $tabs ) ):
						$first = true;
						foreach( $tabs as $tab ): 
							?>
							<li <?php if ($first) echo 'class="active"'; $first = false; ?>>
								<a href="#tabs-<?php echo $post_id; ?>-<?php echo $count; ?>" data-toggle="tab" class="tab-link"><?php echo $tab; ?></a>
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
			</div>
			<div class="tab-content">
				<?php
					$count = 1;
					if ( is_array($tabs) ):
						$first = true;
						foreach ( $tabs as $tab ) :
							?>
							<div id="tabs-<?php echo $post_id; ?>-<?php echo $count; ?>" class="tab-pane <?php if ($first) echo 'active'; $first = false; ?>">
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
				?>
			</div>
			<?php $theme_support = get_theme_support('tabs'); ?>
			<?php if ( $editing ): ?>
				<div id="add-tabshell"></div>
			<?php elseif ( $theme_support[0] != 'twitter-bootstrap' ): ?>
				<script type="text/javascript">
					jQuery(document).ready(function() {
						jQuery(".profile-cct-shell-tabs").tabs();
					});
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