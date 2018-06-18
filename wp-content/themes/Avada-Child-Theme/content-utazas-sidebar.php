<?php
global $travel;

$destionations = $travel->showDestinations();
$durations = $travel->showDuration();
$discount = $travel->getDiscount();
$kiemelt = $travel->isKiemelt();
$price_comment = $travel->getPriceComment();
$tags = $travel->getTags();
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
          <?php if ( (int)$travel->getPrice() !== 0): ?>
            <div class="c">
              <?php echo __('Legkedvezőbb alapár', TD); ?>:
            </div>
            <?php if ($discount): ?>
            <span class="old">
              <?=number_format($travel->getOriginalPrice(), 0, '', ' ')?> <?=get_valuta()?>
            </span>
            <?php endif; ?>
            <span class="current"><?=number_format($travel->getPrice(), 0, '', ' ')?> <?=get_valuta()?><?=($price_comment)?'<sup>*</sup>':''?></span>
          <?php else: ?>
            <div class="c">
              <?php echo __('Utazás ára', TD); ?>:
            </div>
            <span class="current">Egyedi árazás</span>
            <div class="f">
              <?php echo __('Kérje egyedi ajánlatunkat!', TD); ?>
            </div>
          <?php endif; ?>
        </div>
      </div>
    </div>
    <div class="getoffer">
      <a href="javascript:void(0);" data-scrollTarget="getoffer"><?php echo __('Ajánlatot kérek', TD); ?> <i class="far fa-arrow-alt-circle-right"></i></a>
    </div>
  </div>
  <div class="sidebar-group">
    <h3><?php echo __('Ajánlott utazások', TD); ?></h3>
    Hamarosan...
  </div>
  <?php if ($tags): ?>
  <div class="sidebar-group tag-sidebar">
    <h3><?php echo __('Címkék', TD); ?></h3>
    <div class="tags">
    <?php foreach ($tags as $tag): ?>
      <div class="tag">
        <a href="/utazas/?search=&tag=<?=$tag->slug?>"><?php echo $tag->name; ?></a>
      </div>
    <?php endforeach; ?>
    </div>
  </div>
  <?php endif; ?>
</div>
