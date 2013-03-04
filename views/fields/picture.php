<?php 
if ( ! defined( 'PROFILE_CCT_FULL_WIDTH'         ) ) define( 'PROFILE_CCT_FULL_WIDTH',         150 );
if ( ! defined( 'PROFILE_CCT_FULL_HEIGHT'        ) ) define( 'PROFILE_CCT_FULL_HEIGHT',        150 );
if ( ! defined( 'PROFILE_CCT_MAX_PREVIEW_WIDTH'  ) ) define( 'PROFILE_CCT_MAX_PREVIEW_WIDTH',  400 );
if ( ! defined( 'PROFILE_CCT_MAX_PREVIEW_HEIGHT' ) ) define( 'PROFILE_CCT_MAX_PREVIEW_HEIGHT', 400 );

Class Profile_CCT_Picture extends Profile_CCT_Field {
	var $default_options = array(
		'type'         => 'picture',
		'label'        => 'picture',
		'description'  => '',
		'link_to'	   => true,
		'show_link_to' => true,
		'width'        => 'one-third',
		'before'       => '',
		'empty'        => '',
		'after'        => '',
	);
	
	function init() {
		add_action( 'wp_ajax_profile_cct_picture_add_photo',   array( 'Profile_CCT_Picture', 'add_picture' ) );
		add_action( 'wp_ajax_profile_cct_picture_delete_ajax', array( 'Profile_CCT_Picture', 'remove_picture' ) );
		add_action( 'profile_cct_picture_iframe_head',         array( 'Profile_CCT_Picture', 'init_iframe' ) );
	}
	
	function field() {
		$this->picture();
		$this->update_picture_field();
		?>
		<br />
		<em>This change will take effect immediately.</em>
		<?php
	}
	
	function display() {
		$this->picture();
	}
	
	function picture() {
		global $post;
		
		$image = ( isset( $post ) ? Profile_CCT_Picture::get_the_post_thumbnail( $post->ID, 'profile-image' ) : get_avatar( $current_user->user_email, 150 ) );
		?>
		<div class="user-avatar-display-image">
			<?php if ( $image != null ) echo $image; ?>
		</div>
		<?php
	}
	
	function update_picture_field() {
		global $post;
		
		$picture_options = $this->picture_options();
		$iframe_width = $picture_options['width'] + 520;
		
		if ( empty( $post ) ): // If you are viewing the preview.
			?>
			<span class="add-multiple">
				<a class="button disabled" disabled="disabled" style="display: inline;" href="#add">Update Picture</a> <em>disabled in preview</em>
			</span>
			<?php
			return;
		endif;
		
		$update_url = admin_url( 'admin-ajax.php?action=profile_cct_picture_add_photo&step=1&post_id='.$post->ID.'&TB_iframe=true&width='.$iframe_width.'&height=520' );
		$remove_url = admin_url( 'admin-ajax.php?action=profile_cct_picture_delete_ajax&post='.$post->ID.'&_nonce='.wp_create_nonce('profile_cct_picture') );
		$remove_link = '<a id="user-avatar-remove" class="deleteaction" href="'.$remove_url.'" title="Remove Profile Image" onclick="return profile_cct_picture_remove_image();">Remove</a>';
		if ( has_post_thumbnail( $post->ID ) ):
			?>
			<a id="user-avatar-link" class="button thickbox" href="<?php echo $update_url; ?>" title="Upload and Crop an Image to be Displayed">Update Picture</a>
			<?php
			echo $remove_link;
		else:
			?>
			<a id="user-avatar-link" class="button thickbox" href="<?php echo $update_url; ?>" title="Upload and Crop an Image to be Displayed">Add Picture</a>
			<?php
		endif;
		
		?>
		<script type="text/javascript">
			function profile_cct_picture_refresh_image( img ) {
				jQuery( '.user-avatar-display-image' ).html( img );
				jQuery( '#user-avatar-link' ).text( "Update Picture" );
				profile_cct_add_remove_avatar_link();
			}
			
			function profile_cct_add_remove_avatar_link() {
				if ( jQuery( '#user-avatar-remove' ).length == 0 ) {
					jQuery( '#user-avatar-link' ).after( '<?php echo $remove_link; ?>')
				}
			}
			
			function profile_cct_picture_remove_image() {
				jQuery.ajax({
					type: "get",
					url: "<?php echo $remove_url; ?>",
					success: function(response) {
						jQuery( '.user-avatar-display-image' ).html( "" );
						jQuery( '#user-avatar-link' ).text( "Add Picture" );
						jQuery( '#user-avatar-remove' ).remove();
					}
				});
				
				return false;
			}
		</script>
		<?php
	}
	
	/**
	 * init_iframe function.
	 * Description: Initializing user avatar style.
	 * @access public
	 * @return void
	 */
	function init_iframe() {
		wp_enqueue_style( 'global' );
		wp_enqueue_style( 'wp-admin' );
		wp_enqueue_style( 'colors' );
		wp_enqueue_style( 'ie' );
		wp_enqueue_style( 'user-avatar', PROFILE_CCT_DIR_URL.'/css/profile-picture.css', 'css' );
		wp_enqueue_style( 'imgareaselect' );
		wp_enqueue_script( 'imgareaselect' );
		do_action( 'admin_print_styles' );
		do_action( 'admin_print_scripts' );
		do_action( 'admin_head' );
	}
	
	function add_picture() {
		global $current_user;
		$post_id = $_GET['post_id'];
		
		?>
		<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
		<html xmlns="http://www.w3.org/1999/xhtml" <?php do_action('admin_xml_ns'); ?> <?php language_attributes(); ?>>
			<head>
				<meta http-equiv="Content-Type" content="<?php bloginfo('html_type'); ?>; charset=<?php echo get_option('blog_charset'); ?>" />
				<title>
					<?php bloginfo('name') ?> &rsaquo; <?php _e('Uploads'); ?> &#8212; <?php _e('WordPress'); ?>
				</title>
				
				<script type="text/javascript">
					addLoadEvent = function(func) {
						if ( typeof jQuery != "undefined" ) {
							jQuery(document).ready(func);
						} else if ( typeof wpOnload != 'function' ) {
							wpOnload = func;
						} else {
							var oldonload = wpOnload;
							wpOnload = function() {
								oldonload();
								func();
							}
						}
					};
					
					var userSettings = {
						'url': '<?php echo SITECOOKIEPATH; ?>',
						'uid': '<?php if ( ! isset($current_user) ) $current_user = wp_get_current_user(); echo $current_user->ID; ?>',
						'time':'<?php echo time() ?>'
					};
					var ajaxurl = '<?php echo admin_url('admin-ajax.php'); ?>';
					var pagenow = '<?php echo $current_screen->id; ?>';
					var typenow = '<?php if ( isset($current_screen->post_type) ) echo $current_screen->post_type; ?>';
					var adminpage = '<?php echo $admin_body_class; ?>';
					var thousandsSeparator = '<?php echo addslashes( $wp_locale->number_format['thousands_sep'] ); ?>';
					var decimalPoint = '<?php echo addslashes( $wp_locale->number_format['decimal_point'] ); ?>';
					var isRtl = <?php echo (int) is_rtl(); ?>;
				</script>
				<?php
					do_action('profile_cct_picture_iframe_head');
				?>
			</head>
			<body>
				<?php
					switch( $_GET['step'] ):
						case 1:
							profile_cct_picture_add_photo_step1($post_id);
							break;
						case 2:
							profile_cct_picture_add_photo_step2($post_id);
							break;
						case 3:
							profile_cct_picture_add_photo_step3($post_id);
							break;
					endswitch;
					
					do_action('admin_print_footer_scripts');
				?>
				<script type="text/javascript">
					if ( typeof wpOnload == 'function' ) wpOnload();
				</script>
			</body>
		</html>
		<?php
		
		die();
	}
	
	function remove_picture() {
		global $pagenow;
		
		if ( ! is_numeric( $_GET['post'] ) ):
			return;
		endif;
		
		$current_user = wp_get_current_user();
		$post = wp_get_single_post( $_GET['post'] );
		$post_author = $post->post_author;
		
		// If user clicks the remove avatar button, then in the URL deleter_avatar=true
		if ( wp_verify_nonce( $_GET['_nonce'], 'profile_cct_picture' ) && $post_author == $current_user->id || current_user_can('edit_users') ):
			Profile_CCT_Picture::update_picture( $_GET['post'], '' );
		endif;
		
		die();
	}
	
	function update_picture( $post_id, $new_attachment_id ) {
		global $post;
		$post = get_post( $post_id );
		
		Profile_CCT_Picture::delete_files( $post->ID, get_post_meta( $post->ID, '_thumbnail_id', true ) );
		set_post_thumbnail( $post->ID, $new_attachment_id );
		Profile_CCT_Admin::update_profile( $post, false );
	}
	
	/**
	 * picture_options function.
	 * 
	 * @access public
	 * @return void
	 */
	static function picture_options() {
		$profile = Profile_CCT::get_object();
		$options = $profile->settings;
		
		if ( ! isset( $options['picture'] ) ):
			$options['picture'] = array(
				'width'  => PROFILE_CCT_FULL_WIDTH,
				'height' => PROFILE_CCT_FULL_HEIGHT,
			);
		endif;
		
		return $options['picture'];
	}
	
	/**
	 * delete_files function.
	 * 
	 * @access public
	 * @param $u
	 * @return void
	 */
	static function delete_files( $post, $img ) {
		wp_delete_attachment( $img );
		wp_update_attachment_metadata( $post, null );
	}
	
	/**
	 * get_the_post_thumbnail function.
	 * Description: Display post thumbnail if supported by theme, else an error message.
	 * @access public
	 * @param $post_id ID of post to get thumbnail for
	 * @param $type Which thumbnail format to get
	 * @return
	 *		Associative array of all picture related options
	 */
	static function get_the_post_thumbnail( $post_id, $type ){
		if ( current_theme_supports( 'post-thumbnails' ) ):
			return get_the_post_thumbnail( $post_id, $type );
		else:
			return "<p></p>";
		endif;
	}
	
	/**
	 * shell function.
	 * 
	 * @access public
	 * @static
	 * @param mixed $options
	 * @param mixed $data
	 * @return void
	 */
	public static function shell( $options, $data ){
		new Profile_CCT_Picture( $options, $data );
	}
}

function profile_cct_picture_shell( $options, $data ){
	Profile_CCT_Picture::shell( $options, $data );
}

Profile_CCT_Picture::init();

/**
 * profile_cct_picture_add_photo_step1 function.
 * The First Step in the process 
 * Description: Displays the users photo and they can choose to upload another if they please.
 * @access public
 * @param mixed $uid
 * @return void
 */
function profile_cct_picture_add_photo_step1( $post_id ) {
	?>
	<p id="step1-image" >
		<?php echo Profile_CCT_Picture::get_the_post_thumbnail( $post_id, 'full' ); ?>
	</p>
	<div id="user-avatar-step1">
		<form enctype="multipart/form-data" id="uploadForm" method="POST" action="<?php echo admin_url('admin-ajax.php'); ?>?action=profile_cct_picture_add_photo&step=2&post_id=<?php echo $post_id; ?>" >
			<label for="upload">
				<?php _e('Choose an image from your computer:','user-avatar'); ?>
			</label>
			<br />
			<input type="file" id="upload" name="uploadedfile" />
			
			<?php wp_nonce_field('user-avatar') ?>
			<p class="submit">
				<input type="submit" value="<?php esc_attr_e('Upload'); ?>" />
			</p>
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
function profile_cct_picture_add_photo_step2( $post_id ) {
	$picture_options = Profile_CCT_Picture::picture_options();	
	
	if ( ! in_array( $_FILES["uploadedfile"]["type"], array( "image/gif", "image/jpeg", "image/png", "image/pjpeg", "image/x-png" ) ) ):
		?>
			<div class='error'>
				<p><?php _e( "Please upload an image file (.jpeg, .gif, .png).", 'user-avatar' ); ?></p>
			</div>
		<?php
		profile_cct_picture_add_photo_step1( $post_id );
		die();
	endif;
	
	$overrides = array( 'test_form' => false );
	$file = wp_handle_upload( $_FILES['uploadedfile'], $overrides );
	
	if ( isset($file['error']) ): // die on error
		die( $file['error'] );
	endif;
	
	$url      = $file['url'];
	$type     = $file['type'];
	$file     = $file['file'];
	$filename = basename($file);

	// Construct the object array
	$object = array(
		'post_title'     => $filename,
		'post_content'   => $url,
		'post_mime_type' => $type,
		'guid'           => $url,
	);

	// Save the data
	$id = wp_insert_attachment( $object, $file );

	list( $width, $height, $type, $attr ) = getimagesize( $file );
	
	//If the image is below the minimum width or height
	if ( $width < $picture_options['width'] || $height < $picture_options['height'] ):
		?>
			<div class='error'>
				<p>
					The image you selected is too small. Please select an image with width at least <?php echo $picture_options['width']; ?> and height at least <?php echo $picture_options['height']; ?>
				</p>
			</div>
		<?php
		profile_cct_picture_add_photo_step1($post_id);
		return;
	endif;
	
	//If the image is exactly the right size
	if ( $width == $picture_options['width'] && $height == $picture_options['height'] ):
		profile_cct_picture_add_photo_step3( $post_id, true, $id );
		return;
	endif;
	
	if ( $width > 500 ):
		$oitar = $width / 500;
		$image = wp_crop_image( $file, 0, 0, $width, $height, 500, $height / $oitar, false, str_replace( basename($file), 'midsize-'.basename($file), $file ) );
		$url = str_replace( basename($url), basename($image), $url );
		$width = $width / $oitar;
		$height = $height / $oitar;
	else:
		$oitar = 1;
	endif;
	
	$preview_width = $picture_options['width'];
	if ( $preview_width > PROFILE_CCT_MAX_PREVIEW_WIDTH ):
		$ratio = $preview_width / PROFILE_CCT_MAX_PREVIEW_WIDTH;
		
		$preview_width = $preview_width / $ratio;
		$preview_height = min( $picture_options['height'], PROFILE_CCT_MAX_PREVIEW_HEIGHT ) / $ratio;
	else:
		$ratio = 1;
		$preview_height = min( $picture_options['height'], PROFILE_CCT_MAX_PREVIEW_HEIGHT );
	endif;
	
	?>
		<form id="iframe-crop-form" method="POST" action="<?php echo admin_url('admin-ajax.php'); ?>?action=profile_cct_picture_add_photo&step=3&post_id=<?php echo esc_attr($post_id); ?>">
			<div style="float:left;">
				<h4 style=""><?php _e( 'Choose the part of the image you want to use as your profile image.', 'user-avatar' ); ?></h4> 
				<div id="wrap">
					<img src="<?php echo $url; ?>" id="upload" width="<?php echo esc_attr($width); ?>" height="<?php echo esc_attr($height); ?>" />
				</div>
			</div>
			<div id="user-avatar-preview" style="width: <?php echo $preview_width; ?>px; margin-right:10px;">
				<h4>Preview</h4>
				<span class="attachment-thumbnail">
					<div id="preview" style="width: <?php echo $preview_width; ?>px; height: <?php echo $preview_height; ?>px; overflow: hidden;" >
						<img src="<?php echo esc_url_raw($url); ?>"  width="<?php echo esc_attr($width); ?>" height="<?php echo $height; ?>">
					</div>
				</span>
				<p class="submit" >
					<input type="hidden" name="x1" id="x1" value="0" />
					<input type="hidden" name="y1" id="y1" value="0" />
					<input type="hidden" name="x2" id="x2" />
					<input type="hidden" name="y2" id="y2" />
					<input type="hidden" name="width" id="width" value="<?php echo esc_attr($width) ?>" />
					<input type="hidden" name="height" id="height" value="<?php echo esc_attr($height) ?>" />
					<input type="hidden" name="attachment_id" id="attachment_id" value="<?php echo esc_attr($id); ?>" />
					<input type="hidden" name="oitar" id="oitar" value="<?php echo esc_attr($oitar); ?>" />
					<input type="hidden" name="preview_ratio" id="preview_ratio" value="<?php echo esc_attr($oitar); ?>" />
					<?php wp_nonce_field('user-avatar'); ?>
					<input type="submit" id="user-avatar-crop-button" value="<?php esc_attr_e('Crop Image','user-avatar'); ?>" />
				</p>
			</div>
		</form>
		<script type="text/javascript">
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
					onInit: function () {
						jQuery('#width').val(xinit);
						jQuery('#height').val(yinit);
					},
					onSelectChange: function( img, coords ) {
						jQuery('#x1').val(coords.x1);
						jQuery('#y1').val(coords.y1);
						jQuery('#width').val(coords.width);
						jQuery('#height').val(coords.height);
						
						if ( ! coords.width || ! coords.height ) {
							return;
						}
						
						//Scale the picture based on either the maximum preview size or the true picture size (whichever is smaller)
						<?php
							$scale_width  = min( PROFILE_CCT_MAX_PREVIEW_WIDTH, $picture_options['width'] );
							$scale_height = min( PROFILE_CCT_MAX_PREVIEW_HEIGHT, $picture_options['height'] );
						?>
						var scaleX = <?php echo $scale_width; ?> / coords.width;
						var scaleY = <?php echo $picture_options['height'] / ( $picture_options['width'] / $scale_width); ?> / coords.height;
						
						jQuery('#preview img').css({
							width:        Math.round( scaleX * <?php echo $width; ?> ),
							height:       Math.round( scaleY * <?php echo $height; ?> ),
							marginLeft: - Math.round( scaleX * coords.x1 ),
							marginTop:  - Math.round( scaleY * coords.y1 ),
						});
					}
				});
			});
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
function profile_cct_picture_add_photo_step3( $post_id, $no_crop = false, $attachment_id = 0 ) {
	$picture_options = Profile_CCT_Picture::picture_options();
	
	if ( $_POST['oitar'] > 1 ):
		$_POST['x1']     = $_POST['x1'] * $_POST['oitar'];
		$_POST['y1']     = $_POST['y1'] * $_POST['oitar'];
		$_POST['width']  = $_POST['width'] * $_POST['oitar'];
		$_POST['height'] = $_POST['height'] * $_POST['oitar'];
	endif;
	
	if ( $no_crop ):
		$_POST['attachment_id'] = $attachment_id;
	endif;	
	
	$original = get_attached_file( $_POST['attachment_id'] );

	if ( $no_crop ):
		$cropped = wp_crop_image( $_POST['attachment_id'], 0, 0, $picture_options['width'], $picture_options['height'], $picture_options['width'], $picture_options['height'] );
	else:
		$cropped = wp_crop_image( $_POST['attachment_id'], $_POST['x1'], $_POST['y1'], $_POST['width'], $_POST['height'], $picture_options['width'], $picture_options['height'] );
	endif;
	
	if ( is_wp_error( $cropped ) ):
		wp_die( __( 'Image could not be processed. Please go back and try again.' ), __( 'Image Processing Error' ) );
	endif;

	$cropped = apply_filters( 'wp_create_file_in_uploads', $cropped, $_POST['attachment_id'] ); // For replication
	$parent = get_post($_POST['attachment_id']);
	$parent_url = $parent->guid;
	$url = str_replace( basename($parent_url), basename($cropped), $parent_url );

	// Construct the object array
	$object = array(
		'ID'             => $_POST['attachment_id'],
		'post_title'     => basename($cropped),
		'post_content'   => $url,
		'post_mime_type' => 'image/jpeg',
		'guid'           => $url,
	);
	
	// Update the attachment
	wp_insert_attachment( $object, $cropped, $post_id );
	wp_update_attachment_metadata( $_POST['attachment_id'], wp_generate_attachment_metadata( $_POST['attachment_id'], $cropped ) );

	// cleanup
	$medium = str_replace( basename($original), 'midsize-'.basename($original), $original );
	@unlink( apply_filters( 'wp_delete_file', $medium ) );
	@unlink( apply_filters( 'wp_delete_file', $original ) );
	
	Profile_CCT_Picture::update_picture( $post_id, $_POST['attachment_id'] );
	
	if ( is_wp_error( $cropped ) ):
		wp_die( __( 'Image could not be processed. Please go back and try again.' ), __( 'Image Processing Error' ) );
	endif;
	
	?>
	<script type="text/javascript">
		self.parent.profile_cct_picture_refresh_image('<?php echo Profile_CCT_Picture::get_the_post_thumbnail($post_id, 'thumbnail'); ?>');
		self.parent.profile_cct_add_remove_avatar_link();
		self.parent.tb_remove(); //Disabling the final preview step. It's unecessary, as you will see the new image as soon as the thickbox is closed.
	</script>
	<!-- The final preview html
	<div id="user-avatar-step3">
		<h3><?php _e("Here's your new profile picture...",'user-avatar'); ?></h3>
		<span style="float:left;">
			<?php echo Profile_CCT_Picture::get_the_post_thumbnail($post_id, 'full'); ?>
		</span>
		<a id="user-avatar-step3-close" class="button" style="cursor: pointer;" onclick="self.parent.tb_remove();" ><?php _e( 'Close', 'user-avatar' ); ?></a>
	</div>
	-->
	<?php	
}