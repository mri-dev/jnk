var jnk = angular.module('jonapotnagyvilag', ['ngMaterial', 'ngMessages']);

jnk.controller('TravelConfigEditor', ['$scope', '$http', '$mdToast', '$mdDialog', function($scope, $http, $mdToast, $mdDialog)
{
  // Vars
  $scope.postid = 0;
  $scope.range_months = [];
  $scope.range_days = [];
  $scope.date = new Date();
  $scope.terms = {};
  $scope.config_groups = ['szolgaltatas', 'programok'];
  $scope.price_calc_modes = {
    'once': 'Egyszeri díj',
    'daily': '/nap',
    'once_person': '/fő',
    'day_person': '/fő/nap'
  };
  $scope.configs = {};
  $scope.config_creator = {
    'szolgaltatas': [],
    'szobak': [],
    'programok': []
  };
  $scope.config_saving = {
    'szolgaltatas': false,
    'szobak': false,
    'programok': false
  };

  // Datas
  $scope.dates = [];
  $scope.dates_create = [];

  // Flags
  $scope.loading = false;
  $scope.loaded = false;
  $scope.dates_loaded = false;
  $scope.dates_saving = false;

  $scope.init = function( postid )
  {
    $scope.postid = postid;
    $scope.prepareRanges();
    $scope.loadAll();
  }

  $scope.loadAll = function(){
    $scope.loading = true;
    $scope.loadTerms(function(){
      $scope.finishLoad();
      $scope.loadDatas(function(){});
    });
  }

  $scope.finishLoad = function(){
    $scope.loading = false;
    $scope.loaded = true;
  }

  $scope.saveConfig = function( group ) {
    $scope.config_saving[group] = true;

    $http({
      method: 'POST',
      url: '/wp-admin/admin-ajax.php?action=traveler',
      headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
      data: $.param({
        postid: $scope.postid,
        mode: 'saveConfigTerm',
        group: group,
        data: $scope.config_creator[group]
      })
    }).success(function(r){
      $scope.config_saving[group] = false;

      if (r.error == 1) {
        $scope.alertDialog('Hiba történt', r.msg);
      } else {
        $scope.config_creator[group] = [];
        $scope.loadAll();
      }
    });
  }

  $scope.saveDates = function() {
    $scope.dates_saving = true;
    $http({
      method: 'POST',
      url: '/wp-admin/admin-ajax.php?action=traveler',
      headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
      data: $.param({
        postid: $scope.postid,
        mode: 'saveDates',
        data: $scope.dates_create
      })
    }).success(function(r){
      $scope.dates_saving = false;
      if (r.error == 1) {
        $scope.alertDialog('Hiba történt', r.msg);
      } else {
        $scope.dates_create = [];
        $scope.loadAll();
      }
    });
  }

  $scope.loadTerms = function( callback )
  {
    $http({
      method: 'POST',
      url: '/wp-admin/admin-ajax.php?action=getterms',
      headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
      data: $.param({
        postid: $scope.postid,
        terms: ['utazas_duration', 'utazas_ellatas']
      })
    }).success(function(r){
      angular.forEach(r.data, function(e,i){
        $scope.terms[i] = e;
      });
      if (typeof callback !== 'undefined') {
        callback();
      }
    });
  }

  $scope.prepareRanges = function() {
    for( var i = 1; i <= 12; i++){
      $scope.range_months.push(i);
    }
    for( var i = 1; i <= 31; i++){
      $scope.range_days.push(i);
    }
  }

  $scope.addDate = function() {
    $scope.dates_create.push({
      'travel_year': $scope.date.getFullYear(),
      'travel_month': ($scope.date.getMonth()+1),
      'travel_day': $scope.date.getUTCDate(),
      'utazas_duration_id': 0,
      'price_from': 10000
    });
  }

  $scope.addConfig = function( group ) {
    if ( group == 'szobak' ) {
      $scope.config_creator[group].push({
        'title': '',
        'description': '',
        'adult_price': 0,
        'child_price': 0,
        'ellatas_id': 0,
        'date_id': 0,
        'adult_capacity': 1,
        'child_capacity': 0,
        'active': true
      });
    } else {
      $scope.config_creator[group].push({
        'title': '',
        'description': '',
        'price': 0,
        'requireditem': false,
        'price_calc_mode': 0
      });
    }
  }

  $scope.removeEditorDate = function(index) {
    $scope.dates_create.splice(index, 1);
  }

  $scope.removeConfigEditorDate = function( group, index ){
    $scope.config_creator[group].splice(index, 1);
  }

  $scope.loadDatas = function( callback )
  {
    $scope.loadDates();
    $scope.loadConfigTerms();

    if (typeof callback !== 'undefined') {
      callback();
    }
  }

  $scope.loadConfigTerms = function()
  {
    $scope.configs = {};

    $http({
      method: 'POST',
      url: '/wp-admin/admin-ajax.php?action=traveler',
      headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
      data: $.param({
        postid: $scope.postid,
        mode: 'getConfigTerms'
      })
    }).success(function(r){
      angular.forEach($scope.config_groups, function(c,i){
        $scope.configs[c] = r.data[c];
      });
    });

    $http({
      method: 'POST',
      url: '/wp-admin/admin-ajax.php?action=traveler',
      headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
      data: $.param({
        postid: $scope.postid,
        mode: 'getRooms'
      })
    }).success(function(r){
      $scope.configs.szobak = r.data;
    });
  }

  $scope.loadDates = function( callback )
  {
    $scope.dates_loaded = false;
    $http({
      method: 'POST',
      url: '/wp-admin/admin-ajax.php?action=travel_api',
      headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
      data: $.param({
        postid: $scope.postid
      })
    }).success(function(r){
      $scope.dates_loaded = true;
      $scope.dates = r.data;
    });

    if (typeof callback !== 'undefined') {
      callback();
    }
  }

  $scope.alertDialog = function(title, desc) {
    $mdDialog.show(
      $mdDialog.alert()
        .clickOutsideToClose(true)
        .title(title)
        .textContent(desc)
        .ariaLabel('Hibaüzenet')
        .ok('Rendben')
    );
  }

  $scope.toast = function( text, mode, delay ){
		mode = (typeof mode === 'undefined') ? 'simple' : mode;
    delay = (typeof delay === 'undefined') ? 5000 : delay;

		if (typeof text !== 'undefined') {
			$mdToast.show(
				$mdToast.simple()
				.textContent(text)
				.position('top')
				.toastClass('alert-toast mode-'+mode)
				.hideDelay(delay)
			);
		}
	}
}]);
