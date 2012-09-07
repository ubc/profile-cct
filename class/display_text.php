<?php 


	extract( $options );
	
	$hide = ( isset($show) && !$show ? ' style="display:none;"': '');
	if($this->action == 'display' && empty($value) && !in_array($type, array('end_shell','shell') ) && !empty($hide) ):
		echo "";
	return true;
	endif;

	$prepend_class = $class;
	$class = ( isset($class)? ' class="'.$class.'"': ' class=""');
	$id = ( isset($id)? ' id="'.$id.'"': ' ');

	$href = ( isset($href)? ' href="'.$href.'"': ' ');


	$show = ( isset($show) && !$show ? false: true);

	$tag = (isset($tag) ? $tag :"span");

	switch( $default_text ){
	case 'lorem ipsum':

		$default_text = "<p><strong>".$field_type."</strong> Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nunc in velit ac sem dapibus cursus. Donec faucibus adipiscing ipsum ut auctor. Integer quis metus iaculis lacus vulputate facilisis. Fusce malesuada volutpat sapien eu commodo. Integer sed magna orci, quis commodo elit. In convallis fringilla mollis. Pellentesque dapibus mi quis nunc pulvinar lobortis. Sed ut purus auctor ligula aliquam egestas eu at sem. Sed eget nisl urna. Etiam vitae leo id erat porttitor iaculis et et lorem. Curabitur condimentum libero eget sapien dictum congue. In hac habitasse platea dictumst. In in nulla et elit vehicula tempor. Donec sem arcu, viverra quis dignissim ac, adipiscing sed nunc.</p>

<p>Quisque malesuada tellus vitae massa semper non faucibus leo sollicitudin. In sit amet feugiat ligula. Ut id ultrices magna. Proin ut imperdiet tellus. Nulla interdum eleifend massa egestas malesuada. Suspendisse potenti. Nulla suscipit imperdiet velit sit amet pretium. In sit amet lectus felis, commodo varius eros. Duis sapien diam, sagittis faucibus elementum vulputate, faucibus a mi. Class aptent taciti sociosqu ad litora torquent per conubia nostra, per inceptos himenaeos. Donec viverra, quam in pretium volutpat, elit sapien tempor neque, quis adipiscing magna quam vitae velit.</p>";
		break;

	case 'bruce bio':

		$default_text = "<p><strong>".$field_type."</strong> A wealthy businessman who lives in Gotham City, born to Dr. Thomas Wayne and his wife Martha, two very wealthy and charitable Gotham City socialites.</p>

<p>Known for his contributions to charity, notably through his Wayne Foundation, a charity devoted to helping the victims of crime and preventing people from becoming criminals.</p>";
		break;
	}
	//var_dump( $content_filter );
	if( $this->action == 'display' )  //!!
		$display = ( $content_filter ? apply_filters( $content_filter, $value ) : esc_html($value) ); 
	else
		$display = $default_text;

	$separator = (isset($separator) ? '<span class="'.$prepend_class.'-separator separator">'.$separator.'</span>': "");


	switch($type) {
	case 'text':
		
		echo $separator.' <'.$tag.' '.$id.$class.$href.$hide.'>';
		echo $display;
		echo "</".$tag.">";
		
		break;

	case 'shell':
		if($tag == 'a'):
			if($link_to):
				echo '<'.$tag.' '.$id.$class.$href.'>';
			else:
				echo '<span '.$id.$class.'>';
			endif;
		else:
			echo '<'.$tag.' '.$id.$class.'>';
		if($link_to):
			echo '<a '.$href.'>';
		endif;
		endif;

		break;

	case 'end_shell';
		if($tag == 'a'):
			if($link_to):
				echo '</'.$tag.'>';
			else:
				echo '</span>';
			endif;
		else:
			if($link_to):
				echo '</a>';
			endif;
		echo '</'.$tag.'>';
		endif;
		break;

	}