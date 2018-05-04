<?php
global $traveler, $config_group, $mode, $termid;

?>
<md-dialog aria-label="{{modalEditorData.onday}} - szerkesztése">
  <form ng-cloak>
    <md-toolbar>
      <div class="md-toolbar-tools">
        <h2>{{modalEditorData.onday}} - szerkesztése</h2>
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
            <md-select class="" ng-model="modalEditorData.travel_month">
              <md-option ng-value="month" ng-repeat="month in range_months">{{month}}</md-option>
            </md-select>
          </md-input-container>
          <md-input-container class="md-block" flex-gt-sm>
            <label>Hó</label>
            <md-select class="" ng-model="modalEditorData.travel_day">
              <md-option ng-value="day" ng-repeat="day in range_days">{{day}}</md-option>
            </md-select>
          </md-input-container>
        </div>
        <div layout-gt-sm="row">
          <md-input-container class="md-block" flex-gt-sm>
            <label>Utazás hossza</label>
            <md-select class="" ng-model="modalEditorData.utazas_duration_id">
              <md-option ng-value="term.term_id" ng-repeat="term in terms.utazas_duration">{{term.name}}</md-option>
            </md-select>
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
