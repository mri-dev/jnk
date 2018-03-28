<?php
  $travel = new Travel($post);
  $title = $post->post_title;
?>
<div class="fusion-page-title-captions single-post-caption post-caption-utazas">
  <div class="titledata">
    <h1><?php echo $title; ?></h1>
    <div class="meta">
      <div class="cats">
        <i class="fa fa-map-pin"></i>
        <?php echo implode(", ", $travel->showDestinations()); ?>
      </div>
    </div>
  </div>
</div>
