<?php
/**
 * Search Template
 *
 * The search template is loaded when a visitor uses the search form to search for something
 * on the site.
 *
 * @package Hybrid
 * @subpackage Template
 */
get_header(); // Loads the header.php template.
?>
<div id="content" class="hfeed content">
	<?php do_atomic( 'before_content' ); // hybrid_before_content ?>
	<?php get_template_part( 'loop-meta' ); // Loads the loop-meta.php template. ?>
	
	<?php 
	if ( get_post_type( get_the_ID() == 'profile_cct' ) ):
		do_action( "profile_cct_display_archive_controls" ); 
	endif;
	?>

	<?php if ( have_posts() ): ?>
		<?php while ( have_posts() ): ?>
			<?php the_post(); ?>
			<div id="post-<?php the_ID(); ?>" class="<?php hybrid_entry_class(); ?>">
				<?php if ( get_post_type( get_the_ID() ) == 'profile_cct' ): ?>
					<div class="profile-summary">
						<?php the_excerpt(); ?>
					</div>
				<?php else: ?>
					<?php
						get_the_image( array( 'meta_key' => 'Thumbnail', 'size' => 'thumbnail' ) ); 
						do_atomic( 'before_entry' ); // hybrid_before_entry 
					?>
					<div class="entry-summary">
						<?php the_excerpt(); ?>
					</div>
					<?php do_atomic( 'after_entry' ); // hybrid_after_entry ?>
				<?php endif; ?>
			</div>
		<?php endwhile; ?>
	<?php else: ?>
		<?php get_template_part( 'loop-error' ); // Loads the loop-error.php template. ?>
	<?php endif; ?>

	<?php do_atomic( 'after_content' ); // hybrid_after_content ?>
</div>

<?php get_footer(); // Loads the footer.php template. ?>