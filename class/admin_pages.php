<?php 

	$type_of = (in_array($_GET['view'], array('form','page','list'))? $_GET['view']: NULL );

	do_action('profile_cct_admin_pages', $type_of);
	if($type_of):
		if(!is_array($this->field_options[$type_of]))
			$this->field_options[$type_of] = array();

		foreach ($this->get_contexts($type_of) as $context):

			$fields = $this->get_option($type_of, 'fields',$context);
			if( is_array($fields) ):
				foreach( $fields as $field ):
					$this->field_options[$type_of][] = $field;
					$this->field_options_type[$type_of][] = $field['type'];
				endforeach;
			endif;
			unset($fields, $field);
		endforeach;

		// lets not forget the bench
		$fields = $this->get_option($type_of, 'fields','bench');
		if( is_array($fields) ):
			foreach( $fields as $field ):
				$this->field_options[$type_of][] = $field;
			$this->field_options_type[$type_of][] = $field['type'];
		endforeach;
		endif;

		unset($fields, $field);

		// ability to add new field such as dynamic once though this
		// each type has to be unique
		$dynamic_fields = apply_filters("profile_cct_dynamic_fields", array(),$type_of );
		
	
		if(is_array($dynamic_fields)):
			foreach($dynamic_fields as $field):
				// if we can't find the field lets add it to the other things
				if( !in_array($field['type'], $this->field_options_type[$type_of]) ):

					$this->field_options[$type_of][] = $field;
					$this->field_options_type[$type_of][] = $field['type'];
					$this->option[$type_of]['fields']['bench'][] = $field;

				endif;
			endforeach;
		endif;

	endif;


	screen_icon( 'users' );
?>
	<div class="wrap">
		<h2>Profile Settings</h2>
		<h3 class="nav-tab-wrapper">

			<a class="nav-tab <?php if( !isset($_GET['view']) ) { echo "nav-tab-active"; } ?>"
				href="edit.php?post_type=profile_cct&page=profile-cct/profile-custom-content-type.php">About</a>
			<span>Builder:</span>
			<a class="nav-tab <?php if( isset($_GET['view'])  && $_GET['view'] =='taxonomy' ) { echo "nav-tab-active"; } ?>"
				href="edit.php?post_type=profile_cct&page=profile-cct/profile-custom-content-type.php&view=taxonomy">Taxonomy</a>
			<a class="nav-tab <?php if( isset($_GET['view'])  && $_GET['view'] =='form' ) { echo "nav-tab-active"; } ?>"
				href="edit.php?post_type=profile_cct&page=profile-cct/profile-custom-content-type.php&view=form">Form</a>
			<a class="nav-tab <?php if( isset($_GET['view'])  && $_GET['view'] =='page' ) { echo "nav-tab-active"; } ?>"
				href="edit.php?post_type=profile_cct&page=profile-cct/profile-custom-content-type.php&view=page">Person View</a>
			<a class="nav-tab <?php if( isset($_GET['view'])  && $_GET['view'] =='list' ) { echo "nav-tab-active"; } ?>"
				href="edit.php?post_type=profile_cct&page=profile-cct/profile-custom-content-type.php&view=list">List View</a>
	
			<a class="nav-tab <?php if( isset($_GET['view'])  && $_GET['view'] =='fields' ) { echo "nav-tab-active"; } ?>"
				href="edit.php?post_type=profile_cct&page=profile-cct/profile-custom-content-type.php&view=fields">Fields</a>
			<a class="nav-tab <?php if( isset($_GET['view'])  && $_GET['view'] =='settings' ) { echo "nav-tab-active"; } ?>"
				href="edit.php?post_type=profile_cct&page=profile-cct/profile-custom-content-type.php&view=settings">Settings</a>
			<!-- 
	
			<a class="nav-tab <?php if( isset($_GET['view']) && $_GET['view'] =='helper' ) { echo "nav-tab-active"; } ?>"
				href="edit.php?post_type=profile_cct&page=profile-cct/profile-custom-content-type.php&view=helper">HELPER</a>
				-->
		</h3>
	
	<?php
	$this->action = 'edit';
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
	</div>