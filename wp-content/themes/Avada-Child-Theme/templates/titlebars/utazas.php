<?php
  $travel = new Travel($post);
  $title = $post->post_title;
  $kiemelt = $travel->isKiemelt();
?>
<div class="fusion-page-title-captions single-post-caption post-caption-utazas">
  <div class="titledata">
    <h1><?php echo $title; ?></h1>
    <div class="meta">
      <?php if ($kiemelt): ?>
      <div class="kiemelt">
        <?php echo __('Kiemelt utazás',TD); ?>
      </div>
      <?php endif; ?>
      <div class="positions">
        <i class="fa fa-map-pin"></i>
        <?php echo implode(", ", $travel->showDestinations()); ?>
      </div>
    </div>
  </div>
</div>
