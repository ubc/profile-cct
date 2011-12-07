<form method="post" action="">
   <?php settings_fields('Profile_CCT_settings'); ?>
<table class="form-table">
	<tbody><tr valign="top">
	<th scope="row">...</th>
	<td><fieldset><legend class="screen-reader-text"><span>...</span></legend>
	<label for="default_pingback_flag">
	<input type="checkbox" checked="checked" value="1" id="default_pingback_flag" name="default_pingback_flag"> Allow UBC directory intergration</label>
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

<input class="button-primary" type="submit" name="Example" value="<?php _e( 'Example Primary Button' ); ?>" /> 
</form>


<?php
	$this->e(get_option('Profile_CCT_settings'));
  ?>