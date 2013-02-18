<?php
/*
 * Archive template (All profiles)
 * The only modification here is the addition at line 13 which outputs the filter/search form directly below the archive title
 * The form will not be shown on archive pages that are not showing profile_cct posts.
 */
get_header();
?>
<div id="content" class="hfeed content">
	<?php hybrid_before_content(); // Before content hook ?>
	<div class="archive-info hentry">
		<h1 class="archive-title"><?php _e( 'People', 'hybrid' ); ?></h1>
		<?php do_action("profile_cct_display_archive_controls"); ?>
	</div>
	<?php if ( have_posts() ): ?>
		<?php while ( have_posts() ): ?>
			<?php echo the_post(); ?>
			<div id="post-<?php the_ID(); ?>" class="<?php hybrid_entry_class(); ?>">
				<div class="profile-summary">
					<?php the_excerpt(); ?>
				</div>
			</div>
		<?php endwhile; ?>
	<?php else: ?>
		<p class="no-data">
			<?php _e( 'Apologies, but no results were found.', 'hybrid' ); ?>
		</p>
	<?php endif; ?>
	<?php hybrid_after_content(); // After content hook ?>
</div>

<?php get_footer(); ?>