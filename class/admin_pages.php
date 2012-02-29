<?php 

	$type_of = (in_array($_GET['view'], array('form','page','list'))? $_GET['view']: NULL );

	do_action('profile_cct_admin_pages', $type_of);
	
	screen_icon( 'users' );
?>
	<div class="wrap">
		<h2>Settings</h2>
		<h3 class="nav-tab-wrapper">

			<a class="nav-tab <?php if( !isset($_GET['view']) ) { echo "nav-tab-active"; } ?>"
				href="edit.php?post_type=profile_cct&page=profile-cct/profile-custom-content-type.php">About</a>
			<a class="nav-tab <?php if( isset($_GET['view'])  && $_GET['view'] =='settings' ) { echo "nav-tab-active"; } ?>"
				href="edit.php?post_type=profile_cct&page=profile-cct/profile-custom-content-type.php&view=settings">Settings</a>
			<span>Builder:</span>
			<a class="nav-tab <?php if( isset($_GET['view'])  && $_GET['view'] =='taxonomy' ) { echo "nav-tab-active"; } ?>"
				href="edit.php?post_type=profile_cct&page=profile-cct/profile-custom-content-type.php&view=taxonomy">Taxonomy</a>
			<a class="nav-tab <?php if( isset($_GET['view'])  && $_GET['view'] =='form' ) { echo "nav-tab-active"; } ?>"
				href="edit.php?post_type=profile_cct&page=profile-cct/profile-custom-content-type.php&view=form">Form</a>
			<a class="nav-tab <?php if( isset($_GET['view'])  && $_GET['view'] =='page' ) { echo "nav-tab-active"; } ?>"
				href="edit.php?post_type=profile_cct&page=profile-cct/profile-custom-content-type.php&view=page">Profile View</a>
			<a class="nav-tab <?php if( isset($_GET['view'])  && $_GET['view'] =='list' ) { echo "nav-tab-active"; } ?>"
				href="edit.php?post_type=profile_cct&page=profile-cct/profile-custom-content-type.php&view=list">List View</a>
			<a class="nav-tab <?php if( isset($_GET['view'])  && $_GET['view'] =='fields' ) { echo "nav-tab-active"; } ?>"
				href="edit.php?post_type=profile_cct&page=profile-cct/profile-custom-content-type.php&view=fields">Fields</a>
		</h3>
	
	<?php
	$this->action = 'edit';
	
	do_action("profile_cct_before_page",$_GET['view']);
	
	switch( $_GET['view'] ) {
	
	case "form":	
		require(PROFILE_CCT_DIR."views/form.php");
		break;
	case "page":
		require(PROFILE_CCT_DIR."views/page.php");
		break;
	case "list":
		require(PROFILE_CCT_DIR."views/list.php");
		break;
	case "helper":
		require(PROFILE_CCT_DIR."views/helper.php");
		break;
	case "taxonomy":
		require(PROFILE_CCT_DIR."views/taxonomy.php");
		break;
	case "fields":
		require(PROFILE_CCT_DIR."views/fields.php");
		break;
	case "settings":
		require(PROFILE_CCT_DIR."views/settings.php");
		break;
	default:
		require(PROFILE_CCT_DIR."views/about.php");
		break;

	} 
	?>
	</div> <?php 