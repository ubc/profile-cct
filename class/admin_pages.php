<?php 

	$type_of = (in_array($_GET['view'], array('form','page','list'))? $_GET['view']: NULL );

	do_action('profile_cct_admin_pages', $type_of);
	
	screen_icon( 'users' );
	
	$previous_version = get_option( 'profile_cct_version', '1.1.8' );
?>
	<div class="wrap">
		<h2 id="profile-setting">Settings</h2>
		
		<?php if( version_compare( $this->version(), $previous_version, '>' )): ?>
			<div class="update-profiles info" id="update-profile-shell">
			
			You need to update profiles so that they will run smoothly with the latest version of the plugin. <a href="#nogo" id="refresh-profiles" class="button">Update All Profiles</a>
			
			</div>
			
			
		<?php endif; ?>
		<h3 class="nav-tab-wrapper">

			<a class="nav-tab <?php if( !isset($_GET['view']) ) { echo "nav-tab-active"; } ?>"
				href="edit.php?post_type=profile_cct&page=<?php echo PROFILE_CCT_BASENAME; ?>">About</a>
			<a class="nav-tab <?php if( isset($_GET['view'])  && $_GET['view'] =='settings' ) { echo "nav-tab-active"; } ?>"
				href="edit.php?post_type=profile_cct&page=<?php echo PROFILE_CCT_BASENAME; ?>&view=settings">Settings</a>
			<span>Builder:</span>
			<a class="nav-tab <?php if( isset($_GET['view'])  && $_GET['view'] =='taxonomy' ) { echo "nav-tab-active"; } ?>"
				href="edit.php?post_type=profile_cct&page=<?php echo PROFILE_CCT_BASENAME; ?>&view=taxonomy">Taxonomy</a>
			<a class="nav-tab <?php if( isset($_GET['view'])  && $_GET['view'] =='form' ) { echo "nav-tab-active"; } ?>"
				href="edit.php?post_type=profile_cct&page=<?php echo PROFILE_CCT_BASENAME; ?>&view=form">Form</a>
			<a class="nav-tab <?php if( isset($_GET['view'])  && $_GET['view'] =='page' ) { echo "nav-tab-active"; } ?>"
				href="edit.php?post_type=profile_cct&page=<?php echo PROFILE_CCT_BASENAME; ?>&view=page">Profile View</a>
			<a class="nav-tab <?php if( isset($_GET['view'])  && $_GET['view'] =='list' ) { echo "nav-tab-active"; } ?>"
				href="edit.php?post_type=profile_cct&page=<?php echo PROFILE_CCT_BASENAME; ?>&view=list">List View</a>
			<a class="nav-tab <?php if( isset($_GET['view'])  && $_GET['view'] =='fields' ) { echo "nav-tab-active"; } ?>"
				href="edit.php?post_type=profile_cct&page=<?php echo PROFILE_CCT_BASENAME; ?>&view=fields">Fields</a>
		</h3>
	
	<?php
	$this->action = 'edit';
	
	do_action("profile_cct_before_page",$_GET['view']);
	
	switch( $_GET['view'] ) {
	
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
	<div class="profile-version">version <?php echo $previous_version; ?></div>
	</div> <?php 