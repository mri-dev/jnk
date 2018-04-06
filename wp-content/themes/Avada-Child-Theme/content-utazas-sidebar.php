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
          <div class="c">
            <?php echo __('Legkedvezőbb alapár', TD); ?>:
          </div>
          <?php if ($discount): ?>
          <span class="old">
            <?=number_format($travel->getOriginalPrice(), 0, '', ' ')?> <?=get_valuta()?>
          </span>
          <?php endif; ?>
          <span class="current"><?=number_format($travel->getPrice(), 0, '', ' ')?> <?=get_valuta()?><?=($price_comment)?'<sup>*</sup>':''?></span>
        </div>
      </div>
    </div>
    <div class="getoffer">
      <a href="javascript:void(0);" data-scrollTarget="getoffer"><?php echo __('Ajánlatot kérek', TD); ?> <i class="far fa-arrow-alt-circle-right"></i></a>
    </div>
  </div>
</div>
