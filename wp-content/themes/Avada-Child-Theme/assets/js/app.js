var jnk = angular.module('jonapotnagyvilag', []);

jnk.controller('TravelConfigEditor', ['$scope', '$http', function($scope, $http)
{
  // Vars
  $scope.postid = 0;
  $scope.range_months = [];
  $scope.range_days = [];
  $scope.date = new Date();
  $scope.terms = {};

  // Datas
  $scope.dates = [];
  $scope.dates_create = [];

  // Flags
  $scope.loading = false;
  $scope.loaded = false;
  $scope.dates_loaded = false;

  $scope.init = function( postid )
  {
    $scope.postid = postid;
    $scope.prepareRanges();
    $scope.loadTerms(function(){
      $scope.loadDatas();
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
        terms: ['utazas_duration']
      })
    }).success(function(r){

      angular.forEach(r.data, function(e,i){
        $scope.terms[i] = e;
      });
      console.log($scope.terms);
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
      'travel_year': '',
      'travel_month': '',
      'travel_day': '',
      'durration_id': '',
      'price_from': 10000
    });
  }

  $scope.removeEditorDate = function(index) {
    $scope.dates_create.splice(index, 1);
  }

  $scope.loadDatas = function( callback )
  {
    $scope.loadDates();

    if (typeof callback !== 'undefined') {
      callback();
    }
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
      console.log(r);
    });

    if (typeof callback !== 'undefined') {
      callback();
    }
  }

}]);
