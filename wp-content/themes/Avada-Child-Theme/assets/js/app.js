var jnk = angular.module('jonapotnagyvilag', []);

jnk.controller('TravelConfigEditor', ['$scope', '$http', function($scope, $http)
{
  // Vars
  $scope.postid = 0;

  // Datas
  $scope.dates = [];

  // Flags
  $scope.loading = false;
  $scope.loaded = false;
  $scope.dates_loaded = false;

  $scope.init = function( postid )
  {
    $scope.postid = postid;
    $scope.loadDatas();
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
