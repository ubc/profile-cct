<div id="col-container">
	<div id="notify">
		<span class="update"><img src="http://localhost.localdomain/t/wp-admin/images/wpspin_light.gif" alt="spinner" /> notify spinner here when updating the form</span>
	</div>
	
	
	<div id="col-right">
		<div class="col-wrap">
		<p><strong>Fields currently available</strong></p>
		<ul id="profile-fields" class="sort connectedSortable">
			<li id="cct-name">Name</li>
			<li id="cct-picture">Picture</li>
			<?php 
			foreach($this->form_fields["fields"] as $tab):
				
				foreach($tab as $field): ?>
					<li id="<?php echo $field['type']; ?>"><?php echo $field['label']; ?></li>
				<?php
				endforeach;
			endforeach; ?>
		</ul>
		</div>
	</div><!-- /col-right -->
	<div id="col-left">
		<div class="col-wrap">
		<h3>Preview</h3>
		<strong>Header</strong>
		<ul id="static-fields" class="sort connectedSortable dropzone"></ul>
		<strong>Tabs</strong>
		<?php 
		$this->show_tabs('page','edit');
		?>
	
		</div>
	</div><!-- /col-left -->
	
	
	</div>
</div>