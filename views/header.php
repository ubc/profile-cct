<?php 
	do_action( 'profile_cct_admin_pages', Profile_CCT_Admin::$page );
?>
<div class="wrap">
	<?php Profile_CCT_Admin::icon();?>
	<h2 id="profile-setting">Settings</h2>
	
	<?php if ( version_compare( PROFILE_CCT_VERSION, Profile_CCT::version(), '>' ) ): ?>
		<div class="update-profiles info" id="update-profile-shell">
			You need to update profiles so that they will run smoothly with the latest version of the plugin.
			<a href="#nogo" id="refresh-profiles" class="button">Update All Profiles</a>
		</div>
	<?php elseif ( 'update-all' == get_transient('Profile_CCT_needs_refresh') ): ?>
	<div id="update-profile-shell" class="update-profiles info">
			Profile plugin requires you to update your profile. 
			<a href="#nogo" id="refresh-profiles" class="button">Update All Profiles</a>
		</div>
	<?php elseif ( false !== ( $where = get_transient('Profile_CCT_needs_refresh') ) ): ?>
		<?php
			$where = array_keys($where);
			$where_out = "";
			$has = "layout has";
			
			if ( ! is_array($where) ):
				$where_out = $where;
			elseif ( count($where) > 1 ):
				$count = count($where);
				$where[$count-1] = "and ".$where[$count-1];
				if ($count > 2):
					$where_out = implode(", ", $where);
				else:
					$where_out = implode(" ", $where);
				endif;
				$has = "layouts have";
			else:
				$where_out = $where[0];
			endif;
		?>
		<div id="update-profile-shell" class="update-profiles info">
			The profile <?php echo $where_out." ".$has; ?> changed.
			<a href="#nogo" id="refresh-profiles" class="button">Update All Profiles</a>
		</div>
	
	<?php endif; ?>
	<h3 class="nav-tab-wrapper">
		<a class="nav-tab <?php if( ! isset($_GET['view']) ) { echo "nav-tab-active"; } ?>"
			href="edit.php?post_type=profile_cct&page=<?php echo PROFILE_CCT_BASEADMIN; ?>">About</a>
		<a class="nav-tab <?php if( 'settings' == Profile_CCT_Admin::$page ) { echo "nav-tab-active"; } ?>"
			href="edit.php?post_type=profile_cct&page=<?php echo PROFILE_CCT_BASEADMIN; ?>&view=settings">Settings</a>
		<span>Builder:</span>
		<a class="nav-tab <?php if( 'taxonomy' == Profile_CCT_Admin::$page ) { echo "nav-tab-active"; } ?>"
			href="edit.php?post_type=profile_cct&page=<?php echo PROFILE_CCT_BASEADMIN; ?>&view=taxonomy">Taxonomy</a>
		<a class="nav-tab <?php if( 'form' == Profile_CCT_Admin::$page ) { echo "nav-tab-active"; } ?>"
			href="edit.php?post_type=profile_cct&page=<?php echo PROFILE_CCT_BASEADMIN; ?>&view=form">Form</a>
		<a class="nav-tab <?php if( 'page' == Profile_CCT_Admin::$page ) { echo "nav-tab-active"; } ?>"
			href="edit.php?post_type=profile_cct&page=<?php echo PROFILE_CCT_BASEADMIN; ?>&view=page">Profile View</a>
		<a class="nav-tab <?php if( 'list' == Profile_CCT_Admin::$page ) { echo "nav-tab-active"; } ?>"
			href="edit.php?post_type=profile_cct&page=<?php echo PROFILE_CCT_BASEADMIN; ?>&view=list">List View</a>
		<a class="nav-tab <?php if( 'fields' == Profile_CCT_Admin::$page ) { echo "nav-tab-active"; } ?>"
			href="edit.php?post_type=profile_cct&page=<?php echo PROFILE_CCT_BASEADMIN; ?>&view=fields">Fields</a>
	</h3>
	<?php
		do_action("profile_cct_before_page", Profile_CCT_Admin::$page );
		
		switch( Profile_CCT_Admin::$page ) {
		case "form":	
			require( PROFILE_CCT_DIR_PATH. "views/form.php");
			break;
		case "page":
			require( PROFILE_CCT_DIR_PATH. "views/page.php");
			break;
		case "list":
			require( PROFILE_CCT_DIR_PATH. "views/list.php");
			break;
		case "helper":
			require( PROFILE_CCT_DIR_PATH. "views/helper.php");
			break;
		case "taxonomy":
			require( PROFILE_CCT_DIR_PATH. "views/taxonomy.php");
			break;
		case "fields":
			require( PROFILE_CCT_DIR_PATH. "views/fields.php");
			break;
		case "settings":
			require( PROFILE_CCT_DIR_PATH. "views/settings.php");
			break;
		default:
			require( PROFILE_CCT_DIR_PATH. "views/about.php");
			break;
		} 
	?>
	<div class="profile-version">version <?php echo Profile_CCT::version();  ?></div>
</div>