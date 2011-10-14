<?php
// this file contains how the elements should loop like in html
	
	
switch($field_type) {
	case "cct-name": 
	
	
	$fields = array('title','first','last','suffix');
	foreach($fields as $field):
		
		if( !isset($data[$field_type][$field]) )
			$data[$field_type][$field] = '';
	endforeach;
		
	?>
	
	<span class="fn n">
		<span class="honorific-prefix">Title</span>
 		<span class="given-name">Eric</span>
 		<span class="family-name">Meyer</span>
 		<span class="honorific-suffix">Suffix</span>
	</span>
	<?php 	
	break;
	case "cct-address": 
	$fields = array('street-1','street-2','city','province','postal','country');
	foreach($fields as $field):
	
		if(!isset($data[$field_type][$field]) )
			$data[$field_type][$field] = '';
	endforeach;
	?>
		<div class="address adr">
				<span class="type">Work</span>:
				<div class="street-address">169 University Avenue</div>
				<span class="locality">Palo Alto</span>,  
				<abbr class="region" title="California">CA</abbr>&nbsp;&nbsp;
				<span class="postal-code">94301</span>
				<div class="country-name">USA</div>
		</div>
	<?php
	break;
	case "cct-fax":
	case "cct-phone":
		
		$fields = array('tel-1','tel-2','tel-2');
	foreach($fields as $field):
	
		if(!isset($data[$field_type][$field]) )
			$data[$field_type][$field] = '';
	endforeach;  ?>
		<div class="telephone tel">
			<span class="type">Work</span> <span class="value">+1-650-289-4041</span>
		</div>
	<?php
	break;
	case "cct-email": 
	?>

		<div class="email">
			Email: <span class="email">info@commerce.net</span>
		</div>

	<?php
	break;
	case "cct-position": ?>
	
		<div class="position">
			Application Developer 
		</div>

	<?php
	break;
	case "cct-text": ?>
	
		<div class="text">
			<input type="text" tabindex="<?php $this->tab_index();?>" maxlength="255" value="<?php echo esc_attr($data[$field_type]); ?>" class="field text medium" spellcheck="false" name="profile_field[<?php echo $field_type; ?>]" id=""> 
		</div>

	<?php
	break;
	
	case "cct-website": ?>

		<div class="website">
			<a class="url" href="http://www.commerce.net/">www.commerce.net</a> 
		</div>

	<?php
	break;
	
	case "cct-education": 
		$fields = array('school','year','degree','honors');
		foreach($fields as $field):
	
			if(!isset($data[$field_type][$field]) )
				$data[$field_type][$field] = '';
		endforeach; 
	?>
		<div class="educaton">
			<span class="school">
				University of Brithsh Columbia
			</span>
			<span class="year">
				2009
			</span>
			<span class="degree">
			 	Mechanical Engineering
			</span>
			<span class="honors">
			 	BAS
			</span>
		</div>
	<?php
	break;
	case "cct-bio":
	case "cct-publications": 
	case "cct-research":
	case "cct-teaching":
	case "cct-textarea": ?>
	
		<div class="textarea <?php echo $field_type; ?>">
			<p>Nunc ut quam quis quam posuere tincidunt. Nam viverra tortor et mi ornare venenatis. Nam sed ipsum dui. Sed laoreet sem vel turpis pharetra ullamcorper et vel quam. Phasellus pellentesque, nibh non suscipit rutrum, arcu leo sagittis nisl, in malesuada turpis turpis a eros. Praesent blandit nunc posuere lectus aliquam vitae vehicula eros iaculis. Pellentesque turpis orci, eleifend at rhoncus et, pharetra a leo. Pellentesque metus arcu, rutrum vitae dictum vitae, pretium quis dolor. Phasellus in dapibus nunc. </p>
		</div>
	
		<?php
	break;
	case "cct-blog": ?>

		<span class="blog">
			<a rel="me" class="url" href="http://">blog</a>.
		</span>

	<?php
	break;
	case "cct-twitter": ?>
		<span class="twitter">
			<a rel="me" class="url" href="http://twitter.com/tantek">twitter</a>.
		</span>
	<?php
	break;
	case "cct-facebook": ?>
		<span class="facebook">
			<a rel="me" class="url" href="http://facebook.com/">facebook</a>.
		</span>
	<?php
	break;
	case "cct-linkedin": ?>
		<span class="linkedin">
			<a rel="me" class="url" href="http://www.linkedin.com/in/">linkedin</a>.
		</span>
		
	<?php
	break;
	case "cct-delicious": ?>
		<span class="delicious">
			<a rel="me" class="url" href="http://delicious.com/">delicious</a>.
		</span>
	<?php
	break;
	case "cct-flickr": ?>
		<span class="flickr">
			<a rel="me" class="url" href="http://flickr.com/photos/">flickr</a>.
		</span>
	<?php
	break;
	case "cct-google-plus": ?>
		<span class="google-plus">
			<a rel="me" class="url" href="http://plus.googe.com/">google plus</a>.
		</span>
	<?php
	break;
	case 'image': ?>
		<img class="photo fn" src="http://www.factorycity.net/images/avatar.jpg" alt="Chris Messina" />
	<?php
	break;
	default:
		echo "default";
	break;
}