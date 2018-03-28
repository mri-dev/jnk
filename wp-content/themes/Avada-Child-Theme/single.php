<?php
global $travel;
get_header(); ?>
	<section id="primary" class="content-area">
		<div id="content" class="article">
			<?php
				$current_cat = get_the_category();
			?>
			<?php if ( have_posts() ) : ?>

			<?php
					// Start the Loop.
					while ( have_posts() ) :
						the_post();
						$post_type = get_post_type();
						$sub = ($post_type == 'utazas') ? 'single-utazas' : 'single';

						if ( $post_type == 'utazas' ) {
							$travel = new Travel($post);
						}
            get_template_part( 'content', $sub );
					endwhile;
					// Previous/next page navigation.

				else :
					// If no content, include the "No posts found" template.
					get_template_part( 'content', 'none' );

				endif;

        wp_reset_postdata();
			?>
		</div><!-- #content -->
    <?php
			if ( $post_type != 'utazas' ) {
				get_template_part( 'content', 'blog-sidebar' );
			}
		?>
	</section><!-- #primary -->

<?php
get_footer();
