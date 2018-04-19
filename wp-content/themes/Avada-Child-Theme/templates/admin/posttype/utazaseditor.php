<?php
  global $post;
?>
<div class="travel-editor" ng-class="(loading)?'loading':''" ng-app="jonapotnagyvilag" ng-controller="TravelConfigEditor" ng-init="init(<?=$post->ID?>)">
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
        <div class="datas-header" ng-show="(dates_loaded && dates)">
          <div class="data-line">
            <div class="wrapper">
              <div class="date">
                Időpont
              </div>
              <div class="durration">
                Utazás hossza
              </div>
              <div class="price">
                Alapár
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
              <div class="price">
                {{d.price_from}} Ft
              </div>
              <div class="action">
                <span ng-click="editDate(d.ID)">szerkeszt</span>
              </div>
            </div>
          </div>
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
              <div class="price">
                <input type="number" class="fullw" ng-model="dates_create[i].price_from">
              </div>
              <div class="action">
                <span ng-click="removeEditorDate(i)">töröl</span>
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

      </div>
    </div>
  </div>
  <div class="group">
    <div class="ghead">
      <span class="add">új szoba</span>
      <h2>Szobák</h2>
    </div>
    <div class="cont">
      <div class="loading-data" ng-hide="configs.szobak">
        <i class="fa fa-spin fa-spinner"></i> <?php echo __('Szobák betöltése folyamatban', TD); ?>
      </div>
      <div class="no-data" ng-show="(configs.szobak && configs.szobak.length == 0)">
        Nincs szoba meghatározva ehhez az utazáshoz.
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
              <i ng-show="(d.requireditem=='0')" class="fa fa-times"></i>
            </div>
            <div class="price">
              {{d.price}} {{d.price_after}}
            </div>
            <div class="action">
              <span ng-click="">szerkeszt</span>
            </div>
          </div>
        </div>
      </div>

      <div class="create-line" ng-show="(config_creator.szolgaltatas.length!=0)">
        <div class="header">
          Új szolgáltatás hozzáadása
        </div>
        <div class="new-line" ng-repeat="(i,n) in config_creator.szolgaltatas">
          <div class="wrapper">
            <div class="title">
              <input type="text" class="fullw" ng-model="config_creator.szolgaltatas[i].title">
              <div class="desc">
                <input type="text" class="fullw" ng-model="config_creator.szolgaltatas[i].description">
              </div>
            </div>
            <div class="req center">
              <input type="checkbox" ng-model="config_creator.szolgaltatas[i].requireditem">
            </div>
            <div class="price">
              <input type="number" class="fullw" ng-model="config_creator.szolgaltatas[i].price">
              <select class="" ng-model="config_creator.szolgaltatas[i].price_calc_mode" ng-options="key as value for (key, value) in price_calc_modes">
                <option value="">-- válasszon --</option>
              </select>
            </div>
            <div class="action">
              <span ng-click="removeConfigEditorDate('szolgaltatas', i)">töröl</span>
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


    </div>
  </div>
  <div class="group">
    <div class="ghead">
      <span class="add">új program</span>
      <h2>Programok</h2>
    </div>
    <div class="cont">
      <div class="no-data">
        Nincsenek programok meghatározva ehhez az utazáshoz.
      </div>
    </div>
  </div>
</div>
