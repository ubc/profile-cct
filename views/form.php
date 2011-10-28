<?php
	/*
	$this->form_fields = get_option('Profile_CCT_form_fields');
	
	if(wp_verify_nonce($_POST['_wpnonce'],'Profile_CCT_form_fields-options')) :
		unset($form_fields);
		$form_fields = array();
		$i = 0;
		if(isset($_POST['form_field'])):
			foreach($_POST['form_field'] as $field_name):
			
				$label = $_POST['field_label'][$i];
				if($_POST['field_label'][$i] == "")
					$label = $field_name; 
				
				$form_fields[] = array('name'=> $field_name, 'label' => $_POST['field_label'][$i]);
				$i++;
			endforeach;
		endif;
		update_option('Profile_CCT_form_fields',$form_fields);
		$this->form_fields = $form_fields;
	endif;
	*/
?>


<div id="col-container">
	<div id="notify">
		<span id="spinner" class="update"><img src="<?php echo admin_url(); ?>/images/wpspin_light.gif" alt="spinner" /> thinking...</span>
		
	</div>


<div id="col-right">
	<div class="col-wrap">
	<h3>Form fields to add</h3>
	<ul id="cct-fields">
		
		<li><strong>Basic Info</strong></li>
		<li><a href="#address" id="cct-address">Address</a></li>
		<li><a href="#phone" id="cct-phone">Phone</a></li>
		<!-- 
		<li><a href="#fax" id="cct-fax">Fax</a></li> -->
		<li><a href="#email" id="cct-email">Email</a></li>
		<li><a href="#website"id="cct-website">Website</a></li>
		<li><a href="#social" id="cct-social" >Social</a></li>
		
		<li><strong>Bio</strong></li>
		<li><a href="#position"id="cct-position">Position</a></li>
		<li><a href="#bio" id="cct-bio">Bio</a></li>
		<li><a href="#education" id="cct-education">Education</a></li>
		<li><a href="#teaching" id="cct-teaching" >Teaching</a></li>
		<li><a href="#publications" id="cct-publications">Publications</a></li>
		<li><a href="#research" id="cct-research" >Research</a></li>
		
		<!--
		<li><strong>Social</strong></li>
		
		<li><a href="#twitter" id="cct-twitter" >Twitter</a></li>
		<li><a href="#blog" id="cct-blog">Blog</a></li>
		<li><a href="#facebook" id="cct-facebook">Facebook</a></li>
		<li><a href="#linkedin" id="cct-linkedin">LinkedIn</a></li>
		<li><a href="#delicious" id="cct-delicious">Delicious</a></li>
		<li><a href="#flickr" id="cct-flickr">Flickr</a></li>
		<li><a href="#google-plus" id="cct-google-plus">Google Plus</a></li>
		 
		<li><strong>Tools</strong></li>
		<li><a href="#text" id="cct-textare" >Text</a></li>
		<li><a href="#textare" id="cct-textare" >Textarea</a></li>
		-->
	</ul>
		
	</div>
</div><!-- /col-right -->

<div id="col-left">
	<div class="col-wrap">
	<h3>Preview</h3>
	
	<?php
		do_action('profile_cct_form','edit');
		// $this->show_name_field("edit");
		// $this->show_tabs('form','edit');
	?>

	</div>
</div><!-- /col-left -->

</div>
