<?php
/*
 * Taxonomy template (When browsing profiles filtered by a taxonomy)
 * The only modification here is the addition at line 14 which outputs the filter/search form directly below the archive title
 */
get_header();
?>

<div id="content" class="hfeed content">
	<?php hybrid_before_content(); // Before content hook ?>
	<div class="archive-info taxonomy-info">
		<h1 class="archive-title taxonomy-title"><?php $term = get_term_by( 'slug', get_query_var( 'term' ), get_query_var( 'taxonomy' ) ); echo $term->name; ?></h1>
		<?php do_action("profile_cct_display_archive_controls"); // Displays the filter/search form ?>
		
		<div class="archive-description taxonomy-description">
			<?php echo term_description( '', get_query_var( 'taxonomy' ) ); ?>
		</div>
	</div>
	<?php if ( have_posts() ): ?>
		<?php while ( have_posts() ): ?>
			<?php the_post(); ?>
			<?php if ( 'profile_cct' == get_post_type() ): ?>
				<div id="post-<?php the_ID(); ?>" class="<?php hybrid_entry_class(); ?>">
					<div class="profile-summary">
						<?php the_excerpt(); ?>
					</div>
				</div>
			<?php else: ?>
				<div id="post-<?php the_ID(); ?>" class="<?php hybrid_entry_class(); ?>">
					<?php get_the_image( array( 'custom_key' => array( 'Thumbnail' ), 'default_size' => 'thumbnail' ) ); ?>
					
					<?php hybrid_before_entry(); // Before entry hook ?>
					<div class="entry-summary entry">
						<?php the_excerpt(); ?>
					</div>
					<?php hybrid_after_entry(); // After entry hook ?>
				</div>
			<?php endif; ?>
		<?php endwhile; ?>
	<?php else: ?>
		<p class="no-data">
			<?php _e( 'Apologies, but no results were found.', 'hybrid' ); ?>
		</p>
	<?php endif; ?>
	<?php hybrid_after_content(); // After content hook ?>
</div>

<?php get_footer(); ?>