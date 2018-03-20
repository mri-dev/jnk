<?php
  global $post;
  $single = is_single();
  $image = get_the_post_thumbnail_url($post);
?>
<div class="fusion-page-title-bar fusion-page-title-bar-<?php echo $content_type; ?> fusion-page-title-bar-<?php echo $alignment; ?>" style="<?=($single && $image)?'background-image:url(\''.$image.'\');':''?>">
	<div class="fusion-page-title-row">
		<div class="fusion-page-title-wrapper">
      <?php if ($single): ?>
        <?php
          $cats = wp_get_post_categories($post->ID, array('fields' => 'all_with_object_id'));
          $tags = wp_get_post_tags($post->ID, array('fields' => 'all_with_object_id'));
          $comments_nums = wp_count_comments($post->ID);
        ?>
        <div class="fusion-page-title-captions single-post-caption">
          <div class="date">
            <div class="day">
              <?php echo get_the_date('j.'); ?>
            </div>
            <?php echo get_the_date('Y/m'); ?>
          </div>
          <div class="titledata">
            <h1><?php echo $title; ?></h1>
            <div class="meta">
              <div class="cats">
                <i class="far fa-folder"></i>
                <?php if ($cats): ?>
                  <?php foreach ( $cats as $cat ): ?>
                    <span><a href="/category/<?=$cat->slug?>"><strong><?=$cat->name?></strong></a></span>
                  <?php endforeach; ?>
                <?php endif; ?>
              </div>
              <?php if ($tags): ?>
              <div class="tags">
                <i class="fas fa-hashtag"></i>
                <?php foreach ( $tags as $tag ): ?>
                  <span><a href="/tag/<?=$tag->slug?>"><strong><?=$tag->name?></strong></a></span>
                <?php endforeach; ?>                
              </div>
              <?php endif; ?>
              <div class="comments">
                <i class="far fa-comment"></i> <?php echo $comments_nums->approved; ?>
              </div>
            </div>
          </div>
        </div>
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
