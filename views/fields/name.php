<?php
/**
 *
 */
Class Profile_CCT_Name extends Profile_CCT_Field {
    /**
     * default_options
     * 
     * @var mixed
     * @access public
     */
    var $default_options = array(
		'type'         => 'name',
		'label'        => 'name',
		'description'  => '',
		'show'         => array( 'salutations', 'middle', 'credentials' ),
		'show_fields'  => array( 'salutations', 'middle', 'credentials' ),
		'width'        => 'two-third',
		'link_to'      => true,
		'show_link_to' => true,
		'before'       => '',
		'empty'        => '',
		'after'        => '',
    );
	
    /**
     * field function.
     * 
     * @access public
     * @return void
     */
    function field() {
		$this->input_text( array(
			'field_id'  => 'salutations',
			'label'     => 'Salutations',
			'size'      => 2,
		) );
		$this->input_text( array(
			'field_id' => 'first',
			'label'    => 'First',
			'size'     => 13,
		) );
		$this->input_text( array(
			'field_id' => 'middle',
			'label'    => 'Middle',
			'size'     => 3,
		) );
		$this->input_text( array(
			'field_id' => 'last',
			'label'    => 'Last',
			'size'     => 17,
		) );
		$this->input_text( array(
			'field_id' => 'credentials',
			'label'    => 'Credentials',
			'size'     => 7,
		) );
    }
	
    /**
     * display function.
     * 
     * @access public
     * @return void
     */
    function display() {
		$this->display_shell( array( 'class' => 'fn n', 'tag' => 'h2' ) );
		
		$this->display_text( array(
			'field_id'     => 'salutations',
			'class'        => 'honorific-prefix salutations',
			'default_text' => 'Mr',
		) );
		$this->display_text( array(
			'field_id'     => 'first',
			'class'        => 'given-name',
			'separator'    => ' ',
			'default_text' => 'Bruce',
		) );
		$this->display_text( array(
			'field_id'     => 'middle',
			'class'        => 'additional-name middle',
			'separator'    => ' ',
			'default_text' => 'Anthony',
		) );
		$this->display_text( array(
			'field_id'     => 'last',
			'class'        => 'family-name',
			'separator'    => ' ',
			'default_text' => 'Wayne',
		) );
		$this->display_text( array(
			'field_id'     => 'credentials',
			'class'        => 'honorific-suffix suffix credentials',
			'separator'    => ', ',
			'default_text' => 'BCom',
		) );
		
		$this->display_end_shell( array( 'tag' => 'h2' ) );
    }

    public static function shell( $options, $data ) {
		new Profile_CCT_Name( $options, $data );
    }
}

function profile_cct_name_shell( $options, $data ) {
    Profile_CCT_Name::shell( $options, $data );
}

/*
function profile_cct_name_display_shell( $options, $data ) {

    Profile_CCT_Name::shell( $options, $data );
}

/**
 * profile_cct_name_shell function.
 *
 * @access public
 * @param mixed $action
 * @param mixed $options. (default: null)
 * @return void

function profile_cct_name_shell_old( $action, $options = null ) {


    if ( is_object( $action ) ):
	$post	 = $action;
	$action	 = "display";
	$data	 = $options['args']['data'];
	$options = $options['args']['options'];

    endif;

    $field		 = Profile_CCT::get_object(); // prints "Creating new instance."
    $default_options = array(
	'type' => 'name',
	'label' => 'name',
	'description' => '',
	'show' => array( 'salutations', 'middle', 'credentials' ),
	'show_fields' => array( 'salutations', 'middle', 'credentials' )
    );

    $options = (is_array( $options ) ? array_merge( $default_options, $options ) : $default_options );

    $field->start_field( $action, $options );

    profile_cct_name_field( $data, $options );

    $field->end_field( $action, $options );
}

/**
 * profile_cct_name_field function.
 *
 * @access public
 * @param mixed $data
 * @param mixed $options
 * @return void

function profile_cct_name_field_old( $data, $options ) {

    extract( $options );

    $field = Profile_CCT::get_object();

    $show = (is_array( $show ) ? $show : array( ));
    $field->input_field( array( 'field_type' => $type, 'field_id' => 'salutations', 'label' => 'Salutations', 'size' => 2, 'value' => $data['salutations'], 'type' => 'text', 'show' => in_array( "salutations", $show ) ) );
    $field->input_field( array( 'field_type' => $type, 'field_id' => 'first', 'label' => 'First', 'size' => 13, 'value' => $data['first'], 'type' => 'text' ) );
    $field->input_field( array( 'field_type' => $type, 'field_id' => 'middle', 'label' => 'Middle', 'size' => 3, 'value' => $data['middle'], 'type' => 'text', 'show' => in_array( "middle", $show ) ) );
    $field->input_field( array( 'field_type' => $type, 'field_id' => 'last', 'label' => 'Last', 'size' => 17, 'value' => $data['last'], 'type' => 'text' ) );
    $field->input_field( array( 'field_type' => $type, 'field_id' => 'credentials', 'label' => 'Credentials', 'size' => 7, 'value' => $data['credentials'], 'type' => 'text', 'show' => in_array( "credentials", $show ) ) );
}

function profile_cct_name_display_shell_old( $action, $options = null, $data ) {

    if ( is_object( $action ) ):
	$post	 = $action;
	$action	 = "display";
	$data	 = $options['args']['data'];
	$options = $options['args']['options'];
    endif;

    $field = Profile_CCT::get_object(); // prints "Creating new instance."

    $default_options = array(
	'type' => 'name',
	'label' => 'name',
	'width' => 'two-third',
	'link_to' => true,
	'show_link_to' => true,
	'hide_label' => true,
	'before' => '',
	'empty' => '',
	'after' => '',
	'show' => array( 'salutations', 'middle', 'credentials' ),
	'show_fields' => array( 'salutations', 'middle', 'credentials' )
    );

    $options = (is_array( $options ) ? array_merge( $default_options, $options ) : $default_options );

    $field->start_field( $action, $options );

    profile_cct_name_display( $data, $options );

    $field->end_field( $action, $options );
}

function profile_cct_name_display_old( $data, $options ) {

    global $post;

    extract( $options );

    $field = Profile_CCT::get_object();

    $show = (is_array( $show ) ? $show : array( ));


    $href = ( isset( $post ) ? get_permalink() : "#" );

    $field->display_text( array( 'field_type' => $type, 'class' => 'fn n', 'type' => 'shell', 'tag' => 'h2', 'link_to' => $link_to, 'href' => $href ) );
    $field->display_text( array( 'field_type' => $type, 'class' => 'honorific-prefix salutations', 'default_text' => 'Mr', 'value' => $data['salutations'], 'type' => 'text', 'show' => in_array( "salutations", $show ) ) );
    $field->display_text( array( 'field_type' => $type, 'class' => 'given-name', 'default_text' => 'Bruce', 'value' => $data['first'], 'type' => 'text' ) );
    $field->display_text( array( 'field_type' => $type, 'class' => 'additional-name middle', 'default_text' => 'Anthony', 'value' => $data['middle'], 'type' => 'text', 'show' => in_array( "middle", $show ) ) );
    $field->display_text( array( 'field_type' => $type, 'class' => 'family-name', 'default_text' => 'Wayne', 'value' => $data['last'], 'type' => 'text' ) );
    $field->display_text( array( 'field_type' => $type, 'class' => 'honorific-suffix suffix credentials', 'separator' => ',', 'default_text' => 'BCom', 'value' => $data['credentials'], 'type' => 'text', 'show' => in_array( "credentials", $show ) ) );
    $field->display_text( array( 'field_type' => $type, 'type' => 'end_shell', 'tag' => 'h2', 'link_to' => $link_to ) );
}
*/