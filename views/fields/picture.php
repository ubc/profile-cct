<?php 
if ( !defined( 'PROFILE_CCT_FULL_WIDTH' ) )
	define( 'PROFILE_CCT_FULL_WIDTH', 150 );
	
if ( !defined( 'PROFILE_CCT_FULL_HEIGHT' ) )
	define( 'PROFILE_CCT_FULL_HEIGHT', 150 );

/**
 * profile_cct_picture_field_shell function.
 * 
 * @access public
 * @param mixed $action
 * @param mixed $options. (default: null)
 * @return void
 */
function profile_cct_picture_field_shell( $action, $options=null ) {
	if( is_object($action) ):
		$post = $action;
		$action = "display";
		$data = $options['args']['data'];
		$options = $options['args']['options'];
	endif;
	
	$field = Profile_CCT::get_object(); // prints "Creating new instance."
	if( !is_array($options) )
		$options = $field->form_fields['picture']; // stuff that is coming from the db
	
	$default_options = array(
		'type'=>'picture',
		'label'=>'picture',	
		'description'=>'',
		'picture_width' => '150',
		'picture_height' => '150',
		);
		
	$options = (is_array($options) ? array_merge( $default_options, $options ): $default_options );
	$field->start_field($action,$options);
	
	profile_cct_picture_field($data,$options);
	
	$field->end_field( $action, $options );
}
/**
 * profile_cct_picture_field function.
 * 
 * @access public
 * @param mixed $data
 * @param mixed $options
 * @return void
 */
function profile_cct_picture_field( $data, $options ){
	global $post;
	extract( $options );
	
	$field = Profile_CCT::get_object();
	$show = (is_array($show) ? $show : array());
	
	
	if(is_object($post)):
		$thumbnail_id = get_post_meta( $post->ID, '_thumbnail_id', true );
		profile_cct_picture_form($thumbnail_id);
	else:
		profile_cct_picture_display(  $data, $options  );
	endif;
}


function profile_cct_picture_display_shell( $action, $options=null, $data=null ) {
	
	if( is_object($action) ):
		$post 		= $action;
		$action 	= "display";
		$data 		= $options['args']['data'];
		$options 	= $options['args']['options'];
	endif;
	
	$field = Profile_CCT::get_object(); // prints "Creating new instance."
	if( !is_array($options) )
		$options = $field->form_fields['picture']; // stuff that is comming from the db
	
	$default_options = array(
		'type'		=> 'picture',
		'label'		=> 'picture',
		'hide_label'=>  true,
		'before'	=> '',
		'empty'		=> '',
		'width' 	=> 'one-third',
		'link_to'	=> true,
		'show_link_to' => true,
		'after'		=> '',
		);
		
	$options = (is_array($options) ? array_merge( $default_options, $options ): $default_options );
	$field->start_field($action,$options);
	
	profile_cct_picture_display($data,$options);
	
	$field->end_field( $action, $options );
}
/**
 * profile_cct_picture_field function.
 * 
 * @access public
 * @param mixed $data
 * @param mixed $options
 * @return void
 */
function profile_cct_picture_display(  $data, $options  ){
	global $post;
	extract( $options );
	
	$field 	= Profile_CCT::get_object();
	$show 	= (is_array($show) ? $show : array());
	$href 	= ( isset($post) ? get_permalink() : "#" );
	
	if(isset($post)):
		$field->display_text( array( 'field_type'=>$type, 'class'=>'', 'type'=>'shell', 'tag'=>'a','link_to'=>$link_to, 'href'=>$href ) );
		echo get_the_post_thumbnail($post->ID, 'full');
		$field->display_text( array( 'field_type'=>$type, 'type'=>'end_shell', 'tag'=>'a','link_to'=>$link_to) );
	else:
		global $current_user;
      	get_currentuserinfo();
		echo get_avatar($current_user->user_email, 150);
	endif;
		
		
}




/* -- USER AVATAR STUFF -- */


function profile_cct_picture_form($thumbnail_id)
{
	global $post;
	$picture_options = profile_cct_get_picture_options();
	$iframe_width = $picture_options['width'] + 550;
	if($thumbnail_id): ?>
		<div id="user-avatar-display-image"><?php echo get_the_post_thumbnail($post_id, 'thumbnail'); ?></div>
		<a id="user-avatar-link" class="button thickbox" href="<?php echo admin_url('admin-ajax.php'); ?>?action=profile_cct_picture_add_photo&step=1&post_id=<?php echo $post->ID; ?>&TB_iframe=true&width=<?php echo $iframe_width; ?>&height=500" ><?php _e('Update Picture','user-avatar'); ?></a> 
	<?php
		// Remove the User-Avatar button if there is no uploaded image
		$remove_url = admin_url('post.php')."?post=".$post->ID."&action=edit&delete_avatar=true&_nononce=". wp_create_nonce('profile_cct_picture');
		?>
			<a id="user-avatar-remove" class="submitdelete deleteaction" href="<?php echo esc_url_raw($remove_url); ?>" title="<?php _e('Remove User Avatar Image','user-avatar'); ?>" ><?php _e('Remove','user-avatar'); ?></a>
		<?php
	else:
		?>
		<div id="user-avatar-display-image"></div>
	<a id="user-avatar-link" class="button thickbox" href="<?php echo admin_url('admin-ajax.php'); ?>?action=profile_cct_picture_add_photo&step=1&post_id=<?php echo $post->ID; ?>&TB_iframe=true&width=720&height=450" title="<?php _e('Upload and Crop an Image to be Displayed','user-avatar'); ?>" ><?php _e('Add Picture','user-avatar'); ?></a> 
	<?php
	endif;
	?>
	<script type="text/javascript">
	//<![CDATA[
	function profile_cct_picture_refresh_image(img){
	 	jQuery('#user-avatar-display-image').html(img);
	}
	function profile_cct_add_remove_avatar_link(){
		if(!jQuery("#user-avatar-remove")){
			jQuery('#user-avatar-link').after(" <a href='<?php echo $remove_url; ?>' class='submitdelete'  id='user-avatar-remove' ><?php _e('Remove','user-avatar'); ?></a>")
		}
	}
	//]]>
	</script>
	<?php
} 


add_action('wp_ajax_profile_cct_picture_add_photo', 'profile_cct_picture_add_photo');
add_action('profile_cct_picture_iframe_head','profile_cct_picture_init');



/**
 * profile_cct_picture_init function.
 * Description: Initializing user avatar style.
 * @access public
 * @return void
 */
function profile_cct_picture_init(){
	
	wp_enqueue_style( 'global' );
	wp_enqueue_style( 'wp-admin' );
	wp_enqueue_style( 'colors' );
	wp_enqueue_style( 'ie' );
	wp_enqueue_style('user-avatar', plugins_url('/profile-cct/css/profile-picture.css'), 'css');
	wp_enqueue_style('imgareaselect');
	wp_enqueue_script('imgareaselect');
	do_action('admin_print_styles');
	do_action('admin_print_scripts');
	do_action('admin_head');

}


/**
 * profile_cct_picture_add_photo function.
 * The content inside the iframe 
 * Description: Creating panels for the different steps users take to upload a file and checking their uploads.
 * @access public
 * @return void
 */
function profile_cct_picture_add_photo() {
	global $current_user;
	
	$post_id = $_GET['post_id'];
	
	
	?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" <?php do_action('admin_xml_ns'); ?> <?php language_attributes(); ?>>
<head>
<meta http-equiv="Content-Type" content="<?php bloginfo('html_type'); ?>; charset=<?php echo get_option('blog_charset'); ?>" />
<title><?php bloginfo('name') ?> &rsaquo; <?php _e('Uploads'); ?> &#8212; <?php _e('WordPress'); ?></title>
<script type="text/javascript">
//<![CDATA[
addLoadEvent = function(func){if(typeof jQuery!="undefined")jQuery(document).ready(func);else if(typeof wpOnload!='function'){wpOnload=func;}else{var oldonload=wpOnload;wpOnload=function(){oldonload();func();}}};
var userSettings = {
		'url': '<?php echo SITECOOKIEPATH; ?>',
		'uid': '<?php if ( ! isset($current_user) ) $current_user = wp_get_current_user(); echo $current_user->ID; ?>',
		'time':'<?php echo time() ?>'
	},
	ajaxurl = '<?php echo admin_url('admin-ajax.php'); ?>',
	pagenow = '<?php echo $current_screen->id; ?>',
	typenow = '<?php if ( isset($current_screen->post_type) ) echo $current_screen->post_type; ?>',
	adminpage = '<?php echo $admin_body_class; ?>',
	thousandsSeparator = '<?php echo addslashes( $wp_locale->number_format['thousands_sep'] ); ?>',
	decimalPoint = '<?php echo addslashes( $wp_locale->number_format['decimal_point'] ); ?>',
	isRtl = <?php echo (int) is_rtl(); ?>;
//]]>
</script>
<?php
	do_action('profile_cct_picture_iframe_head');
?>

</head>
<body>
<?php
	switch($_GET['step'])
	{
		case 1:
			profile_cct_picture_add_photo_step1($post_id);
		break;
		
		case 2:
			profile_cct_picture_add_photo_step2($post_id);
		break;
		
		case 3:
			profile_cct_picture_add_photo_step3($post_id);
		break;
	}
		
	do_action('admin_print_footer_scripts');
?>
<script type="text/javascript">
	    //<![CDATA[
		if(typeof wpOnload=='function')wpOnload();
		//]]>
	</script>
</body>
</html>
<?php
	die();
}

/**
 * profile_cct_picture_add_photo_step1 function.
 * The First Step in the process 
 * Description: Displays the users photo and they can choose to upload another if they please.
 * @access public
 * @param mixed $uid
 * @return void
 */
function profile_cct_picture_add_photo_step1($post_id)
{
	?>
	<p id="step1-image" >
		<?php echo get_the_post_thumbnail($post_id, 'full'); ?>
	</p>
	<div id="user-avatar-step1">
	<form enctype="multipart/form-data" id="uploadForm" method="POST" action="<?php echo admin_url('admin-ajax.php'); ?>?action=profile_cct_picture_add_photo&step=2&post_id=<?php echo $post_id; ?>" >
			<label for="upload"><?php _e('Choose an image from your computer:','user-avatar'); ?></label><br /><input type="file" id="upload" name="uploadedfile" />
			<input type="hidden" name="action" value="save" />
			<?php wp_nonce_field('user-avatar') ?>
		<p class="submit"><input type="submit" value="<?php esc_attr_e('Upload'); ?>" /></p>
	</form>
	</div>
	
	<?php
}

/**
 * profile_cct_picture_add_photo_step2 function.
 * The Second Step in the process 
 * Description: Takes the uploaded photo and saves it to database.
 * @access public
 * @param mixed $uid
 * @return void
 */
function profile_cct_picture_add_photo_step2($post_id)
{
		$picture_options = profile_cct_get_picture_options();	
		//print_r($picture_options);
		
		if (!(($_FILES["uploadedfile"]["type"] == "image/gif") || ($_FILES["uploadedfile"]["type"] == "image/jpeg") || ($_FILES["uploadedfile"]["type"] == "image/png") || ($_FILES["uploadedfile"]["type"] == "image/pjpeg") || ($_FILES["uploadedfile"]["type"] == "image/x-png"))){
			echo "<div class='error'><p>".__("Please upload an image file (.jpeg, .gif, .png).",'user-avatar')."</p></div>";
			profile_cct_picture_add_photo_step1($post_id);
			die();
		}
		$overrides = array('test_form' => false);
		$file = wp_handle_upload($_FILES['uploadedfile'], $overrides);
		
		if ( isset($file['error']) ) // die on error
			die( $file['error'] );
		


		$url = $file['url'];
		$type = $file['type'];
		$file = $file['file'];
		$filename = basename($file);

		// Construct the object array
		$object = array(
		'post_title' => $filename,
		'post_content' => $url,
		'post_mime_type' => $type,
		'guid' => $url);

		// Save the data
		$id = wp_insert_attachment($object, $file);

		list($width, $height, $type, $attr) = getimagesize( $file );
		
		if ( $width > 500 ) {
			$oitar = $width / 500;
			$image = wp_crop_image($file, 0, 0, $width, $height, 500, $height / $oitar, false, str_replace(basename($file), 'midsize-'.basename($file), $file));
			

			$url = str_replace(basename($url), basename($image), $url);
			$width = $width / $oitar;
			$height = $height / $oitar;
		} else {
			$oitar = 1;
		}
		
		$preview_width = $picture_options['width'];
		if($preview_width > 400)
			$preview_width = 400;
		?>
		<form id="iframe-crop-form" method="POST" action="<?php echo admin_url('admin-ajax.php'); ?>?action=profile_cct_picture_add_photo&step=3&post_id=<?php echo esc_attr($post_id); ?>">
		
		<div style="float:left;">
		
		<h4><?php _e('Choose the part of the image you want to use as your profile image.','user-avatar'); ?></h4> 
		
		<div id="wrap">
		<img src="<?php echo $url; ?>" id="upload" width="<?php echo esc_attr($width); ?>" height="<?php echo esc_attr($height); ?>" />
		</div>
		
		</div>
		
		<div id="user-avatar-preview" style="width: <?php echo $preview_width; ?>px; margin-right:10px;">
		<h4>Preview</h4>
		<span class="attachment-thumbnail"><div id="preview" style="width: <?php echo $picture_options['width']; ?>px; height: <?php echo $picture_options['height']; ?>px; overflow: hidden;" >
		<img src="<?php echo esc_url_raw($url); ?>"  width="<?php echo esc_attr($width); ?>" height="<?php echo $height; ?>">
		</div></span>
		<p class="submit" >
		<input type="hidden" name="x1" id="x1" value="0" />
		<input type="hidden" name="y1" id="y1" value="0" />
		<input type="hidden" name="x2" id="x2" />
		<input type="hidden" name="y2" id="y2" />
		<input type="hidden" name="width" id="width" value="<?php echo esc_attr($width) ?>" />
		<input type="hidden" name="height" id="height" value="<?php echo esc_attr($height) ?>" />
		<input type="hidden" name="attachment_id" id="attachment_id" value="<?php echo esc_attr($id); ?>" />
		<input type="hidden" name="oitar" id="oitar" value="<?php echo esc_attr($oitar); ?>" />
		<?php wp_nonce_field('user-avatar'); ?>
		<input type="submit" id="user-avatar-crop-button" value="<?php esc_attr_e('Crop Image','user-avatar'); ?>" />
		</p>
		</div>
		
		
		</form>
		
		<script type="text/javascript">
	//<![CDATA[
	function onEndCrop( coords ) {
		jQuery( '#x1' ).val(coords.x);
		jQuery( '#y1' ).val(coords.y);
		jQuery( '#width' ).val(coords.w);
		jQuery( '#height' ).val(coords.h);
	}

	jQuery(document).ready(function() {
		var xinit = <?php echo $picture_options['width']; ?>;
		var yinit = <?php echo $picture_options['height']; ?>;
		var ratio = xinit / yinit;
		var ximg = jQuery('img#upload').width();
		var yimg = jQuery('img#upload').height();

		if ( yimg < yinit || ximg < xinit ) {
			if ( ximg / yimg > ratio ) {
				yinit = yimg;
				xinit = yinit * ratio;
			} else {
				xinit = ximg;
				yinit = xinit / ratio;
			}
		}

		jQuery('img#upload').imgAreaSelect({
			handles: true,
			keys: true,
			aspectRatio: xinit + ':' + yinit,
			show: true,
			x1: 0,
			y1: 0,
			x2: xinit,
			y2: yinit,
			//maxHeight: <?php echo PROFILE_CCT_FULL_HEIGHT; ?>,
			//maxWidth: <?php echo PROFILE_CCT_FULL_WIDTH; ?>,
			onInit: function () {
				jQuery('#width').val(xinit);
				jQuery('#height').val(yinit);
			},
			onSelectChange: function(img, c) {
				jQuery('#x1').val(c.x1);
				jQuery('#y1').val(c.y1);
				jQuery('#width').val(c.width);
				jQuery('#height').val(c.height);

				if (!c.width || !c.height)
        			return;
    
			    var scaleX = <?php echo $picture_options['width']; ?> / c.width;
			    var scaleY = <?php echo $picture_options['height']; ?> / c.height;
				
			    jQuery('#preview img').css({
			        width: Math.round(scaleX * <?php echo $width; ?>),
			        height: Math.round(scaleY * <?php echo $height; ?>),
			        marginLeft: -Math.round(scaleX * c.x1),
			        marginTop: -Math.round(scaleY * c.y1)
			    });

			}
		});
	});
	//]]>
</script>
		<?php
}
/**
 * profile_cct_picture_add_photo_step3 function.
 * The Third Step in the Process
 * Description: Deletes previous uploaded picture and creates a new cropped image in its place. 
 * @access public
 * @param mixed $uid
 * @return void
 */
function profile_cct_picture_add_photo_step3($post_id)
{
	$picture_options = profile_cct_get_picture_options();
	
	if ( $_POST['oitar'] > 1 ) {
			$_POST['x1'] = $_POST['x1'] * $_POST['oitar'];
			$_POST['y1'] = $_POST['y1'] * $_POST['oitar'];
			$_POST['width'] = $_POST['width'] * $_POST['oitar'];
			$_POST['height'] = $_POST['height'] * $_POST['oitar'];
		}
		
	$original = get_attached_file( $_POST['attachment_id'] );

	$cropped = wp_crop_image($_POST['attachment_id'], $_POST['x1'], $_POST['y1'], $_POST['width'], $_POST['height'], $picture_options['width'], $picture_options['height']);
	if ( is_wp_error( $cropped ) )
			wp_die( __( 'Image could not be processed.  Please go back and try again.' ), __( 'Image Processing Error' ) );

	$cropped = apply_filters('wp_create_file_in_uploads', $cropped, $_POST['attachment_id']); // For replication

	$parent = get_post($_POST['attachment_id']);
	
	$parent_url = $parent->guid;
	$url = str_replace(basename($parent_url), basename($cropped), $parent_url);

	// Construct the object array
	$object = array(
		'ID' => $_POST['attachment_id'],
		'post_title' => basename($cropped),
		'post_content' => $url,
		'post_mime_type' => 'image/jpeg',
		'guid' => $url
	);
	
	// Update the attachment
	wp_insert_attachment($object, $cropped, $post_id);
	wp_update_attachment_metadata( $_POST['attachment_id'], wp_generate_attachment_metadata( $_POST['attachment_id'], $cropped ) );

	// cleanup
	$medium = str_replace(basename($original), 'midsize-'.basename($original), $original);
	@unlink( apply_filters( 'wp_delete_file', $medium ) );
	@unlink( apply_filters( 'wp_delete_file', $original ) );
	
	set_post_thumbnail( $post_id, $_POST['attachment_id'] );
	
	if ( is_wp_error( $cropped ) )
		wp_die( __( 'Image could not be processed.  Please go back and try again.' ), __( 'Image Processing Error' ) );		
	?>
	<script type="text/javascript">
	    //<![CDATA[
		self.parent.profile_cct_picture_refresh_image('<?php echo get_the_post_thumbnail($post_id, 'thumbnail'); ?>');
		self.parent.profile_cct_add_remove_avatar_link();
		//]]>
	</script>
	<div id="user-avatar-step3">
		<h3><?php _e("Here's your new profile picture...",'user-avatar'); ?></h3>
		<span style="float:left;">
		<?php
		 echo get_the_post_thumbnail($post_id, 'full'); 
		 ?>
		</span>
		<a id="user-avatar-step3-close" class="button" onclick="self.parent.tb_remove();" ><?php _e('Close','user-avatar'); ?></a>
	</div>
	
<?php	
}

add_action("admin_init", "profile_cct_picture_delete");
/**
 * profile_cct_picture_delete function.
 * 
 * @access public
 * @return void
 */
function profile_cct_picture_delete(){
		
		global $pagenow;
		
		$current_user = wp_get_current_user();
		
		// If user clicks the remove avatar button, in URL deleter_avatar=true
		if( isset($_GET['delete_avatar']) && wp_verify_nonce($_GET['_nononce'], 'profile_cct_picture') && ( $_GET['u'] == $current_user->id || current_user_can('edit_users')) )
		{
			$user_id = $_GET['user_id'];
			if(is_numeric($user_id))
				$user_id = "?user_id=".$user_id;
				
			profile_cct_picture_delete_files($_GET['u']);
			wp_redirect(get_option('siteurl') . '/wp-admin/'.$pagenow.$user_id);
			
		}		
}

function profile_cct_get_picture_options(){
	$options = get_option('Profile_CCT_settings');
	
	if(!isset($options['picture']))
		$options['picture'] = array('width'=>150, 'height'=>150);
		
	return $options['picture'];
}
