<?php
/**
 * Profile_CCT_Field class.
 */
class Profile_CCT_Field {
	// options
	var $type;          // this is the unique field
	var $label;         // the label is the main label of the field
 	var $description;   // the descript that is displayed to the user when they are entering it
	var $show;          // all the once that are currently shown
	var $show_fields;   // all the possible field that you can toggle
	var $show_link_to;  // enable the functionality
	var $link_to;       // do we offer a link to from this field
	var $class;         // the class of the field
	var $hide_label;    // hide the label if you don't want to display it
	var $width;         // the width of the elemenet in different sizes
	var $text;          // the replacment text for fields like permalink (read more) the text that is suppoed to be there also for  taxonomies
	var $before;        // html to show before the field
	var $after;         // html to show after the field
	var $empty;         // the info to show when the field is empty
	var $url_prefix;    // used by the data field to enable a prefix
	var $show_multiple; // does the field have the option to be replicated
	var $multiple;      // if the field should be replicated
	var $page;

	// action
	var $action;        // are we currently creating the form or displaying it
	var $options;       // save all the options again
	var $field_counter    = 0;
	var $subfield_counter = 0;
	
	// data
	var $data;
	
	function __construct ( $options_or_post, $data ) {
		if ( self::is_post( $options_or_post ) ): 
			//This means the parameters were passed from do_meta_boxes, and are not correctly configured.
			$options = $data['args']['options'];
			$data = $data['args']['data'];
		else:	
			$options = $options_or_post;
		endif;
		
		$this->options       = ( is_array( $options ) ? array_merge( $this->default_options, $options ): $this->default_options );
		$this->options       = stripslashes_deep($this->options); 
		$this->action        = ( isset( Profile_CCT_Admin::$action ) ? Profile_CCT_Admin::$action : 'edit' );
		$this->page          = ( isset( $this->options['page'] ) ? $this->options['page'] : ( isset( Profile_CCT_Admin::$page ) ? Profile_CCT_Admin::$page : false ) );
		$this->type          = ( isset( $this->options['type'] ) ? $this->options['type'] : null );
		$this->label         = ( isset( Profile_CCT_Admin::$current_form_fields[$this->type]['label'] ) ? Profile_CCT_Admin::$current_form_fields[$this->type]['label'] : ( isset( $this->options['label'] ) ? $this->options['label'] : false ) );
		$this->description   = ( isset( $this->options['description'] ) ? $this->options['description'] : null );
		$this->show_link_to  = ( isset( $this->options['show_link_to'] ) ? $this->options['show_link_to'] : false );
		$this->link_to       = ( isset( $this->options['link_to'] ) && $this->options['link_to']  ? true : false );
		$this->show          = ( is_array( $this->options['show'] ) ? $this->options['show'] : array() ) ;
		$this->show_fields   = ( is_array( $this->options['show_fields'] ) ? $this->options['show_fields'] : array() ) ;
		$this->class         = ( isset( $this->options['class'] ) ? $this->options['class'] : "" );
		$this->hide_label    = ( isset( $this->options['hide_label'] ) && $this->options['hide_label'] ? true: false );
		$this->width         = ( isset( $this->options['width'] ) ? $this->options['width'] : false );
		$this->width         = ( 'form' == $this->page ? 'full' : $this->width );
		$this->text          = ( isset( $this->options['text'] ) ? $this->options['text'] : false );
		$this->before        = ( isset( $this->options['before'] ) ? $this->options['before'] : false );
		$this->after         = ( isset( $this->options['after'] ) ? $this->options['after'] : false );
		$this->empty         = ( isset( $this->options['empty'] ) ? $this->options['empty'] : false );
		$this->url_prefix    = ( isset( $this->options['url_prefix'] ) ? $this->options['url_prefix'] : false );
		$this->show_multiple = ( isset( $this->options['show_multiple'] ) ? $this->options['show_multiple'] : false );
		$this->multiple      = ( isset( $this->options['multiple'] ) ? $this->options['multiple'] : false );
		$this->data          = $data;
		
		
		ob_start();
		$this->start_field();
		$echo_start_field = ob_get_contents();
		ob_end_clean();
		
		
		ob_start();
		
		if ( $this->show_multiple && isset( $data ) ):
			$first = true;
			
			foreach ( $data as $singular_data ):
				
				if( !is_array( $singular_data ) ):
					$this->multiple = false;
					$this->data = $data;
				else:
					$this->data = $singular_data;
				endif;
				
				$this->create_subfield( ! $first && ! in_array( $this->page, array('page', 'list') ) ); 
				$this->subfield_counter++;
				if ( $first ):
					if ( $this->multiple == false ) break;
					$first = false;
				endif;
			endforeach;
		else:
			$this->create_subfield();
		endif;
		
		$echo_field_content = ob_get_contents();
		
		ob_end_clean();
		
		if( !empty( $echo_field_content ) ):
			echo $echo_start_field;
			echo $echo_field_content;
			$this->end_field();
		endif;
	}
	
	/**
	 * create_subfield function.
	 * 
	 * @access public
	 * @param bool $enable_remove (default: false)
	 * @return void
	 */
	function create_subfield( $enable_remove = false ) {
			$start_div = '<div class="field" data-count="'.$this->subfield_counter.'">';
			$end_div = "</div>";
			if ( 'form' == $this->page || false == $this->page ):
				echo $start_div;
					$this->field();
					
					if ( $enable_remove ):	?>
						<a href="#" class="remove-fields button">Remove</a>
						<?php
					endif;
					
				echo $end_div;
				
			else:
				
				ob_start();
				$this->display();
				$contents = ob_get_contents();
				ob_end_clean();
				
				if ( trim( strip_tags( $contents, '<img>' ) ) == '' || empty( $contents ) ):
					if( !empty($this->empty)):
						echo $start_div;
						echo $this->empty;
						echo $end_div;
					endif;
				else:
					echo $start_div;
					if ( isset( $this->shell ) ) $this->display_shell( $this->shell );
					echo $contents;
					if ( isset( $this->shell ) ) $this->display_end_shell( $this->shell );
					
					echo $end_div;
				endif;
				
			endif;
			
	}
	
	public static function is_post( $options_or_post ){
		return ( isset( $options_or_post->ID ) && is_numeric( $options_or_post->ID ) );
	}
	
	/**
	 * start_field function.
	 * 
	 * @access public
	 * @return void
	 */
	function start_field() {
		// Lets display the start of the field to the user
		if ( 'edit' == $this->action ): ?>
			<?php
				$shell_type = 'shell-'.esc_attr( $this->type );
				$is_active = ( ( isset( Profile_CCT_Admin::$current_form_fields ) && Profile_CCT_Admin::$current_form_fields[$this->type]['is_active'] == 1 ) ? "is-active" : "" );
			?>
	 		<li class="field-item <?php echo $shell_type." ".$this->width." ".$this->class." ".$is_active; ?>" for="cct-<?php echo esc_attr( $this->type ); ?>" data-options="<?php echo esc_attr( $this->serialize( $this->options ) ); ?>" >
				<div class="action-shell">
					<a href="#" class="action arrow" onclick="Profile_CCT_FORM.moveUp(jQuery(this)); return false;"><img height=16 src="<?php echo PROFILE_CCT_DIR_URL."/img/arrow-up.png"; ?>" /></a>
					<a href="#" class="action arrow" onclick="Profile_CCT_FORM.moveDown(jQuery(this)); return false;"><img height=16 src="<?php echo PROFILE_CCT_DIR_URL."/img/arrow-down.png"; ?>" /></a>
					<a href="#edit-field" class="action edit">Edit</a>
				</div>
				<div class="edit-shell" style="display:none;">
					<input type="hidden" name="type" value="<?php echo esc_attr( $this->type ); ?>" />
					<?php
					if ( 'form' == $this->page ):
						$this->input_text( array(
							'name'         => 'label',
							'class'        => 'field-label',
							'label'        => 'label',
							'before_label' => true,
							'value'        => $this->label,
						) );
					else:
						$this->input_hidden( array(
							'name'         => 'label',
							'value'        => $this->label,
						) );
					endif;
					
					if ( isset( $this->description ) && 'form' == $this->page):
						$this->input_textarea( array(
							'name'         => 'description',
							'size'         => 10,
							'class'        => 'field-description',
							'label'        => 'description',
							'before_label' => true,
							'value'        => $this->description,
						) );
					endif;
					
					if ( $this->width && 'form' != $this->page ):
						$this->input_select( array(
							'name'         => 'width',
							'class'        => 'field-width',
							'label'        => 'select width',
							'before_label' => true,
							'all_fields'   => array( 'full', 'half', 'one-third', 'two-third' ),
							'value'        => $this->width,
						) );
					endif;
					
					if ( 'form' != $this->page ):
						$this->input_text( array(
							'name'         => 'text',
							'size'         => 30,
							'class'        => 'field-text',
							'label'        => 'label',
							'before_label' => true,
							'value'        => $this->text,
						) );
					endif;
					
					if ( isset( $this->before ) && 'form' != $this->page ):
						$this->input_textarea( array(
							'name'         => 'before',
							'size'         => 10,
							'class'        => 'field-textarea',
							'label'        => 'before html',
							'before_label' => true,
							'value'        => $this->before,
						) );
					endif;
					
					if ( isset( $this->after ) && 'form' != $this->page ):
						$this->input_textarea( array(
							'name'         => 'after',
							'size'         => 10,
							'class'        => 'field-textarea',
							'label'        => 'after html',
							'before_label' => true,
							'value'        => $this->after,
						) );
					endif;
					
					if ( isset( $this->empty ) && 'form' != $this->page ):
						$this->input_textarea( array(
							'name'         => 'empty',
							'size'         => 10,
							'class'        => 'field-textarea',
							'label'        => 'content to be displayed on empty',
							'before_label' => true,
							'value'        => $this->empty,
						) );
					endif;
					
					if ( isset( $this->default_options['url_prefix'] ) && 'form' == $this->page ): // needed for the data field
						$this->input_text( array(
							'name'         => 'url_prefix',
							'class'        => 'field-url-prefix',
							'label'        => 'url prefix ( http:// )',
							'before_label' => true,
							'value'        => $this->url_prefix,
						) );
					endif;
					
					if ( $this->show_fields ):
						$this->input_multiple( array(
							'name'            => 'show',
							'class'           => 'field-show',
							'label'           => 'show / hide input area',
							'before_label'    => true,
							'selected_fields' => $this->show,
							'all_fields'      => $this->show_fields,
						) );
					endif;
					
					if ( $this->show_multiple && 'form' == $this->page ):
						$this->input_checkbox( array(
							'name'            => 'multiple',
							'class'           => 'field-multiple',
							'label'           => 'multiple',
							'sub_label'       => 'yes, allow the user to create multiple fields',
							'before_label'    => true,
							'value'           => $this->multiple,
						) );
					endif;
					
					if ( $this->show_link_to && 'form' != $this->page ):
						$this->input_checkbox( array(
							'name'         => 'link_to',
							'class'        => 'field-multiple',
							'label'        => 'link to profile',
							'sub_label'    => 'wrap the field with a link to the profile page',
							'before_label' => true ,
							'value'        => $this->link_to,
						) );
					endif;
					?>
					<input type="button" value="Save" class="button save-field-settings" />
					<span class="spinner" style="display:none;"><img src="<?php echo admin_url('/images/wpspin_light.gif'); ?>" alt="spinner" /> saving...</span>
				</div>
				<label class="field-title"><?php echo $this->label; ?></label>
		<?php
		else:
			$taxonomy_class = ( Profile_CCT::string_starts_with( $this->type, PROFILE_CCT_TAXONOMY_PREFIX) ? 'field-item-taxonomy' : "");
			?>
			<div class="<?php echo esc_attr( $this->type ); ?> field-item <?php echo $this->class." ".$taxonomy_class." ".$this->width; ?>">
			<?php
			echo $this->before;
		endif;
		
		$multiple_class = ( $this->show_multiple ? "field-shell-multiple": "");
		?>
		<div class="field-shell field-shell-<?php echo $this->type.' '.$multiple_class; ?>">
		
		<?php if ( 'form' == $this->page ): ?>
			<div class="description">
				<?php
					if ( ! empty( $this->description ) ):
						echo esc_html($this->description);
					endif;
				?>
			</div>
		<?php endif; ?>
		
		<?php if ( 'display' == $this->action && isset( $this->text ) && ! empty( $this->text ) ): ?> <!-- only display the span if there is something to display on the -->
			<span class="text-input">
				<?php echo esc_html( $this->text ); ?>
			</span>
		<?php else: ?>
			<span class="text-input">
				<?php if ( isset( $this->text ) && 'form' != $this->page && ! empty( $this->text ) ) echo esc_html($this->text); ?>
			</span>
		<?php endif; 
	}
	
	/**
	 * end_field function.
	 * 
	 * @access public
	 * @return void
	 */
	function end_field() {
		$shell_tag  = ( $this->action == 'edit' ? 'li' : 'div');
        
		if ( $this->show_multiple ):
			$style_multiple = ( $this->multiple ? 'style="display: inline;"' : 'style="display: none;"' );
            
			if ( 'edit' == $this->action && 'form' == $this->page ):
				?>
				<span class="add-multiple" <?php echo $style_multiple; ?>>
					<a href="#add" class="button disabled" disabled="disabled">Add another</a> 
					 <em>disabled in preview</em>
				</span>
				<?php
			elseif ( $this->multiple && ! in_array( $this->page, array('page', 'list') ) ):
				?>
				<a href="#add" class="button add-multiple">Add another</a>
				<?php
			endif;
		endif;
		?>
		</div>
		<?php
		if ( 'edit' != $this->action ):
			echo $this->after;
        endif;
		?>
		</<?php echo $shell_tag; ?>>
		<?php
	}
	
	############################################################################################################
	/* Inputs */
	
	/**
	 * input_text function.
	 *
	 * @access public
	 * @return void
	 */
	function input_text( $attr ) {
		$attr = $this->field_attr( $attr, 'text' );
		
		printf( "<span %s>", $attr['field_shell_attr'] );
		$this->input_label_before( $attr['id'], $attr['label'], $attr['before_label'] );
		printf( '<input type="text" %s value="%s" />', $attr['field_attr'], $attr['value'] );
		$this->input_label_after( $attr['id'], $attr['label'], $attr['before_label'] );
		printf( "</span>" );
	}
	
	/**
	 * input_hidden function.
	 *
	 * @access public
	 * @return void
	 */
	function input_hidden( $attr ) {
		$attr = $this->field_attr( $attr, 'hidden' );
		
		printf( '<span %s>', $attr['field_shell_attr'] );
		$this->input_label_before( $attr['id'], $attr['label'], $attr['before_label'] );
		printf( '<input type="hidden" %s value="%s" />', $attr['field_attr'], $attr['value'] );
		$this->input_label_after( $attr['id'], $attr['label'], $attr['before_label'] );
		printf( '</span>' );
	}
	
	/**
	 * input_multiple function.
	 *
	 * @access public
	 * @param mixed $attr
	 * @return void
	 */
	function input_multiple( $attr ) {
		$attr = $this->field_attr( $attr, 'multiple' );
		$selected_fields = is_array( $attr['selected_fields'] ) ? $attr['selected_fields'] : array();
		
		printf( "<div %s>", $attr['field_shell_attr'] );
		$this->input_label_before( $attr['id'], $attr['label'], $attr['before_label'] );
		foreach ( $attr['all_fields'] as $field ):
			$this->input_checkbox_raw( in_array( $field, $selected_fields ), $attr['field_attr'], $field,  $field ); // produces the checkbox
        endforeach;
		$this->input_label_after( $attr['id'], $attr['label'], $attr['before_label'] );
		printf( "</div>" );
	}
	
	/**
	 * input_checkbox function.
	 *
	 * @access public
	 * @param mixed $attr
	 * @return void
	 */
	function input_checkbox( $attr ) {
		$attr = $this->field_attr( $attr, 'checkbox' );
		
		printf( "<div %s>", $attr['field_shell_attr'] );
		$this->input_label_before( $attr['id'], $attr['label'], $attr['before_label'] );
		$this->input_checkbox_raw( $attr['value'], $attr['field_attr'], $attr['sub_label'] ); // produces the checkbox
		$this->input_label_after( $attr['id'], $attr['label'], $attr['before_label'] );
		printf( "</div>" );
	}
	
	function input_checkbox_raw( $checked, $field_attr, $field, $value = 1 ) {
		?>
		<label><input type="checkbox" <?php checked( $checked ); ?> value="<?php echo $value; ?>" <?php echo $field_attr; ?>  /> <?php echo $field; ?></label>
		<?php
	}
	
	/**
	 * input_select function.
	 *
	 * @access public
	 * @param mixed $attr
	 * @return void
	 */
	function input_select( $attr ) {
		$attr = $this->field_attr( $attr, 'select' );
		
		printf( "<span %s>", $attr['field_shell_attr'] );
		$this->input_label_before( $attr['id'], $attr['label'], $attr['before_label'] );
		?>
		<select <?php echo $attr['field_attr']; ?> >
			<option value=""></option><!-- This gives us an emty field for if the user doesn't select anything -->
            <?php 
			foreach ( $attr['all_fields'] as $field ):
				printf('<option value="%s" %s>%s</option>', $field, selected( $attr['value'], $field , false ), $field );
			endforeach;
            ?>
		</select>
		<?php
		$this->input_label_after( $attr['id'], $attr['label'], $attr['before_label'] );
		printf( "</span>" );
	}
	
	/**
	 * input_textarea function.
	 *
	 * @access public
	 * @param mixed $attr
	 * @return void
	 */
	function input_textarea( $attr ) {
		$profile = Profile_CCT::get_object();
		$options = $profile->settings['wp_editor'];
		$attr = $this->field_attr( $attr, 'textarea' );
		
		?>
		<span <?php echo $attr['field_shell_attr']; ?>>
		<?php
		$this->input_label_before( $attr['id'], $attr['label'], $attr['before_label'] );
		
		if ( 'edit' == $this->action || $this->multiple ): // only display the editor on the Profile edit side
			?>
			<textarea <?php echo $attr['field_attr']; ?>><?php echo esc_html( $attr['value'] ); ?></textarea>
			<?php
		else:
			$args = array(
				'textarea_name' => $attr['name'],
				'teeny'         => ! $options['advanced'],
				'media_buttons' => $options['media_buttons'],
			);
			wp_editor( $attr['value'], $attr['id'], $args );
		endif;
        
		$this->input_label_after( $attr['id'], $attr['label'], $attr['before_label'] );
		?>
		</span>
		<?php
	}
	
	/**
	 * input_label_before function.
	 *
	 * @access public
	 * @param mixed $id
	 * @param mixed $label
	 * @param mixed $before_label
	 * @return void
	 */
	function input_label_before( $id, $label, $before_label ) {
		if ( $before_label ):
			$this->input_label( $id, $label );
        endif;
	}
	
	/**
	 * input_label_after function.
	 *
	 * @access public
	 * @param mixed $id
	 * @param mixed $label
	 * @param mixed $before_label
	 * @return void
	 */
	function input_label_after( $id, $label, $before_label ) {
		if ( ! $before_label ):
			$this->input_label( $id, $label );
        endif;
    }
	
	/**
	 * input_label function.
	 *
	 * @access public
	 * @param mixed $id
	 * @param mixed $label
	 * @return void
	 */
	function input_label( $id, $label ) {
		?>
        <label for="<?php echo $id; ?>" ><?php echo $label; ?></label>
        <?php
	}
	
	############################################################################################################
	/* Display */
	/**
	 * display_shell function.
	 * 
	 * @access public
	 * @param mixed $attr
	 * @return void
	 */
	function display_shell( $attr = array() ) {
		$tag        = ( isset( $attr['tag'] )   ? $attr['tag'] : 'div' );
		$class_attr = ( isset( $attr['class'] ) ? 'class="'.$attr['class'].'"' : '' );
		
		printf( '<%s %s>', $tag, $class_attr );
		if ( $this->link_to ):
			printf( '<a href="%s">', get_permalink() ); // this should always just link to the profile
        endif;
	}
	
	/**
	 * display_end_shell function.
	 * 
	 * @access public
	 * @param mixed $attr
	 * @return void
	 */
	function display_end_shell( $attr = array() ) {
		$tag = ( isset($attr['tag']) ? $attr['tag'] : 'div' );
        
		if ( $this->link_to ):
			?>
			</a>
			<?php
        endif;
        
		printf( '</%s>', $tag );
	}
	
	/**
	 * display_text function.
	 * 
	 * @access public
	 * @param mixed $attr
	 * @return void
	 */
	function display_text( $attr ) {
		$attr = self::display_attr( $attr, 'text' );
		$found = strpos ($attr['field_shell_attr'], 'display:none');
		
		if( $found === false || 'edit' == $this->action):
			printf( "<span %s>", $attr['field_shell_attr'] );
			if ( ! empty($attr['display']) ):
		    	$this->display_separator( array( 'separator' => $attr['separator'], 'class' => $attr['class'] ) );
		    	if( !empty( $attr['filter_text'] ) ):
					echo "<".$attr['tag']." ".$attr['field_attr'].">". call_user_func ( $attr['filter_text'], $attr['display'] ) ."</".$attr['tag'].">";
				else:
					echo "<".$attr['tag']." ".$attr['field_attr'].">".$attr['display']."</".$attr['tag'].">";
				endif;
				$this->display_separator( array( 'separator' => $attr['post_separator'], 'class' => $attr['class'] ) );
			endif;
			printf( "</span>" );
		endif;
		
	}
	
	function display_separator( $attr ) {
		$separator = ( isset( $attr['separator'] ) && $attr['separator'] != '' ? '<span class="'.$attr['class'].'-separator separator">'.$attr['separator'].'</span>' : '' );
		echo $separator;
	}
	
	/**
	 * display_link function.
	 * 
	 * @access public
	 * @param mixed $attr
	 * @return void
	 */
	function display_link( $attr ) {
		// todo: implement maybe_link - this might be a link or not favour the text 
		// todo: implement force_link - this should be a link if not next use the url as the link - example website
		
		if ( isset( $attr['force_link'] ) && $attr['force_link'] ):
			if ( isset( $attr['value'] ) && $attr['value'] ):
				$attr['display'] = $attr['value'];
			else:
				$attr['display'] = ( 'edit' == $this->action ? $attr['default_text'] : $this->data[$attr['field_id']] );
			endif;
		endif;
		
		if ( isset( $attr['maybe_link'] ) && $attr['maybe_link'] &&  empty( $attr['href'] ) ):
			$attr['tag'] = 'span';
		else:
			$attr['tag'] = 'a';
		endif;
		
		if ( empty( $attr['href'] ) ):
			$attr['href'] = ( 'edit' == $this->action ? $attr['default_text'] : $this->data[$attr['field_id']] );
		endif;
		
		$this->display_text( $attr );
	}
	
	function display_textfield( $attr ) {
		$attr['tag'] = 'div';
		
		$attr['filter_text'] = 'wpautop';
		// what we want to display should be filtered using the special filter
		$this->display_text( $attr );
	}
	
	############################################################################################################
	/* Helper functions */
	/**
	 * serialize function.
	 * converts the data into a url string
	 * @access public
	 * @param mixed $data
	 * @return void
	 */
	function serialize( $data ) {
		foreach ( $data as $key => $value ):
			if ( in_array($key, array( "show_fields", "show_multiple" )) ):
				continue;
			elseif ( is_array($value) ):
				foreach ( $value as $value_data ):
					$str[] = urlencode($key."[]")."=".urlencode($value_data);
				endforeach;
			else:
				$str[] = urlencode($key)."=".urlencode($value);
			endif;
		endforeach;
        
		return implode( "&", $str );
	}
	
	/**
	 * Creates a string of html attributes to be inserted into a dom element.
	 * 
	 * @access public
	 * @param mixed $attr
	 * @param mixed $field_type
	 * @return void
	 */
	function field_attr( $attr, $field_type ) {
		$show = ( isset( $attr['field_id'] ) && ! in_array( $attr['field_id'], $this->show ) && in_array( $attr['field_id'], $this->show_fields )  ? ' style="display:none;"' : '' ); // should this field be displayed
		
		// Things to be returned.
		$needed_attr['id']               = ( isset( $attr['field_id'] ) && $attr['field_id'] ? $attr['field_id'] : 'profile-cct-'.$this->type.'-'.$field_type.'-'.$this->field_counter ); // todo: show warning
		$needed_attr['before_label']     = ( isset( $attr['before_label'] ) && $attr['before_label'] ? true : false );
		$needed_attr['field_shell_attr'] = ( isset( $attr['field_id'] )        ? ' class="'.$attr['field_id'].' '.$field_type.'-shell"' : ' class="'.$this->type.' '.$field_type.'-shell"' ).$show;
		$needed_attr['label']            = ( isset( $attr['label'] )		   ? $attr['label']           : '' );
		$needed_attr['sub_label']        = ( isset( $attr['sub_label'] )	   ? $attr['sub_label']       : '' );
		$needed_attr['all_fields']       = ( isset( $attr['all_fields'] )	   ? $attr['all_fields']      : '' );
		$needed_attr['selected_fields']  = ( isset( $attr['selected_fields'] ) ? $attr['selected_fields'] : '' );
		$needed_attr['sub_label']        = ( isset( $attr['sub_label'] )       ? $attr['sub_label']       : '' );
		
		if ( $field_type == 'multiple' ):
			$needed_attr['name'] = ( ! empty( $attr['name'] ) ? $attr['name'].'[]' : 'profile_cct['.$this->type.']['.$this->subfield_counter.']['.$needed_attr['id'].'][]' );
		elseif ( $this->show_multiple ):
			$needed_attr['name'] = ( ! empty( $attr['name'] ) ? $attr['name']      : 'profile_cct['.$this->type.']['.$this->subfield_counter.']['.$needed_attr['id'].']' );
		else:
			$needed_attr['name'] = ( ! empty( $attr['name'] ) ? $attr['name']      : 'profile_cct['.$this->type.']['.$needed_attr['id'].']' );
		endif;
		
		if ( isset( $attr['value'] ) && $attr['value'] != '' ):
			$needed_attr['value'] = $attr['value'];
		elseif ( isset( $this->data[$needed_attr['id']] ) ):
			$needed_attr['value'] = $this->data[$needed_attr['id']];
		elseif ( isset( $attr['default'] ) && $attr['default'] != '' && 'edit' != $this->action ):
			$needed_attr['value'] = $attr['default'];
		else:
			$needed_attr['value'] = '';
		endif;
		
		$id    = ( isset( $needed_attr['id']   ) ? ' id="'   . $needed_attr['id']  .'" ' : ''                    );
		$name  = ( isset( $needed_attr['name'] ) ? ' name="' . $needed_attr['name'].'" ' : ''                    );
		$size  = ( isset( $attr['size']        ) ? ' size="' . $attr['size']       .'" ' : ''                    );
		$row   = ( isset( $attr['row']         ) ? ' row="'  . $attr['row']        .'" ' : ''                    );
		$cols  = ( isset( $attr['cols']        ) ? ' cols="' . $attr['cols']       .'" ' : ''                    );
		$class = ( isset( $attr['class']       ) ? ' class="'. $attr['class']      .'" ' : ' class="field text"' );
		
		if ( is_array( $attr['data'] ) ):
			$data = "";
			foreach ( $attr['data'] as $key => $value ):
				$data .= 'data-'.$key.'="'.$value.'" ';
			endforeach;
		endif;
		
		$needed_attr['field_attr'] = $id.$name.$class.$row.$cols.$size.$data.' ';
		
		$this->field_counter++;
		
		return $needed_attr;
	}
	
	/**
	 * The display_attr function.
	 * 
	 * @access public
	 * @param mixed $attr
	 * @param mixed $field_type
	 * @return void
	 */
	function display_attr( $attr, $field_type ) {
		$lorem_ipsum  = '<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. In et tempor lorem. Nam eget sapien sit amet risus porttitor pellentesque. Sed vestibulum tellus et quam faucibus vel tristique metus sagittis. Integer neque justo, suscipit sit amet lobortis eu, aliquet imperdiet sapien. Morbi id tellus quis nisl tempor semper.</p><p>Nunc sed diam sit amet augue venenatis scelerisque quis eu ante. Cras mattis auctor turpis, non congue nibh auctor at. Nulla libero ante, dapibus a tristique eu, semper ac odio. Nulla ultrices dui et velit eleifend congue. Mauris vel mauris eu justo lobortis semper. Duis lacinia faucibus nibh, ac sodales leo condimentum id. Suspendisse commodo mattis dui, eu rutrum sapien vehicula a. Proin iaculis sollicitudin lacus vitae commodo.</p>';
		$default_text = ( 'lorem ipsum' == $attr['default_text'] ? $lorem_ipsum : $attr['default_text'] );
		
		$show = ( isset( $attr['field_id'] ) && ! in_array( $attr['field_id'], $this->show ) && in_array( $attr['field_id'], $this->show_fields )  ? ' style="display:none;"' : '' ); // should this field be displayed
		
		$needed_attr['id']               = ( isset( $attr['field_id'] ) && $attr['field_id'] ? $attr['field_id'] : '' );
	    $needed_attr['display']          = ( 'edit' == $this->action          ? $default_text           : ( isset($attr['value']) ? $attr['value'] : $this->data[$needed_attr['id']] ) );
		$needed_attr['field_shell_attr'] = ( isset( $attr['field_id'] )       ? ' class="'.$attr['field_id'].' '.$field_type.'-shell"' : ' class="'.$this->type.' '.$field_type.'-shell"' ).$show;
	    $needed_attr['tag']              = ( isset( $attr['tag'] )            ? $attr['tag']            : 'span' );
		$needed_attr['post_separator']   = ( isset( $attr['post_separator'] ) ? $attr['post_separator'] : ''     );
		$needed_attr['separator']        = ( isset( $attr['separator'] )      ? $attr['separator']      : ''     );
		$needed_attr['class']            = ( isset( $attr['class'] )          ? $attr['class']          : ''     );
		$needed_attr['filter_text']      = ( isset( $attr['filter_text'] )          ? $attr['filter_text']          : ''     );
		
		if ( ( ! isset( $needed_attr['display'] ) || $needed_attr['display'] == '' ) && isset( $attr['href'] ) ):
			$needed_attr['display'] = $attr['href'];
		endif;
		
		$id    = ( isset( $attr['field_id'] ) ? ' id="'   . esc_attr($attr['field_id']).'" ' : ''  );
		$class = ( isset( $attr['class']    ) ? ' class="'. esc_attr( sanitize_html_class( $attr['class']))   .'" ' : ' class="field"' );
	    $href  = ( isset( $attr['href']     ) ? ' href="' . esc_url( $attr['href'] )    .'" ' : '' );
	    
		
		$needed_attr['field_attr'] = $id.$class.$href.' ';
		
		$this->field_counter++;
		
		return $needed_attr;
	}
    
	/**
	 * Time
	 * list_of_months function.
	 *
	 * @access public
	 * @return void
	 */
	function list_of_months() {
		return array(
			"January",
			"February",
			"March",
			"April",
			"May",
			"June",
			"July",
			"August",
			"September",
			"October",
			"November",
			"December",
		);
	}
	
	/**
	 * list_of_years function.
	 *
	 * @access public
	 * @return void
	 */
	function list_of_years( $start = 3, $end = -40 ) {
		return range( date("Y") + $start, date("Y") + $end );
	}
	
	/**
	 * list_of_days function.
	 *
	 * @access public
	 * @return void
	 */
	function list_of_days() {
		return array( "Monday", "Tuesday", "Wednesday", "Thursday", "Friday", "Saturday", "Sunday" );
	}
	
	/**
	 * list_of_hours function.
	 *
	 * @access public
	 * @return void
	 */
	function list_of_hours() {
		return range( 1, 12 );
	}
	
	/**
	 * list_of_minutes function.
	 *
	 * @access public
	 * @return void
	 */
	function list_of_minutes() {
		return array_merge( array('00', '05'), range(10, 55, 5) );
	}
	
	/**
	 * list_of_periods function.
	 *
	 * @access public
	 * @return void
	 */
	function list_of_periods() {
		return array( 'AM', 'PM' );
	}
	
	/**
	 * phone_options function.
	 *
	 * @access public
	 * @return void
	 */
	function phone_options() {
		return array(
			"phone",
			"work phone",
			"mobile",
			"fax",
			"work fax",
			"pager",
			"other",
		);
	}
	
	/**
	 * project_status function.
	 * 
	 * @access public
	 * @return void
	 */
	function project_status() {
		return array( 'Planning', 'Current', 'Completed' );
	}
	
	/**
	 * list_of_countries function.
	 *
	 * @access public
	 * @return void
	 */
	function list_of_countries() {
		return array(
			"Canada",
			"United States",
			"United Kingdom",
			'---',
			"Afghanistan",
			"Albania",
			"Algeria",
			"Andorra",
			"Angola",
			"Antigua and Barbuda",
			"Argentina",
			"Armenia",
			"Australia",
			"Austria",
			"Azerbaijan",
			"Bahamas",
			"Bahrain",
			"Bangladesh",
			"Barbados",
			"Belarus",
			"Belgium",
			"Belize",
			"Benin",
			"Bhutan",
			"Bolivia",
			"Bosnia and Herzegovina",
			"Botswana",
			"Brazil",
			"Brunei",
			"Bulgaria",
			"Burkina Faso",
			"Burundi",
			"Cambodia",
			"Cameroon",
			"Canada",
			"Cape Verde",
			"Central African Republic",
			"Chad",
			"Chile",
			"China",
			"Colombi",
			"Comoros",
			"Congo (Brazzaville)",
			"Congo",
			"Costa Rica",
			"Cote d'Ivoire",
			"Croatia",
			"Cuba",
			"Cyprus",
			"Czech Republic",
			"Denmark",
			"Djibouti",
			"Dominica",
			"Dominican Republic",
			"East Timor (Timor Timur)",
			"Ecuador",
			"Egypt",
			"El Salvador",
			"Equatorial Guinea",
			"Eritrea",
			"Estonia",
			"Ethiopia",
			"Fiji",
			"Finland",
			"France",
			"Gabon",
			"Gambia, The",
			"Georgia",
			"Germany",
			"Ghana",
			"Greece",
			"Grenada",
			"Guatemala",
			"Guinea",
			"Guinea-Bissau",
			"Guyana",
			"Haiti",
			"Honduras",
			"Hungary",
			"Iceland",
			"India",
			"Indonesia",
			"Iran",
			"Iraq",
			"Ireland",
			"Israel",
			"Italy",
			"Jamaica",
			"Japan",
			"Jordan",
			"Kazakhstan",
			"Kenya",
			"Kiribati",
			"Korea, North",
			"Korea, South",
			"Kuwait",
			"Kyrgyzstan",
			"Laos",
			"Latvia",
			"Lebanon",
			"Lesotho",
			"Liberia",
			"Libya",
			"Liechtenstein",
			"Lithuania",
			"Luxembourg",
			"Macedonia",
			"Madagascar",
			"Malawi",
			"Malaysia",
			"Maldives",
			"Mali",
			"Malta",
			"Marshall Islands",
			"Mauritania",
			"Mauritius",
			"Mexico",
			"Micronesia",
			"Moldova",
			"Monaco",
			"Mongolia",
			"Morocco",
			"Mozambique",
			"Myanmar",
			"Namibia",
			"Nauru",
			"Nepa",
			"Netherlands",
			"New Zealand",
			"Nicaragua",
			"Niger",
			"Nigeria",
			"Norway",
			"Oman",
			"Pakistan",
			"Palau",
			"Panama",
			"Papua New Guinea",
			"Paraguay",
			"Peru",
			"Philippines",
			"Poland",
			"Portugal",
			"Qatar",
			"Romania",
			"Russia",
			"Rwanda",
			"Saint Kitts and Nevis",
			"Saint Lucia",
			"Saint Vincent",
			"Samoa",
			"San Marino",
			"Sao Tome and Principe",
			"Saudi Arabia",
			"Senegal",
			"Serbia and Montenegro",
			"Seychelles",
			"Sierra Leone",
			"Singapore",
			"Slovakia",
			"Slovenia",
			"Solomon Islands",
			"Somalia",
			"South Africa",
			"Spain",
			"Sri Lanka",
			"Sudan",
			"Suriname",
			"Swaziland",
			"Sweden",
			"Switzerland",
			"Syria",
			"Taiwan",
			"Tajikistan",
			"Tanzania",
			"Thailand",
			"Togo",
			"Tonga",
			"Trinidad and Tobago",
			"Tunisia",
			"Turkey",
			"Turkmenistan",
			"Tuvalu",
			"Uganda",
			"Ukraine",
			"United Arab Emirates",
			"United Kingdom",
			"United States",
			"Uruguay",
			"Uzbekistan",
			"Vanuatu",
			"Vatican City",
			"Venezuela",
			"Vietnam",
			"Yemen",
			"Zambia",
			"Zimbabwe"
		);
	}
}