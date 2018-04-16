<?php
  global $post;
?>
<div class="travel-editor" ng-app="jonapotnagyvilag" ng-controller="TravelConfigEditor" ng-init="init(<?=$post->ID?>)">
  <div class="group">
    <div class="ghead">
      <span class="add">új időpont</span>
      <h2>Időpontok</h2>
    </div>
    <div class="cont">
      <div class="no-data">
        <div class="loading-data" ng-hide="dates_loaded">
          <i class="fa fa-spin fa-spinner"></i> <?php echo __('Időpontok betöltése folyamatban', TD); ?>
        </div>
        <div class="no-data" ng-show="(dates_loaded && dates.length == 0)">
          Nincs időpont meghatározva ehhez az utazáshoz.
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
            </div>
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
