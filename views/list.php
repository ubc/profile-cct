
<div id="col-container">
	<div><span id="notify">notify spinner here when updating the form</span></div>
	
	<div id="col-right">
		<div class="col-wrap">
		<p><strong>Fields currently in the form</strong></p>
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
		<strong>list</strong>
		<ul id="static-fields" class="sort connectedSortable dropzone"><li>hey</li></ul>
		</div><!-- /col-left -->
	
	
	</div>
</div>