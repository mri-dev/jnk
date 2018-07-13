<?php
  global $post;
  $image = get_the_post_thumbnail_url($post->ID);
  $terms = wp_get_object_terms( $post->ID, 'partner_kategoria');
?>
<div class="content-inside partner-page-inside <?=($image != '')?'imaged':''?>">
  <div class="wrapper">
    <div class="images">
      <div class="img">
        <img src="<?=$image?>" alt="<?php the_title(); ?>">
      </div>
    </div>
    <div class="datas">
      <div class="title">
        <h1><?php the_title(); ?></h1>
        <div class="nav">
          <ul>
            <li><a href="<? echo get_bloginfo('url', true); ?>"><i class="fa fa-home"></i> <?php echo __('Főoldal', TD); ?> </a></li>
            <li class="space">&nbsp;</li>
            <?php if ($terms): ?>
            <li><i class="far fa-folder"></i> </li>
            <?php foreach ($terms as $t): ?>
            <li><a href="<?=get_term_link($t)?>"><?=$t->name?></a></li>
            <?php endforeach; ?>
            <?php endif; ?>
          </ul>
        </div>
      </div>
      <div class="excerpt">
        <?php the_excerpt(); ?>
      </div>
      <div class="description">
        <?php the_content(); ?>
      </div>
    </div>
  </div>
</div>
<div class="navigation"><p><?php posts_nav_link(); ?></p></div>
