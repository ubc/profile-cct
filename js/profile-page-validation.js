var Profile_CCT_PAGE_Validation = {
	invalid_colour: "red",
	
	on_ready: function() {
		Profile_CCT_PAGE_Validation.register_fields();
	},
	
	register_fields: function(element) {
		var elements;
		
		if ( element == null ) {
			elements = jQuery(".field-shell > .field .field");
		} else {
			elements = element.find(".field");
		}
		
		elements.keypress(Profile_CCT_PAGE_Validation.on_key);
		elements.blur(function() {
			jQuery(this).css("border-color", "#DFDFDF");
		});
		elements.keydown(function() {
			jQuery(this).css("border-color", "#DFDFDF");
		});
		
		elements.css("border-color", "blur");
	},
	
	on_key: function( event ) {
		var new_character = String.fromCharCode(event.originalEvent.charCode);
		var element = jQuery(this);
		
		if ( event.originalEvent.charCode == false ) {
			return true;
		}
		
		if ( ! Profile_CCT_PAGE_Validation.check_length( element, new_character ) ) {
			return false;
		}
		
		if ( ! Profile_CCT_PAGE_Validation.check_regex( element, new_character ) ) {
			element.css("border-color", Profile_CCT_PAGE_Validation.invalid_colour);
			return false;
		}
		
		return true;
	},
	
	check_length: function( element, new_character ) {
		var jumps_to_next_input = element.data("jumps");
		var length_limit = element.data("limit");
		var value_length = element.val().length;
		
		if ( value_length >= length_limit && window.getSelection().toString() == "" ) {
			if ( jumps_to_next_input ) {
				var next_input = element.parent().next().children('.field');
				if ( Profile_CCT_PAGE_Validation.check_regex( next_input, new_character ) ) {
					next_input.val(new_character);
				} else {
					next_input.val("");
					next_input.css("border-color", Profile_CCT_PAGE_Validation.invalid_colour);
				}
				
				next_input.focus();
			}
			
			return false;
		} else {
			return true;
		}
	},
	
	check_regex: function( element, new_character ) {
		var character_regex = element.data("accepts");
		if( !character_regex )
			return new_character;
		var flags = character_regex.replace( /.*\/([gimy]*)$/, '$1' );
		var pattern = character_regex.replace( new RegExp( '^/(.*?)/'+flags+'$' ), '$1' );
		character_regex = new RegExp( pattern, flags );
		
		return character_regex.test(new_character);
		
	},
};

jQuery(document).ready( Profile_CCT_PAGE_Validation.on_ready );