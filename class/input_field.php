<?php


	extract( $options );

	$before_label = ( isset($before_label) && $before_label ? true:false);
	if( !isset($field_id_class) )
	$field_id_class = ( isset($field_id)? ' class="'.$field_id.' '.$type.'-shell"': '');
	
	//print_r($options);
	
	$size = ( isset($size)? ' size="'.$size.'"': '');
	$row = ( isset($row)? ' row="'.$row.'"': '');
	$cols = ( isset($cols)? ' cols="'.$cols.'"': '');
	$class = ( isset($class)? ' class="'.$class.'"': ' class="field text"');
	$id = ( isset($id)? ' id="'.$id.'"': ' ');
	$separator = (isset($separator) ? '<span class="separator">'.$separator.'</span>': "");
	
	if($type =='multiple'):
		$name = ( isset($name)? ' name="'.$name.'[]"':  ' name="profile_cct['.$field_type.']['.$count.']['.$field_id.'][]"');
	$textarea_id = 'profile_cct-'.$field_type.'-'.$count.'-'.$field_id;
	$textarea_name = 'profile_cct['.$field_type.']['.$count.']['.$field_id.'][]';
	elseif($multiple):
		$name = ( isset($name)? ' name="'.$name.'[]"':  ' name="profile_cct['.$field_type.']['.$count.']['.$field_id.']"');
	$textarea_id = 'profile_cct-'.$field_type.'-'.$count.'-'.$field_id;
	$textarea_name = 'profile_cct['.$field_type.']['.$count.']['.$field_id.']';
	else:
		$name = ( isset($name)? ' name="'.$name.'"': ' name="profile_cct['.$field_type.']['.$field_id.']"');
	$textarea_id = 'profile_cct-'.$field_type.'-'.$field_id;
	$textarea_name = 'profile_cct['.$field_type.']['.$field_id.']';
	endif;
	$show = ( isset($show) && !$show ? ' style="display:none;"': '');
	switch($type) {
	case 'text':
	
		if ($separator)
			echo $separator;
	?>
		 	<span <?php echo $field_id_class.$show; ?>>
		 		<?php if($before_label){ ?><label for="<?php echo $textarea_id; ?>"><?php echo $label; ?></label> <?php } ?>
				<input type="text" id="<?php echo $textarea_id; ?>" <?php echo $size.$class.$name; ?> value="<?php echo esc_attr($value); ?>" id="">
				<?php if(!$before_label){ ?><label for="<?php echo $textarea_id; ?>"><?php echo $label; ?></label> <?php } ?>
			</span>
			<?php
		break;
	
	case 'hidden':
	
		if ($separator)
			echo $separator;
	?>
		 	<span <?php echo $field_id_class.$show; ?>>
		 		<?php if($before_label){ ?><label for="" ><?php echo $label; ?></label> <?php } ?>
				<input type="hidden" <?php echo $size.$class.$name; ?> value="<?php echo esc_attr($value); ?>" id="">
				<?php if(!$before_label){ ?><label for="" ><?php echo $label; ?></label> <?php } ?>
			</span>
			<?php
		break;
	
	
	case 'multiple':
		?><div <?php echo $field_id_class.$show;  ?>><?php
		if ($separator)
			echo $separator;
	
		if($before_label){ ?><label for="<?php echo $textarea_id; ?>"><?php echo $label; ?></label> <?php }
		// need to change the name in this case
		$selected_fields = (is_array($selected_fields) ? $selected_fields : array());
	
		foreach($all_fields as $field):
	
	?>
						<label><input type="checkbox" id="<?php echo $textarea_id; ?>" <?php checked( in_array($field,$selected_fields) ); ?> value="<?php echo $field; ?>" <?php echo $class.$name; ?> /> <?php echo $field; ?></label>
						<?php
		endforeach;
	
		if(!$before_label){ ?><label for="<?php echo $textarea_id; ?>"><?php echo $label; ?></label> <?php } ?>
	
					</div>
					<?php
		break;
	case 'checkbox':
		if ($separator)
			echo $separator;
	
		?><div <?php echo $field_id_class.$show;  ?>>
					<?php if($before_label){ ?><label for="<?php echo $textarea_id; ?>"><?php echo $label; ?></label> <?php } ?>
						<label><input id="<?php echo $textarea_id; ?>" type="checkbox" <?php checked( $value ); ?> value="1" <?php echo $class.$name; ?> /> <?php echo $field; ?></label>
					<?php if(!$before_label){ ?><label for="<?php echo $textarea_id; ?>"><?php echo $label; ?></label> <?php } ?>
					</div>
					<?php
		break;
	
	case 'select':
	
		if ($separator)
			echo $separator;
	
		?><span <?php echo $field_id_class.$show;  ?>>
					<?php
		if($before_label){ ?><label for="<?php echo $textarea_id; ?>"><?php echo $label; ?></label> <?php }
	?>
					<select id="<?php echo $textarea_id; ?>" <?php echo $name; ?> >
						<option value=""></option>
					<?php
		foreach($all_fields as $field): ?>
						<option  value="<?php echo $field; ?>" <?php selected($value,$field); ?> > <?php echo $field; ?></option>
						<?php
		endforeach;
	?>
					</select>
					<?php
		if(!$before_label){ ?><label for="<?php echo $textarea_id; ?>"><?php echo $label; ?></label> <?php } ?>
	
					</span>
					<?php
		break;
	
	case 'textarea':
		if ($separator)
			echo $separator;
	?>
					<span <?php echo $field_id_class.$show; ?>>
					<?php if($before_label){ ?><label for="" ><?php echo $label; ?></label> <?php } ?>
	
					<?php
		// only dispaly the editor on the Profile edit side
		if( $this->action == 'edit' || $multiple ): ?>
						<textarea <?php echo $size.$class.$name.$row.$cols; ?> id="<?php echo $textarea_id; ?>"><?php echo esc_html($value); ?></textarea>
					<?php
		else:
			wp_editor( $value, $textarea_id, array('textarea_name'=>$textarea_name,'teeny'=>true, 'media_buttons'=>false) );
		endif;
		if(!$before_label){ ?><label for="" ><?php echo $label; ?></label> <?php } ?>
				</span>
					<?php
		break;
	
	
	}
