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
        {{dates}}
        Nincs időpont meghatározva ehhez az utazáshoz.
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
