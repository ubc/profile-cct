<?php
$default_options=array(
	"picture"=>array(
		"width"=>150,
		"height"=>150,
	)
);

$options = get_option('Profile_CCT_settings');
if( !empty($_POST)):
	if(wp_verify_nonce($_POST['update_settings_nonce_field'], 'update_settings_nonce')):
	
		if(empty($options)):
			$options = array();
		endif;
	
		//Validate pic options
		$width = intval($_POST['picture_width']);
		$height = intval($_POST['picture_height']);
		if( $width >= 100 && $width <= 360 && $height >= 100 && $height <= 400):
			$picture_options = array('picture' => array ( 'width'=>$width, 'height'=>$height ));
			$new_options = array_merge($options, $picture_options);
		else:
			echo '<div class="error settings-error"><p>Picture dimensions should be between 100x100 and 360x400</p></div>';
		endif;
		
		//Store updated options
		update_option('Profile_CCT_settings', $new_options);
	
	else:	//if nonce failed
		echo '<div class="error settings-error"><p>Verification error. Try again.</p></div>';
	endif;
endif;

//If new options were submitted, put them into the $options variable since that's what the form's
//default values are filled from
if($new_options):
	$options = $new_options;
elseif(empty($options)):
	$options = $default_options;
endif;

?>

<form method="post" action="">
	<h3>Picture Dimensions</h3>
	<?php wp_nonce_field( 'update_settings_nonce','update_settings_nonce_field' ); ?>
		
	<table class="form-table">
	<tbody>
	<tr valign="top">
		<th scope="row">Width</th>
		<td><input type="text" size="3" name="picture_width" id="picture_width" value="<?php echo $options['picture']['width']; ?>" /> pixels</td>
	</tr>
	<tr valign="top">
		<th scope="row">Height</th>
		<td><input type="text" size="3" name="picture_height" id="picture_height" value="<?php echo $options['picture']['height']; ?>" /> pixels</td>
	</tr>
	</tbody></table>
	<input type="submit" class="button-primary" value="<?php _e('Submit') ?>" />
</form>	
	
<!--
<table class="form-table">
	<tbody><tr valign="top">
	<th scope="row">...</th>
	<td><fieldset><legend class="screen-reader-text"><span>...</span></legend>
	<label for="default_pingback_flag">
	<input type="checkbox" checked="checked" value="1" id="default_pingback_flag" name="default_pingback_flag"> Allow UBC directory integration</label>
	<br>
	<label for="default_ping_status">
	<input type="checkbox" checked="checked" value="open" id="default_ping_status" name="default_ping_status"> Allow subscribers to manage profile</label>
	<br>
	<label for="default_comment_status">
	<input type="checkbox" checked="checked" value="open" id="default_comment_status" name="default_comment_status"> Allow multiple profiles</label>
	<br>
	<label for="default_comment_status">
	<input type="checkbox" checked="checked" value="open" id="default_comment_status" name="default_comment_status"> Allow someone else to edit profile</label>
	</fieldset></td>
	</tr>
</tbody></table>
</form>

<h3>Export</h3>
<pre> export string goes here</pre>

<h3>Import</h3>
<form>
<table class="form-table">
	<tbody><tr valign="top">
	<th scope="row">Import</th>
	<td><fieldset><legend class="screen-reader-text"><span>Import</span></legend>
	<label for="default_pingback_flag"></label><br />
	<textarea type="checkbox" checked="checked" value="1" id="default_pingback_flag" name="default_pingback_flag"></textarea>
	
	</tr>
</tbody></table>
<p class="submit">
		<input type="submit" class="button-primary" value="<?php _e('Import Changes') ?>" />
		<em><span>copy and paste</span></em>
	</p>
</form>
have options for how you want to list the view. 

Have options on how many person you want to list.
<form>
<table class="form-table">
	<tbody><tr valign="top">
		<th scope="row">ID</th>
		<td><input type="text" /></td>
	</tr>
	<tr>
		<td>label</td>
		<td><input type="text" /></td>
	</tr>
	<tr>
		<td>service url</td>
		<td><input type="text" /></td>
	</tr>
	<tr>
		<td>user url</td>
		<td><input type="text" /></td>
	</tr>
	<tr>
		<td>icon url</td>
		<td><input type="text" /></td>
	</tr>
</tbody></table>
<p class="submit">
		<input type="submit" class="button-primary" value="<?php _e('Add') ?>" />
	</p>
</form>


-->