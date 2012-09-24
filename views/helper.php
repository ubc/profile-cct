
<?php // this file could should be deleted ?>
		<div id="poststuff" class="metabox-holder has-right-side">
		<div id="side-info-column" class="inner-side">
		<div class="meta-box-sortables">
		<div id="about" class="postbox ">
		<div class="handlediv" title="<?php _e('Click to toggle'); ?>"><br/></div>
		<h3 class="hndle" id="about-side"><?php _e('About the plugin', 'profile-cct-td' ) ?></h3>
		<div class="inside">
		<p><?php _e('Please read more about this small plugin on github.', 'profile-cct-td' ); ?></p>
		<p>&copy; Copyright 2008 - <?php echo date('Y'); ?> <a href="http://bueltge.de">Frank B&uuml;ltge</a></p>
		</div>
		</div>
		</div>
		</div>
		<div id="post-body" class="has-side">
		<div id="post-body-content" class="has-side-content">
		<div id="normal-sortables" class="meta-box-sortables">
		<div id="about" class="postbox ">
		<div class="handlediv" title="<?php _e('Click to toggle'); ?>"><br/></div>
		<h3 class="hndle" id="menu"><?php _e('MiniMenu', 'profile-cct-td' ) ?></h3>
		<div class="inside">
		<table class="widefat" cellspacing="0">
		<tr class="alternate">
		<td class="row-title"><a href="#headers"><?php _e('Headers', 'profile-cct-td' ); ?></a></td>
		</tr>
		<tr>
		<td class="row-title"><a href="#header_icons"><?php _e('Header Icons', 'profile-cct-td' ); ?></a></td>
		</tr>
		<tr class="alternate">
		<td class="row-title"><a href="#buttons"><?php _e('Buttons', 'profile-cct-td' ); ?></a></td>
		</tr>
		<tr>
		<td class="row-title"><a href="#tables"><?php _e('Tables', 'profile-cct-td' ); ?></a></td>
		</tr>
		<tr class="alternate">
		<td class="row-title"><a href="#admin_notices"><?php _e('Admin Notices', 'profile-cct-td' ); ?></a></td>
		</tr>
		<tr>
		<td class="row-title"><a href="#alternative_colours"><?php _e('Alternative Colours', 'profile-cct-td' ); ?></a></td>
		</tr>
		<tr class="alternate">
		<td class="row-title"><a href="#pagination"><?php _e('Pagination', 'profile-cct-td' ); ?></a></td>
		</tr>
		<tr>
		<td class="row-title"><a href="#form_elements"><?php _e('Form Elements', 'profile-cct-td' ); ?></a></td>
		</tr>
		</table>
		</div>
		</div>
		</div>
		</div>
		</div>
		<br class="clear"/>
		</div>
		<code>&lt;hr /&gt;</code>
		<hr id="headers" />
		<h3><?php _e( 'Headers', 'profile-cct-td' ); ?></h3>
		<h2><code>h2</code><?php echo $this -> get_plugin_data( 'Name' ) ?></h2>
		<h3><code>h3</code><?php echo $this -> get_plugin_data( 'Name' ) ?></h3>
		<h4><code>h4</code><?php echo $this -> get_plugin_data( 'Name' ) ?></h4>
		<p><a class="alignright button" href="javascript:void(0);" onclick="window.scrollTo(0,0);" style="margin:3px 0 0 30px;"><?php _e('scroll to top', 'profile-cct-td' ); ?></a><br class="clear" /></p>
		<code>&lt;hr /&gt;</code>
		<hr id="header_icons" />
		<h3><?php _e( 'Header Icons', 'profile-cct-td' ); ?></h3>
		<?php _e( 'php-function:' , 'profile-cct-td' ) ?> <code>screen_icon( 'edit' );</code>
		<?php _e( 'or via markup' , 'profile-cct-td' ) ?>
		<code>&lt;div id=&quot;icon-edit&quot; class=&quot;icon32&quot;&gt;&lt;/div&gt;</code>
		<br />
		<code>edit</code><div id="icon-edit" class="icon32"></div>
		<br class="clear" />
		<code>upload</code><div id="icon-upload" class="icon32"></div>
		<br class="clear" />
		<code>link-manager</code><div id="icon-link-manager" class="icon32"></div>
		<br class="clear" />
		<code>edit-pages</code><div id="icon-edit-pages" class="icon32"></div>
		<br class="clear" />
		<code>edit-comments</code><div id="icon-edit-comments" class="icon32"></div>
		<br class="clear" />
		<code>themes</code><div id="icon-themes" class="icon32"></div>
		<br class="clear" />
		<code>plugins</code><div id="icon-plugins" class="icon32"></div>
		<br class="clear" />
		<code>users</code><div id="icon-users" class="icon32"></div>
		<br class="clear" />
		<code>tools</code><div id="icon-tools" class="icon32"></div>
		<br class="clear" />
		<code>options-general</code><div id="icon-options-general" class="icon32"></div>
		<br class="clear" />
		<p><a class="alignright button" href="javascript:void(0);" onclick="window.scrollTo(0,0);" style="margin:3px 0 0 30px;"><?php _e('scroll to top', 'profile-cct-td' ); ?></a><br class="clear" /></p>
		<code>&lt;hr /&gt;</code>
		<hr id="buttons" />
		<h3><?php _e( 'Buttons', 'profile-cct-td' ); ?></h3>
		<code>&lt;input class=&quot;button-primary&quot; type=&quot;submit&quot; name=&quot;Example&quot; value=&quot;&lt;?php _e( 'Example Primary Button' ); ?&gt;&quot; /&gt;</code>
		<br />
		<input class="button-primary" type="submit" name="Example" value="<?php _e( 'Example Primary Button' ); ?>" />
		<br />
		<code>&lt;input class=&quot;button-secondary&quot; type=&quot;submit&quot; value=&quot;&lt;?php _e( 'Example Secondary Button' ); ?&gt;&quot; /&gt;</code>
		<br />
		<input class="button-secondary" type="submit" value="<?php _e( 'Example Secondary Button' ); ?>" />
		<br />
		<code>&lt;a class=&quot;button-secondary&quot; href=&quot;#&quot; title=&quot;&lt;?php _e( 'Title for Example Link Button' ); ?&gt;&quot;&gt;&lt;?php _e( 'Example Link Button' ); ?&gt;&lt;/a&gt;</code>
		<br />
		<a class="button-secondary" href="#" title="<?php _e( 'Title for Example Link Button' ); ?>"><?php _e( 'Example Link Button' ); ?></a>
		<p><a class="alignright button" href="javascript:void(0);" onclick="window.scrollTo(0,0);" style="margin:3px 0 0 30px;"><?php _e('scroll to top', 'profile-cct-td' ); ?></a><br class="clear" /></p>
		<code>&lt;hr /&gt;</code>
		<hr id="tables" />
		<h3><?php _e( 'Tables', 'profile-cct-td' ); ?></h3>
		<pre><code>&lt;table class=&quot;form-table&quot;&gt;
		&lt;tr&gt;
		&lt;th class=&quot;row-title&quot;&gt;Table header cell #1&lt;/th&gt;
		&lt;th&gt;Table header cell #2&lt;/th&gt;
		&lt;tr /&gt;
		&lt;tr&gt;
		&lt;tr valign=&quot;top&quot;&gt;
		&lt;td scope=&quot;row&quot;&gt;&lt;label for=&quot;tablecell&quot;&gt;Table Cell #1, with label&lt;/label&gt;&lt;/td&gt;
		&lt;td&gt;Table Cell #2&lt;/td&gt;
		&lt;/tr&gt;
		&lt;tr valign=&quot;top&quot; class=&quot;alternate&quot;&gt;
		&lt;td scope=&quot;row&quot;&gt;&lt;label for=&quot;tablecell&quot;&gt;Table Cell #3, with label and class &lt;code&gt;alternate&lt;/code&gt;&lt;/label&gt;&lt;/td&gt;
		&lt;td&gt;Table Cell #4&lt;/td&gt;
		&lt;/tr&gt;
		&lt;tr valign=&quot;top&quot;&gt;
		&lt;td scope=&quot;row&quot;&gt;&lt;label for=&quot;tablecell&quot;&gt;Table Cell #5, with label&lt;/label&gt;&lt;/td&gt;
		&lt;td&gt;Table Cell #6&lt;/td&gt;
		&lt;/tr&gt;
		&lt;/table&gt;</code></pre>
		<table class="form-table">
		<tr>
		<th class="row-title"><?php _e( 'Table header cell #1', 'profile-cct-td' ); ?></th>
		<th><?php _e( 'Table header cell #2', 'profile-cct-td' ); ?></th>
		</tr>
		<tr>
		<tr valign="top">
		<td scope="row"><label for="tablecell"><?php _e( 'Table data cell #1, with label', 'profile-cct-td' ); ?></label></td>
		<td><?php _e( 'Table Cell #2', 'profile-cct-td' ); ?></td>
		</tr>
		<tr valign="top" class="alternate">
		<td scope="row"><label for="tablecell"><?php _e( 'Table Cell #3, with label and class', 'profile-cct-td' ); ?> <code>alternate</code></label></td>
		<td><?php _e( 'Table Cell #4', 'profile-cct-td' ); ?></td>
		</tr>
		<tr valign="top">
		<td scope="row"><label for="tablecell"><?php _e( 'Table Cell #5, with label', 'profile-cct-td' ); ?></label></td>
		<td><?php _e( 'Table Cell #6', 'profile-cct-td' ); ?></td>
		</tr>
		</table>
		<br class="clear"/>
		<pre><code>&lt;table class=&quot;widefat&quot;&gt;
		&lt;tr&gt;
		&lt;th class=&quot;row-title&quot;&gt;Table header cell #1&lt;/th&gt;
		&lt;th&gt;Table header cell #2&lt;/th&gt;
		&lt;tr /&gt;
		&lt;tr&gt;
		&lt;tr&gt;
		&lt;td class=&quot;row-title&quot;&gt;&lt;label for=&quot;tablecell&quot;&gt;Table Cell #1, with label&lt;/label&gt;&lt;/td&gt;
		&lt;td&gt;Table Cell #2&lt;/td&gt;
		&lt;/tr&gt;
		&lt;tr class=&quot;alternate&quot;&gt;
		&lt;td class=&quot;row-title&quot;&gt;&lt;label for=&quot;tablecell&quot;&gt;Table Cell #3, with label and class &lt;code&gt;alternate&lt;/code&gt;&lt;/label&gt;&lt;/td&gt;
		&lt;td&gt;Table Cell #4&lt;/td&gt;
		&lt;/tr&gt;
		&lt;tr&quot;&gt;
		&lt;td class=&quot;row-title&quot;&gt;&lt;label for=&quot;tablecell&quot;&gt;Table Cell #5, with label&lt;/label&gt;&lt;/td&gt;
		&lt;td&gt;Table Cell #6&lt;/td&gt;
		&lt;/tr&gt;
		&lt;/table&gt;</code></pre>
		<table class="widefat">
		<tr>
		<th class="row-title"><?php _e( 'Table header cell #1', 'profile-cct-td' ); ?></th>
		<th><?php _e( 'Table header cell #2', 'profile-cct-td' ); ?></th>
		<tr/>
		<tr>
		<td class="row-title"><label for="tablecell"><?php _e( 'Table Cell #1, with label', 'profile-cct-td' ); ?></label></td>
		<td><?php _e( 'Table Cell #2', 'profile-cct-td' ); ?></td>
		</tr>
		<tr class="alternate">
		<td class="row-title"><label for="tablecell"><?php _e( 'Table Cell #3, with label and class', 'profile-cct-td' ); ?> <code>alternate</code></label></td>
		<td><?php _e( 'Table Cell #4', 'profile-cct-td' ); ?></td>
		</tr>
		<tr>
		<td class="row-title"><?php _e( 'Table Cell #5, without label', 'profile-cct-td' ); ?></td>
		<td><?php _e( 'Table Cell #6', 'profile-cct-td' ); ?></td>
		</tr>
		</table>
		<br class="clear"/>
		<pre><code>&lt;table class=&quot;widefat&quot;&gt;
		&lt;thead&gt;
		&lt;tr&gt;
		&lt;th class=&quot;row-title&quot;&gt;Table header cell #1&lt;/th&gt;
		&lt;th&gt;Table header cell #2&lt;/th&gt;
		&lt;/tr&gt;
		&lt;/thead&gt;
		&lt;tbody&gt;
		&lt;tr&gt;
		&lt;td class=&quot;row-title&quot;&gt;&lt;label for=&quot;tablecell&quot;&gt;Table Cell #1, with label&lt;/label&gt;&lt;/td&gt;
		&lt;td&gt;Table Cell #2&lt;/td&gt;
		&lt;/tr&gt;
		&lt;tr class=&quot;alternate&quot;&gt;
		&lt;td class=&quot;row-title&quot;&gt;&lt;label for=&quot;tablecell&quot;&gt;Table Cell #3, with label and class &lt;code&gt;alternate&lt;/code&gt;&lt;/label&gt;&lt;/td&gt;
		&lt;td&gt;Table Cell #4&lt;/td&gt;
		&lt;/tr&gt;
		&lt;tr&gt;
		&lt;td class=&quot;row-title&quot;&gt;Table Cell #5, without label&lt;/td&gt;
		&lt;td&gt;Table Cell #6&lt;/td&gt;
		&lt;/tr&gt;
		&lt;/tbody&gt;
		&lt;tfoot&gt;
		&lt;tr&gt;
		&lt;th class=&quot;row-title&quot;&gt;Table header cell #1&lt;/th&gt;
		&lt;th&gt;Table header cell #2&lt;/th&gt;
		&lt;/tr&gt;
		&lt;/tfoot&gt;
		&lt;/table&gt;</code></pre>
		<table class="widefat">
		<thead>
		<tr>
		<th class="row-title"><?php _e( 'Table header cell #1', 'profile-cct-td' ); ?></th>
		<th><?php _e( 'Table header cell #2', 'profile-cct-td' ); ?></th>
		</tr>
		</thead>
		<tbody>
		<tr>
		<td class="row-title"><label for="tablecell"><?php _e( 'Table Cell #1, with label', 'profile-cct-td' ); ?></label></td>
		<td><?php _e( 'Table Cell #2', 'profile-cct-td' ); ?></td>
		</tr>
		<tr class="alternate">
		<td class="row-title"><label for="tablecell"><?php _e( 'Table Cell #3, with label and class', 'profile-cct-td' ); ?> <code>alternate</code></label></td>
		<td><?php _e( 'Table Cell #4', 'profile-cct-td' ); ?></td>
		</tr>
		<tr>
		<td class="row-title"><?php _e( 'Table Cell #5, without label', 'profile-cct-td' ); ?></td>
		<td><?php _e( 'Table Cell #6', 'profile-cct-td' ); ?></td>
		</tr>
		</tbody>
		<tfoot>
		<tr>
		<th class="row-title"><?php _e( 'Table header cell #1', 'profile-cct-td' ); ?></th>
		<th><?php _e( 'Table header cell #2', 'profile-cct-td' ); ?></th>
		</tr>
		</tfoot>
		</table>
		<p><a class="alignright button" href="javascript:void(0);" onclick="window.scrollTo(0,0);" style="margin:3px 0 0 30px;"><?php _e('scroll to top', 'profile-cct-td' ); ?></a><br class="clear" /></p>
		<code>&lt;hr /&gt;</code>
		<hr id="admin_notices" />
		<h3><?php _e( 'Admin Notices', 'profile-cct-td' ); ?></h3>
		<?php _e( 'define the style via param (same as the classes) on function <code>add_settings_error()</code> or use the class inside a div', 'profile-cct-td' ); ?>
		<div style="width:99%; padding: 5px;" class="updated" ><p><?php _e( 'class .updated with paragraph', 'profile-cct-td' ); ?></p></div>
		<div style="width:99%; padding: 5px;" class="error"><?php _e( 'class .alternate without paragraph', 'profile-cct-td' ); ?></div>
		<div style="width:99%; padding: 5px;" class="settings-error"><?php _e( 'class .settings-error without paragraph', 'profile-cct-td' ); ?></div>
		<p><a class="alignright button" href="javascript:void(0);" onclick="window.scrollTo(0,0);" style="margin:3px 0 0 30px;"><?php _e('scroll to top', 'profile-cct-td' ); ?></a><br class="clear" /></p>
		<code>&lt;hr /&gt;</code>
		<hr id="alternative_colours" />
		<h3><?php _e( 'Alternative Colours', 'profile-cct-td' ); ?></h3>
		<div style="width:99%; padding: 5px;" ><?php _e( 'without class', 'profile-cct-td' ); ?></div>
		<div style="width:99%; padding: 5px;" class="alternate">.alternate</div>
		<div style="width:99%; padding: 5px;" class="form-invalid">.form-invalid</div>
		<p><a class="alignright button" href="javascript:void(0);" onclick="window.scrollTo(0,0);" style="margin:3px 0 0 30px;"><?php _e('scroll to top', 'profile-cct-td' ); ?></a><br class="clear" /></p>
		<code>&lt;hr /&gt;</code>
		<hr id="pagination" />
		<h3><?php _e( 'Pagination', 'profile-cct-td' ); ?></h3>
		<pre><code>&lt;div class="tablenav"&gt;
		&lt;div class="tablenav-pages"&gt;
		//<?php _e( 'here is your pagination code', 'profile-cct-td' ); ?>
		&lt;/div&gt;
		&lt;/div&gt;</code> </pre>
		<div class="tablenav">
		<div class="tablenav-pages">
		<span class="displaying-num"><?php _e( 'Example Markup for n items', 'profile-cct-td' ); ?></span>
		<a class='first-page disabled' title='Go to the first page' href='http://bueltge.de/photos/wp-admin/edit.php'>&laquo;</a>
		<a class='prev-page disabled' title='Go to the previous page' href='http://bueltge.de/photos/wp-admin/edit.php?paged=1'>&lsaquo;</a>
		<span class="paging-input"><input class='current-page' title='Current page' type='text' name='paged' value='1' size='1' /> of <span class='total-pages'>5</span></span>
		<a class='next-page' title='Go to the next page' href='http://bueltge.de/photos/wp-admin/edit.php?paged=2'>&rsaquo;</a>
		<a class='last-page' title='Go to the last page' href='http://bueltge.de/photos/wp-admin/edit.php?paged=5'>&raquo;</a>
		</div>
		</div>
		<p><a class="alignright button" href="javascript:void(0);" onclick="window.scrollTo(0,0);" style="margin:3px 0 0 30px;"><?php _e('scroll to top', 'profile-cct-td' ); ?></a><br class="clear" /></p>
		<code>&lt;hr /&gt;</code>
		<hr id="form_elements" />
		<h3><?php _e( 'Form Elements', 'profile-cct-td' ); ?></h3>
		<form method="post" action="options.php">
		<table class="form-table">
		<tr valign="top">
		<td colspan="2">
		<code>&lt;input name="" id="" type="text" value="" class="regular-text" /&gt;</code>
		</td>
		</tr>
		<tr valign="top">
		<th scope="row">Input type text <code>.regular-text</code></label></th>
		<td>
		<input type="text" value="input type=&quot;text&quot; class=&quot;regular-text&quot;" class="regular-text" />
		</td>
		</tr>
		<tr valign="top">
		<th scope="row">Input type text <code>.small-text</code></label></th>
		<td>
		<input type="text" value="small-text" class="small-text" />
		</td>
		</tr>
		<tr valign="top">
		<th scope="row">Input type text <code>.large-text</code></label></th>
		<td>
		<input type="text" value="large-text" class="large-text" />
		</td>
		</tr>
		<tr valign="top">
		<th scope="row">Input type text <code>.all-options</code></label></th>
		<td>
		<input type="text" value="all-options" class="all-options" />
		</td>
		</tr>
		<tr valign="top">
		<td colspan="2">
		<code>&lt;input name="" id="" type="text" value="" class="regular-text" /&gt;</code>
		<br /><code>&lt;span class="description"&gt;...&lt;/span&gt;</code>
		</td>
		</tr>
		<tr valign="top">
		<th scope="row">Input type text <code>.regular-text</code> <?php _e( 'with description', 'profile-cct-td' ); ?></th>
		<td>
		<input type="text" value="Example string" class="regular-text" />
		<span class="description"><?php _e( 'Here is the description for an form element', 'profile-cct-td' ); ?></span>
		</td>
		</tr>
		<tr valign="top">
		<td colspan="2">
		<code>&lt;input name="" id="" type="text" value="" class="regular-text code" /&gt;</code>
		<br /><code>&lt;span class="description"&gt;...&lt;/span&gt;</code>
		</td>
		</tr>
		<tr valign="top">
		<th scope="row">Input type text <code>.regular-text code</code></th>
		<td>
		<input type="text" value="Example string for code" class="regular-text code" />
		</td>
		</tr>
		<tr valign="top">
		<td colspan="2">
		<pre><code>&lt;fieldset&gt;
		&lt;legend class=&quot;screen-reader-text&quot;&gt;&lt;span&gt;Fieldset Example&lt;/span&gt;&lt;/legend&gt;
		&lt;label for=&quot;users_can_register&quot;&gt;
		&lt;input name=&quot;users_can_register&quot; type=&quot;checkbox&quot; id=&quot;users_can_register&quot; value=&quot;1&quot; /&gt;
		&lt;/label&gt;
		&lt;/fieldset&gt;
		</code></pre>
		</td>
		</tr>
		<tr valign="top">
		<th scope="row">Input type checkbox</th>
		<td>
		<fieldset>
		<legend class="screen-reader-text"><span>Fieldset Example</span></legend>
		<label for="users_can_register">
		<input name="" type="checkbox" id="" value="1" />
		</label>
		</fieldset>
		</td>
		</tr>
		<tr valign="top">
		<td colspan="2">
		<pre><code>&lt;select name=&quot;&quot; id=&quot;&quot;&gt;
		&lt;option selected=&quot;selected&quot; value=&quot;&quot;&gt;Example option&lt;/option&gt;
		&lt;option value=&quot;&quot;&gt;foo&lt;/option&gt;
		&lt;/select&gt;</code></pre>
		</td>
		</tr>
		<tr valign="top">
		<th scope="row"><label for="">Select list</label></th>
		<td>
		<select name="" id="">
		<option selected="selected" value="">Example option</option>
		<option value="">foo</option>
		</select>
		</td>
		</tr>
		<tr valign="top">
		<td colspan="2">
		<pre><code>&lt;fieldset&gt;
		&lt;legend class=&quot;screen-reader-text&quot;&gt;&lt;span&gt;input type=&quot;radio&quot;&lt;/span&gt;&lt;/legend&gt;
		&lt;label title='g:i a'&gt;&lt;input type=&quot;radio&quot; name=&quot;example&quot; value=&quot;&quot; /&gt; &lt;span&gt;description&lt;/span&gt;&lt;/label&gt;&lt;br /&gt;
		&lt;label title='g:i a'&gt;&lt;input type=&quot;radio&quot; name=&quot;example&quot; value=&quot;&quot; /&gt; &lt;span&gt;description #2&lt;/span&gt;&lt;/label&gt;
		&lt;/fieldset&gt;</code></pre>
		</td>
		</tr>
		<tr>
		<th scope="row">Input type radio</th>
		<td>
		<fieldset><legend class="screen-reader-text"><span>input type="radio"</span></legend>
		<label title='g:i a'><input type="radio" name="example" value="" /> <span>description</span></label><br />
		<label title='g:i a'><input type="radio" name="example" value="" /> <span>description #2</span></label>
		</fieldset>
		</td>
		</tr>
		<tr valign="top">
		<td colspan="2">
		<pre><code>&lt;textarea id=&quot;&quot; name=&quot;&quot; cols=&quot;80&quot; rows=&quot;10&quot; class=&quot;large-text&quot;&gt;.large-text&lt;/textarea&gt;</code></pre>
		</td>
		</tr>
		<tr valign="top">
		<th scope="row"><label for="">Textarea</label></th>
		<td>
		<textarea id="" name="" cols="80" rows="10">without class</textarea>
		</td>
		</tr>
		<tr valign="top">
		<th scope="row"><label for="">Textarea <code>.large-text</code></label></th>
		<td>
		<textarea id="" name="" cols="80" rows="10" class="large-text">.large-text</textarea>
		</td>
		</tr>
		<tr valign="top">
		<th scope="row"><label for="">Textarea <code>.all-options</code></label></th>
		<td>
		<textarea id="" name="" cols="80" rows="10" class="all-options">.all-options</textarea>
		</td>
		</tr>
		</table>
		</form>
		<p><a class="alignright button" href="javascript:void(0);" onclick="window.scrollTo(0,0);" style="margin:3px 0 0 30px;"><?php _e('scroll to top', 'profile-cct-td' ); ?></a><br class="clear" /></p>
		</div>
