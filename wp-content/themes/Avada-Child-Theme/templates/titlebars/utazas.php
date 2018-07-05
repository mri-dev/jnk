<?php
  $travel = new Travel($post);
  $title = $post->post_title;
  $kiemelt = $travel->isKiemelt();
  $egyeni_utazas = $travel->isEgyeni();
  $stars = $travel->getHotelStars();
  $hotel_type = $travel->getHotelType();
?>
<div class="fusion-page-title-captions single-post-caption post-caption-utazas">
  <div class="titledata">
    <h1><div class="t"><?php echo $title; ?></div><? if($egyeni_utazas): ?>
    <?php if ($stars): ?>
      <span class="stars"><?php echo str_repeat('<i class="fa fa-star"></i>', $stars); ?></span>
    <?php endif; ?>
    <? endif; ?></h1>
    <div class="clr clearfix fusion-clear"></div>
    <div class="meta">
      <?php if ($kiemelt): ?>
      <div class="kiemelt">
        <?php if ($egyeni_utazas): ?>
        <?php echo __('Kiemelt szálláshely',TD); ?>
        <?php else: ?>
        <?php echo __('Kiemelt utazás',TD); ?>
        <?php endif; ?>
      </div>
      <?php endif; ?>
      <div class="positions">
        <i class="fa fa-map-pin"></i>
        <?php echo implode(", ", $travel->showDestinations()); ?>
      </div>
    </div>
  </div>
</div>
