<?php
	// data unserealized 
	$data = unserialize(base64_decode($post->post_content_filtered));
	$data = $this->stripslashes_deep($data);
	
	$count = 0;
	
	if(!isset($data[$field_type]) )
			$data[$field_type] = '';
	
switch($field_type) {
	case "cct-name": 
	
	
	$fields = array('title','first','last','suffix');
	foreach($fields as $field):
		
		if( !isset($data[$field_type][$field]) )
			$data[$field_type][$field] = '';
	endforeach;
		
	?>
		<div class="name">
		<span>
			<input type="text" tabindex="1" size="2" value="<?php echo esc_attr($data[$field_type]['title']); ?>" class="field text" name="profile_field[<?php echo $field_type; ?>][title]" id="">
			<label for="">Title</label>
		</span>
		<span>
			<input type="text" tabindex="2" size="14" value="<?php echo esc_attr($data[$field_type]['first']); ?>" class="field text fn" name="profile_field[<?php echo $field_type; ?>][first]" id="">
			<label for="">First</label>
		</span>
		<span>
			<input type="text" tabindex="3" size="19" value="<?php echo esc_attr($data[$field_type]['last']); ?>" class="field text ln" name="profile_field[<?php echo $field_type; ?>][last]" id="">
			<label for="">Last</label>
		</span>
		<span>
			<input type="text" tabindex="4" size="3" value="<?php echo esc_attr($data[$field_type]['suffix']); ?>" class="field text" name="profile_field[<?php echo $field_type; ?>][suffix]" id="">
			<label for="">Suffix</label>
		</span>
		</div>
	<?php 	
	break;
	case "cct-address": 
	$fields = array('street-1','street-2','city','province','postal','country');
	foreach($fields as $field):
		if(!isset($data[$field_type][$field]) )
			$data[$field_type][$field] = '';
	endforeach;
	?>
		<div class="address">
			<span class="left">
				<input type="text" tabindex="<?php $this->tab_index();?>" value="<?php echo esc_attr($data[$field_type]['building-name']); ?>" class="field text addr" name="profile_field[<?php echo $field_type; ?>][city]" id="">
				<label for="">Building Name</label>
			</span>
			<span class="right">
				<input type="text" tabindex="<?php $this->tab_index();?>" value="<?php echo esc_attr($data[$field_type]['room-number']); ?>" class="field text addr" name="profile_field[<?php echo $field_type; ?>][room-number]" id="">
				<label for="">Room number</label>
			</span>
			<span class="full addr1">
			<input type="text" tabindex="<?php $this->tab_index();?>" value="<?php echo esc_attr($data[$field_type]['street-1']); ?>" class="field text addr" name="profile_field[<?php echo $field_type; ?>][street-1]" id="">
			<label for="">Street Address</label>
			</span>
			<span class="full addr2">
			<input type="text" tabindex="<?php $this->tab_index();?>" value="<?php echo esc_attr($data[$field_type]['street-2']); ?>" class="field text addr" name="profile_field[<?php echo $field_type; ?>][street-2]" id="">
			<label for="">Address Line 2</label>
			</span>
			<span class="left">
			<input type="text" tabindex="<?php $this->tab_index();?>" value="<?php echo esc_attr($data[$field_type]['city']); ?>" class="field text addr" name="profile_field[<?php echo $field_type; ?>][city]" id="">
			<label for="">City</label>
			</span>
			<span class="right">
			<input type="text" tabindex="<?php $this->tab_index();?>" value="<?php echo esc_attr($data[$field_type]['province']); ?>" class="field text addr" name="profile_field[<?php echo $field_type; ?>][province]" id="">
			<label for="">State / Province / Region</label>
			</span>
			<span class="left">
			<input type="text" tabindex="<?php $this->tab_index();?>" maxlength="15" value="<?php echo esc_attr($data[$field_type]['postal']); ?>" class="field text addr" name="profile_field[<?php echo $field_type; ?>][postal]" id="">
			<label for="">Postal / Zip Code</label>
			</span>
			<span class="right">
			<select tabindex="<?php $this->tab_index();?>" class="field select addr" name="profile_field[<?php echo $field_type; ?>][country]" id="">
				<option selected="selected" value="<?php echo esc_attr($data[$field_type]['country']); ?>"><?php echo esc_attr($data[$field_type]['country']); ?></option>
				<option value="Canada">Canada</option>
				<option value="United States">United States</option>
				<option value="United Kingdom">United Kingdom</option>
				<option value="Australia">Australia</option>
				<option value="France">France</option>
				<option value="New Zealand">New Zealand</option>
				<option value="India">India</option>
				<option value="Brazil">Brazil</option>
				<option value="----">----</option>
				<option value="Afghanistan">Afghanistan</option>
				<option value="?and Islands">?and Islands</option>
				<option value="Albania">Albania</option>
				<option value="Algeria">Algeria</option>
				<option value="American Samoa">American Samoa</option>
				<option value="Andorra">Andorra</option>
				<option value="Angola">Angola</option>
				<option value="Anguilla">Anguilla</option>
				<option value="Antarctica">Antarctica</option>
				<option value="Antigua and Barbuda">Antigua and Barbuda</option>
				<option value="Argentina">Argentina</option>
				<option value="Armenia">Armenia</option>
				<option value="Aruba">Aruba</option>
				<option value="Austria">Austria</option>
				<option value="Azerbaijan">Azerbaijan</option>
				<option value="Bahamas">Bahamas</option>
				<option value="Bahrain">Bahrain</option>
				<option value="Bangladesh">Bangladesh</option>
				<option value="Barbados">Barbados</option>
				<option value="Belarus">Belarus</option>
				<option value="Belgium">Belgium</option>
				<option value="Belize">Belize</option>
				<option value="Benin">Benin</option>
				<option value="Bermuda">Bermuda</option>
				<option value="Bhutan">Bhutan</option>
				<option value="Bolivia">Bolivia</option>
				<option value="Bosnia and Herzegovina">Bosnia and Herzegovina</option>
				<option value="Botswana">Botswana</option>
				<option value="British Indian Ocean Territory">British Indian Ocean Territory</option>
				<option value="Brunei Darussalam">Brunei Darussalam</option>
				<option value="Bulgaria">Bulgaria</option>
				<option value="Burkina Faso">Burkina Faso</option>
				<option value="Burundi">Burundi</option>
				<option value="Cambodia">Cambodia</option>
				<option value="Cameroon">Cameroon</option>
				<option value="Cape Verde">Cape Verde</option>
				<option value="Cayman Islands">Cayman Islands</option>
				<option value="Central African Republic">Central African Republic</option>
				<option value="Chad">Chad</option>
				<option value="Chile">Chile</option>
				<option value="China">China</option>
				<option value="Colombia">Colombia</option>
				<option value="Comoros">Comoros</option>
				<option value="Democratic Republic of the Congo">Democratic Republic of the Congo</option>
				<option value="Republic of the Congo">Republic of the Congo</option>
				<option value="Cook Islands">Cook Islands</option>
				<option value="Costa Rica">Costa Rica</option>
				<option value="C?e d'Ivoire">C?e d'Ivoire</option>
				<option value="Croatia">Croatia</option>
				<option value="Cuba">Cuba</option>
				<option value="Cyprus">Cyprus</option>
				<option value="Czech Republic">Czech Republic</option>
				<option value="Denmark">Denmark</option>
				<option value="Djibouti">Djibouti</option>
				<option value="Dominica">Dominica</option>
				<option value="Dominican Republic">Dominican Republic</option>
				<option value="East Timor">East Timor</option>
				<option value="Ecuador">Ecuador</option>
				<option value="Egypt">Egypt</option>
				<option value="El Salvador">El Salvador</option>
				<option value="Equatorial Guinea">Equatorial Guinea</option>
				<option value="Eritrea">Eritrea</option>
				<option value="Estonia">Estonia</option>
				<option value="Ethiopia">Ethiopia</option>
				<option value="Faroe Islands">Faroe Islands</option>
				<option value="Fiji">Fiji</option>
				<option value="Finland">Finland</option>
				<option value="Gabon">Gabon</option>
				<option value="Gambia">Gambia</option>
				<option value="Georgia">Georgia</option>
				<option value="Germany">Germany</option>
				<option value="Ghana">Ghana</option>
				<option value="Greece">Greece</option>
				<option value="Grenada">Grenada</option>
				<option value="Guatemala">Guatemala</option>
				<option value="Guinea">Guinea</option>
				<option value="Guinea-Bissau">Guinea-Bissau</option>
				<option value="Guyana">Guyana</option>
				<option value="Haiti">Haiti</option>
				<option value="Honduras">Honduras</option>
				<option value="Hong Kong">Hong Kong</option>
				<option value="Hungary">Hungary</option>
				<option value="Iceland">Iceland</option>
				<option value="Indonesia">Indonesia</option>
				<option value="Iran">Iran</option>
				<option value="Iraq">Iraq</option>
				<option value="Ireland">Ireland</option>
				<option value="Israel">Israel</option>
				<option value="Italy">Italy</option>
				<option value="Jamaica">Jamaica</option>
				<option value="Japan">Japan</option>
				<option value="Jordan">Jordan</option>
				<option value="Kazakhstan">Kazakhstan</option>
				<option value="Kenya">Kenya</option>
				<option value="Kiribati">Kiribati</option>
				<option value="North Korea">North Korea</option>
				<option value="South Korea">South Korea</option>
				<option value="Kuwait">Kuwait</option>
				<option value="Kyrgyzstan">Kyrgyzstan</option>
				<option value="Laos">Laos</option>
				<option value="Latvia">Latvia</option>
				<option value="Lebanon">Lebanon</option>
				<option value="Lesotho">Lesotho</option>
				<option value="Liberia">Liberia</option>
				<option value="Libya">Libya</option>
				<option value="Liechtenstein">Liechtenstein</option>
				<option value="Lithuania">Lithuania</option>
				<option value="Luxembourg">Luxembourg</option>
				<option value="Macedonia">Macedonia</option>
				<option value="Madagascar">Madagascar</option>
				<option value="Malawi">Malawi</option>
				<option value="Malaysia">Malaysia</option>
				<option value="Maldives">Maldives</option>
				<option value="Mali">Mali</option>
				<option value="Malta">Malta</option>
				<option value="Marshall Islands">Marshall Islands</option>
				<option value="Mauritania">Mauritania</option>
				<option value="Mauritius">Mauritius</option>
				<option value="Mexico">Mexico</option>
				<option value="Micronesia">Micronesia</option>
				<option value="Moldova">Moldova</option>
				<option value="Monaco">Monaco</option>
				<option value="Mongolia">Mongolia</option>
				<option value="Montenegro">Montenegro</option>
				<option value="Morocco">Morocco</option>
				<option value="Mozambique">Mozambique</option>
				<option value="Myanmar">Myanmar</option>
				<option value="Namibia">Namibia</option>
				<option value="Nauru">Nauru</option>
				<option value="Nepal">Nepal</option>
				<option value="Netherlands">Netherlands</option>
				<option value="Netherlands Antilles">Netherlands Antilles</option>
				<option value="Nicaragua">Nicaragua</option>
				<option value="Niger">Niger</option>
				<option value="Nigeria">Nigeria</option>
				<option value="Norway">Norway</option>
				<option value="Oman">Oman</option>
				<option value="Pakistan">Pakistan</option>
				<option value="Palau">Palau</option>
				<option value="Palestine">Palestine</option>
				<option value="Panama">Panama</option>
				<option value="Papua New Guinea">Papua New Guinea</option>
				<option value="Paraguay">Paraguay</option>
				<option value="Peru">Peru</option>
				<option value="Philippines">Philippines</option>
				<option value="Poland">Poland</option>
				<option value="Portugal">Portugal</option>
				<option value="Puerto Rico">Puerto Rico</option>
				<option value="Qatar">Qatar</option>
				<option value="Romania">Romania</option>
				<option value="Russia">Russia</option>
				<option value="Rwanda">Rwanda</option>
				<option value="Saint Kitts and Nevis">Saint Kitts and Nevis</option>
				<option value="Saint Lucia">Saint Lucia</option>
				<option value="Saint Vincent and the Grenadines">Saint Vincent and the Grenadines</option>
				<option value="Samoa">Samoa</option>
				<option value="San Marino">San Marino</option>
				<option value="Sao Tome and Principe">Sao Tome and Principe</option>
				<option value="Saudi Arabia">Saudi Arabia</option>
				<option value="Senegal">Senegal</option>
				<option value="Serbia and Montenegro">Serbia and Montenegro</option>
				<option value="Seychelles">Seychelles</option>
				<option value="Sierra Leone">Sierra Leone</option>
				<option value="Singapore">Singapore</option>
				<option value="Slovakia">Slovakia</option>
				<option value="Slovenia">Slovenia</option>
				<option value="Solomon Islands">Solomon Islands</option>
				<option value="Somalia">Somalia</option>
				<option value="South Africa">South Africa</option>
				<option value="Spain">Spain</option>
				<option value="Sri Lanka">Sri Lanka</option>
				<option value="Sudan">Sudan</option>
				<option value="Suriname">Suriname</option>
				<option value="Swaziland">Swaziland</option>
				<option value="Sweden">Sweden</option>
				<option value="Switzerland">Switzerland</option>
				<option value="Syria">Syria</option>
				<option value="Taiwan">Taiwan</option>
				<option value="Tajikistan">Tajikistan</option>
				<option value="Tanzania">Tanzania</option>
				<option value="Thailand">Thailand</option>
				<option value="Togo">Togo</option>
				<option value="Tonga">Tonga</option>
				<option value="Trinidad and Tobago">Trinidad and Tobago</option>
				<option value="Tunisia">Tunisia</option>
				<option value="Turkey">Turkey</option>
				<option value="Turkmenistan">Turkmenistan</option>
				<option value="Tuvalu">Tuvalu</option>
				<option value="Uganda">Uganda</option>
				<option value="Ukraine">Ukraine</option>
				<option value="United Arab Emirates">United Arab Emirates</option>
				<option value="United States Minor Outlying Islands">United States Minor Outlying Islands</option>
				<option value="Uruguay">Uruguay</option>
				<option value="Uzbekistan">Uzbekistan</option>
				<option value="Vanuatu">Vanuatu</option>
				<option value="Vatican City">Vatican City</option>
				<option value="Venezuela">Venezuela</option>
				<option value="Vietnam">Vietnam</option>
				<option value="Virgin Islands, British">Virgin Islands, British</option>
				<option value="Virgin Islands, U.S.">Virgin Islands, U.S.</option>
				<option value="Yemen">Yemen</option>
				<option value="Zambia">Zambia</option>
				<option value="Zimbabwe">Zimbabwe</option>
			</select><br />
			<label for="">Country</label>
			</span>
		</div>
	<?php
	break;
	case "cct-fax":
	case "cct-phone":
		
		$fields = array('type','tel-1','tel-2','tel-2');
	foreach($fields as $field):
	
		if(!isset($data[$field_type][$field]) )
			$data[$field_type][$field] = '';
	endforeach;  ?>
		<div class="telephone">
		<span class="select-span">
			<select  tabindex="<?php $this->tab_index();?>"  class="field text" name="profile_field[<?php echo $field_type; ?>][type]" >
				<option>Work</option>
				<option>Mobile</option>
				<option>Fax</option>
				<option>Work Fax</option>
				<option>Pager</option>
				<option>Other</option>
			</select>
			<label for="">Type</label>
		</span>
		<span>
			<input type="text" tabindex="<?php $this->tab_index();?>" maxlength="3" size="3" value="<?php echo esc_attr($data[$field_type]['tel-1']); ?>" class="field text" name="profile_field[<?php echo $field_type; ?>][tel-1]" id="">
			<label for="">###</label>
		</span>
		<span class="symbol">-</span>
		<span>
			<input type="text" tabindex="<?php $this->tab_index();?>" maxlength="3" size="3" value="<?php echo esc_attr($data[$field_type]['tel-2']); ?>" class="field text" name="profile_field[<?php echo $field_type; ?>][tel-2]" id="">
			<label for="">###</label>
		</span>
		<span class="symbol">-</span>
		<span>
		 	<input type="text" tabindex="<?php $this->tab_index();?>" maxlength="4" size="4" value="<?php echo esc_attr($data[$field_type]['tel-3']); ?>" class="field text" name="profile_field[<?php echo $field_type; ?>][tel-3]" id="">
			<label for="">####</label>
		</span>
		
		</div>
		<a href="#" class="add-fields button">Add</a>
	<?php
	break;
	case "cct-email": 
	?>
		<div class="email">
			<input type="text" tabindex="<?php $this->tab_index();?>" maxlength="255" value="<?php echo esc_attr($data[$field_type]); ?>" class="field text medium" spellcheck="false" name="profile_field[<?php echo $field_type; ?>]" id=""> 
		</div>
		<a href="#" class="add-fields button">Add</a>

	<?php
	break;
	case "cct-position": ?>
	
		<div class="position">
			<input type="text" tabindex="<?php $this->tab_index();?>" maxlength="255" value="<?php echo esc_attr($data[$field_type]); ?>" class="field text medium" spellcheck="false" name="profile_field[<?php echo $field_type; ?>]" id=""> 
		</div>
		<a href="#" class="add-fields button">Add</a>

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
			<input type="text" tabindex="<?php $this->tab_index();?>" maxlength="255" value="<?php echo esc_attr($data[$field_type]); ?>" size="200" class="field text large" name="profile_field[<?php echo $field_type; ?>]" id=""> 
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
		<span>
			<input type="text" tabindex="<?php $this->tab_index();?>"  size="30" value="<?php echo esc_attr($data[$field_type]['school']); ?>" class="field text" name="profile_field[<?php echo $field_type; ?>][school]" id="">
			<label for="">School</label>
		</span>
		<span class="symbol"></span>
		<span class="short">
			<input type="text" tabindex="<?php $this->tab_index();?>" maxlength="5" size="5" value="<?php echo esc_attr($data[$field_type]['year']); ?>" class="field text" name="profile_field[<?php echo $field_type; ?>][year]" id="">
			<label for="">Year</label>
		</span>
		<span class="symbol"></span>
		<span class="short">
		 	<input type="text" tabindex="<?php $this->tab_index();?>" maxlength="5" size="5" value="<?php echo esc_attr($data[$field_type]['degree']); ?>" class="field text" name="profile_field[<?php echo $field_type; ?>][degree]" id="">
			<label for="">Degree</label>
		</span>
		<span class="symbol"></span>
		<span class="short">
		 	<input type="text" tabindex="<?php $this->tab_index();?>" maxlength="10" size="10" value="<?php echo esc_attr($data[$field_type]['honors']); ?>" class="field text" name="profile_field[<?php echo $field_type; ?>][honors]" id="">
			<label for="">Honors</label>
		</span>
		</div>
		<a href="#" class="add-fields button">Add</a>
	<?php
	break;
	case "cct-bio":
	case "cct-publications": 
	case "cct-research":
	case "cct-teaching":
	case "cct-textarea": ?>
	
		<div class="textarea">
			<textarea onkeyup="" tabindex="<?php $this->tab_index();?>" cols="50" rows="10" spellcheck="true" class="field textarea large" name="profile_field[<?php echo $field_type; ?>]" id=""><?php echo ($data[$field_type]); ?></textarea>
		</div>
	
		<?php
	break;
	case "cct-social":?>
		<div class="social">
			<span>
			<select  tabindex="<?php $this->tab_index();?>"  class="field text" name="profile_field[<?php echo $field_type; ?>][type]" >
				<?php foreach($this->social_fields() as $social_field): ?>
					<option <?php echo $social_field['type']; ?> style="background:url('<?php echo plugins_url("profile-custom-content-type/img/").$social_field['type']; ?>.png') no-repeat 2px; padding:3px 0 3px 22px;"> <?php echo $social_field['label']; ?></option>
				<?php endforeach; ?>
			</select>
			<input type="text" tabindex="<?php $this->tab_index();?>" maxlength="255" value="<?php echo esc_attr($data[$field_type]); ?>" class="field text" name="profile_field[<?php echo $field_type; ?>]" id="">
			<label for="">http://</label>
			</span>
		</div>
		<a href="#" class="add-fields button">Add</a>
	<?php
	break;
	case "cct-blog": ?>

		<div class="blog">
			<span>
			<input type="text" tabindex="<?php $this->tab_index();?>" maxlength="255" value="<?php echo esc_attr($data[$field_type]); ?>" class="field text large" name="profile_field[<?php echo $field_type; ?>]" id="">
			<label for="">http://</label>
			</span>
		</div>

	<?php
	break;
	case "cct-twitter": ?>
		<div class="twitter">
		<span class="partof"><label for="">http://twitter.com/#/</label></span>
		<span><input type="text"   tabindex="<?php $this->tab_index();?>" maxlength="255" value="<?php echo esc_attr($data[$field_type]); ?>" class="field text" name="profile_field[<?php echo $field_type; ?>]" id=""></span>
		</div>
	<?php
	break;
	case "cct-facebook": ?>
		<div class="facebook">
		<span class="partof"><label for="">http://facebook.com/</label></span>
		<span><input type="text"    tabindex="<?php $this->tab_index();?>" maxlength="255" value="<?php echo esc_attr($data[$field_type]); ?>" class="field text" name="profile_field[<?php echo $field_type; ?>]" id=""></span>
		</div>
	<?php
	break;
	case "cct-linkedin": ?>
		<div class="linkedin">
		<span class="partof"><label for="">http://www.linkedin.com/in/</label></span>
		<span><input type="text"    tabindex="<?php $this->tab_index();?>" maxlength="255" value="<?php echo esc_attr($data[$field_type]); ?>" class="field text" name="profile_field[<?php echo $field_type; ?>]" id=""></span>
		</div>
	<?php
	break;
	case "cct-delicious": ?>
		<div class="delicious">
		<span class="partof"><label for="">http://delicious.com/</label></span>
		<span> <input type="text"    tabindex="<?php $this->tab_index();?>" maxlength="255" value="<?php echo esc_attr($data[$field_type]); ?>" class="field text" name="profile_field[<?php echo $field_type; ?>]" id=""></span>
		</div>
	<?php
	break;
	case "cct-flickr": ?>
		<div class="flickr">
		<span class="partof"><label for="">http://flickr.com/photos/</label></span>
		<span><input type="text"   tabindex="<?php $this->tab_index();?>" maxlength="255" value="<?php echo esc_attr($data[$field_type]); ?>" class="field text" name="profile_field[<?php echo $field_type; ?>]" id=""></span>
		</div>
	<?php
	break;
	case "cct-google-plus": ?>
		<div class="google-plus">
		<span class="partof"><label for="">http://plus.googe.com/</label></span>
		<span><input type="text"   tabindex="<?php $this->tab_index();?>" maxlength="255" value="<?php echo esc_attr($data[$field_type]); ?>" class="field text" name="profile_field[<?php echo $field_type; ?>]" id=""></span>
		</div>
	<?php
	break;	
	default:
		echo "default";
	break;
}