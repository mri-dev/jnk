<?php
  global $post;
  $image = get_the_post_thumbnail_url($post);
  $cats = wp_get_post_categories($post->ID, array('fields' => 'all_with_object_id'));
  $pf = get_post_format($post->ID);
  $isquote = ($pf == 'quote') ? true : false;
  $is_tag = is_tag();
?>
<article <?php post_class(); ?>>
  <?php if ($image && !$isquote): ?>
  <div class="image">
    <a href="<?php the_permalink(); ?>"><img src="<?php echo $image; ?>" alt="<?php the_title(); ?>"></a>
  </div>
  <?php endif; ?>
  <div class="datas">
    <?php if (!$isquote): ?>
    <div class="metas">
      <div class="author"><i class="far fa-file-alt"></i> <strong><?php the_author(); ?></strong></div>
      <?php if ($is_tag): ?>
      <div class="cats">
        <i class="far fa-folder"></i>
        <?php if ($cats): ?>
          <?php foreach ( $cats as $cat ): ?>
            <span><a href="/category/<?=$cat->slug?>"><strong><?=$cat->name?></strong></a></span>
          <?php endforeach; ?>
        <?php endif; ?>
      </div>
      <?php endif; ?>
    </div>
    <?php endif; ?>
    <?php if (!$isquote): ?>
    <div class="title">
      <h2><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
    </div>
    <?php endif; ?>
    <div class="desc">
      <?php if ($isquote): ?>
        <div class="quote-left">
          <i class="fa fa-quote-left"></i>
        </div>
        <a href="<?php the_permalink(); ?>"><?php the_content(); ?></a>
        <div class="quote-right">
          <i class="fa fa-quote-right"></i>
        </div>
        <div class="by">
          &mdash; <?php the_title(); ?>
        </div>
      <?php else: ?>
        <?php the_excerpt(); ?>
      <?php endif; ?>
    </div>
    <?php if ( !$isquote ): ?>
    <div class="buttons">
      <a href="<?php the_permalink(); ?>" class="read-more"><?php echo __('Részletek', TD); ?></a>
    </div>
    <?php endif; ?>
    <div class="clear fusion-clear"></div>
  </div>
</article>
