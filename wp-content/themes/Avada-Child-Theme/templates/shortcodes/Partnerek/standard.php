<?php
  $image = get_the_post_thumbnail_url($partner->ID);
  $url = get_post_permalink($partner->ID);
?>
<article class="partner">
  <div class="wrapper">
    <div class="image">
      <a href="<?=$url?>"><img src="<?=($image) ? $image : IMG.'/no-travel-img.jpg'?>" alt="<?=$partner->post_title?>"></a>
    </div>
    <div class="datas">
      <div class="title">
        <h3><a title="<?=$partner->post_title?>" href="<?=$url?>"><?=$partner->post_title?></a></h3>
      </div>
      <div class="desc">
        <?=get_the_excerpt($partner->ID)?>
      </div>
    </div>
  </div>
</article>
