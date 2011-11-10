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



