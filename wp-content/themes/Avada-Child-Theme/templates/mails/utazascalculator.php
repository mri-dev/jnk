<?php
  $nights = (int)$calculator['nights'];
  $no_rooms = ($calculator['selected_room']['title'] == '') ? true : false;
?>
<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>Új ajánlatkérés</title>
<style media="all">
  body, html{
    position: relative;
    width: 100%;
    height: 100%;
  }

  body {
    background: #f1f1f1;
    color: #4c4c4c;
    font-size: 14px;
    font-family: 'Arial', sans-serif;
    padding: 40px;
  }

  a, a:link, a:visited{
    color: #0b85d5;
  }

  * {
    box-sizing: border-box;
  }

  header{
    text-align: center;
  }

  h1 {
    text-align: center;
  }

  h3 {
    text-transform: uppercase;
    color: black;
    font-weight: bold;
    margin: 0 0 5px 0;
    font-size: 12px;
  }

  h2 {
    font-size: 16px;
    color: #222222;
    margin: 5px 0 10px 0;
    text-align: center;
  }

  .mail-box{
    width: 800px;
    margin: 25px auto;
  }

  .mail-box .group{
    margin: 0 0 15px 0;
  }
  .mail-box .group .option-select{
    background: white;
    padding: 15px;
    font-size: 15px;
    border-radius: 5px;
    color: #0b85d5;
  }
  .calculation-price{
    background: #3ace8b;
    padding: 15px;
    color: white;
    font-size: 18px;
  }
  .calculation-price .price{
    float:right;
    font-weight: bold;
  }
  .calculation-table,
  .travel-term{
    background: white;
    border: 3px solid #eaeaea;
  }

  .passengers{
    display: -webkit-box;
    display: -ms-flexbox;
    display: flex;
    align-items: center;
    flex-wrap: wrap;
    align-items: flex-start;
    margin: -5px;
  }

  .passengers .passenger {
    flex-basis: 50%;
    padding: 5px;
  }
  .passengers .passenger table td {
    font-size: 12px;
  }

  .passengers .passenger .wrapper{
    border: 0.5px solid #e6e6e6;
    padding: 10px;
    background: #ffffff;
  }
  .passengers .passenger.adult .wrapper .head{
    color: #fbb03b;
  }
  .passengers .passenger.child .wrapper .head{
    color: #0b85d5;
  }
  table td {
    padding: 5px;
  }
  .travel-term{
    display: -webkit-box;
    display: -ms-flexbox;
    display: flex;
    align-items: center;
    flex-wrap: nowrap;
    align-items: flex-start;
    padding: 10px;
  }
  .travel-term > .img{
    flex-basis: 200px;
  }
  .travel-term > .img img{
    max-width: 100%;
  }
  .travel-term > .data{
    flex:1;
    padding: 0 0 0 15px;
  }
  .travel-term > .data .title a{
    display: block;
    text-decoration: none;
    font-weight: bold;
    margin: 0 0 10px 0;
    color: #fbb03b;
    font-weight: bold;
    font-size: 18px;
  }
    /* line 1343, sass/base.scss */
  .calculation-table table {
    width: 100%;
    font-size: 12px;
    border-collapse: collapse;
  }
  /* line 1346, sass/base.scss */
  .calculation-table table thead tr th {
    background: #0b85d5;
    color: white;
    padding: 5px;
    font-weight: bold;
    text-transform: uppercase;
    font-size: 0.9em;
    text-align: center;
  }
  /* line 1358, sass/base.scss */
  .calculation-table table tbody tr td {
    padding: 10px;
    text-align: center;
    border-bottom: 0.5px solid #dddddd;
  }
  /* line 1363, sass/base.scss */
  .calculation-table table tbody tr td.tetel {
    text-align: left;
    position: relative;
  }
  /* line 1366, sass/base.scss */
  .calculation-table table tbody tr td.tetel .label {
    background: #8f8f8f;
    color: white;
    padding: 2px 5px;
    line-height: 1;
    font-size: 0.7em;
    border-radius: 7px;
  }
  /* line 1374, sass/base.scss */
  .calculation-table table tbody tr td.tetel .label.info {
    float: right;
    background: #fbb03b;
    cursor: help;
  }
  /* line 1380, sass/base.scss */
  .calculation-table table tbody tr td.tetel.opcio {
    font-size: 0.9em;
    padding-left: 30px;
    box-shadow: inset 20px 0 0 #f1f1f1;
  }
  /* line 1386, sass/base.scss */
  .calculation-table table tbody tr td.price-group {
    text-align: center;
    font-weight: bold;
    color: black;
    padding: 8px;
    background: #f5f5f5;
  }
  /* line 1519, sass/base.scss */
  .calculation-table .priceev {
    font-size: 1.2em;
  }
  /* line 1521, sass/base.scss */
  .calculation-table .priceev .ev {
    text-align: right !important;
  }
  /* line 1524, sass/base.scss */
  .calculation-table .priceev .price {
    text-align: center;
    font-weight: bold;
    color: #3ace8b;
  }

  .calculation-table .tetel .desc{
    color: #acacac;
    font-size: 10px;
  }

  .calculation-table span.req{
    color: #fff;
    font-size: 8px;
    padding: 2px;
    background: #0b85d5;
  }
  .user-alert-msg{
    font-size: 15px;
    color: black;
    margin: 10px 0 25px 0;
    line-height: 1.3;
    font-weight: bold;
  }
  .user-alert-msg h1{
    font-size: 18px;
    text-align: left;
  }

</style>
</head>
<body>
  <header>
    <img src="/wp-content/uploads/2018/08/Logo_pecset_atlatszo_png200.png" alt="Jó Napot Nagyvilág">
  </header>
  <div class="mail-box">
    <?php if ($is_user_alert): ?>
    <div class="user-alert-msg">
      <h1><?=sprintf(__('Tisztelt %s', 'jnk'), $name)?>!</h1>
      <?=__('Köszönjük, hogy érdeklődik szolgáltatásaink iránt! Az előzetes utazás kalkulációját fogadtuk és hamarosan megkezdjük a feldolgozását. Kollégáink hamarosan fel fogják venni Önnel a kapcsolatot!', 'jnk')?>
    </div>
    <?php endif; ?>
    <h2><?=__('Választott utazási termék', 'jnk')?></h2>
    <div class="group">
      <div class="travel-term">
        <div class="img">
          <img src="<?=$spost_img?>" alt="<?=$spost['post_title']?>">
        </div>
        <div class="data">
          <div class="title">
            <a href="<?=$spost_url?>"><?=$spost['post_title']?></a>
          </div>
          <div class="desc">
            <?=$spost_sdesc?>
          </div>
        </div>
      </div>
    </div>
    <h1><?=__('Konfiguráció', 'jnk')?> </h1>
    <div class="group">
      <h3><?=__('Utasok száma', 'jnk')?></h3>
      <div class="option-select">
        <?=$calculator['passengers']['adults']?> <?=__('felnőtt', 'jnk')?> <? if($calculator['passengers']['children'] != 0): ?> + <?=$calculator['passengers']['children']?> <?=__('gyermek', 'jnk')?>.<? endif; ?>
      </div>
    </div>
    <div class="group">
      <h3><?=__('Kiválasztott időpont', 'jnk')?></h3>
      <div class="option-select">
        <?php if ($calculator['egyeni'] == 0): ?>
          <?=$calculator['selected_date']['onday']?>, <?=$calculator['selected_date']['travel_weekday']?> - <?=$nights?> <?=__('éjszaka', 'jnk')?>.
        <?php else: ?>
          <?=__('Egyéni utazás', 'jnk')?>: <?=$calculator['datepicker']['selectedTemplateName']?>, <?=$nights?> <?=__('éjszaka', 'jnk')?>.
        <?php endif; ?>
      </div>
    </div>
    <div class="group">
      <h3><?=__('Kiválasztott ellátás', 'jnk')?></h3>
      <div class="option-select">
        <?=$calculator['selected_ellatas']['ellatas']['name']?>
      </div>
    </div>
    <div class="group">
      <h3><?=__('Kiválasztott szobatípus', 'jnk')?></h3>
      <div class="option-select">
        <?php if ( !$no_rooms ): ?>
          <strong><?=$calculator['selected_room']['title']?></strong><br>
          <small><?=$calculator['selected_room']['description']?></small>
        <?php else: ?>
          <em>(!) <?=__('Nem történt szoba kiválasztás.', 'jnk')?></em>
        <?php endif; ?>
      </div>
    </div>
    <h2><?=__('Ár összesítő táblázat', 'jnk')?></h2>
    <div class="group">
      <div class="calculation-table">
        <table>
          <thead>
            <tr>
              <th><?=__('Tétel', 'jnk')?></th>
              <th><?=__('Egységár', 'jnk')?></th>
              <th><?=__('Egység', 'jnk')?></th>
              <th><?=__('Mennyiség', 'jnk')?></th>
              <th><?=__('Összesen', 'jnk')?></th>
            </tr>
          </thead>
          <tbody>
            <?php if ( !$no_rooms ): ?>
              <tr>
                <td class="tetel">
                  <strong><?=$calculator['selected_room']['title']?></strong>
                </td>
                <td><?=$price_before?><?=number_format((float)$calculator['selected_room']['adult_price'], 0, '', ',')?><?=$price_after?></td>
                <td><?=__('/fő/éjszaka', 'jnk')?></td>
                <td>x<?=($nights * $calculator['passengers']['adults'])?></td>
                <td><?=$price_before?><?=number_format((float)$calculator['roomprice'][$calculator['selected_room']['ID']]['adults'], 0, '', ',')?><?=$price_after?></td>
              </tr>
            <?php else: ?>
              <tr>
                <td colspan="5">
                  <?=__('Az elérhető szobákkal és szállásokkal kapcsolatban ajánlatunkban tájékoztatjuk.', 'jnk')?>
                </td>
              </tr>
            <?php endif; ?>
            <?php if ($calculator['passengers']['children'] != 0): ?>
            <tr>
              <td class="tetel opcio">
                <strong>-> <?=$calculator['passengers']['children']?> <?=__('gyerek', 'jnk')?></strong>
              </td>
              <td><?=$price_before?><?=number_format((float)$calculator['selected_room']['child_price'], 0, '', ',')?><?=$price_after?></td>
              <td><?=__('/fő/éjszaka', 'jnk')?></td>
              <td>x<?=($nights * $calculator['passengers']['children'])?></td>
              <td><?=$price_before?><?=number_format((float)$calculator['roomprice'][$calculator['selected_room']['ID']]['children'], 0, '', ',')?><?=$price_after?></td>
            </tr>
            <?php endif; ?>
            <tr class="priceev">
              <td colspan="4" class="ev"><?=__('Szállás összesen', 'jnk')?>:</td>
              <td class="price"><?=$price_before?><?=number_format((float)$calculator['travel_prices'], 0, '', ',')?><?=$price_after?></td>
            </tr>
            <?php if ( count($calculator['selected_szolgaltatas']) > 0 ): ?>
            <tr>
              <td colspan="5" class="price-group"><?=__('Választott szolgáltatások', 'jnk')?></td>
            </tr>
            <?php foreach ( (array)$calculator['selected_szolgaltatas'] as $item ): ?>
            <tr>
              <td class="tetel">
                <?php if ($item['requireditem'] == 'true'): ?>
                <span class="req" title="<?=__('Kötelező elem', 'jnk')?>">K</span>
                <?php endif; ?>
                <strong><?=$item['title']?></strong>
                <?php if ($item['description'] != ''): ?>
                  <div class="desc">
                    <?=$item['description']?>
                  </div>
                <?php endif; ?>
              </td>
              <td><?=number_format((float)$item['price'], 0, '', ',')?></td>
              <td><?=$item['price_after']?></td>
              <td>x<?=$this->priceCalcMe($item, $calculator['selected_date'], $calculator['passengers'])?></td>
              <td><?=$price_before?><?=number_format($this->priceCalcSum($item, $calculator['selected_date'], $calculator['passengers']), 0, '', ',')?><?=$price_after?></td>
            </tr>
            <?php endforeach; ?>
            <tr class="priceev">
              <td colspan="4" class="ev"><?=__('Szolgáltatások összesen', 'jnk')?>:</td>
              <td class="price"><?=$price_before?><?=number_format((float)$calculator['config_szolgaltatas_prices'], 0, '', ',')?><?=$price_after?></td>
            </tr>
            <?php endif; ?>
            <?php if ( count($calculator['selected_programs']) > 0 ): ?>
            <tr>
              <td colspan="5" class="price-group"><?=__('Választott programok', 'jnk')?></td>
            </tr>
            <?php foreach ( (array)$calculator['selected_programs'] as $item ): ?>
            <tr>
              <td class="tetel">
                <?php if ($item['requireditem'] == 'true'): ?>
                <span class="req" title="<?=__('Kötelező elem', 'jnk')?>">K</span>
                <?php endif; ?>
                <strong><?=$item['title']?></strong>
                <?php if ($item['description'] != ''): ?>
                  <div class="desc">
                    <?=$item['description']?>
                  </div>
                <?php endif; ?>
              </td>
              <td><?=number_format((float)$item['price'], 0, '', ',')?></td>
              <td><?=$item['price_after']?></td>
              <td>x<?=$this->priceCalcMe($item, $calculator['selected_date'], $calculator['passengers'])?></td>
              <td><?=$price_before?><?=number_format($this->priceCalcSum($item, $calculator['selected_date'], $calculator['passengers']), 0, '', ',')?><?=$price_after?></td>
            </tr>
            <?php endforeach; ?>
            <tr class="priceev">
              <td colspan="4" class="ev"><?=__('Programok összesen', 'jnk')?>:</td>
              <td class="price"><?=$price_before?><?=number_format((float)$calculator['config_programok_prices'], 0, '', ',')?><?=$price_after?></td>
            </tr>
            <?php endif; ?>
            <tr>
              <td colspan="5" class="price-group"><?=__('Utasbiztosítás', 'jnk')?></td>
            </tr>
            <?php if ( $calculator['configs']['biztositas'] != '0' ): ?>
            <tr>
              <td><strong><?=__('Kér biztosítást!', 'jnk')?></strong></td>
              <td><?=number_format((float)$calculator['biztositas']['price'], 0, '', ',')?></td>
              <td><?=$item['price_after']?></td>
              <td>x<?=$this->priceCalcMe($calculator['biztositas'], $calculator['selected_date'], $calculator['passengers'])?></td>
              <td><?=$price_before?><?=number_format($this->priceCalcSum($calculator['biztositas'], $calculator['selected_date'], $calculator['passengers']), 0, '', ',')?><?=$price_after?></td>
            </tr>
            <?php else: ?>
            <tr>
              <td colspan="5" style="text-align: center;"><?=__('Nem kért biztosítást!', 'jnk')?></td>
            </tr>
            <?php endif; ?>
          </tbody>
        </table>
      </div>
    </div>
    <div class="group">
      <div class="calculation-price">
        <?php if ( $no_rooms ): ?>
          <?=__('Kalkulált ár', 'jnk')?>: <span class="price"><?=__('Ajánlatban küldjük', 'jnk')?></span>
        <?php else: ?>
          <?=__('Kalkulált ár', 'jnk')?>: <span class="price"><?=$price_before?><?=number_format((float)$calculator['final_calc_price'], 0, '', ',')?><?=$price_after?>*</span>
        <?php endif; ?>
      </div>
    </div>
    <?php if ($is_user_alert && !$no_rooms): ?>
    <small>
      <?=__('* a kalkulált ár tájékoztató jellegű, nem minősül konkrét ajánlatnak. Adatai megadása után kollégáink felveszik Önnel a kapcsolatot és részletes tájékoztatást adnak a kiválasztott utazásról. Az árváltozás jogát fenntartjuk.', 'jnk')?>
    </small>
    <?php endif; ?>
    <br><br>
    <h2><?=__('Kapcsolattartó adatai', 'jnk')?></h2>
    <div class="group">
      <div class="contact">
        <table>
          <tr>
            <td><?=__('Név', 'jnk')?></td>
            <td><strong><?=$calculator['order']['contact']['name']?></strong></td>
          </tr>
          <tr>
            <td><?=__('E-mail', 'jnk')?></td>
            <td><strong><?=$calculator['order']['contact']['email']?></strong></td>
          </tr>
          <tr>
            <td><?=__('Telefon', 'jnk')?></td>
            <td><strong><?=$calculator['order']['contact']['phone']?></strong></td>
          </tr>
        </table>
      </div>
    </div>
    <h2><?=__('Utasok adatai', 'jnk')?></h2>
    <div class="passengers">
      <?php foreach ((array)$calculator['passengers_details']['adults'] as $i => $p): ?>
        <div class="passenger adult">
          <div class="wrapper">
            <div class="head">
              #<?=$i+1?> <?=__('felnőtt adatai', 'jnk')?>:
            </div>
            <table>
              <tr>
                <td><?=__('Név', 'jnk')?></td>
                <td><strong><?=$p['name']?></strong></td>
              </tr>
              <tr>
                <td><?=__('Születési idő', 'jnk')?></td>
                <td><strong><?=$p['dob']?></strong></td>
              </tr>
              <tr>
                <td><?=__('Lakcím', 'jnk')?></td>
                <td><strong><?=$p['address']?></strong></td>
              </tr>
            </table>
          </div>
        </div>
      <?php endforeach; ?>
      <?php foreach ((array)$calculator['passengers_details']['children'] as $i => $p): ?>
        <div class="passenger child">
          <div class="wrapper">
            <div class="head">
              #<?=$i+1?> <?=__('gyermek adatai', 'jnk')?>:
            </div>
            <table>
              <tr>
                <td><?=__('Név', 'jnk')?></td>
                <td><strong><?=$p['name']?></strong></td>
              </tr>
              <tr>
                <td><?=__('Születési idő', 'jnk')?></td>
                <td><strong><?=$p['dob']?></strong></td>
              </tr>
              <tr>
                <td><?=__('Lakcím', 'jnk')?></td>
                <td><strong><?=$p['address']?></strong></td>
              </tr>
            </table>
          </div>
        </div>
      <?php endforeach; ?>

    </div>
    <br>
    <div class="group">
      <h3><?=__('Megjegyzés az ajánlatkéréshez', 'jnk')?>:</h3>
      <?php if ($calculator['order']['comment']): ?>
        <?=$calculator['order']['comment']?>
      <?php else: ?>
        -
      <?php endif; ?>
    </div>
    <br><br>
    <div class="group">
      <?=__('Az ajánlatkérés kalkuláció leadási ideje', 'jnk')?>: <strong><?=date('Y-m-d H:i:s')?></strong>.
    </div>
  </div>
</body>
</html>
