<div class="newest-article">
  <div class="head">
    <i class="far fa-newspaper"></i> <?php echo __('Legfrissebb bejegyzés',TD); ?>
  </div>
  <article>
    <?php
      $post = $posts[0];
      $id = $post->ID;
      $title = $post->post_title;
      $url = get_permalink($post);
      $date = get_the_date('Y. F j.', $id);
      $cats = wp_get_post_categories($id, array('fields' => 'all_with_object_id'));
      $image = get_the_post_thumbnail_url($post);
      $exc = get_the_excerpt($post);
    ?>
    <?php if ($image): ?>
    <div class="image">
      <a href="<?=$url?>"><img src="<?=$image?>" alt="<?=$title?>"></a>
    </div>
    <?php endif; ?>
    <div class="data">
      <div class="title">
        <a href="<?=$url?>"><?=$title?></a>
      </div>
      <div class="desc">
        <?php echo $exc; ?>
      </div>
      <div class="meta">
        <span class="time"><i class="far fa-clock"></i> <?=$date?></span>
      </div>
    </div>
  </article>
</div>
<div class="more-articles">
  <div class="head">
   <i class="far fa-lightbulb"></i> <?php echo __('További bejegyzések',TD); ?>
  </div>
<?php
  $i = 0;
  foreach ( $posts as $post ): $i++; if($i == 1) continue;
    $id = $post->ID;
    $title = $post->post_title;
    $url = get_permalink($post);
    $date = get_the_date('Y. F j.', $id);
    $cats = wp_get_post_categories($id, array('fields' => 'all_with_object_id'));
    $image = get_the_post_thumbnail_url($post);
    $image = (!$image) ? IFROOT . '/images/no-article-img.jpg' : $image;
?>
  <article>
    <?php if ($image): ?>
    <div class="image">
      <a href="<?=$url?>"><img src="<?=$image?>" alt="<?=$title?>"></a>
    </div>
    <?php endif; ?>
    <div class="data">
      <div class="title">
        <a href="<?=$url?>"><?=$title?></a>
      </div>
      <div class="meta">
        <span class="time"><i class="far fa-clock"></i> <?=$date?></span>
      </div>
    </div>
  </article>
<?php endforeach; ?>
</div>
