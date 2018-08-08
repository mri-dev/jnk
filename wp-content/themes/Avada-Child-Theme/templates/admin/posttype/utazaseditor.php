<?php
  global $post;
  $egyeni_utazas = get_post_meta($post->ID, METAKEY_PREFIX . 'egyeni_utazas', true);


    $valuta = wp_get_post_terms($post->ID, array(
      'taxonomy' => 'penznem'
    ));

    $penznem = $valuta[0];
?>
<div class="travel-editor" ng-class="(loading)?'loading':''" ng-app="jonapotnagyvilag" ng-controller="TravelConfigEditor" ng-init="init(<?=$post->ID?>)">
  <?php if ( empty($egyeni_utazas) ): ?>
  <div class="group">
    <div class="ghead">
      <span class="add" ng-click="addDate()">új időpont</span>
      <h2>Időpontok</h2>
    </div>
    <div class="cont">
      <div>
        <div class="loading-data" ng-hide="dates_loaded">
          <i class="fa fa-spin fa-spinner"></i> <?php echo __('Időpontok betöltése folyamatban', TD); ?>
        </div>
        <div class="no-data" ng-show="(dates_loaded && dates.length == 0)">
          Nincs időpont meghatározva ehhez az utazáshoz.
        </div>

        <div class="create-line" ng-show="(dates_create.length!=0)">
          <div class="header">
            Új időpontok hozzáadása
          </div>
          <div class="new-line" ng-repeat="(i,n) in dates_create">
            <div class="wrapper">
              <div class="date">
                <input type="number" ng-model="dates_create[i].travel_year">
                <select class="" ng-model="dates_create[i].travel_month" ng-options="month for month in range_months"></select>
                <select class="" ng-model="dates_create[i].travel_day" ng-options="day for day in range_days"></select>
              </div>
              <div class="durration">
                <select class="fullw" ng-model="dates_create[i].utazas_duration_id" ng-options="term.name for term in terms.utazas_duration"></select>
              </div>
              <div class="active">
                <div>
                  <label for="">Aktív</label>
                  <input type="checkbox" ng-model="dates_create[i].active">
                </div>
              </div>
              <div class="action">
                <i class="fa fa-times" title="Töröl" ng-click="removeEditorDate(i)"></i>
              </div>
            </div>
          </div>
          <div class="footer">
            <div class="saving" ng-show="dates_saving">
              <i class="fa fa-spin fa-spinner"></i> Időpontok mentése folyamatban.
            </div>
            <button type="button" ng-hide="dates_saving" ng-click="saveDates()">Változások mentése</button>
          </div>
        </div>
        <div class="datas-header" ng-show="(dates_loaded && dates.length!==0)">
          <div class="data-line">
            <div class="wrapper">
              <div class="date">
                Időpont
              </div>
              <div class="durration">
                Utazás hossza
              </div>
              <div class="active center">
                Aktív
              </div>
              <div class="action"></div>
            </div>
          </div>
        </div>
        <div class="datas data-dates" ng-show="(dates_loaded && dates)">
          <div class="data-line" ng-repeat="(i, d) in dates">
            <div class="wrapper">
              <div class="date">
                {{d.travel_year}} / {{d.travel_month}} / {{d.travel_day}}
              </div>
              <div class="durration">
                {{d.durration.name}}
              </div>
              <div class="active center">
                <i ng-show="(d.active)" class="fa fa-check-circle"></i>
                <i ng-show="(!d.active)" class="fa fa-times"></i>
              </div>
              <div class="action">
                <span ng-click="deleteDate(d.ID, d.onday)" title="Szerkesztés"><i class="fa fa-trash"></i></span>
                <span ng-click="editDate(d.ID)" title="Szerkesztés"><i class="fa fa-pencil"></i></span>
              </div>
            </div>
          </div>
        </div>

      </div>
    </div>
  </div>
  <?php endif; ?>

  <div class="group">
    <div class="ghead">
      <span class="add" ng-click="addConfig('szobak')">új szoba</span>
      <h2>Szobák</h2>
    </div>
    <div class="cont">
      <div class="loading-data" ng-hide="configs.szobak">
        <i class="fa fa-spin fa-spinner"></i> <?php echo __('Szobák betöltése folyamatban', TD); ?>
      </div>
      <div class="no-data" ng-show="(configs.szobak && configs.szobak.length == 0)">
        Nincs szoba meghatározva ehhez az utazáshoz.
      </div>
      <div class="rooms">

        <div class="create-line" ng-show="(config_creator.szobak.length!=0)">
          <div class="header">
            Új szoba hozzáadása
          </div>
          <div class="new-line" ng-repeat="(i,n) in config_creator.szobak">
            <div class="wrapper">
              <div class="datas">
                <div class="avdate">
                  <div class="w">
                    <label for="">Időpont kiválasztása</label>
                    <?php if (empty($egyeni_utazas)): ?>
                    <select class="fullw" ng-model="config_creator.szobak[i].date_id" ng-options="day.onday for day in dates"></select>
                    <?php else: ?>
                      (i) Csak csoportos utazásoknál választható.
                    <?php endif; ?>
                  </div>
                </div>
                <div class="ellatas">
                  <div class="w">
                    <label for="">Ellátás kiválasztása</label>
                    <select class="fullw" ng-model="config_creator.szobak[i].ellatas_id" ng-options="term.name for term in terms.utazas_ellatas"></select>
                  </div>
                </div>
                <div class="title">
                  <div class="w">
                    <input type="text" class="fullw" placeholder="* Megnevezés" ng-model="config_creator.szobak[i].title">
                    <div class="desc">
                      <input type="text" class="fullw" placeholder="Rövid leírás (nem kötelező)"  ng-model="config_creator.szobak[i].description">
                    </div>
                  </div>
                </div>
                <div class="capacity">
                  <div class="w">
                    <label for="">Felnőtt kapacitás (fő)</label>
                    <input type="number" ng-value="1" min="1" step="1" class="fullw" ng-model="config_creator.szobak[i].adult_capacity">
                  </div>
                </div>
                <div class="capacity">
                  <div class="w">
                    <label for="">Gyermek kapacitás (fő)</label>
                    <input type="number" ng-value="0" min="0" step="1" class="fullw" ng-model="config_creator.szobak[i].child_capacity">
                  </div>
                </div>
                <div class="price">
                  <div class="w">
                    <label for="">Felnőtt ár (<?=$penznem->name?> /fő)</label>
                    <input type="number" class="fullw" ng-model="config_creator.szobak[i].adult_price">
                  </div>
                </div>
                <div class="price">
                  <div class="w">
                    <label for="">Gyermek ár (<?=$penznem->name?> /fő)</label>
                    <input type="number" class="fullw" ng-model="config_creator.szobak[i].child_price">
                  </div>
                </div>
                <div class="active">
                  <div class="w">
                    <label for="">Aktív</label>
                    <input type="checkbox" ng-model="config_creator.szobak[i].active">
                  </div>
                </div>
              </div>
              <div class="action">
                <i class="fa fa-times" title="Töröl" ng-click="removeConfigEditorDate('szobak', i)"></i>
              </div>
            </div>
          </div>
          <div class="footer">
            <div class="saving" ng-show="config_saving.szobak">
              <i class="fa fa-spin fa-spinner"></i> Szoba hozzáadás folyamatban.
            </div>
            <button type="button" ng-hide="config_saving.szobak" ng-click="saveConfig('szobak')">Változások mentése</button>
          </div>
        </div>

        <div class="date-group" ng-repeat="dategroup in configs.szobak">
          <div class="header" ng-show="dategroup.day.name">
            <strong>{{dategroup.date_on}}</strong> ({{dategroup.day.name}})
          </div>
          <div class="header" ng-hide="dategroup.day.name">
            Választott időpontban
          </div>
          <div class="ellatas-group" ng-repeat="ellatas in dategroup.ellatas">
            <div class="header">
              <div class="n">
                {{ellatas.rooms.length}}
              </div>
              <strong>{{ellatas.ellatas.name}}</strong> esetén
            </div>
            <div class="room-header">
              <div class="wrapper">
                <div class="title">
                  <strong>Szoba</strong> / Leírás
                </div>
                <div class="capacity">
                  Kapacitás
                </div>
                <div class="price price-adult center">
                  Felnőtt ár
                </div>
                <div class="price price-adult center">
                  Gyermek ár
                </div>
                <div class="active center">
                  Aktív
                </div>
                <div class="action center"></div>
              </div>
            </div>
            <div class="room" ng-class="(room.active=='0')?'inactive':''" ng-repeat="room in ellatas.rooms">
              <div class="wrapper">
                <div class="title">
                  <strong>{{room.title}}</strong>
                  <div class="desc">
                    {{room.description}}
                  </div>
                </div>
                <div class="capacity">
                  {{room.adult_capacity+room.child_capacity}} fő ({{room.adult_capacity}} felnőtt + {{room.child_capacity}} gyermek)
                </div>
                <div class="price price-adult center">
                  {{price_before}}{{room.adult_price}}{{price_after}} / fő
                </div>
                <div class="price price-adult center">
                  {{price_before}}{{room.child_price}}{{price_after}} / fő
                </div>
                <div class="active center">
                  <i ng-show="(room.active=='1')" class="fa fa-check-circle"></i>
                  <i ng-show="(room.active=='0')" class="fa fa-ban"></i>
                </div>
                <div class="action">
                  <span ng-click="deleteRoom(room.ID, room.title)" title="Törlés"><i class="fa fa-trash"></i></span>
                  <span ng-click="editRoom(room.ID)" title="Szerkesztés"><i class="fa fa-pencil"></i></span>
                </div>
              </div>
            </div>
          </div>
        </div>

      </div>

    </div>
  </div>
  <div class="group">
    <div class="ghead">
      <span class="add" ng-click="addConfig('szolgaltatas')">új szolgáltatás</span>
      <h2>Szolgáltatások</h2>
    </div>
    <div class="cont">
      <div class="loading-data" ng-hide="configs.szolgaltatas">
        <i class="fa fa-spin fa-spinner"></i> <?php echo __('Szolgáltatások betöltése folyamatban', TD); ?>
      </div>
      <div class="no-data" ng-show="(configs.szolgaltatas && configs.szolgaltatas.length == 0)">
        Nincsenek szolgáltatások meghatározva ehhez az utazáshoz.
      </div>

      <div class="create-line" ng-show="(config_creator.szolgaltatas.length!=0)">
        <div class="header">
          Új szolgáltatás hozzáadása
        </div>
        <div class="new-line" ng-repeat="(i,n) in config_creator.szolgaltatas">
          <div class="wrapper">
            <div class="title">
              <input type="text" class="fullw" placeholder="* Megnevezés" ng-model="config_creator.szolgaltatas[i].title">
              <div class="desc">
                <input type="text" class="fullw" placeholder="Rövid leírás (nem kötelező)"  ng-model="config_creator.szolgaltatas[i].description">
              </div>
            </div>
            <div class="req center">
              <input type="checkbox" ng-model="config_creator.szolgaltatas[i].requireditem">
            </div>
            <div class="price">
              <input type="number" class="fullw" ng-model="config_creator.szolgaltatas[i].price">
              <select class="" ng-model="config_creator.szolgaltatas[i].price_calc_mode" ng-options="key as value for (key, value) in price_calc_modes">
                <option value="">-- ár jellege? --</option>
              </select>
            </div>
            <div class="action">
              <i class="fa fa-times" title="Töröl" ng-click="removeConfigEditorDate('szolgaltatas', i)"></i>
            </div>
          </div>
        </div>
        <div class="footer">
          <div class="saving" ng-show="config_saving.szolgaltatas">
            <i class="fa fa-spin fa-spinner"></i> Szolgáltatás hozzáadás folyamatban.
          </div>
          <button type="button" ng-hide="config_saving.szolgaltatas" ng-click="saveConfig('szolgaltatas')">Változások mentése</button>
        </div>
      </div>

      <div class="datas-header" ng-show="(configs.szolgaltatas && configs.szolgaltatas.length != 0)">
        <div class="data-line">
          <div class="wrapper">
            <div class="title">
              Megnevezés
            </div>
            <div class="req center">
              Kötelező
            </div>
            <div class="price">
              Ár
            </div>
            <div class="action"></div>
          </div>
        </div>
      </div>
      <div class="datas data-dates" ng-show="(configs.szolgaltatas && configs.szolgaltatas.length != 0)">
        <div class="data-line" ng-repeat="(i, d) in configs.szolgaltatas">
          <div class="wrapper">
            <div class="title">
              {{d.title}}
              <div class="desc" ng-show="d.description">
                {{d.description}}
              </div>
            </div>
            <div class="req center">
              <i ng-show="(d.requireditem=='1')" class="fa fa-check-circle"></i>
              <i ng-show="(d.requireditem=='0')" class="fa fa-ban"></i>
            </div>
            <div class="price">
              <span class="p" ng-show="(d.price!=0)">{{price_before}}{{d.price}}{{price_after}}</span>
              <span class="ba" ng-show="(d.price==0)">Benne az árban.</span>
            </div>
            <div class="action">
              <span ng-click="deleteConfigRecord('szolgaltatas', d.title, d.ID)" title="Törlés"><i class="fa fa-trash"></i></span>
              <span ng-click="editConfigRecord('szolgaltatas', d.ID)" title="Szerkesztés"><i class="fa fa-pencil"></i></span>
            </div>
          </div>
        </div>
      </div>

    </div>
  </div>
  <div class="group">
    <div class="ghead">
      <span class="add" ng-click="addConfig('programok')">új program</span>
      <h2>Programok</h2>
    </div>
    <div class="cont">

      <div class="loading-data" ng-hide="configs.programok">
        <i class="fa fa-spin fa-spinner"></i> <?php echo __('Programok betöltése folyamatban', TD); ?>
      </div>
      <div class="no-data" ng-show="(configs.programok && configs.programok.length == 0)">
        Nincsenek programok meghatározva ehhez az utazáshoz.
      </div>

      <div class="create-line" ng-show="(config_creator.programok.length!=0)">
        <div class="header">
          Új program hozzáadása
        </div>
        <div class="new-line" ng-repeat="(i,n) in config_creator.programok">
          <div class="wrapper">
            <div class="title">
              <input type="text" class="fullw" placeholder="* Megnevezés" ng-model="config_creator.programok[i].title">
              <div class="desc">
                <input type="text" class="fullw" placeholder="Rövid leírás (nem kötelező)"  ng-model="config_creator.programok[i].description">
              </div>
            </div>
            <div class="req center">
              <input type="checkbox" ng-model="config_creator.programok[i].requireditem">
            </div>
            <div class="price">
              <input type="number" class="fullw" ng-model="config_creator.programok[i].price">
              <select class="" ng-model="config_creator.programok[i].price_calc_mode" ng-options="key as value for (key, value) in price_calc_modes">
                <option value="">-- ár jellege? --</option>
              </select>
            </div>
            <div class="action">
              <i class="fa fa-times" title="Töröl" ng-click="removeConfigEditorDate('programok', i)"></i>
            </div>
          </div>
        </div>
        <div class="footer">
          <div class="saving" ng-show="config_saving.programok">
            <i class="fa fa-spin fa-spinner"></i> Program hozzáadás folyamatban.
          </div>
          <button type="button" ng-hide="config_saving.programok" ng-click="saveConfig('programok')">Változások mentése</button>
        </div>
      </div>

      <div class="datas-header" ng-show="(configs.programok && configs.programok.length != 0)">
        <div class="data-line">
          <div class="wrapper">
            <div class="title">
              Megnevezés
            </div>
            <div class="req center">
              Kötelező
            </div>
            <div class="price">
              Ár
            </div>
            <div class="action"></div>
          </div>
        </div>
      </div>
      <div class="datas data-dates" ng-show="(configs.programok && configs.programok.length != 0)">
        <div class="data-line" ng-repeat="(i, d) in configs.programok">
          <div class="wrapper">
            <div class="title">
              {{d.title}}
              <div class="desc" ng-show="d.description">
                {{d.description}}
              </div>
            </div>
            <div class="req center">
              <i ng-show="(d.requireditem=='1')" class="fa fa-check-circle"></i>
              <i ng-show="(d.requireditem=='0')" class="fa fa-ban"></i>
            </div>
            <div class="price">
              <span class="p" ng-show="(d.price!=0)">{{price_before}}{{d.price}}{{price_after}}</span>
              <span class="ba" ng-show="(d.price==0)">Benne az árban.</span>
            </div>
            <div class="action">
              <span ng-click="deleteConfigRecord('programok', d.title, d.ID)" title="Törlés"><i class="fa fa-trash"></i></span>
              <span ng-click="editConfigRecord('programok', d.ID)" title="Szerkesztés"><i class="fa fa-pencil"></i></span>
            </div>
          </div>
        </div>
      </div>

    </div>
  </div>
  <div class="group biztositas">
    <div class="ghead">
      <h2>Urasbiztosítás beállítások</h2>
    </div>
    <div class="cont">
      Biztosítási költségnek adjunk meg 0 (nulla) értéket, ha nem szeretnénk kalkulálni árat, de igényelhetnek biztosítás opciót. -1 érték esetén a biztosítás opció kikapcsolásra kerül.
      <div class="create-line">
        <div class="new-line">
          <div class="wrapper">
            <div class="price">
              <label for="bizt_price">Biztosítás költsége</label>
              <input type="number" min="-1" step="1" id="bizt_price" class="fullw" ng-model="config_creator.biztositas[0].price">
            </div>
            <div class="calc-mode">
              <label for="bizt_calcmode">Árszámítás módja</label>
              <select class="" id="bizt_calcmode" ng-model="config_creator.biztositas[0].price_calc_mode" ng-options="key as value for (key, value) in price_calc_modes">
                <option value="">-- ár jellege? --</option>
              </select>
            </div>
            <div class="save">
              <button type="button" class="button button-primary button-large" ng-hide="config_saving.programok" ng-click="saveConfig('biztositas')">Változások mentése</button>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
