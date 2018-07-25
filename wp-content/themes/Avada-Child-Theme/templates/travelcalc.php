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
                <input type="number" ng-change="(passengers.adults<1)?passengers.adults=1:passengers.adults" id="passengers_adults" ng-model="passengers.adults">
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
              <div class="date-picker" ng-show="(dates.length==0)">
                <div class="selected-date-text">
                  <div class="wrapper">
                    <div class="head" ng-show="calendarModel.selectedTemplateName"><?php echo __('Kiválasztott időtartam', TD); ?>:</div>
                    {{calendarModel.selectedTemplateName}}
                  </div>
                </div>
                <md-date-range-picker
                  first-day-of-week="1"
                  localization-map="localizationMap"
                  selected-template="calendarModel.selectedTemplate"
                  selected-template-name="calendarModel.selectedTemplateName"
                  show-template="true"
                  is-disabled-date="isDisabledDate($date)"
                  custom-templates="customPickerTemplates"
                  disable-templates="TD YD TW LW TM LM LY TY"
                  date-start="calendarModel.dateStart"
                  date-end="calendarModel.dateEnd">
                </md-date-range-picker>
              </div>
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
        <div class="next" ng-show="!step_loading && ( (dateselect.year && dateselect.durration && dateselect.date) || (calendarModel.dateStart && calendarModel.dateEnd) )" ng-class="(step_done[2])?'done':''">
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
                <div class="ellatas" ng-repeat="ellatas in configs.ellatas" ng-class="(selected_ellatas==ellatas.term_id)?'selected':''" ng-show="(configs.szobak[dateselect.date].ellatas[ellatas.term_id] || dates.length == 0)">
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
                    <span class="show-on-mobile mobile-label"><?=__('Felnőtt ár', TD)?>:</span> {{calced_room_price[szoba.ID].adults|number:0}} Ft
                  </div>
                  <div class="price-children">
                    <span class="show-on-mobile mobile-label"><?=__('Gyermek ár', TD)?>:</span> {{calced_room_price[szoba.ID].children|number:0}} Ft
                  </div>
                  <div class="total-prices">
                    <span class="show-on-mobile mobile-label"><?=__('Összesen', TD)?>:</span> {{calced_room_price[szoba.ID].all|number:0}} Ft
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="next" ng-class="(step_done[4])?'done':''" ng-show="selected_room_id">
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
                <td>x{{nights*passengers.adults}}</td>
                <td>{{(calced_room_price[selected_room_data.ID].adults)|number:0}} Ft</td>
              </tr>
              <tr ng-show="(passengers.children!=0)">
                <td class="tetel opcio">
                  <strong>-> {{passengers.children}} gyerek</strong>
                </td>
                <td>{{selected_room_data.child_price|number:0}} Ft</td>
                <td>/fő/éjszaka</td>
                <td>x{{nights*passengers.children}}</td>
                <td>{{(calced_room_price[selected_room_data.ID].children)|number:0}} Ft</td>
              </tr>
              <tr class="priceev" ng-show="configs.szolgaltatas.length!=0">
                <td colspan="4" class="ev">Szállás összesen:</td>
                <td class="price">{{travel_prices|number:0}} Ft</td>
              </tr>
              <tr ng-show="configs.szolgaltatas.length!=0">
                <td colspan="5" class="price-group">
                  <?=__('Elérhető szolgáltatások', TD)?>
                </td>
              </tr>
              <tr ng-repeat="item in configs.szolgaltatas">
                <td class="tetel">
                  <input type="checkbox" ng-change="pickExtraItem()" ng-model="configs_selected['szolgaltatas'][item.ID]" ng-value="item.ID" ng-checked="item.requireditem" ng-disabled="item.requireditem" id="program_{{item.ID}}"> <label for="program_{{item.ID}}">{{item.title}} <span class="label required" ng-show="item.requireditem">kötelező</span></label>
                  <span class="label info" ng-show="(item.description!=''&& item.description)" title="{{item.description}}">infó</span>
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
                  <input type="checkbox" ng-change="pickExtraItem()" ng-model="configs_selected['programok'][item.ID]" ng-checked="item.requireditem" ng-disabled="item.requireditem" id="program_{{item.ID}}"> <label for="program_{{item.ID}}">{{item.title}} <span class="label required" ng-show="item.requireditem">kötelező</span></label>
                  <span class="label info" ng-show="(item.description!=''&& item.description)" title="{{item.description}}">infó</span>
                </td>
                <td ng-hide="item.price==0">{{item.price|number:0}}</td>
                <td ng-hide="item.price==0">{{item.price_after}}</td>
                <td ng-hide="item.price==0">x {{priceCalcMe(item)}}</td>
                <td ng-hide="item.price==0">{{priceCalcSum(item)|number:0}} Ft</td>
                <td colspan="4" ng-show="item.price==0"><?=__('Benne az árban', TD)?></td>
              </tr>
              <tr class="priceev" ng-show="configs.programok.length!=0">
                <td colspan="4" class="ev">Programok összesen:</td>
                <td class="price">{{config_programok_prices|number:0}} Ft</td>
              </tr>
              <tr ng-hide="biztositas.price===-1">
                <td colspan="5" class="price-group">
                  <?=__('Utasbiztosítás', TD)?>
                </td>
              </tr>
              <tr ng-hide="biztositas.price===-1">
                <td class="tetel">
                  <input type="radio" ng-change="pickExtraItem()" name="utasbiztositas" ng-model="configs_selected['biztositas']" ng-value="0" checked="checked" id="utasbizt_no"> <label for="utasbizt_no"><?=__('Nem kérek biztosítást', TD)?></label>
                </td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
              </tr>
              <tr ng-hide="biztositas.price===-1">
                <td class="tetel">
                  <input type="radio" ng-change="pickExtraItem()" name="utasbiztositas" ng-model="configs_selected['biztositas']" ng-value="biztositas" id="utasbizt_yes"> <label for="utasbizt_yes"><?=__('Kérek biztosítást', TD)?></label>
                </td>
                <td ng-hide="biztositas.price===0">{{biztositas.price}}</td>
                <td ng-hide="biztositas.price===0">{{biztositas.price_after}}</td>
                <td ng-hide="biztositas.price===0">x {{priceCalcMe(biztositas)}}</td>
                <td ng-hide="biztositas.price===0">{{priceCalcSum(biztositas)|number:0}} Ft</td>
                <td colspan="4" ng-show="biztositas.price===0"><?=__('Egyedi ajánlat', TD)?></td>
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
          <?php echo __('Kalkulált ár',TD); ?>*
        </div>
        <div class="value">
          {{final_calc_price|number:0}} Ft
        </div>
      </div>
    </div>
    <div class="price-overview-info" ng-show="(step>=5)">
      <em>* <?=__('a kalkulált ár tájékoztató jellegű, nem minősül konkrét ajánlatnak. Adatai megadása után kollégáink felveszik Önnel a kapcsolatot és részletes tájékoztatást adnak a kiválasztott utazásról. Az árváltozás jogát fenntartjuk.', TD)?></em>
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
          <div class="orderder-details">
            <div class="wrapper">
              <div class="orderer">
                <h3><?=__('Kapcsolattartó adatai', TD)?></h3>
                <div class="flex">
                  <div class="name">
                    <div class="w">
                      <label for="orderer_name"><?=__('Név', TD)?> *</label>
                      <input type="text" id="orderer_name" ng-model="order.contact.name" required>
                    </div>
                  </div>
                  <div class="email">
                    <div class="w">
                      <label for="orderer_email"><?=__('E-mail cím', TD)?> *</label>
                      <input type="email" id="orderer_email" ng-model="order.contact.email" required>
                    </div>
                  </div>
                  <div class="phone">
                    <div class="w">
                      <label for="orderer_phone"><?=__('Telefonszám', TD)?> *</label>
                      <input type="text" id="orderer_phone" ng-model="order.contact.phone" required>
                    </div>
                  </div>
                </div>
              </div>
              <div class="passengers-details">
                <h3><?=__('Utasok adatai', TD)?></h3>
                <div class="flex">
                  <div class="adult" ng-repeat="ix in passengerArray('adults') track by $index">
                    <div class="wrapper">
                      <h4>#{{($index+1)}} <?=__('felnőtt adatai', TD)?></h4>
                      <div class="iwrapper">
                        <div class="flex">
                          <div class="name">
                            <div class="w">
                              <label for="pass_adult{{$index}}_name"><?=__('Név', TD)?></label>
                              <input type="text" id="pass_adult{{$index}}_name" ng-model="passengers_detail.adults[$index].name">
                            </div>
                          </div>
                          <div class="dob">
                            <div class="w">
                              <label for="pass_adult{{$index}}_dob"><?=__('Születési idő', TD)?></label>
                              <input type="text" id="pass_adult{{$index}}_dob" ng-model="passengers_detail.adults[$index].dob">
                              <span class="inf"><?=__('Minta: 1990.01.01',TD)?></span>
                            </div>
                          </div>
                          <div class="address">
                            <div class="w">
                              <label for="pass_adult{{$index}}_address"><?=__('Lakcím', TD)?></label>
                              <input type="text" id="pass_adult{{$index}}_address" ng-model="passengers_detail.adults[$index].address">
                              <span class="inf"><?=__('Pl.: 1067 Budapest, Szondi utca 30.',TD)?></span>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                  <div class="child" ng-repeat="ix in passengerArray('children') track by $index">
                    <div class="wrapper">
                      <h4>#{{($index+1)}} <?=__('gyermek adatai', TD)?></h4>
                      <div class="iwrapper">
                        <div class="flex">
                          <div class="name">
                            <div class="w">
                              <label for="pass_child{{$index}}_name"><?=__('Név', TD)?></label>
                              <input type="text" id="pass_child{{$index}}_name" ng-model="passengers_detail.children[$index].name">
                            </div>
                          </div>
                          <div class="dob">
                            <div class="w">
                              <label for="pass_child{{$index}}_dob"><?=__('Születési idő', TD)?></label>
                              <input type="text" id="pass_child{{$index}}_dob" ng-model="passengers_detail.children[$index].dob">
                              <span class="inf"><?=__('Minta: 1990.01.01',TD)?></span>
                            </div>
                          </div>
                          <div class="address">
                            <div class="w">
                              <label for="pass_child{{$index}}_address"><?=__('Lakcím', TD)?></label>
                              <input type="text" id="pass_child{{$index}}_address" ng-model="passengers_detail.children[$index].address">
                              <span class="inf"><?=__('Pl.: 1067 Budapest, Szondi utca 30.',TD)?></span>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              <div class="comment">
                <label for="order_comment"><?=__('Megjegyzés az ajánlatkéréshez', TD)?></label>
                <textarea ng-model="order.comment" id="order_comment" placeholder="<?=__('Amennyiben speciális igényei merülnek fel, azt itt leírhatja...', TD)?>"></textarea>
              </div>
              <div class="accepts">
                <input type="checkbox" class="cb" id="order_accept_term" ng-model="order.accept.term"> <label for="order_accept_term">* <?php printf(__('Az ajánlatkérés elküldésével elfogadom az <a target="_blank" href="%s">Általános Szerződési Feltételekben</a> és <a target="_blank" href="%s">Adatvédelmi Tájékoztatóban</a> foglaltakat, melyekkel elolvastam, tudomásul vettem.', TD), '/aszf', '/adatvedelmi-tajekoztato'); ?></label>
              </div>
            </div>
          </div>
        </div>
        <div class="missing-form-data" ng-hide="canSendPreOrder()">
          <div class="" ng-show="(order.contact.name==null || order.contact.name == '')">
            <?=__('A kapcsolattartó nevét kötelezően meg kell adni! Kérjük, pótolja!', TD)?>
          </div>
          <div class="" ng-show="(order.contact.email==null || order.contact.email == '')">
            <?=__('A kapcsolattartó e-mail címét kötelezően meg kell adni! Kérjük, pótolja!', TD)?>
          </div>
          <div class="" ng-show="(order.contact.phone==null || order.contact.phone == '')">
            <?=__('A kapcsolattartó telefonszámát kötelezően meg kell adni! Kérjük, pótolja!', TD)?>
          </div>
          <div class="" ng-hide="passengersDetailsCheck()">
            <?=__('Kérjük, hogy adja meg valamennyi utas adatát!', TD)?>
          </div>
          <div class="" ng-show="order.accept.term==false">
            <?=__('Az ajánlatkérés elküldéséhez el kell fogadnia az Általános Szerződési Feltételeket és az Adatvédelmi Tájékoztatót!', TD)?>
          </div>
        </div>
        <div class="next" ng-class="(step_done[6])?'done':''" ng-show="canSendPreOrder() && !preorder_sending">
          <button type="button" ng-hide="step_done[6]" ng-click="doPreOrder()"><?=__('Ajánlatkérés elküldése',TD)?> <i class="fas fa-angle-right"></i></button>
        </div>
        <div class="preorder-sending" ng-show="preorder_sending">
          Ajánlatkérés küldése folyamatban <i class="fa fa-spin fa-spinner"></i>
        </div>
      </div>
    </div>
    <div class="offer-group preorder-msg" ng-show="preorder_msg.msg" ng-class="(preorder_msg.error==0)?'success':'error'">
      <i class="fa fa-check-circle" ng-show="(preorder_msg.error==0)"></i>
      <i class="fa fa-times-circle" ng-show="(preorder_msg.error==1)"></i>
      {{preorder_msg.msg}}
    </div>
  </div>
</div>
