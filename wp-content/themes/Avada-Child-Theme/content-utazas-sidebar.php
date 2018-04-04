<?php
global $travel;

$destionations = $travel->showDestinations();
$durations = $travel->showDuration();
$discount = $travel->getDiscount();
$kiemelt = $travel->isKiemelt();
$price_comment = $travel->getPriceComment();
?>
<div class="sidebar-fix-holder">
  <div class="utazas-sidebar">
    <?php if ($discount): ?>
    <div class="discount">
      <?php echo $discount['percent'].__('% leárazás',TD); ?>
    </div>
    <?php endif; ?>
    <div class="sidebar-header">
      <div class="swrapper">
        <div class="price">
          <?php if ($discount): ?>
          <span class="old">
            <?=number_format($travel->getOriginalPrice(), 0, '', ' ')?> <?=get_valuta()?>
          </span>
          <?php endif; ?>
          <span class="current"><?=number_format($travel->getPrice(), 0, '', ' ')?> <?=get_valuta()?><?=($price_comment)?'<sup>*</sup>':''?></span>
        </div>
      </div>
    </div>
    <div class="sidebar-content">
      <div class="title">
        <h2><?php echo __('Ajánlatkérés', TD); ?></h2>
      </div>
      <div class="header">
        <h3><?php echo __('Személyes adatok', TD); ?></h3>
      </div>
      <div class="swrapper">
        Személyes adatok
      </div>
      <div class="header">
        <h3><?php echo __('Igények', TD); ?></h3>
      </div>
      <div class="swrapper">
        Igények
      </div>
    </div>
    <div class="sidebar-footer">
      <div class="price-info">
        <?php echo __('A feltüntetett árak tájékoztató jellegűek, tartalmazzák az ÁFA-t.', TD); ?>
      </div>
      <?php if ($price_comment): ?>
      <div class="price-comment">
        * <?php echo $price_comment; ?>
      </div>
      <?php endif; ?>
    </div>
  </div>
</div>
