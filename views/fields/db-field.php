<?php 
class Profile_CCT_DB_Field {
	function init() {
		add_filter( 'profile_cct_dynamic_fields', array( __CLASS__, 'add_custom_fields' ) );
		
		$profile = Profile_CCT::get_object();
		
		foreach ( $profile->settings['clone_fields'] as $field_key => $field ):
			if ( ! is_numeric($field_key) ):
				add_action( 'profile_cct_shell_'.$field['type'], 'profile_cct_'.$field['field_clone'].'_shell', 10, 3 );
			else:
				//This removes old and invalid fields that are left over from previous versions of the plugin.
				unset($profile_cct->settings['clone_fields'][$field_key]);
			endif;
		endforeach;
		
		foreach( $profile->settings['clone_fields'] as $field ):
			add_action( 'profile_cct_'.$field['type'].'_add_meta_box', array( __CLASS__, 'add_db_meta_box' ), 10, 4 );
		endforeach;
	}
	
	/**
	 * profile_cct_add_db_fields function.
	 * 
	 * @access public
	 * @param mixed $fields
	 * @return void
	 */
	function add_custom_fields( $fields ) {
		$profile_cct = Profile_CCT::get_object();
		
		foreach ( $profile_cct->settings['clone_fields'] as $field_key => $field_data ):
			stripslashes_deep($field_data);
			if ( ! is_numeric($field_key) ):
				$fields[] = array(
					"type"  => $field_data['type'],
					"label" => $field_data['label'],
				);
			else:
				//This removes old and invalid fields that are left over from previous versions of the plugin.
				// unset($profile_cct->settings['clone_fields'][$field_key]);
				
				$fields[] = array( "type"=> $field_data['type'], "label"=> $field_data['label']);
				
			endif;
		endforeach;
		
		return $fields;
	}
	
	/**
	 * profile_cct_db_add_meta_box function.
	 * 
	 * @access public
	 * @param mixed $field
	 * @param mixed $context
	 * @param mixed $data
	 * @param mixed $i
	 * @return void
	 */
	function add_db_meta_box( $field, $context, $data, $i ) {
		$profile = Profile_CCT::get_object();
		
		if ( isset( $profile->settings['clone_fields'][$field['type']] ) ):
			$custom_field = $profile->settings['clone_fields'][$field['type']];
			
			$id = $field['type']."-".$i.'-'.rand( 0, 999 );
			$type = $field['label'];
			$callback = 'profile_cct_'.$custom_field['field_clone'].'_shell';
			$post_type = 'profile_cct';
			$priority = 'core';
			$callback_args = array(
				'options' => $field,
				'data'    => $data
			);
			
			add_meta_box( $id, $type, $callback, $post_type, $context, $priority, $callback_args );
		endif;
	}
}

if ( is_array( Profile_CCT::get_object()->settings['clone_fields'] ) ):
	Profile_CCT_DB_Field::init();
endif;