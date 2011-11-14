<?php 

function profile_cct_publications_field_shell($action, $options ) {
	
	if( is_object($action) ):
		$post = $action;
		$action = "display";
		$data = $options['args']['data'];
		$options = $options['args']['options'];
	endif;

	$field = Profile_CCT::get_object(); // prints "Creating new instance."
	
	
	$default_options = array(
		'type' => 'publications',
		'label'=>'publications',
		'description'=>'',
		);
	$options = (is_array($options) ? array_merge( $default_options, $options ): $default_options );
	
	$field->start_field($action,$options);
	
	profile_cct_publications_field($data,$options);
	
	$field->end_field( $action, $options );
	
}
function profile_cct_publications_field( $data, $options ){
	extract( $options );
	$field = Profile_CCT::get_object();

	$field->input_field( array( 'field_type'=>$type, 'multiple'=>$multiple,'field_id'=>'publication','label'=>'', 'size'=>25, 'row'=>2, 'cols'=>20, 'value'=>$data['publication'], 'type'=>'textarea') );
}



function profile_cct_publications_display_shell($action, $options ) {
	
	if( is_object($action) ):
		$post = $action;
		$action = "display";
		$data = $options['args']['data'];
		$options = $options['args']['options'];
	endif;

	$field = Profile_CCT::get_object(); // prints "Creating new instance."
	
	
	$default_options = array(
		'type' => 'publications',
		'label'=>'publications',
		'description'=>'',
		'hide_label'=>true,
		'before'=>'',
		'after'=>''
		);
	$options = (is_array($options) ? array_merge( $default_options, $options ): $default_options );
	
	
	$field->start_field($action,$options);
	
	profile_cct_publications_display($data,$options);
	
	
	$field->end_field( $action, $options );
	
}
function profile_cct_publications_display( $data, $options ){
	extract( $options );
	$field = Profile_CCT::get_object();

?><div class=""><p><strong>publications</strong> Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nunc in velit ac sem dapibus cursus. Donec faucibus adipiscing ipsum ut auctor. Integer quis metus iaculis lacus vulputate facilisis. Fusce malesuada volutpat sapien eu commodo. Integer sed magna orci, quis commodo elit. In convallis fringilla mollis. Pellentesque dapibus mi quis nunc pulvinar lobortis. Sed ut purus auctor ligula aliquam egestas eu at sem. Sed eget nisl urna. Etiam vitae leo id erat porttitor iaculis et et lorem. Curabitur condimentum libero eget sapien dictum congue. In hac habitasse platea dictumst. In in nulla et elit vehicula tempor. Donec sem arcu, viverra quis dignissim ac, adipiscing sed nunc.</p>

<p>Quisque malesuada tellus vitae massa semper non faucibus leo sollicitudin. In sit amet feugiat ligula. Ut id ultrices magna. Proin ut imperdiet tellus. Nulla interdum eleifend massa egestas malesuada. Suspendisse potenti. Nulla suscipit imperdiet velit sit amet pretium. In sit amet lectus felis, commodo varius eros. Duis sapien diam, sagittis faucibus elementum vulputate, faucibus a mi. Class aptent taciti sociosqu ad litora torquent per conubia nostra, per inceptos himenaeos. Donec viverra, quam in pretium volutpat, elit sapien tempor neque, quis adipiscing magna quam vitae velit.</p> </div>
<?php
}

