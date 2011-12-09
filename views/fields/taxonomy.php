<?php 

add_action('profile_cct_admin_pages', 'profile_cct_add_taxonomy_fields_filter', 10, 1);

add_action('profile_cct_page', 'profile_cct_add_taxonomy_fields_filter', 5);

function profile_cct_add_taxonomy_fields_filter($type_of= null){
	
	// for now only on pages and lists
	if(in_array($type_of, array('page','list')) )
		add_filter( 'profile_cct_dynamic_fields', 'profile_cct_add_taxonomy_fields' );
	
	$profile_cct = Profile_CCT::get_object(); // prints "Creating new instance."
	
	if( is_array($profile_cct->taxonomies) ):
		foreach($profile_cct->taxonomies as $taxonomy):
			$sanitized_single = str_replace( '-','_', sanitize_title($taxonomy['single']));
			
			add_action('profile_cct_display_shell_profile_cct_'.$sanitized_single, 'profile_cct_taxonomy_display_shell',10, 3);
		endforeach;
	endif;
}

function profile_cct_add_taxonomy_fields( $fields ){
	$profile_cct = Profile_CCT::get_object();
	
	if( is_array($profile_cct->taxonomies) ):
		foreach($profile_cct->taxonomies as $taxonomy):
		
			// add it to the fields 
			// also add the action to display the stuff 
			$sanitized_single = str_replace( '-','_', sanitize_title($taxonomy['single']));
			$fields[] = array( "type"=> 'profile_cct_'.$sanitized_single, "label"=> $taxonomy['plural']);
		endforeach;
	endif;
	return $fields;

}
	

function profile_cct_taxonomy_display_shell(  $action, $options, $data=null ) {
	
	if( is_object($action) ):
		$post = $action;
		$action = "display";
		$data = $options['args']['data'];
		$options = $options['args']['options'];
	endif;
	
	
	
	$field = Profile_CCT::get_object(); // prints "Creating new instance."
	
	$default_options = array(
		'width' => 'full',
		'before'=>'',
		'empty'=>'',
		'after' =>'',
		'text'	=>$options['label'].":",
		'hide_label'=>true
		);
	
	$options = (is_array($options) ? array_merge( $default_options, $options ): $default_options );
	
	$field->start_field($action,$options);
	if( $field->is_data_array( $data ) ):
		
		foreach($data as $item_data):
			profile_cct_taxonomy_display($item_data,$options);
		endforeach;
		
	else:
		profile_cct_taxonomy_display($item_data,$options);
	endif;
	
	$field->end_field( $action, $options );
	
}
function profile_cct_taxonomy_display( $data, $options ){
	global $post;
	extract( $options );
	$show = (is_array($show) ? $show : array());
	$field = Profile_CCT::get_object();
	
	$taxonomy = $type;
	$field->display_text( array( 'field_type'=>$taxonomy, 'class'=>'taxonomy', 'type'=>'shell', 'tag'=>'p') );
	$field->display_text( array( 'field_type'=>$type, 'default_text'=>$label, 'value'=>$text." ", 'type'=>'text', 'tag'=>'span', 'class'=>'text-input', 'title'=>$label.":") );
	if( is_object( $post ) ):
		
		$terms =  get_the_terms( $post->ID, $taxonomy );
		
		if(is_array($terms)):
			foreach ( $terms as $term ):
		        $link = get_term_link( $term, $taxonomy );
		        if ( is_wp_error( $link ) )
		        	return $link;
		        $term_links[] = '<a href="' . $link . '" rel="tag">' . $term->name . '</a>';
			endforeach;
	
		    $term_links = apply_filters( "term_links-$taxonomy", $term_links );
	    
			echo join( ", ", $term_links );	
	   	endif;
	else:
		$single = str_replace("profile_cct_","",$type);	
		
		$field->display_text( array( 'field_type'=>$type,  'class'=>'tag',  'href'=>'#', 'default_text'=>$single.' 1', 'value'=>'', 'type'=>'text', 'tag'=>'a') );
		$field->display_text( array( 'field_type'=>$type,  'class'=>'tag', 'separator'=>', ', 'href'=>'#', 'default_text'=>$single.' 2', 'value'=>'', 'type'=>'text', 'tag'=>'a') );
		$field->display_text( array( 'field_type'=>$type,  'class'=>'tag', 'separator'=>', ', 'href'=>'#', 'default_text'=>$single.' 3', 'value'=>'', 'type'=>'text', 'tag'=>'a') );
		
	endif;
	$field->display_text( array( 'field_type'=>$type, 'type'=>'end_shell', 'tag'=>'p') );

}