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
              <div class="action">

              </div>
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
      <div class="no-data">
        Nincs szoba meghatározva ehhez az utazáshoz.
      </div>
    </div>
  </div>
  <div class="group">
    <div class="ghead">
      <span class="add">új szolgáltatás</span>
      <h2>Szolgáltatások</h2>
    </div>
    <div class="cont">
      <div class="no-data">
        Nincsenek szolgáltatások meghatározva ehhez az utazáshoz.
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
