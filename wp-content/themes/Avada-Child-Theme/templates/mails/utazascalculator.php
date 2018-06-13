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
  }
  .calculation-price{
    background: #3ace8b;
    padding: 15px;
    color: white;
    font-size: 16px;
  }
  .calculation-table{
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

</style>
</head>
<body>
  <header>
    <img src="http://jnk.ideafontana.eu/wp-content/uploads/2018/02/jnn_logo_hor-x2.png" alt="Jó Napot Nagyvilág">
  </header>
  <div class="mail-box">
    <h2>Utazási termék</h2>
    <div class="group">
      <div class="travel-term">
        termék
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
        <?=$calculator['selected_date']['onday']?>, <?=$calculator['selected_date']['travel_weekday']?> - <?=$calculator['selected_date']['durration']['nights']?> éjszaka.
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
        <strong><?=$calculator['selected_room']['title']?></strong><br>
        <small><?=$calculator['selected_room']['description']?></small>
      </div>
    </div>
    <h2>Ár összesítő táblázat</h2>
    <div class="group">
      <div class="calculation-table">
        asd
      </div>
    </div>
    <div class="group">
      <div class="calculation-price">
        Kalkulált ár: <?=$calculator['final_calc_price']?> Ft
      </div>
    </div>
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
