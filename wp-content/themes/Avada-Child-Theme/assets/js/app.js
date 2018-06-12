var jnk = angular.module('jonapotnagyvilag', ['ngMaterial', 'ngMessages']);

jnk.controller('TravelCalculator', ['$scope', '$http', '$mdToast', '$mdDialog', '$httpParamSerializerJQLike', function($scope, $http, $mdToast, $mdDialog, $httpParamSerializerJQLike)
{
  // Vars
  $scope.postid = 0;
  $scope.loading = false;
  $scope.loaded = false;
  $scope.dates_loaded = false;
  $scope.config_loaded = false;
  $scope.config_groups = ['szolgaltatas', 'programok', 'biztositas'];

  // Datas
  $scope.passengers = {
    adults: 2,
    children: 0
  };
  $scope.passengers_detail = {
    adults: [],
    children: []
  };
  $scope.final_calc_price = 0;
  $scope.calced_room_price = {};
  $scope.dates = [];
  $scope.datelist = {};
  $scope.terms = {};
  $scope.configs = {};
  $scope.biztositas = {};
  $scope.configs_selected = {
    programok: [],
    szolgaltatas: [],
    biztositas: 0
  };
  $scope.dateselect = {
    durration: false,
    year: false,
    date: false
  };
  $scope.order = {
    contact: {
      name: null,
      email: null,
      phone: null
    },
    accept: {
      term: false
    }
  };
  $scope.selected_room_id = 0;
  $scope.selected_room_data = {};
  $scope.selected_ellatas = false;
  $scope.selected_ellatas_data = false;
  $scope.selected_date_data = {};
  $scope.config_szolgaltatas_prices = 0;
  $scope.config_programok_prices = 0;
  $scope.travel_prices = 0;

  // Flags
  $scope.step = 1;
  $scope.max_step = 6;
  $scope.step_done = {
    1: false,
    2: false,
    3: false,
    4: false,
    5: false,
    6: false
  };
  $scope.step_loading = false;

  $scope.init = function( postid )
  {
    $scope.postid = postid;
    $scope.loadAll();
  }

  $scope.canSendPreOrder = function(){
    var can = true;

    if ( !$scope.order.accept.term ) {
      can = false;
    }

    if ( $scope.order.contact.name == null || $scope.order.contact.name == '' ) {
      can = false;
    }

    if ( $scope.order.contact.email == null || $scope.order.contact.email == '' ) {
      can = false;
    }

    if ( $scope.order.contact.phone == null || $scope.order.contact.phone == '' ) {
      can = false;
    }

    return can;
  }

  $scope.passengersDetailsCheck = function()
  {
    var ok = true;

    var adults = parseInt($scope.passengers.adults);
    var children = parseInt($scope.passengers.children);

    if ( adults !== 0 ) {
      for (var i = adults; i > 0; i--) {
        var ai = i-1;
        var co = $scope.passengers_detail.adults[ai];
        if(typeof co == 'undefined' || typeof co.name === 'undefined' || co.name == '') { ok = false; }
        if(typeof co == 'undefined' || typeof co.dob === 'undefined' || co.dob == '') { ok = false; }
        if(typeof co == 'undefined' || typeof co.address === 'undefined' || co.address == '') { ok = false; }
      }
    }

    if ( children !== 0 ) {
      for (var i = children; i > 0; i--) {
        var ai = i-1;
        var co = $scope.passengers_detail.children[ai];
        if(typeof co == 'undefined' || typeof co.name === 'undefined' || co.name == '') { ok = false; }
        if(typeof co == 'undefined' || typeof co.dob === 'undefined' || co.dob == '') { ok = false; }
        if(typeof co == 'undefined' || typeof co.address === 'undefined' || co.address == '') { ok = false; }
      }
    }

    return ok;
  }

  $scope.loadAll = function(){
    $scope.loading = true;
    $scope.loaded = false;
    $scope.loadTerms(function(){
      $scope.loadDatas();
    });
  }

  $scope.passengerArray = function( g ) {
    return new Array( parseInt($scope.passengers[g]) );
  }

  $scope.recalcFinalPrice = function(){
    var price = 0;

    // Utasok szobaárai
    price += $scope.calced_room_price[$scope.selected_room_data.ID].adults;
    price += $scope.calced_room_price[$scope.selected_room_data.ID].children;

    // Egyéb utazási költségek
    // TODO: utazási költség lista

    $scope.travel_prices = price;

    // Kötelező szolgálatások
    $scope.config_szolgaltatas_prices = 0;
    angular.forEach( $scope.configs.szolgaltatas, function(e,i) {
      if (e.requireditem) {
        var p = $scope.priceCalcSum(e);
        if ( p > 0 ) {
          price += p;
          $scope.config_szolgaltatas_prices += p;
        }
      }
    });

    // Kötelező programok
    $scope.config_programok_prices = 0;
    angular.forEach( $scope.configs.programok, function(e,i) {
      if (e.requireditem) {
        var p = $scope.priceCalcSum(e);
        if ( p > 0 ) {
          price += p;
          $scope.config_programok_prices += p;
        }
      }
    });

    // Extra konfig itemek kalkulációja
    var extraprices = $scope.calcExtraItemPrices();
    price += extraprices;

    $scope.final_calc_price = price;
  }

  $scope.loadTerms = function( callback )
  {
    $http({
      method: 'POST',
      url: '/wp-admin/admin-ajax.php?action=getterms',
      headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
      data: $httpParamSerializerJQLike({
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

  $scope.loadDatas = function( callback )
  {
    $scope.loadConfigTerms(function(){
      $scope.finishLoad();
      if (typeof callback !== 'undefined') {
        callback();
      }
    });
  }

  $scope.loadDates = function( callback )
  {
    $scope.dates_loaded = false;
    $scope.datelist = {};
    $http({
      method: 'POST',
      url: '/wp-admin/admin-ajax.php?action=travel_api',
      headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
      data: $httpParamSerializerJQLike({
        postid: $scope.postid,
        passengers: $scope.passengers
      })
    }).success(function(r){
      $scope.dates_loaded = true;
      $scope.dates = r.data;

      // list
      angular.forEach(r.data, function(a,i){
        angular.forEach(a.data, function(b,i){
          angular.forEach(b.data, function(date,i){
            $scope.datelist[date.ID] = date;
          });
        });
      });

      if (typeof callback !== 'undefined') {
        callback();
      }
    });
  }

  $scope.calcExtraItemPrices = function(){
    var price = 0;
    angular.forEach($scope.configs_selected, function(item, group){
      if (group == 'biztositas') {
        if( item !== 0) {
          price += $scope.priceCalcSum(item);
        }
      } else {
        angular.forEach(item, function(e, i){
          if (e === true) {
            var item = $scope.findConfigItemByID( group, i );
            var p = $scope.priceCalcSum(item);
            if(group == 'programok') {
              $scope.config_programok_prices += p;
            }
            if(group == 'szolgaltatas') {
              $scope.config_szolgaltatas_prices += p;
            }

            price += p;
          }
        });
      }
    });

    return price;
  }

  $scope.pickExtraItem = function()
  {
    $scope.recalcFinalPrice();
  }

  $scope.findConfigItemByID = function( what, id )
  {
    var list = $scope.configs[what];
    var item = null;

    angular.forEach( list, function(e, i){
      if( id == parseInt(e.ID) ) {
       item = e;
      }
    });

    return item;
  }

  $scope.selectRoom = function( id )
  {
    $scope.selected_room_id = id;
    $scope.selected_room_data = $scope.selected_ellatas_data.rooms[id];
  }

  $scope.selectEllatas = function( id )
  {
    $scope.selected_ellatas = id;
    $scope.selected_ellatas_data = $scope.getEllatasInfo(id);

    angular.forEach( $scope.selected_ellatas_data.rooms, function(e,i) {
      var calc = 0;
      $scope.calced_room_price[e.ID] = {};

      if ($scope.passengers.adults > 0) {
        $scope.calced_room_price[e.ID].adults = $scope.passengers.adults * (e.adult_price * $scope.selected_date_data.durration.nights);
        calc += $scope.passengers.adults * (e.adult_price * $scope.selected_date_data.durration.nights);
      } else {
        $scope.calced_room_price[e.ID].adults = 0;
      }

      if ($scope.passengers.children > 0) {
        $scope.calced_room_price[e.ID].children = $scope.passengers.children * (e.child_price * $scope.selected_date_data.durration.nights);
        calc += $scope.passengers.children * (e.child_price * $scope.selected_date_data.durration.nights);
      } else {
        $scope.calced_room_price[e.ID].children = 0;
      }

      $scope.calced_room_price[e.ID].all = calc;
    });
  }

  $scope.getEllatasInfo = function( id ){
    return $scope.configs.szobak[$scope.dateselect.date].ellatas[id];
  }

  $scope.loadEllatas = function( callback )
  {
    $http({
      method: 'POST',
      url: '/wp-admin/admin-ajax.php?action=getterms',
      headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
      data: $httpParamSerializerJQLike({
        postid: $scope.postid,
        terms: ['utazas_ellatas']
      })
    }).success(function(r){
      $scope.configs.ellatas = {};
      $scope.configs.ellatas = r.data.utazas_ellatas;
      if (typeof callback !== 'undefined') {
        callback();
      }
    });
  }

  $scope.loadConfigTerms = function( callback )
  {
    $scope.configs = {};
    $scope.config_loaded = false;

    $http({
      method: 'POST',
      url: '/wp-admin/admin-ajax.php?action=traveler',
      headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
      data: $httpParamSerializerJQLike({
        postid: $scope.postid,
        mode: 'getConfigTerms'
      })
    }).success(function(r){
      $scope.config_loaded = true;
      angular.forEach($scope.config_groups, function(c,i){
        $scope.configs[c] = r.data[c];
      });

      if ( r.data.biztositas && typeof r.data.biztositas[0] !== 'undefined' ) {
        $scope.biztositas = r.data.biztositas[0];
      }

      $http({
        method: 'POST',
        url: '/wp-admin/admin-ajax.php?action=traveler',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        data: $httpParamSerializerJQLike({
          postid: $scope.postid,
          mode: 'getRooms'
        })
      }).success(function(r){
        $scope.configs.szobak = r.data;
        if (typeof callback !== 'undefined') {
          callback();
        }
      });
    });
  }

  $scope.dateselectInfo = function() {
    var text = '';
    if ($scope.dateselect.durration && $scope.dateselect.year && $scope.dateselect.date) {
      $scope.selected_date_data = $scope.datelist[$scope.dateselect.date];
      var seldate = $scope.selected_date_data;
      text += seldate.travel_year+'. '+ seldate.travel_month+'. '+seldate.travel_day+'., '+$scope.datelist[$scope.dateselect.date].travel_weekday+' - '+seldate.durration.name+', '+seldate.durration.nights+' '+ 'éjszaka';
    }
    return text;
  }

  $scope.priceCalcSum = function( item ){
    var me = $scope.priceCalcMe( item );

    return parseFloat(item.price) * me;
  }

  $scope.priceCalcMe = function( item )
  {
    switch( item.price_calc_mode ) {
      case 'once':
        return 1;
      break;
      case 'daily':
      if ($scope.selected_date_data.durration) {
        var n = ($scope.selected_date_data) ? parseInt($scope.selected_date_data.durration.nights) : 0;
      }
        return n+1;
      break;
      case 'day_person':
        var fo = $scope.passengers.adults + $scope.passengers.children;
        if ($scope.selected_date_data.durration) {
          var n = ($scope.selected_date_data) ? parseInt($scope.selected_date_data.durration.nights) : 0;
        }
        return (n+1) * fo;
      break;
      case 'once_person':
        var fo = $scope.passengers.adults + $scope.passengers.children;
        return fo;
      break;
    }
  }

  $scope.selectCalcDurr = function( v ) {
    $scope.dateselect.durration = v;
    $scope.dateselect.year = false;
    $scope.dateselect.date = false;
  }

  $scope.selectCalcYearmonth = function( v ) {
    $scope.dateselect.year = v;
    $scope.dateselect.date = false;
  }

  $scope.selectCalcDate = function( dateid ) {
    $scope.dateselect.date = dateid;
  }

  $scope.finishLoad = function(){
    $scope.loading = false;
    $scope.loaded = true;
  }

  $scope.setStepDone = function( step ) {
    $scope.step_done[step] = true;
  }

  $scope.backToEdit = function( step ){
    $scope.step = step;

    // Reset steps fo false
    angular.forEach($scope.step_done, function(v,i){
      if ( i < step ) {
        $scope.step_done[i] = true;
      } else {
        $scope.step_done[i] = false;
      }
    });
  }

  $scope.nextStep = function( current )
  {
    $scope.step_loading = current;
    $scope.step = current+1;
    $scope.setStepDone(current);

    switch ( current ) {
      // Utasok megadása - Dátumok betöltése
      case 1:
        $scope.loadDates(function(){
          $scope.step_loading = false;
        });
      break;
      case 2:
        $scope.loadEllatas(function(){
          $scope.step_loading = false;
        });
      break;
      case 4:
        $scope.recalcFinalPrice();
      break;
      default:
        $scope.step_loading = false;
      break;
    }
  }

}]);

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
    'programok': [],
    'biztositas': []
  };
  $scope.config_saving = {
    'szolgaltatas': false,
    'szobak': false,
    'programok': false,
    'biztositas': false
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

  $scope.saveConfigData = function( id, data, callback ){
    $http({
      method: 'POST',
      url: '/wp-admin/admin-ajax.php?action=traveler',
      headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
      data: $.param({
        postid: $scope.postid,
        mode: 'saveConfigData',
        id: id,
        datas: data
      })
    }).success(function(r){
      if (typeof callback !== 'undefined') {
        callback( id, r );
      }
    });
  }

  $scope.saveRoomData = function( id, data, callback ){
    $http({
      method: 'POST',
      url: '/wp-admin/admin-ajax.php?action=traveler',
      headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
      data: $.param({
        postid: $scope.postid,
        mode: 'saveRoomData',
        id: id,
        datas: data
      })
    }).success(function(r){
      if (typeof callback !== 'undefined') {
        callback( id, r );
      }
    });
  }

  $scope.saveDateData = function( id, data, callback ){
    $http({
      method: 'POST',
      url: '/wp-admin/admin-ajax.php?action=traveler',
      headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
      data: $.param({
        postid: $scope.postid,
        mode: 'saveDateData',
        id: id,
        datas: data
      })
    }).success(function(r){
      if (typeof callback !== 'undefined') {
        callback( id, r );
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
      console.log(r);
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
      'active': true
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

  $scope.editConfigRecord = function( group, id ) {
    $scope.editorConfigModal(group, id);
  }

  $scope.deleteConfigRecord = function( group, elem, id){
    var del = $mdDialog.confirm()
          .title('Biztos, hogy törölni szeretné?')
          .parent(angular.element(document.body))
          .textContent('Törli a(z) "'+elem+'" elemet? A művelet nem visszavonható.')
          .ariaLabel('Elem törlése')
          .ok('Végleges törlés')
          .cancel('Mégse');

    $mdDialog.show(del).then(function() {
      $http({
        method: 'POST',
        url: '/wp-admin/admin-ajax.php?action=traveler',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        data: $.param({
          postid: $scope.postid,
          mode: 'deleteConfigData',
          id: id
        })
      }).success(function(r){
        $scope.loadAll();
      });
    }, function() {});
  }

  $scope.deleteDate = function( id, elem){
    var del = $mdDialog.confirm()
          .title('Biztos, hogy törölni szeretné?')
          .parent(angular.element(document.body))
          .textContent('Törli a(z) "'+elem+'" elemet? A művelet nem visszavonható.')
          .ariaLabel('Elem törlése')
          .ok('Végleges törlés')
          .cancel('Mégse');

    $mdDialog.show(del).then(function() {
      $http({
        method: 'POST',
        url: '/wp-admin/admin-ajax.php?action=traveler',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        data: $.param({
          postid: $scope.postid,
          mode: 'deleteDate',
          id: id
        })
      }).success(function(r){
        $scope.loadAll();
      });
    }, function() {});
  }

  $scope.deleteRoom = function( id, elem){
    var del = $mdDialog.confirm()
      .title('Biztos, hogy törölni szeretné?')
      .parent(angular.element(document.body))
      .textContent('Törli a(z) "'+elem+'" szoba konfigurációt? A művelet nem visszavonható.')
      .ariaLabel('Elem törlése')
      .ok('Végleges törlés')
      .cancel('Mégse');

    $mdDialog.show(del).then(function() {
      $http({
        method: 'POST',
        url: '/wp-admin/admin-ajax.php?action=traveler',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        data: $.param({
          postid: $scope.postid,
          mode: 'deleteRoomData',
          id: id
        })
      }).success(function(r){
        $scope.loadAll();
      });
    }, function() {});
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

      if ( r.data.biztositas && typeof r.data.biztositas[0] !== 'undefined' ) {
        $scope.config_creator.biztositas[0] = r.data.biztositas[0];
      }
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
      if (typeof callback !== 'undefined') {
        callback();
      }
    });

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

  $scope.editDate = function( id )
  {
    $scope.modalEditorData = {};

    // Preloader dialog
    $mdDialog.show({
      template: '<div class="preloader-text"><i class="fa fa-spin fa-spinner"></i><br><h2>Betöltés folyamatban!</h2>Kis türelmét kérjük.</div>',
      clickOutsideToClose: false,
      parent: angular.element(document.body)
    });

    $http({
      method: 'POST',
      url: '/wp-admin/admin-ajax.php?action=traveler',
      headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
      data: $.param({
        postid: $scope.postid,
        id: id,
        mode: 'getDateData'
      })
    }).success(function(r){
      console.log(r);
      $scope.modalEditorData = r.data;
      $mdDialog.show({
        parent: angular.element(document.body),
        multiple: true,
        controller: DateConfigModalController,
        templateUrl: '/travelmodalconfig/'+$scope.postid+'/dateeditor/edit/'+id,
        clickOutsideToClose: false,
        scope: $scope,
        preserveScope: true,
        onShowing: function(){
          // Preloader dialog close
          $mdDialog.hide();
        }
      })
      .then(function(answer) {
        console.log('You said the information was');
      }, function() {
        console.log('You cancelled the dialog.');
      });
    });
  }

  $scope.editRoom = function( id )
  {
    $scope.modalEditorData = {};

    // Preloader dialog
    $mdDialog.show({
      template: '<div class="preloader-text"><i class="fa fa-spin fa-spinner"></i><br><h2>Betöltés folyamatban!</h2>Kis türelmét kérjük.</div>',
      clickOutsideToClose: false,
      parent: angular.element(document.body)
    });

    $http({
      method: 'POST',
      url: '/wp-admin/admin-ajax.php?action=traveler',
      headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
      data: $.param({
        postid: $scope.postid,
        id: id,
        mode: 'getRoomData'
      })
    }).success(function(r){
      $scope.modalEditorData = r.data;
      $mdDialog.show({
        parent: angular.element(document.body),
        multiple: true,
        controller: RoomConfigModalController,
        templateUrl: '/travelmodalconfig/'+$scope.postid+'/roomedit/edit/'+id,
        clickOutsideToClose: false,
        scope: $scope,
        preserveScope: true,
        onShowing: function(){
          // Preloader dialog close
          $mdDialog.hide();
        }
      })
      .then(function(answer) {
        console.log('You said the information was');
      }, function() {
        console.log('You cancelled the dialog.');
      });
    });
  }

  $scope.editorConfigModal = function( group, id, index )
  {
    $scope.modalEditorData = {};

    // Preloader dialog
    $mdDialog.show({
      parent: angular.element(document.body),
      template: '<div class="preloader-text"><i class="fa fa-spin fa-spinner"></i><br><h2>Betöltés folyamatban!</h2>Kis türelmét kérjük.</div>',
      clickOutsideToClose: false
    });

    $http({
      method: 'POST',
      url: '/wp-admin/admin-ajax.php?action=traveler',
      headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
      data: $.param({
        postid: $scope.postid,
        id: id,
        group: group,
        mode: 'getConfigData'
      })
    }).success(function(r){
      $scope.modalEditorData = r.data;
      $mdDialog.show({
        parent: angular.element(document.body),
        multiple: true,
        controller: ConfigModalController,
        templateUrl: '/travelmodalconfig/'+$scope.postid+'/termconfig/'+group+'/'+id,
        clickOutsideToClose: false,
        scope: $scope,
        preserveScope: true,
        onShowing: function(){
          // Preloader dialog close
          $mdDialog.hide();
        }
      })
      .then(function(answer) {
        console.log('You said the information was');
      }, function() {
        console.log('You cancelled the dialog.');
      });
    });
  }



  function DateConfigModalController( $scope, $mdDialog ) {
    $scope.hide = function() {
      $mdDialog.hide();
    };

    $scope.cancel = function() {
      $mdDialog.cancel();
    };

    $scope.answer = function(answer) {
      $mdDialog.hide(answer);
    };

    $scope.saveConfig = function(){
      $scope.saving_dialog = true;
      $scope.saveDateData($scope.modalEditorData.ID, $scope.modalEditorData, function(id, back){
        console.log(back);
        $mdDialog.hide();
        $scope.modalEditorData = {};
        $scope.saving_dialog = false;
        $scope.loadAll();
      });
    };
  }

  function RoomConfigModalController( $scope, $mdDialog ) {
    $scope.hide = function() {
      $mdDialog.hide();
    };

    $scope.cancel = function() {
      $mdDialog.cancel();
    };

    $scope.answer = function(answer) {
      $mdDialog.hide(answer);
    };

    $scope.saveConfig = function(){
      $scope.saving_dialog = true;
      $scope.saveRoomData($scope.modalEditorData.ID, $scope.modalEditorData, function(){
        $mdDialog.hide();
        $scope.modalEditorData = {};
        $scope.saving_dialog = false;
        $scope.loadAll();
      });
    };
  }

  function ConfigModalController( $scope, $mdDialog ) {
    $scope.hide = function() {
      $mdDialog.hide();
    };

    $scope.cancel = function() {
      $mdDialog.cancel();
    };

    $scope.answer = function(answer) {
      $mdDialog.hide(answer);
    };

    $scope.saveConfig = function(){
      $scope.saving_dialog = true;
      $scope.saveConfigData($scope.modalEditorData.ID, $scope.modalEditorData, function(){
        $mdDialog.hide();
        $scope.modalEditorData = {};
        $scope.saving_dialog = false;
        $scope.loadAll();
      });
    };
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
