<?php
$destionations = $travel->showDestinations();
$durations = $travel->showDuration();
$discount = $travel->getDiscount();
$kiemelt = $travel->isKiemelt();
$egyeni_utazas = $travel->isEgyeni();
$stars = $travel->getHotelStars();
$hotel_type = $travel->getHotelType();
?>
<article class="travel">
  <div class="wrapper">
    <div class="badges">
    <?php if ($kiemelt): ?>
      <div class="highlighted">
        <?php echo __('Kiemelt',TD); ?>
      </div>
      <div class="fusion-clearfix"></div>
    <?php endif; ?>
    <?php if ($egyeni_utazas): ?>
      <div class="egyeni">
        <?php echo __('Egyéni utazás',TD); ?>
      </div>
      <div class="fusion-clearfix"></div>
      <?php if (!$stars): ?>
      <div class="hotel-type">
        <?php echo $hotel_type->name; ?>
      </div>
      <div class="fusion-clearfix"></div>
      <?php else: ?>
      <div class="hotel-type">
        <?php echo str_repeat('<i class="fa fa-star"></i>', $stars); ?>
      </div>
      <div class="fusion-clearfix"></div>
      <?php endif; ?>
    <?php endif; ?>
    <?php if ($discount): ?>
      <div class="discounted">
        <?php echo $discount['percent'].__('% leárazás',TD); ?>
      </div>
      <div class="fusion-clearfix"></div>
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
            <?=$travel->getPriceBefore()?><?=number_format($travel->getOriginalPrice(), 0, '', ' ')?><?=$travel->getPriceAfter()?>
          </div>
          <?php endif; ?>
          <div class="current">
            <?=$travel->getPriceBefore()?><?=number_format($travel->getPrice(), 0, '', ' ')?><?=$travel->getPriceAfter()?>
          </div>
        <?php else: ?>
          <?php if ($egyeni_utazas): ?>
            <div class="current"></div>
          <?php else: ?>
            <div class="current"><?php echo __('Egyedi ár',TD); ?> </div>
          <?php endif; ?>
        <?php endif; ?>
      </div>
      <div class="position">
        <?php if (count($destionations) > 0 ): ?>
        <i class="fa fa-map-pin"></i>
        <?php if (count($destionations) > 5): ?>
          <span title="<?php echo implode(', ', $destionations); ?>"><?php echo sprintf(__('%d úti célt érint', TD), count($destionations)); ?></span>
        <?php else: ?>
          <?php echo implode(', ', $destionations); ?>
        <?php endif; ?>
        <?php else: ?>
          &nbsp;
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
