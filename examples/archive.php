<?php
/*
 * Archive template (All profiles)
 * The only modification here is the addition at line 14 which outputs the filter/search form directly below the archive title
 * The form will not be shown on archive pages that are not showing profile_cct posts.
 */

get_header(); ?>

	<div id="content" class="hfeed content">

		<?php hybrid_before_content(); // Before content hook ?>

		<div class="archive-info hentry">

			<h1 class="archive-title"><?php _e( 'People', 'hybrid' ); ?></h1>
			
			<?php do_action("profile_cct_display_archive_controls"); ?>
			
			<?php /* we should be able to define this in the settings 
			<div class="archive-description">
				<p>
				<?php _e( 'You are browsing the site archives.', 'hybrid' ); ?>
				</p>
			</div><!-- .archive-description -->
			*/ ?> 
		</div><!-- .archive-info -->

		<?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>

			<div id="post-<?php the_ID(); ?>" class="<?php hybrid_entry_class(); ?>">

				<div class="profile-summary">
					<?php the_excerpt(); ?>
				</div><!-- .entry-summary -->

			</div><!-- .hentry -->

			<?php endwhile; ?>

		<?php else: ?>

			<p class="no-data">
				<?php _e( 'Apologies, but no results were found.', 'hybrid' ); ?>
			</p><!-- .no-data -->

		<?php endif; ?>

		<?php hybrid_after_content(); // After content hook ?>

	</div><!-- .content .hfeed -->

<?php get_footer(); ?>