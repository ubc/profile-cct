<?php
class Profile_CCT_Table {
	static function init() {
		add_filter( 'manage_edit-profile_cct_columns',        array( __CLASS__, 'register' ) );
		add_action( 'manage_profile_cct_posts_custom_column', array( __CLASS__, 'display_thumb' ), 10, 2 );
		//add_action( 'manage_profile_cct_posts_custom_column', array( __CLASS__, 'display_last_name' ), 10, 2 );
		
		global $coauthors_plus;
		if ( class_exists('coauthors_plus') && isset( $coauthors_plus ) ) {
			// Show multiple authors in dashboard profile listing
			add_filter ( 'manage_edit-profile_cct_columns',	array( $coauthors_plus, '_filter_manage_posts_columns') );
			// Customize dashboard listing table
			add_filter ( 'manage_edit-profile_cct_columns', array( __CLASS__, 'manage_profile_columns') );
			add_action ( 'manage_profile_cct_posts_custom_column', array( __CLASS__, 'replace_title_column'), 10, 2);
			
			// Hide default author box
			add_filter ( 'add_meta_boxes_profile_cct', 		array( __CLASS__, 'remove_authors_box'), 11 );
			// Add co-author box to users who can create multiple profiles (or manage all profiles - disable)
			add_action ( 'coauthors_plus_edit_authors', 	array( __CLASS__, 'coauthors_plus_edit_authors') );
			add_action ( 'coauthors_meta_box_context', array( __CLASS__, 'coauthors_meta_box_context' ) );
		}
	}

	// Modify profile dashboard table and deactivate link if user is not author to profile
	function manage_profile_columns( $columns ) {
		foreach ($columns as $k => $v) {
			if (strcasecmp($k, "title") != 0) {
				$new_columns[$k] = $v;
			}
			else {
				$new_columns['person-name'] = 'Name';
			}
		}
		return $new_columns;
	}

	function replace_title_column( $column_name, $post_id ) {
		
		if ( strcasecmp($column_name, "person-name") != 0 )
			return;
		
		global $post;
		//print_r ($post);
		$level = 0;
		$have_access = false;
		
		$edit_link = get_edit_post_link( $post->ID );
		$title = _draft_or_post_title();
		$lock_holder = wp_check_post_lock( $post->ID );
		$post_type_object = get_post_type_object( $post->post_type );
		if ( $lock_holder ) {
			$classes .= ' wp-locked';
			$lock_holder = get_userdata( $lock_holder );
		}
		
		$coauthors = get_coauthors( $post_id );
		foreach( $coauthors as $user ) {
			if ( current_user_can('edit_others_profile_cct') || $user->data->ID ==get_current_user_id() ) {
				$have_access = true;
			}
		}
		
		if (!$have_access) {
			echo "<strong>".$title."</strong>";
			if ( $post->post_status != 'trash' ) {
				if ( $lock_holder ) {
					$locked_avatar = get_avatar( $lock_holder->ID, 18 );
					$locked_text = esc_html( sprintf( __( '%s is currently editing' ), $lock_holder->display_name ) );
				} else {
					$locked_avatar = $locked_text = '';
				}

				echo '<div class="locked-info"><span class="locked-avatar">' . $locked_avatar . '</span> <span class="locked-text">' . $locked_text . "</span></div>\n";
			}
		} else {
			$pad = str_repeat( '&#8212; ', $level );
			echo "<strong>";

			if ( $format = get_post_format( $post->ID ) ) {
				$label = get_post_format_string( $format );
				echo '<a href="' . esc_url( add_query_arg( array( 'post_format' => $format, 'post_type' => $post->post_type ), 'edit.php' ) ) . '" class="post-state-format post-format-icon post-format-' . $format . '" title="' . $label . '">' . $label . ":</a> ";
			}

			if ( $post->post_status != 'trash' ) {
				echo '<a class="row-title" href="' . $edit_link . '" title="' . esc_attr( sprintf( __( 'Edit &#8220;%s&#8221;' ), $title ) ) . '">' . $pad . $title . '</a>';
			} else {
				echo $pad . $title;
			}
			_post_states( $post );

			if ( isset( $parent_name ) )
				echo ' | ' . $post_type_object->labels->parent_item_colon . ' ' . esc_html( $parent_name );

			echo "</strong>\n";

			if ( $post->post_status != 'trash' ) {
				if ( $lock_holder ) {
					$locked_avatar = get_avatar( $lock_holder->ID, 18 );
					$locked_text = esc_html( sprintf( __( '%s is currently editing' ), $lock_holder->display_name ) );
				} else {
					$locked_avatar = $locked_text = '';
				}

				echo '<div class="locked-info"><span class="locked-avatar">' . $locked_avatar . '</span> <span class="locked-text">' . $locked_text . "</span></div>\n";
			}

			// if ( ! $this->hierarchical_display && 'excerpt' == $mode && current_user_can( 'read_post', $post->ID ) )
				// the_excerpt();

			$actions = array();
			if ( 'trash' != $post->post_status ) {
				$actions['edit'] = '<a href="' . get_edit_post_link( $post->ID, true ) . '" title="' . esc_attr( __( 'Edit this item' ) ) . '">' . __( 'Edit' ) . '</a>';
				// $actions['inline hide-if-no-js'] = '<a href="#" class="editinline" title="' . esc_attr( __( 'Edit this item inline' ) ) . '">' . __( 'Quick&nbsp;Edit' ) . '</a>';
			}
			if ( current_user_can( 'delete_post', $post->ID ) ) {
				if ( 'trash' == $post->post_status )
					$actions['untrash'] = "<a title='" . esc_attr( __( 'Restore this item from the Trash' ) ) . "' href='" . wp_nonce_url( admin_url( sprintf( $post_type_object->_edit_link . '&amp;action=untrash', $post->ID ) ), 'untrash-post_' . $post->ID ) . "'>" . __( 'Restore' ) . "</a>";
				elseif ( EMPTY_TRASH_DAYS )
					$actions['trash'] = "<a class='submitdelete' title='" . esc_attr( __( 'Move this item to the Trash' ) ) . "' href='" . get_delete_post_link( $post->ID ) . "'>" . __( 'Trash' ) . "</a>";
				if ( 'trash' == $post->post_status || !EMPTY_TRASH_DAYS )
					$actions['delete'] = "<a class='submitdelete' title='" . esc_attr( __( 'Delete this item permanently' ) ) . "' href='" . get_delete_post_link( $post->ID, '', true ) . "'>" . __( 'Delete Permanently' ) . "</a>";
			}
			if ( $post_type_object->public ) {
				if ( in_array( $post->post_status, array( 'pending', 'draft', 'future' ) ) ) {
					//if ( $can_edit_post ) {

						/** This filter is documented in wp-admin/includes/meta-boxes.php */
						$actions['view'] = '<a href="' . esc_url( apply_filters( 'preview_post_link', set_url_scheme( add_query_arg( 'preview', 'true', get_permalink( $post->ID ) ) ) ) ) . '" title="' . esc_attr( sprintf( __( 'Preview &#8220;%s&#8221;' ), $title ) ) . '" rel="permalink">' . __( 'Preview' ) . '</a>';
					//}
				} elseif ( 'trash' != $post->post_status ) {
					$actions['view'] = '<a href="' . get_permalink( $post->ID ) . '" title="' . esc_attr( sprintf( __( 'View &#8220;%s&#8221;' ), $title ) ) . '" rel="permalink">' . __( 'View' ) . '</a>';
				}
			}
// 
			if ( is_post_type_hierarchical( $post->post_type ) ) {


				$actions = apply_filters( 'page_row_actions', $actions, $post );
			} else {

				$actions = apply_filters( 'post_row_actions', $actions, $post );
			}
			
			$action_count = count($actions);
			$i = 0;
			if ($action_count > 0) {
				$out = '<div class="row-actions">';
				foreach ( $actions as $action => $link ) {
					++$i;
					( $i == $action_count ) ? $sep = '' : $sep = ' | ';
					$out .= "<span class='$action'>$link$sep</span>";
				}
				$out .= '</div>';
				echo $out;
			}
			//echo $this->row_actions( $actions );

			get_inline_data( $post );
		  
		}
	} 
	
	// Added co-authors box to users who can "Create multiple profiles" (OR "Manage all profiles")
	function coauthors_plus_edit_authors ($post_types) {
		//return current_user_can('edit_profiles_cct') || current_user_can('edit_others_profile_cct');
		return current_user_can('edit_profiles_cct');
	}

	function coauthors_meta_box_context( $context ) {
		return 'side';
	}
	
	function remove_authors_box() {
		remove_meta_box ( 'authordiv', 'profile_cct', 'side' );
	}
	
	static function register( $columns ) {
		unset($columns);
		
		$columns["cb"]        = '<input type="checkbox" />';
		$columns['thumb']     = __( 'Picture', 'profile_cct' );
		$columns['title']     = __( "Name" );
		//$columns['last_name'] = __( "Last Name" );
		
		$columns['author']    = __( "Author" );     
		$columns['date']      = __( "Date" );
	  
		return $columns;
	}
	
	static function display_thumb( $column_name, $post_id ) {
		if ( 'thumb' != $column_name ) return;
		
		echo get_the_post_thumbnail( $post_id, array( 50 , 50 ) );
	}
	
	function display_last_name( $column_name, $post_id ) {
		if ( 'last_name' != $column_name ) return;
		
		echo get_post_meta( $post_id, 'profile_cct_last_name', true);
	}
}

if ( function_exists( 'add_action' ) && class_exists( 'Profile_CCT_Table' ) ):
	add_action( 'plugins_loaded', array( 'Profile_CCT_Table', 'init' ) );
endif;
