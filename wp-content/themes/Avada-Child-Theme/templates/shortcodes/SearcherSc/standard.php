<?php
$destionations = $travel->showDestinations();
$durations = $travel->showDuration();
$discount = $travel->getDiscount();
$kiemelt = $travel->isKiemelt();
?>
<article class="travel">
  <div class="wrapper">
    <div class="badges">
    <?php if ($kiemelt): ?>
      <div class="highlighted">
        <?php echo __('Kiemelt',TD); ?>
      </div>
    <?php endif; ?>
    <?php if ($discount): ?>
      <div class="discounted">
        <?php echo $discount['percent'].__('% leárazás',TD); ?>
      </div>
    <?php endif; ?>
    </div>
    <div class="image">
      <?php $image = $travel->Image(); ?>
      <a title="<?=$travel->Title()?>" href="<?=$travel->Url()?>"><img src="<?=($image) ? $image : IMG.'/no-travel-img.jpg'?>" alt="<?=$travel->Title()?>"></a>
    </div>
    <div class="datas">
      <div class="title">
        <h3><a title="<?=$travel->Title()?>" href="<?=$travel->Url()?>"><?=$travel->Title()?></a></h3>
      </div>
      <div class="price">
        <?php if ((int)$travel->getPrice() !== 0): ?>
          <?php if ($discount): ?>
          <div class="old">
            <?=number_format($travel->getOriginalPrice(), 0, '', ' ')?> <?=get_valuta()?>
          </div>
          <?php endif; ?>
          <div class="current">
            <?=number_format($travel->getPrice(), 0, '', ' ')?> <?=get_valuta()?>
          </div>
        <?php else: ?>
          <div class="current">
            Egyedi árazás
          </div>
        <?php endif; ?>
      </div>
      <div class="position">
        <i class="fa fa-map-pin"></i>
        <?php if (count($destionations) > 5): ?>
          <span title="<?php echo implode(', ', $destionations); ?>"><?php echo sprintf(__('%d úti célt érint', TD), count($destionations)); ?></span>
        <?php else: ?>
          <?php echo implode(', ', $destionations); ?>
        <?php endif; ?>
      </div>
      <div class="duration">
        <i class="far fa-clock"></i> <?php if (count($durations) > 5): ?>
          <span title="<?php echo implode(', ', $durations); ?>"><?php echo sprintf(__('%d utazási hossz elérhető', TD), count($durations)); ?></span>
        <?php else: ?>
          <?php echo implode(', ', $durations); ?>
        <?php endif; ?>
      </div>
    </div>
  </div>
</article>