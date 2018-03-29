<?php
global $travel;

$destionations = $travel->showDestinations();
$durations = $travel->showDuration();
$discount = $travel->getDiscount();
$kiemelt = $travel->isKiemelt();
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
          <span class="current"><?=number_format($travel->getPrice(), 0, '', ' ')?> <?=get_valuta()?></span>
        </div>
      </div>
    </div>
    <div class="sidebar-content">
      <div class="swrapper">
        tartalom
      </div>
    </div>
  </div>
</div>
