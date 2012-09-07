<?php 
$updated = "";
if ( !empty($_POST) && wp_verify_nonce($_POST['_wpnonce'],"save_order") ):
	// save the order 
	foreach( $_POST['profile_order'] as $id => $profile ):
		$post["menu_order"] = $profile['menu_order'];
		$post["ID"] = $id;
		wp_update_post($post);
	endforeach;
	$updated = '
	<div id="message" class="updated below-h2">
		<p> Menu order updated.</p>
	</div>';
endif;
?>
<div class="wrap">
	<h2>Order Profiles</h2>
	<?php echo $updated; ?>
	<ul class="subsubsub">
		<li >Quick Sort : </li>
		<li ><a href="#first" id="sort-first">First Name</a> |</li>
		<li ><a href="#last" id="sort-last">Last Name</a></li>
	</ul> 
	<div class="tablenav top">
	<form method="get" action="<?php echo admin_url( 'edit.php' ); ?>" >
	<input  type="hidden" name="post_type" value="profile_cct" />
	<input  type="hidden" name="page" value="order_profiles" />
	<?php $taxonomys = get_option( 'Profile_CCT_taxonomy');
		if(!is_array($taxonomys))
			$taxonomys = array();
			$cat = null;
			$taxonomy_names = array();
		foreach( $taxonomys as $tax ):
				
				$taxonomy = profile_cct_taxonomy_id( $tax['single'] );
				$taxonomy_names[] = $taxonomy;
				
				if( is_integer( (int)$_GET[$taxonomy] ))
					$cat = (int)$_GET[$taxonomy];
				$dropdown_options = array(
					'show_option_all' => __( 'View all '.$tax["plural"] ),
					'hide_empty' => 0,
					'hierarchical' => $tax["hierarchical"],
					'show_count' => 0,
					'taxonomy' => $taxonomy,
					'orderby' => 'name',
					'selected' => $cat,
					'name' => $taxonomy
				);
				wp_dropdown_categories( $dropdown_options );
		endforeach;
	?>
	<input type="submit" value="Filter" class="button-secondary" id="post-query-submit" name="" />
	</form>
	</div>
	
	<form action="<?php echo admin_url( 'edit.php?post_type=profile_cct&page=order_profiles' ); ?>" method="post" >
	<?php wp_nonce_field( "save_order"  ) ?>
	<table cellspacing="0" class="widefat">
		<thead><tr>
		<th class="order-head">Order</th>
		<th>Profile</th>
		</tr></thead>
	</table>
	<div id="profile-items" >
	<?php 
	$tax_query = null;
	foreach( $_GET as $item => $value ):
		
		if( in_array( $item, $taxonomy_names ) && is_integer((int)$value) && (int)$value > 0 ):
			
			$tax_query[] = array(
							'taxonomy' => $item,
							'field' => 'id',
							'terms' => (int)$value
						);
		endif;
		
	endforeach;
	
	$args = array(
		'post_type' 	=> 'profile_cct',
		'post_status' 	=> array( 'publish','pending','draft' ),
		'posts_per_page'=> -1,
		'orderby' 		=> 'menu_order',
		'order'			=> 'ASC',
		'tax_query'		=> $tax_query
	);
	
	$the_query = new WP_Query( $args );
	global $post;
	// The Loop
	if( $the_query->have_posts() ):
	while ( $the_query->have_posts() ) : $the_query->the_post();
		$status = '';
		if($post->post_status != "publish" )
			$status = '<strong> - '.$post->post_status.'</strong>';
	?>
	
		<div class="profile-item" id="profile-item-<?php the_ID(); ?>">
			<?php echo profile_cct_get_the_post_thumbnail( $post->ID, array(30,30) ); ?>
			<div class="menu_order"> 
				<input type="text" value="<?php echo $post->menu_order; ?>" name="profile_order[<?php the_ID(); ?>][menu_order]" id="profile_order[<?php the_ID(); ?>][menu_order]" class="menu_order_input">
			</div>
			
			<span class="name"><?php echo edit_post_link( get_the_title()) ; echo $status; ?> </span>
			<input type="hidden" name="profile-id" value="<?php the_ID(); ?>" id="profile-<?php the_ID(); ?>">
			
			
		</div>
	
	<?php
	endwhile;
	else:
		?><p>No Profiles were found.</p><?php
	endif;

	// Reset Post Data
	wp_reset_postdata();
	?>
	</div>
	<p class="ml-submit">
		<input type="submit"  value="Save Order" class="button-primary" id="save-all" name="save" />
	</p>
	</form>
</div>