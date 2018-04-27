<?php
global $traveler, $config_group, $mode, $termid;

$title = '';
$data = $traveler->getTermData( $termid );

switch ($mode) {
  case 'szolgaltatas':
    $title = 'Szolgáltatás szekesztése: '.$data->title;
  break;

  case 'programok':
    $title = 'Program szekesztése: '.$data->title;
  break;
}

?>
<md-dialog aria-label="<?=$title?>">
  <form ng-cloak>
    <md-toolbar>
      <div class="md-toolbar-tools">
        <h2><?=$title?></h2>
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
            <input ng-model="config_creator[<?=$mode?>].title">
          </md-input-container>
        </div>
      </div>
    </md-dialog-content>

    <md-dialog-actions layout="row">
      <md-button ng-click="answer('not useful')">
       Not Useful
      </md-button>
      <md-button ng-click="answer('useful')">
        Useful
      </md-button>
    </md-dialog-actions>
  </form>
</md-dialog>
