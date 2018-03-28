<?php
  global $post;
  $single = is_single();
  $image = get_the_post_thumbnail_url($post);
  $post_type = get_post_type();
?>
<div class="fusion-page-title-bar fusion-page-title-bar-<?php echo $content_type; ?> fusion-page-title-bar-<?php echo $alignment; ?>" style="<?=($single && $image)?'background-image:url(\''.$image.'\');':''?>">
	<div class="fusion-page-title-row">
		<div class="fusion-page-title-wrapper">
      <?php if ($single): ?>
        <?php get_template_part('templates/titlebars/'.$post_type); ?>
      <?php else: ?>

        <div class="fusion-page-title-captions">
          <?php if ( $title ) : ?>
            <?php // Add entry-title for rich snippets ?>
            <?php $entry_title_class = ( Avada()->settings->get( 'disable_date_rich_snippet_pages' ) ) ? ' class="entry-title"' : ''; ?>
            <h1<?php echo $entry_title_class; ?>><?php echo $title; ?></h1>

            <?php if ( $subtitle ) : ?>
              <h3><?php echo $subtitle; ?></h3>
            <?php endif; ?>
          <?php endif; ?>

          <?php if ( 'center' == $alignment ) : // Render secondary content on center layout ?>
            <?php if ( 'none' != fusion_get_option( 'page_title_bar_bs', 'page_title_breadcrumbs_search_bar', $post_id ) ) : ?>
              <div class="fusion-page-title-secondary"><?php echo $secondary_content; ?></div>
            <?php endif; ?>
          <?php endif; ?>
        </div>

        <?php if ( 'center' != $alignment ) : // Render secondary content on left/right layout ?>
          <?php if ( 'none' != fusion_get_option( 'page_title_bar_bs', 'page_title_breadcrumbs_search_bar', $post_id ) ) : ?>
            <div class="fusion-page-title-secondary"><?php echo $secondary_content; ?></div>
          <?php endif; ?>
        <?php endif;?>

      <?php endif; ?>
		</div>
	</div>
</div>
