<?php
get_header(); ?>

	<section id="primary" class="content-area">
		<div id="content" class="articles tag-list">
			<?php
				$current_cat = get_the_category();
			?>
			<div class="cat-header">
				<div class="count-post">
					<?php echo sprintf(__('%d db bejegyzést találtunk a(z) <strong>%s</strong> címke szerint:', TD), $current_cat[0]->count, single_tag_title('', false)); ?>
				</div>
			</div>

			<?php if ( have_posts() ) : ?>

			<?php
					// Start the Loop.
					while ( have_posts() ) : the_post();
            get_template_part( 'content', 'blog' );
					endwhile;
					// Previous/next page navigation.

				else :
					// If no content, include the "No posts found" template.
					get_template_part( 'content', 'none' );

				endif;

        wp_reset_postdata();
			?>
		</div><!-- #content -->
    <?php get_template_part( 'content', 'blog-sidebar' ); ?>
	</section><!-- #primary -->

<?php
get_footer();
