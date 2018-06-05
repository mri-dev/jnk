<?php
  global $post;
?>
<div class="calc-holder" ng-app="jonapotnagyvilag" ng-controller="TravelCalculator" ng-init="init(<?=$post->ID?>)">
  <div class="calc-loader" ng-show="!loaded">
    <i class="fab fa-telegram-plane"></i><br>
    <?=__('Utazás kalkulátor betöltése folyamatban...', TD)?>
  </div>
  <div class="steps" ng-show="loaded">
    <div class="offer-group passengers" ng-show="(step>=1)" ng-class="(step>1)?'done':''">
      <div class="progline"></div>
      <div class="header">
        <div class="n"><div class="c">1</div></div>
        <div class="title">
          <h3><?=__('Utasok megadása', TD)?></h3>
          <?=__('Itt adhatja meg, hogy hányan szeretnének utazni.', TD)?>
        </div>
      </div>
      <div class="cholder">
        <div class="wrapper">
          <div class="selected-item-holder" ng-show="(step_done[1])">
            <div class="holder">
              <div class="header">
                <i class="far fa-check-circle"></i>
              </div>
              <div class="v">
                <span>{{passengers.adults}} <?=__('felnőtt', TD)?></span><span ng-hide="passengers.children==0"> + {{passengers.children}} <?=__('gyermek', TD)?></span>
              </div>
              <div class="be">
                <button type="button" ng-click="backToEdit(1)"><?=__('Módosít', TD)?></button>
              </div>
            </div>
          </div>
          <div class="header">
            <?=__('Hányan szeretnének utazni?', TD)?>
          </div>
          <div class="form">
            <div class="adults">
              <div class="w">
                <label for="passengers_adults"><?=__('Felnőttek száma', TD)?></label>
                <input type="number" id="passengers_adults" ng-model="passengers.adults">
              </div>
            </div>
            <div class="children">
              <div class="w">
                <label for="passengers_children"><?=__('Gyermekek száma', TD)?></label>
                <input type="number" id="passengers_children" ng-model="passengers.children">
              </div>
            </div>
          </div>
        </div>
        <div class="next" ng-class="(step_done[1])?'done':''">
          <button type="button" ng-hide="step_done[1]" ng-click="nextStep(1)"><?=__('Tovább',TD)?> <i class="fas fa-angle-right"></i></button>
        </div>
      </div>
    </div>
    <div class="offer-group" ng-show="(step>=2)" ng-class="(step>2)?'done':''">
      <div class="progline"></div>
      <div class="header">
        <div class="n"><div class="c">2</div></div>
        <div class="title">
          <h3><?=__('Időpont kiválasztása', TD)?></h3>
          <?=__('Válassza ki az utazás idejét az elérhető időpontok közül.', TD)?>
        </div>
      </div>
      <div class="cholder">
        <div class="wrapper">
          <div class="selected-item-holder" ng-show="(step_done[2])">
            <div class="holder">
              <div class="header">
                <i class="far fa-check-circle"></i>
              </div>
              <div class="v">
                {{dateselectInfo()}}
              </div>
              <div class="be">
                <button type="button" ng-click="backToEdit(2)"><?=__('Módosít', TD)?></button>
              </div>
            </div>
          </div>
          <div class="step-loader" ng-show="(step_loading==1)">
            <?=__('Adatok betöltése folyamatban', TD)?> <i class="fas fa-spinner fa-spin fa-pulse"></i>
          </div>
          <div class="date-selectors" ng-show="(!step_loading || step_loading!=1)">
            <div class="selector-wrapper">
              <div class="durrations">
                <div class="durration" ng-repeat="(durrid, durr) in dates" ng-class="(dateselect.durration==durrid)?'selected':''">
                  <div class="v" ng-click="selectCalcDurr(durrid)"><strong>{{durr.name}}</strong><br><span class="nights">{{durr.nights}} <?=__('éjszaka', TD)?></span><br><?=__('időtartam', TD)?></div>
                </div>
              </div>
              <div class="travel-years" ng-show="(dateselect.durration)">
                <div class="travel-year" ng-repeat="(year, yeardata) in dates[dateselect.durration].data" ng-class="(dateselect.year==year)?'selected':''">
                  <div class="v" ng-click="selectCalcYearmonth(year)">
                    <div class="year">
                      {{yeardata.year}}
                    </div>
                    <div class="month">
                      {{yeardata.month_name}}
                    </div>
                  </div>
                </div>
              </div>
              <div class="dates" ng-show="(dateselect.year)">
                <div class="date" ng-repeat="sdate in dates[dateselect.durration].data[dateselect.year].data" ng-class="(dateselect.date==sdate.ID)?'selected':''">
                  <div class="v" ng-click="selectCalcDate(sdate.ID)">
                    <div class="yearmonth">
                      {{sdate.travel_year}}.{{sdate.travel_month}}.
                    </div>
                    <div class="day">
                      {{sdate.travel_day}}.
                    </div>
                    <div class="weekday">
                      {{sdate.travel_weekday}}
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="next" ng-show="!step_loading && (dateselect.year && dateselect.durration && dateselect.date)" ng-class="(step_done[2])?'done':''">
          <button type="button" ng-hide="step_done[2]" ng-click="nextStep(2)"><?=__('Tovább',TD)?> <i class="fas fa-angle-right"></i></button>
        </div>
      </div>
    </div>
    <div class="offer-group" ng-show="(step>=3)" ng-class="(step>3)?'done':''">
      <div class="progline"></div>
      <div class="header">
        <div class="n"><div class="c">3</div></div>
        <div class="title">
          <h3><?=__('Ellátások', TD)?></h3>
          <?=__('Válassza ki, hogy milyen ellátással szeretné igénybe venni a szolgáltatást.', TD)?>
        </div>
      </div>
      <div class="cholder">
        <div class="wrapper">
          <div class="selected-item-holder" ng-show="(step_done[3])">
            <div class="holder">
              <div class="header">
                <i class="far fa-check-circle"></i>
              </div>
              <div class="v">
                <span>{{selected_ellatas_data.ellatas.name}}</span>
              </div>
              <div class="be">
                <button type="button" ng-click="backToEdit(3)"><?=__('Módosít', TD)?></button>
              </div>
            </div>
          </div>
          <div class="step-loader" ng-show="(step_loading==2)">
            <?=__('Adatok betöltése folyamatban', TD)?> <i class="fas fa-spinner fa-spin fa-pulse"></i>
          </div>
          <div class="ellatas-selectors" ng-show="(!step_loading || step_loading!=2)">
            <div class="selector-wrapper">
              <div class="ellatasok">
                <div class="ellatas" ng-repeat="ellatas in configs.ellatas" ng-class="(selected_ellatas==ellatas.term_id)?'selected':''" ng-show="configs.szobak[dateselect.date].ellatas[ellatas.term_id]">
                  <div class="wrapper" ng-click="selectEllatas(ellatas.term_id)">
                    {{ellatas.name}}
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="next" ng-class="(step_done[3])?'done':''" ng-show="(selected_ellatas)">
          <button type="button" ng-hide="step_done[3]" ng-click="nextStep(3)"><?=__('Tovább',TD)?> <i class="fas fa-angle-right"></i></button>
        </div>
      </div>
    </div>
    <div class="offer-group" ng-show="(step>=4)" ng-class="(step>4)?'done':''">
      <div class="progline"></div>
      <div class="header">
        <div class="n"><div class="c">4</div></div>
        <div class="title">
          <h3><?=__('Szobatípus', TD)?></h3>
          <?=__('Válassza ki, hogy melyik szobát szeretné kérni az elérhető kínálatból.', TD)?>
        </div>
      </div>
      <div class="cholder">
        <div class="wrapper">
          <div class="selected-item-holder" ng-show="(step_done[4])">
            <div class="holder">
              <div class="header">
                <i class="far fa-check-circle"></i>
              </div>
              <div class="v">
                <span>{{selected_room_data.title}}<br><small>{{selected_room_data.description}}</small></span>
              </div>
              <div class="be">
                <button type="button" ng-click="backToEdit(4)"><?=__('Módosít', TD)?></button>
              </div>
            </div>
          </div>
          <div class="szoba-selectors">
            <div class="selector-wrapper">
              <div class="szobak">
                <div class="header">
                  <div class="datas">Típus</div>
                  <div class="price-adult">Ár - Felnőttek</div>
                  <div class="price-children">Ár - Gyermekek</div>
                  <div class="total-prices">Ár összesen</div>
                </div>
                <div class="szoba" ng-repeat="szoba in selected_ellatas_data.rooms" ng-class="(selected_room_id==szoba.ID)?'selected':''" ng-click="selectRoom(szoba.ID)">
                  <div class="datas">
                    <div class="title">{{szoba.title}}</div>
                    <div class="desc">
                      {{szoba.description}}
                    </div>
                  </div>
                  <div class="price-adult">
                    {{calced_room_price[szoba.ID].adults|number:0}} Ft
                  </div>
                  <div class="price-children">
                    {{calced_room_price[szoba.ID].children|number:0}} Ft
                  </div>
                  <div class="total-prices">
                    {{calced_room_price[szoba.ID].all|number:0}} Ft
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="next" ng-class="(step_done[4])?'done':''">
          <button type="button" ng-hide="step_done[4]" ng-click="nextStep(4)"><?=__('Tovább',TD)?> <i class="fas fa-angle-right"></i></button>
        </div>
      </div>
    </div>
    <div class="offer-group overview" ng-show="(step>=5)" ng-class="(step>5)?'done':''">
      <div class="progline"></div>
      <div class="header">
        <div class="n"><div class="c">5</div></div>
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
                  <strong>{{selected_room_data.title}}</strong>
                </td>
                <td>{{selected_room_data.adult_price|number:0}} Ft</td>
                <td>/fő/éjszaka</td>
                <td>x{{selected_date_data.durration.nights}}</td>
                <td>{{(calced_room_price[selected_room_data.ID].adults)|number:0}} Ft</td>
              </tr>
              <tr ng-show="(passengers.children!=0)">
                <td class="tetel opcio">
                  <strong>-> {{passengers.children}} gyerek</strong>
                </td>
                <td>{{selected_room_data.child_price|number:0}} Ft</td>
                <td>/fő/éjszaka</td>
                <td>x{{selected_date_data.durration.nights}}</td>
                <td>{{(calced_room_price[selected_room_data.ID].children)|number:0}} Ft</td>
              </tr>
              <tr class="priceev" ng-show="configs.programok.length!=0">
                <td colspan="4" class="ev">Szállás és utazás összesen:</td>
                <td class="price">{{travel_prices|number:0}} Ft</td>
              </tr>
              <tr ng-show="configs.szolgaltatas.length!=0">
                <td colspan="5" class="price-group">
                  <?=__('Elérhető szolgáltatások', TD)?>
                </td>
              </tr>
              <tr ng-repeat="item in configs.szolgaltatas">
                <td class="tetel">
                  <input type="checkbox" ng-checked="item.requireditem" ng-disabled="item.requireditem" id="program_{{item.ID}}"> <label for="program_{{item.ID}}">{{item.title}} <span class="label required" ng-show="item.requireditem">kötelező</span></label>
                  <span class="label info" ng-show="(item.description!='')" title="{{item.description}}">infó</span>
                </td>
                <td>{{item.price|number:0}}</td>
                <td>{{item.price_after}}</td>
                <td>x {{priceCalcMe(item)}}</td>
                <td>{{priceCalcSum(item)|number:0}} Ft</td>
              </tr>
              <tr class="priceev" ng-show="configs.szolgaltatas.length!=0">
                <td colspan="4" class="ev">Szolgáltatások összesen:</td>
                <td class="price">{{config_szolgaltatas_prices|number:0}} Ft</td>
              </tr>
              <tr ng-show="configs.programok.length!=0">
                <td colspan="5" class="price-group">
                  <?=__('Fakultatív programok', TD)?>
                </td>
              </tr>
              <tr ng-repeat="item in configs.programok">
                <td class="tetel">
                  <input type="checkbox" ng-checked="item.requireditem" ng-disabled="item.requireditem" id="program_{{item.ID}}"> <label for="program_{{item.ID}}">{{item.title}} <span class="label required" ng-show="item.requireditem">kötelező</span></label>
                  <span class="label info" ng-show="(item.description!='')" title="{{item.description}}">infó</span>
                </td>
                <td>{{item.price|number:0}}</td>
                <td>{{item.price_after}}</td>
                <td>x {{priceCalcMe(item)}}</td>
                <td>{{priceCalcSum(item)|number:0}} Ft</td>
              </tr>
              <tr class="priceev" ng-show="configs.programok.length!=0">
                <td colspan="4" class="ev">Programok összesen:</td>
                <td class="price">{{config_programok_prices|number:0}} Ft</td>
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
        <div class="next" ng-class="(step_done[5])?'done':''">
          <button type="button" ng-hide="step_done[5]" ng-click="nextStep(5)"><?=__('Tovább az adatok megadásához',TD)?> <i class="fas fa-angle-right"></i></button>
        </div>
      </div>
    </div>
    <div class="price-overview" ng-show="(step>=5)">
      <div class="wrapper">
        <div class="head">
          <?php echo __('Kalkulált ár',TD); ?>
        </div>
        <div class="value">
          {{final_calc_price|number:0}} Ft
        </div>
      </div>
    </div>
    <div class="offer-group last-item" ng-show="(step>=6)" ng-class="(step>6)?'done':''">
      <div class="progline"></div>
      <div class="header">
        <div class="n"><div class="c">6</div></div>
        <div class="title">
          <h3><?=__('Adatok megadása', TD)?></h3>
          <?=__('Adja meg személyes adatait az ajánlatkérés véglegesítéséhez.', TD)?>
        </div>
      </div>
      <div class="cholder">
        <div class="wrapper">
          <div class="selected-item-holder" ng-show="(step_done[6])">
            <div class="holder">
              <div class="header">
                <i class="far fa-check-circle"></i>
              </div>
              <div class="v">
                <span>{{passengers.adults}} <?=__('felnőtt', TD)?></span><span ng-hide="passengers.children==0"> + {{passengers.children}} <?=__('gyermek', TD)?></span>
              </div>
              <div class="be">
                <button type="button" ng-click="backToEdit(6)"><?=__('Módosít', TD)?></button>
              </div>
            </div>
          </div>
          Tartalom
          <br><br><br>
        </div>
        <div class="next" ng-class="(step_done[6])?'done':''">
          <button type="button" ng-hide="step_done[6]" ng-click="nextStep(6)"><?=__('Tovább',TD)?> <i class="fas fa-angle-right"></i></button>
        </div>
      </div>
    </div>
  </div>
</div>
