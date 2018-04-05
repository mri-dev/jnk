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
        <h3><?php echo __('Igények megadása', TD); ?></h3>
      </div>
      <div class="swrapper">
        <div class="flex spaced auto">
          <div class="inp">
            <label for="adults"><?=__('Felnőttek', TD)?></label>
            <select class="" id="adults" name="">
              <? for($i = 1; $i <= 20; $i++): ?>
              <option value="<?=$i?>"><?=$i?></option>
              <? endfor; ?>
            </select>
          </div>
          <div class="inp">
            <label for="children"><?=__('Gyerekek', TD)?></label>
            <select class="" id="children" name="">
              <? for($i = 0; $i <= 20; $i++): ?>
              <option value="<?=$i?>"><?=$i?></option>
              <? endfor; ?>
            </select>
          </div>
        </div>
        <div class="inp">
          <label><?=__('Programok', TD)?></label>
          ...
        </div>
        <div class="inp">
          <label><?=__('Időpont', TD)?></label>
          ...
        </div>
        <div class="flex spaced auto">
          <div class="inp">
            <input type="checkbox" id="biztositas"> <label for="biztositas"><?=__('Biztosítás kérése', TD)?></label>
          </div>
          <div class="inp">
            <input type="checkbox" id="irodasegitseg"> <label for="irodasegitseg"><?=__('Segítség kérése', TD)?></label>
          </div>
        </div>
        <div class="inp">
          <label><?=__('Megjegyzés', TD)?></label>
          <textarea placeholder="<?=__('Írja le speciális igényeit', TD)?>"></textarea>
        </div>
      </div>
      <div class="header">
        <h3><?php echo __('Kapcsolat adatok', TD); ?></h3>
      </div>
      <div class="swrapper">
        <div class="inp">
          <input type="text" placeholder="* <?=__('Név', TD)?>">
        </div>
        <div class="flex spaced">
          <div class="inp flex-w-50">
            <input type="text" placeholder="* <?=__('E-mail', TD)?>">
          </div>
          <div class="inp flex-w-50">
            <input type="text" placeholder="* <?=__('E-mail újra', TD)?>">
          </div>
        </div>
        <div class="inp">
          <input type="text" placeholder="<?=__('Telefonszám', TD)?>">
        </div>
        <div class="inp">
          <input type="text" placeholder="<?=__('Lakcím', TD)?>">
        </div>
      </div>
      <div class="header">
        <h3><?php echo __('Összesítő', TD); ?></h3>
      </div>
      <div class="swrapper">
        <div class="calc-result">
          <?=__('Az utazás kalkulált költsége', TD)?>:
          <div class="cash">
            89900 Ft
          </div>
          <div class="taj">
            (<?=__('tájékoztató jellegű', TD)?>)
          </div>
        </div>
        <div class="aszfcheck">
          <input type="checkbox" id="aszfcheck"> <label for="aszfcheck"><?=__('Igényem elküldésével elfogadom az ÁSZF-ben foglaltakat.', TD)?></label>
        </div>
        <button type="button" class="requestOrder"><?=__('Igény elküldése', TD)?></button>
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
