<?php
global $traveler, $config_group, $mode, $termid;

?>
<md-dialog aria-label="{{modalEditorData.title}} - szerkesztése">
  <form ng-cloak>
    <md-toolbar>
      <div class="md-toolbar-tools">
        <h2>{{modalEditorData.title}} - szerkesztése</h2>
        <span flex></span>
        <md-button class="md-icon-button" ng-click="cancel()">
          <md-icon md-svg-src="img/icons/ic_close_24px.svg" aria-label="Close dialog"></md-icon>
        </md-button>
      </div>
    </md-toolbar>

    <md-dialog-content>
      <div class="md-dialog-content">
        <div layout-gt-sm="row">
          <md-input-container class="md-block" flex-gt-sm>
            <label>Év</label>
            <input ng-model="modalEditorData.travel_year">
          </md-input-container>
          <md-input-container class="md-block" flex-gt-sm>
            <label>Hó</label>
            <input ng-model="modalEditorData.travel_month">
          </md-input-container>
          <md-input-container class="md-block" flex-gt-sm>
            <label>Nap</label>
            <input ng-model="modalEditorData.travel_day">
          </md-input-container>
        </div>
        <div layout-gt-sm="row">
          <md-input-container class="md-block" flex-gt-sm>
            <label>Megnevezés</label>
            <input ng-model="modalEditorData.title">
          </md-input-container>
        </div>
        <div layout-gt-sm="row">
          <md-input-container class="md-block" flex-gt-sm>
            <label>Rövid leírás</label>
            <input ng-model="modalEditorData.description">
          </md-input-container>
        </div>
        <div layout-gt-sm="row">
          <md-input-container class="md-block" flex-gt-sm>
            <label>Felnőtt Kapacitás</label>
            <input ng-model="modalEditorData.adult_capacity">
          </md-input-container>
          <md-input-container class="md-block" flex-gt-sm>
            <label>Gyermek Kapacitás</label>
            <input ng-model="modalEditorData.child_capacity">
          </md-input-container>
        </div>
        <div layout-gt-sm="row">
          <md-input-container class="md-block" flex-gt-sm>
            <label>Felnőtt Ár (Ft / fő)</label>
            <input ng-model="modalEditorData.adult_price">
          </md-input-container>
          <md-input-container class="md-block" flex-gt-sm>
            <label>Gyermek Ár (Ft / fő)</label>
            <input ng-model="modalEditorData.child_price">
          </md-input-container>
        </div>
        <div layout-gt-sm="row">
          <md-input-container class="md-block" flex-gt-sm>
            <md-checkbox name="tos" ng-model="modalEditorData.active">
              Aktív
            </md-checkbox>
          </md-input-container>
        </div>
        <div layout-gt-sm="row" class="saving-dialog-data" ng-show="saving_dialog">
          Adatok mentése folyamatban...
        </div>
      </div>
    </md-dialog-content>

    <md-dialog-actions layout="row" ng-hide="saving_dialog">
      <md-button ng-click="cancel()">
        Mégse
      </md-button>
      <md-button ng-click="saveConfig()">
        Változások mentése
      </md-button>
    </md-dialog-actions>
  </form>
</md-dialog>
