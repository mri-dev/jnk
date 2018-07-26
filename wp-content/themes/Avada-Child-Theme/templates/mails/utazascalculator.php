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
    <img src="http://jnk.ideafontana.eu/wp-content/uploads/2018/02/jnn_logo_hor-x2.png" alt="Jó Napot Nagyvilág">
  </header>
  <div class="mail-box">
    <?php if ($is_user_alert): ?>
    <div class="user-alert-msg">
      <h1>Tisztelt <?=$name?>!</h1>
      Köszönjük, hogy érdeklődik szolgáltatásaink iránt! Az előzetes utazás kalkulációját fogadtuk és hamarosan megkezdjük a feldolgozását. Kollégáink hamarosan fel fogják venni Önnel a kapcsolatot!
    </div>
    <?php endif; ?>
    <h2>Választott utazási termék</h2>
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
    <h1>Konfiguráció</h1>
    <div class="group">
      <h3>Utasok száma</h3>
      <div class="option-select">
        <?=$calculator['passengers']['adults']?> felnőtt <? if($calculator['passengers']['children'] != 0): ?> + <?=$calculator['passengers']['children']?> gyermek.<? endif; ?>
      </div>
    </div>
    <div class="group">
      <h3>Kiválasztott időpont</h3>
      <div class="option-select">
        <?php if ($calculator['egyeni'] == 0): ?>
          <?=$calculator['selected_date']['onday']?>, <?=$calculator['selected_date']['travel_weekday']?> - <?=$nights?> éjszaka.
        <?php else: ?>
          Egyéni utazás: <?=$calculator['datepicker']['selectedTemplateName']?>, <?=$nights?> éjszaka.
        <?php endif; ?>
      </div>
    </div>
    <div class="group">
      <h3>Kiválasztott ellátás</h3>
      <div class="option-select">
        <?=$calculator['selected_ellatas']['ellatas']['name']?>
      </div>
    </div>
    <div class="group">
      <h3>Kiválasztott szobatípus</h3>
      <div class="option-select">
        <?php if ( !$no_rooms ): ?>
          <strong><?=$calculator['selected_room']['title']?></strong><br>
          <small><?=$calculator['selected_room']['description']?></small>
        <?php else: ?>
          <em>(!) Nem történt szoba kiválasztás.</em>
        <?php endif; ?>
      </div>
    </div>
    <h2>Ár összesítő táblázat</h2>
    <div class="group">
      <div class="calculation-table">
        <table>
          <thead>
            <tr>
              <th>Tétel</th>
              <th>Egységár</th>
              <th>Egység</th>
              <th>Mennyiség</th>
              <th>Összesen</th>
            </tr>
          </thead>
          <tbody>
            <?php if ( !$no_rooms ): ?>
              <tr>
                <td class="tetel">
                  <strong><?=$calculator['selected_room']['title']?></strong>
                </td>
                <td><?=number_format((float)$calculator['selected_room']['adult_price'], 0, '', ',')?> Ft</td>
                <td>/fő/éjszaka</td>
                <td>x<?=($nights * $calculator['passengers']['adults'])?></td>
                <td><?=number_format((float)$calculator['roomprice'][$calculator['selected_room']['ID']]['adults'], 0, '', ',')?> Ft</td>
              </tr>
            <?php else: ?>
              <tr>
                <td colspan="5">
                  Az elérhető szobákkal és szállásokkal kapcsoaltban ajánlatunkban tájékoztatjuk.
                </td>
              </tr>
            <?php endif; ?>
            <?php if ($calculator['passengers']['children'] != 0): ?>
            <tr>
              <td class="tetel opcio">
                <strong>-> <?=$calculator['passengers']['children']?> gyerek</strong>
              </td>
              <td><?=number_format((float)$calculator['selected_room']['child_price'], 0, '', ',')?> Ft</td>
              <td>/fő/éjszaka</td>
              <td>x<?=($nights * $calculator['passengers']['children'])?></td>
              <td><?=number_format((float)$calculator['roomprice'][$calculator['selected_room']['ID']]['children'], 0, '', ',')?> Ft</td>
            </tr>
            <?php endif; ?>
            <tr class="priceev">
              <td colspan="4" class="ev">Szállás összesen:</td>
              <td class="price"><?=number_format((float)$calculator['travel_prices'], 0, '', ',')?> Ft</td>
            </tr>
            <?php if ( count($calculator['selected_szolgaltatas']) > 0 ): ?>
            <tr>
              <td colspan="5" class="price-group">Választott szolgáltatások</td>
            </tr>
            <?php foreach ( (array)$calculator['selected_szolgaltatas'] as $item ): ?>
            <tr>
              <td class="tetel">
                <?php if ($item['requireditem'] == 'true'): ?>
                <span class="req" title="Kötelező elem">K</span>
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
              <td><?=number_format($this->priceCalcSum($item, $calculator['selected_date'], $calculator['passengers']), 0, '', ',')?> Ft</td>
            </tr>
            <?php endforeach; ?>
            <tr class="priceev">
              <td colspan="4" class="ev">Szolgáltatások összesen:</td>
              <td class="price"><?=number_format((float)$calculator['config_szolgaltatas_prices'], 0, '', ',')?> Ft</td>
            </tr>
            <?php endif; ?>
            <?php if ( count($calculator['selected_programs']) > 0 ): ?>
            <tr>
              <td colspan="5" class="price-group">Választott programok</td>
            </tr>
            <?php foreach ( (array)$calculator['selected_programs'] as $item ): ?>
            <tr>
              <td class="tetel">
                <?php if ($item['requireditem'] == 'true'): ?>
                <span class="req" title="Kötelező elem">K</span>
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
              <td><?=number_format($this->priceCalcSum($item, $calculator['selected_date'], $calculator['passengers']), 0, '', ',')?> Ft</td>
            </tr>
            <?php endforeach; ?>
            <tr class="priceev">
              <td colspan="4" class="ev">Programok összesen:</td>
              <td class="price"><?=number_format((float)$calculator['config_programok_prices'], 0, '', ',')?> Ft</td>
            </tr>
            <?php endif; ?>
            <tr>
              <td colspan="5" class="price-group">Utasbiztosítás</td>
            </tr>
            <?php if ( $calculator['configs']['biztositas'] != '0' ): ?>
            <tr>
              <td><strong>Kér biztosítást!</strong></td>
              <td><?=number_format((float)$calculator['biztositas']['price'], 0, '', ',')?></td>
              <td><?=$item['price_after']?></td>
              <td>x<?=$this->priceCalcMe($calculator['biztositas'], $calculator['selected_date'], $calculator['passengers'])?></td>
              <td><?=number_format($this->priceCalcSum($calculator['biztositas'], $calculator['selected_date'], $calculator['passengers']), 0, '', ',')?> Ft</td>
            </tr>
            <?php else: ?>
            <tr>
              <td colspan="5" style="text-align: center;">Nem kért biztosítást!</td>
            </tr>
            <?php endif; ?>
          </tbody>
        </table>
      </div>
    </div>
    <div class="group">
      <div class="calculation-price">
        <?php if ( $no_rooms ): ?>
          Kalkulált ár: <span class="price">Ajánlatban küldjük</span>
        <?php else: ?>
          Kalkulált ár: <span class="price"><?=number_format((float)$calculator['final_calc_price'], 0, '', ',')?> Ft*</span>
        <?php endif; ?>
      </div>
    </div>
    <?php if ($is_user_alert && !$no_rooms): ?>
    <small>
      * a kalkulált ár tájékoztató jellegű, nem minősül konkrét ajánlatnak. Adatai megadása után kollégáink felveszik Önnel a kapcsolatot és részletes tájékoztatást adnak a kiválasztott utazásról. Az árváltozás jogát fenntartjuk.
    </small>
    <?php endif; ?>
    <br><br>
    <h2>Kapcsolattartó adatai</h2>
    <div class="group">
      <div class="contact">
        <table>
          <tr>
            <td>Név</td>
            <td><strong><?=$calculator['order']['contact']['name']?></strong></td>
          </tr>
          <tr>
            <td>E-mail</td>
            <td><strong><?=$calculator['order']['contact']['email']?></strong></td>
          </tr>
          <tr>
            <td>Telefon</td>
            <td><strong><?=$calculator['order']['contact']['phone']?></strong></td>
          </tr>
        </table>
      </div>
    </div>
    <h2>Utasok adatai</h2>
    <div class="passengers">
      <?php foreach ((array)$calculator['passengers_details']['adults'] as $i => $p): ?>
        <div class="passenger adult">
          <div class="wrapper">
            <div class="head">
              #<?=$i+1?> felnőtt adatai:
            </div>
            <table>
              <tr>
                <td>Név</td>
                <td><strong><?=$p['name']?></strong></td>
              </tr>
              <tr>
                <td>Születési idő</td>
                <td><strong><?=$p['dob']?></strong></td>
              </tr>
              <tr>
                <td>Lakcím</td>
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
              #<?=$i+1?> gyermek adatai:
            </div>
            <table>
              <tr>
                <td>Név</td>
                <td><strong><?=$p['name']?></strong></td>
              </tr>
              <tr>
                <td>Születési idő</td>
                <td><strong><?=$p['dob']?></strong></td>
              </tr>
              <tr>
                <td>Lakcím</td>
                <td><strong><?=$p['address']?></strong></td>
              </tr>
            </table>
          </div>
        </div>
      <?php endforeach; ?>

    </div>
    <br>
    <div class="group">
      <h3>Megjegyzés az ajánlatkéréshez:</h3>
      <?php if ($calculator['order']['comment']): ?>
        <?=$calculator['order']['comment']?>
      <?php else: ?>
        -
      <?php endif; ?>
    </div>
    <br><br>
    <div class="group">
      Az ajánlatkérés kalkuláció leadási ideje: <strong><?=date('Y-m-d H:i:s')?></strong>.
    </div>
  </div>
</body>

</html>
