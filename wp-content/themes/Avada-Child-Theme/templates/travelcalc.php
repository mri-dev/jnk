<div class="offer-group done">
  <div class="progline"></div>
  <div class="header">
    <div class="n"><div class="c">1</div></div>
    <div class="title">
      <h3><?=__('Időpont kiválasztása', TD)?></h3>
      <?=__('Válassza ki az utazás idejét az elérhető időpontok közül.', TD)?>
    </div>
  </div>
  <div class="cholder">
    <div class="wrapper">
      Tartalom
      <br><br><br><br><br><br><br><br><br>
    </div>
  </div>
</div>
<div class="offer-group">
  <div class="progline"></div>
  <div class="header">
    <div class="n"><div class="c">2</div></div>
    <div class="title">
      <h3><?=__('Ellátások', TD)?></h3>
      <?=__('Válassza ki, hogy milyen ellátással szeretné igénybe venni a szolgáltatást.', TD)?>
    </div>
  </div>
  <div class="cholder">
    <div class="wrapper">
      Tartalom
      <br><br><br>
    </div>
  </div>
</div>
<div class="offer-group">
  <div class="progline"></div>
  <div class="header">
    <div class="n"><div class="c">3</div></div>
    <div class="title">
      <h3><?=__('Szobatípus', TD)?></h3>
      <?=__('Válassza ki, hogy melyik szobát szeretné kérni az elérhető kínálatból.', TD)?>
    </div>
  </div>
  <div class="cholder">
    <div class="wrapper">
      Tartalom
      <br><br><br>
    </div>
  </div>
</div>
<div class="offer-group overview">
  <div class="progline"></div>
  <div class="header">
    <div class="n"><div class="c">4</div></div>
    <div class="title">
      <h3><?=__('Ár összesítő', TD)?></h3>
      <?=__('Az itteni listában láthatja, hogy hozzávetőlegesen milyen költségekkel számolhat az Ön által kiválasztott paraméterek alapján.', TD)?>
      <br>
      <?=__('Egyéb szolgáltatások, programok és biztosítások közül tud választani.', TD)?>
    </div>
  </div>
  <div class="cholder">
    <div class="wrapper">
      <table>
        <thead>
          <tr>
            <th><?=__('Tétel', TD)?></th>
            <th><?=__('Egységár', TD)?></th>
            <th><?=__('Egység', TD)?></th>
            <th><?=__('Mennyiség', TD)?></th>
            <th><?=__('Összesen', TD)?></th>
          </tr>
        </thead>
        <tbody>
          <tr>
            <td class="tetel">
              <strong>1. szoba: 1 felnőtt</strong>
            </td>
            <td>12 840 Ft</td>
            <td>/fő/éjszaka</td>
            <td>x7</td>
            <td>89 990 Ft</td>
          </tr>
          <tr>
            <td class="tetel">
              <strong>2. szoba: 2 felnőtt + 1 gyerek</strong>
            </td>
            <td>12 840 Ft</td>
            <td>/fő/éjszaka</td>
            <td>x14</td>
            <td>179 800 Ft</td>
          </tr>
          <tr>
            <td class="tetel opcio">
              <strong>-> 1 gyerek</strong>
            </td>
            <td>8 000 Ft</td>
            <td>/fő/éjszaka</td>
            <td>x7</td>
            <td>56 000 Ft</td>
          </tr>
          <tr>
            <td class="tetel">
              Foglalási díj
            </td>
            <td>1000 Ft</td>
            <td>/fő</td>
            <td>x3</td>
            <td>3 000 Ft</td>
          </tr>
          <tr>
            <td class="tetel">
              Repülőjegy
            </td>
            <td>24 990 Ft</td>
            <td>/fő</td>
            <td>x3</td>
            <td>74 970 Ft</td>
          </tr>
          <tr>
            <td colspan="5" class="price-group">
              <?=__('Választható opcionális szolgáltatások', TD)?>
            </td>
          </tr>
          <tr>
            <td colspan="5" class="price-group">
              <?=__('Fakultatív programok', TD)?>
            </td>
          </tr>
          <tr>
            <td class="tetel">
              <input type="checkbox" id="program_0" checked="checked" disabled="disabled"> <label for="program_0"><?=__('Városnéző túra', TD)?> <span class="label required">kötelező</span> </label>
            </td>
            <td colspan="4">Benne van az árban</td>
          </tr>
          <tr>
            <td class="tetel">
              <input type="checkbox" id="program_1"> <label for="program_1"><?=__('Borkóstoló est', TD)?></label>
              <span class="label info">infó</span>
            </td>
            <td>1 890 Ft</td>
            <td>/ fő</td>
            <td>x1</td>
            <td>1 890 Ft</td>
          </tr>
          <tr>
            <td colspan="5" class="price-group">
              <?=__('Utasbiztosítás', TD)?>
            </td>
          </tr>
          <tr>
            <td class="tetel">
              <input type="radio" name="utasbiztositas" checked="checked" id="utasbizt_no"> <label for="utasbizt_no"><?=__('Nem kérek biztosítást', TD)?></label>
            </td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
          </tr>
          <tr>
            <td class="tetel">
              <input type="radio" name="utasbiztositas" id="utasbizt_yes"> <label for="utasbizt_yes"><?=__('Kérek biztosítást', TD)?></label>
            </td>
            <td>890 Ft</td>
            <td>/ fő / nap</td>
            <td>x8</td>
            <td>7 120 Ft</td>
          </tr>
        </tbody>
      </table>
    </div>
  </div>
</div>
<div class="offer-group last-item">
  <div class="progline"></div>
  <div class="header">
    <div class="n"><div class="c">5</div></div>
    <div class="title">
      <h3><?=__('Adatok megadása', TD)?></h3>
      <?=__('Adja meg személyes adatait az ajánlatkérés véglegesítéséhez.', TD)?>
    </div>
  </div>
  <div class="cholder">
    <div class="wrapper">
      Tartalom
      <br><br><br>
    </div>
  </div>
</div>
