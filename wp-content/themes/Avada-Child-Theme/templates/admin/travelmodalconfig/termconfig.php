<?php
global $traveler, $config_group, $mode, $termid;

$title = '';
$data = $traveler->getTermData( $termid );

switch ($mode) {
  case 'szolgaltatas':
    $title = 'Szolgáltatás';
  break;

  case 'programok':
    $title = 'Program';
  break;
}

?>
<md-dialog aria-label="<?=$title?> szekesztése: {{modalEditorData.title}}">
  <form ng-cloak>
    <md-toolbar>
      <div class="md-toolbar-tools">
        <h2><?=$title?> szekesztése: {{modalEditorData.title}}</h2>
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
            <label>Ár</label>
            <input ng-model="modalEditorData.price">
          </md-input-container>
          <md-input-container class="md-block" flex-gt-sm>
            <label>Ár jellege</label>
            <md-select class="" ng-model="modalEditorData.price_calc_mode">
              <md-option ng-value="key" ng-repeat="(key, value) in price_calc_modes">{{value}}</md-option>
            </md-select>
          </md-input-container>
        </div>
        <div layout-gt-sm="row">
          <md-input-container class="md-block" flex-gt-sm>
            <md-checkbox name="tos" ng-model="modalEditorData.requireditem">
              Kötelező elem
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
