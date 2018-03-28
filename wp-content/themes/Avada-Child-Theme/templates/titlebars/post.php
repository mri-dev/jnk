<?php
  $title = $post->post_title;
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
