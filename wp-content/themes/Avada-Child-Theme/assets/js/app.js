var jnk = angular.module('jonapotnagyvilag', ['ngMaterial', 'ngMessages', 'ngMaterialDateRangePicker']);

jnk.controller('TravelCalculator', ['$scope', '$http', '$mdToast', '$mdDialog', '$httpParamSerializerJQLike', '$mdDateRangePicker', function($scope, $http, $mdToast, $mdDialog, $httpParamSerializerJQLike, $mdDateRangePicker)
{
  // Vars
  var date = new Date();

  $scope.translate = function( t ) {
    return $scope.translateTexts[t][$scope.lang];
  }

  $scope.lang = 'hu_HU';
  $scope.blogid = 1;
  $scope.translateTexts = {
    '3 nap': {
      'hu_HU': '3 nap',
      'en_US': '3 days'
    },
    '5 nap': {
      'hu_HU': '5 nap',
      'en_US': '5 days'
    },
    '1 hét': {
      'hu_HU': '1 hét',
      'en_US': '1 week'
    },
    'éjszaka': {
      'hu_HU': 'éjszaka',
      'en_US': 'nights'
    },
    'Sikeresen elküldte ajánlatkérését! Köszönjük, hogy érdeklődik szolgáltatásaink iránt!': {
      'hu_HU': 'Sikeresen elküldte ajánlatkérését! Köszönjük, hogy érdeklődik szolgáltatásaink iránt!',
      'en_US': 'Successfull sent your travel request!'
    },
    'Nem sikerült elküldeni az ajánlatkérést. Próbálja meg később!': {
      'hu_HU': 'Nem sikerült elküldeni az ajánlatkérést. Próbálja meg később!',
      'en_US': 'Could not submit request now. Try it later!'
    }    
  };

  $scope.init = function( postid, lang, blogid )
  {
    $scope.blogid = blogid;
    $scope.lang = lang;
    $scope.postid = postid;
    $scope.loadAll();

    $scope.customPickerTemplates = [
      {
        name: $scope.translate('3 nap'),
        dateStart: new Date(date.getFullYear(), date.getMonth(), date.getDate() + 2),
        dateEnd: new Date(date.getFullYear(), date.getMonth(), date.getDate() + 4)
      },
      {
        name: $scope.translate('5 nap'),
        dateStart: new Date(date.getFullYear(), date.getMonth(), date.getDate() + 2),
        dateEnd: new Date(date.getFullYear(), date.getMonth(), date.getDate() + 6)
      },
      {
        name: $scope.translate('1 hét'),
        dateStart: new Date(date.getFullYear(), date.getMonth(), date.getDate() + 2),
        dateEnd: new Date(date.getFullYear(), date.getMonth(), date.getDate() + 8)
      }
    ];

    $scope.calendarModel = {
      selectedTemplate: $scope.translate('3 nap'),
      selectedTemplateName: null,
      dateStart: new Date(date.getFullYear(), date.getMonth(), date.getDate() + 2),
      dateEnd: new Date(date.getFullYear(), date.getMonth(), date.getDate() + 4)
    };

    if ($scope.lang == 'hu_HU') {
      $scope.localizationMap = {
        'Mon': 'H',
        'Tue': 'K',
        'Wed': 'Sz',
        'Thu': 'Cs',
        'Fri': 'P',
        'Sat': 'Szo',
        'Sun': 'V',
        'Today': 'Ma',
        'Yesterday': 'Tegnap',
        'This week': 'Ez a hét',
        'Last week': 'Utolsó hét',
        'This month': 'Ez a hónap',
        'Last month': 'Utolsó hónap',
        'This year': 'Ez az év',
        'Last year': 'Utolsó év',
        'January': 'Január',
        'February': 'Február',
        'March': 'Március',
        'April': 'Április',
        'May': 'Május',
        'June': 'Június',
        'July': 'Július',
        'August': 'Augusztus',
        'September': 'Szeptember',
        'October': 'Október',
        'November': 'November',
        'December': 'December'
      };
    }
  }

  $scope.postid = 0;
  $scope.loading = false;
  $scope.loaded = false;
  $scope.dates_loaded = false;
  $scope.config_loaded = false;
  $scope.config_groups = ['szolgaltatas', 'programok', 'biztositas'];
  $scope.preorder_sending = false;
  $scope.price_before = '';
  $scope.price_after = '';
  $scope.valuta = null;
  $scope.price_after = ' Ft';
  $scope.price_before = '';

  // Datas
  $scope.nights = 0;
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
  $scope.preorder_msg = {
    error: 1,
    msg: null
  };
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
  $scope.selectedUniqueDate = false;
  $scope.selected_room_id = 0;
  $scope.selected_room_data = {};
  $scope.selected_ellatas = false;
  $scope.selected_ellatas_data = false;
  $scope.selected_date_data = {};
  $scope.selected_programs = {};
  $scope.selected_szolgaltatas = {};
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

  // datepicker

  $scope.isDisabledDate = function( d ) {
    if (
      d < date
    ) {
      return true;
    } else return false;
  };

  $scope.doPreOrder = function()
  {
    $scope.preorder_sending = true;
    var prepare = {};

    prepare.passengers = $scope.passengers;
    prepare.configs = $scope.configs_selected;
    prepare.biztositas = $scope.biztositas;
    prepare.roomprice = $scope.calced_room_price;
    prepare.passengers_details = $scope.passengers_detail;
    prepare.final_calc_price = $scope.final_calc_price;
    prepare.order = $scope.order;
    prepare.selected_date = $scope.selected_date_data;
    prepare.selected_room = $scope.selected_room_data;
    prepare.selected_ellatas = $scope.selected_ellatas_data;
    prepare.selected_programs = $scope.selected_programs;
    prepare.selected_szolgaltatas = $scope.selected_szolgaltatas;
    prepare.config_szolgaltatas_prices = $scope.config_szolgaltatas_prices;
    prepare.config_programok_prices = $scope.config_programok_prices;
    prepare.travel_prices = $scope.travel_prices;
    prepare.egyeni = ($scope.dates.length == 0) ? 1 : 0;
    prepare.datepicker = $scope.calendarModel;
    prepare.nights = $scope.nights;
    prepare.valuta = $scope.valuta;

    $http({
      method: 'POST',
      url: '/wp-admin/admin-ajax.php?action=traveler&blogid='+$scope.blogid,
      headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
      data: $httpParamSerializerJQLike({
        postid: $scope.postid,
        mode: 'sendPreOrder',
        calculator: prepare,
        blogid: $scope.blogid
      })
    }).success(function(r){
      console.log(r);
      $scope.preorder_sending = false;
      if (r.data) {
        // Reset
        //$scope.backToEdit(1);
        $scope.preorder_msg.error = 0;
        $scope.preorder_msg.msg = $scope.translate('Sikeresen elküldte ajánlatkérését! Köszönjük, hogy érdeklődik szolgáltatásaink iránt!');
      } else {
        // Nem küldte el a levelet.
        $scope.preorder_msg.error = 1;
        $scope.preorder_msg.msg = $scope.translate('Nem sikerült elküldeni az ajánlatkérést. Próbálja meg később!');
      }
    });
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

    // Reset
    $scope.selected_programs = {};
    $scope.selected_szolgaltatas = {};

    // Utasok szobaárai
    if (typeof $scope.calced_room_price[$scope.selected_room_data.ID] !== 'undefined') {
      price += $scope.calced_room_price[$scope.selected_room_data.ID].adults;
      price += $scope.calced_room_price[$scope.selected_room_data.ID].children;
    }

    // Egyéb utazási költségek
    // TODO: utazási költség lista

    $scope.travel_prices = price;

    // Kötelező szolgálatások
    $scope.config_szolgaltatas_prices = 0;
    angular.forEach( $scope.configs.szolgaltatas, function(e,i) {
      if (e.requireditem) {
        var p = $scope.priceCalcSum(e);
        if ( p > 0 ) {
          if (typeof $scope.selected_szolgaltatas[e.ID] === 'undefined') {
            $scope.selected_szolgaltatas[e.ID] = e;
          }
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
          if (typeof $scope.selected_programs[e.ID] === 'undefined') {
            $scope.selected_programs[e.ID] = e;
          }
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
        terms: ['utazas_duration', 'utazas_ellatas'],
        blogid: $scope.blogid
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
        passengers: $scope.passengers,
        blogid: $scope.blogid
      })
    }).success(function(r){
      $scope.dates_loaded = true;
      $scope.dates = r.data;

      console.log(r);

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
              if (typeof $scope.selected_programs[item.ID] === 'undefined') {
                $scope.selected_programs[item.ID] = item;
              }
              $scope.config_programok_prices += p;
            }
            if(group == 'szolgaltatas') {
              if (typeof $scope.selected_programs[item.ID] === 'undefined') {
                $scope.selected_szolgaltatas[item.ID] = item;
              }
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

  $scope.findConfigItemByID = function( what, id, by )
  {
    var list = $scope.configs[what];
    var item = null;
    by = (typeof by === 'undefined') ? 'ID' : by;

    angular.forEach( list, function(e, i){
      if( id == parseInt(e[by]) ) {
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

  $scope.getNightsByDateDiff = function( end, start ){
    var d_end = new Date(end);
    var d_start = new Date(start);
    var diff = Math.round((d_end-d_start)/(1000*60*60*24));

    return diff;
  }

  $scope.selectEllatas = function( id )
  {
    $scope.selected_ellatas = id;

    if ($scope.configs.szobak.length == 0 ) {
      $scope.selected_ellatas_data = {};
      $scope.selected_ellatas_data.ellatas = $scope.getEllatasInfo(id);
    } else {
      $scope.selected_ellatas_data = $scope.getEllatasInfo(id);
    }

    var nights = 1;
    if ($scope.dates.length == 0) {
      // Egyéni utazás esetén az éjszaka kiszámítása
      nights = $scope.getNightsByDateDiff($scope.calendarModel.dateEnd, $scope.calendarModel.dateStart);
    } else {
      // Csoportos utazás esetén
      nights = parseInt($scope.selected_date_data.durration.nights);
    }

    if ($scope.selected_ellatas_data.rooms) {
      angular.forEach( $scope.selected_ellatas_data.rooms, function(e,i) {
        var calc = 0;
        $scope.calced_room_price[e.ID] = {};

        if ($scope.passengers.adults > 0) {
          $scope.calced_room_price[e.ID].adults = $scope.passengers.adults * (e.adult_price * nights);
          calc += $scope.passengers.adults * (e.adult_price * nights);
        } else {
          $scope.calced_room_price[e.ID].adults = 0;
        }

        if ($scope.passengers.children > 0) {
          $scope.calced_room_price[e.ID].children = $scope.passengers.children * (e.child_price * nights);
          calc += $scope.passengers.children * (e.child_price * nights);
        } else {
          $scope.calced_room_price[e.ID].children = 0;
        }

        $scope.calced_room_price[e.ID].all = calc;
      });
    }
  }

  $scope.getEllatasInfo = function( id )
  {
    if ($scope.dates.length == 0) {
      // Egyéni utazások
      if ($scope.configs.szobak.length != 0) {
        return $scope.configs.szobak[0].ellatas[id];
      } else {
        return $scope.findConfigItemByID('ellatas', id, 'term_id');
      }
    } else {
      // Csoportos utazások
      if ($scope.configs.szobak.length != 0) {
      return $scope.configs.szobak[$scope.dateselect.date].ellatas[id];
      } else {
        return $scope.findConfigItemByID('ellatas', id, 'term_id');
      }
    }
  }

  $scope.loadEllatas = function( callback )
  {
    $http({
      method: 'POST',
      url: '/wp-admin/admin-ajax.php?action=getterms',
      headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
      data: $httpParamSerializerJQLike({
        postid: $scope.postid,
        terms: ['utazas_ellatas'],
        blogid: $scope.blogid
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
        mode: 'getConfigTerms',
        blogid: $scope.blogid
      })
    }).success(function(r)
    {
      console.log(r);
      $scope.config_loaded = true;
      angular.forEach($scope.config_groups, function(c,i){
        $scope.configs[c] = r.data[c];
      });

      if ( r.data.biztositas && typeof r.data.biztositas[0] !== 'undefined' ) {
        $scope.biztositas = r.data.biztositas[0];
      }

      // Valuta
      if (r.data.valuta) {
        $scope.valuta = r.data.valuta;
        if (r.data.valuta.name == 'Ft') {
          $scope.price_after = ' '+r.data.valuta.name;
          $scope.price_before = '';
        } else {
          $scope.price_after = '';
          $scope.price_before = r.data.valuta.name;
        }
      } else {
        $scope.price_after = ' Ft';
        $scope.price_before = '';
      }

      $http({
        method: 'POST',
        url: '/wp-admin/admin-ajax.php?action=traveler',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        data: $httpParamSerializerJQLike({
          postid: $scope.postid,
          mode: 'getRooms',
          blogid: $scope.blogid
        })
      }).success(function(r){
        $scope.configs.szobak = r.data;
        if (typeof callback !== 'undefined') {
          callback();
        }
      });
    });
  }

  $scope.dateselectInfo = function()
  {
    var text = '';
    // Csoportos utazások
    if ($scope.dates.length != 0) {
      if ($scope.dateselect.durration && $scope.dateselect.year && $scope.dateselect.date) {
        $scope.selected_date_data = $scope.datelist[$scope.dateselect.date];
        var seldate = $scope.selected_date_data;
        text += seldate.travel_year+'. '+ seldate.travel_month+'. '+seldate.travel_day+'., '+$scope.datelist[$scope.dateselect.date].travel_weekday+' - '+seldate.durration.name+', '+seldate.durration.nights+' '+ $scope.translate('éjszaka');
      }
    } else {
      //Egyéni utazások
      text += $scope.calendarModel.selectedTemplateName+', '+ $scope.nights + ' '+ $scope.translate('éjszaka');
    }

    return text;
  }

  $scope.priceCalcSum = function( item ){
    var me = $scope.priceCalcMe( item );

    return parseFloat(item.price) * me;
  }

  $scope.priceCalcMe = function( item )
  {
    var nights = 1;
    if ($scope.dates.length == 0) {
      // Egyéni utazás esetén az éjszaka kiszámítása
      nights = $scope.getNightsByDateDiff($scope.calendarModel.dateEnd, $scope.calendarModel.dateStart);
    } else {
      // Csoportos utazás esetén
      nights = ($scope.selected_date_data && $scope.selected_date_data.durration) ? parseInt($scope.selected_date_data.durration.nights) : 0;
    }

    $scope.nights = nights;

    switch( item.price_calc_mode ) {
      case 'once':
        return 1;
      break;
      case 'daily':
        if ($scope.selected_date_data.durration) {
          var n = ($scope.selected_date_data) ? parseInt(nights) : 0;
        } else if($scope.dates.length == 0){
          var n = nights;
        }
        return n+1;
      break;
      case 'day_person':
        var fo = $scope.passengers.adults + $scope.passengers.children;
        if ($scope.selected_date_data.durration) {
          var n = ($scope.selected_date_data) ? parseInt(nights) : 0;
        } else if($scope.dates.length == 0){
          var n = nights;
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

    // Utasok megadása
    if ( step == 1 )
    {
      $scope.dateselect = {
        durration: false,
        year: false,
        date: false
      };
      $scope.selected_date_data = {};
      $scope.selected_ellatas = false;
      $scope.selected_ellatas_data = false;
      $scope.selected_room_id = 0;
      $scope.selected_room_data = {};
      $scope.configs_selected = {
        programok: [],
        szolgaltatas: [],
        biztositas: 0
      };
      $scope.selected_programs = {};
      $scope.selected_szolgaltatas = {};
      $scope.config_szolgaltatas_prices = 0;
      $scope.config_programok_prices = 0;
      $scope.travel_prices = 0;
      $scope.passengers_detail = {
        adults: [],
        children: []
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
    }
    // Időpont kiválasztása
    else if( step == 2 )
    {
      $scope.selected_ellatas = false;
      $scope.selected_ellatas_data = false;
      $scope.selected_room_id = 0;
      $scope.selected_room_data = {};
      $scope.configs_selected = {
        programok: [],
        szolgaltatas: [],
        biztositas: 0
      };
      $scope.selected_programs = {};
      $scope.selected_szolgaltatas = {};
      $scope.config_szolgaltatas_prices = 0;
      $scope.config_programok_prices = 0;
      $scope.travel_prices = 0;
      $scope.passengers_detail = {
        adults: [],
        children: []
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
    }
    // Ellátások
    else if( step == 3 )
    {
      $scope.selected_room_id = 0;
      $scope.selected_room_data = {};
      $scope.configs_selected = {
        programok: [],
        szolgaltatas: [],
        biztositas: 0
      };
      $scope.selected_programs = {};
      $scope.selected_szolgaltatas = {};
      $scope.config_szolgaltatas_prices = 0;
      $scope.config_programok_prices = 0;
      $scope.travel_prices = 0;
      $scope.passengers_detail = {
        adults: [],
        children: []
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
    }
    // Szobatípus
    else if( step == 4 )
    {
      $scope.configs_selected = {
        programok: [],
        szolgaltatas: [],
        biztositas: 0
      };
      $scope.selected_programs = {};
      $scope.selected_szolgaltatas = {};
      $scope.config_szolgaltatas_prices = 0;
      $scope.config_programok_prices = 0;
      $scope.travel_prices = 0;
      $scope.passengers_detail = {
        adults: [],
        children: []
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
    }
    // Ár összesítő
    else if( step == 5 )
    {
      $scope.passengers_detail = {
        adults: [],
        children: []
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
    }

    $scope.recalcFinalPrice();

    // Reset steps to false
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
        $scope.preorder_msg = {
          error: 0,
          msg: null
        };
        $scope.loadDates(function(){
          $scope.step_loading = false;
        });
      break;
      case 2:
        $scope.loadEllatas(function(){
          $scope.step_loading = false;
        });
      break;
      case 3:
        if ( $scope.configs.szobak.length == 0 ) {
          $scope.nextStep(5);
        }
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
  $scope.blogid = 1;
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
  $scope.valuta = null;

  // Datas
  $scope.dates = [];
  $scope.dates_create = [];

  // Flags
  $scope.loading = false;
  $scope.loaded = false;
  $scope.dates_loaded = false;
  $scope.dates_saving = false;

  $scope.init = function( postid, blogid )
  {
    $scope.blogid = blogid;
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
        data: $scope.config_creator[group],
        blogid: $scope.blogid
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
        datas: data,
        blogid: $scope.blogid
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
        datas: data,
        blogid: $scope.blogid
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
        datas: data,
        blogid: $scope.blogid
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
        data: $scope.dates_create,
        blogid: $scope.blogid
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
        terms: ['utazas_duration', 'utazas_ellatas'],
        blogid: $scope.blogid
      })
    }).success(function(r){
      console.log(r);
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
          id: id,
          blogid: $scope.blogid
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
          id: id,
          blogid: $scope.blogid
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
          id: id,
          blogid: $scope.blogid
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
        mode: 'getConfigTerms',
        blogid: $scope.blogid
      })
    }).success(function(r){
      angular.forEach($scope.config_groups, function(c,i){
        $scope.configs[c] = r.data[c];
      });

      if ( r.data.biztositas && typeof r.data.biztositas[0] !== 'undefined' ) {
        $scope.config_creator.biztositas[0] = r.data.biztositas[0];
      }

      // Valuta
      if (r.data.valuta) {
        $scope.valuta = r.data.valuta;
        if (r.data.valuta.name == 'Ft') {
          $scope.price_after = ' '+r.data.valuta.name;
          $scope.price_before = '';
        } else {
          $scope.price_after = '';
          $scope.price_before = r.data.valuta.name;
        }
      } else {
        $scope.price_after = ' Ft';
        $scope.price_before = '';
      }
    });

    $http({
      method: 'POST',
      url: '/wp-admin/admin-ajax.php?action=traveler',
      headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
      data: $.param({
        postid: $scope.postid,
        mode: 'getRooms',
        blogid: $scope.blogid
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
        postid: $scope.postid,
        blogid: $scope.blogid
      })
    }).success(function(r){
      console.log(r);
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
        mode: 'getDateData',
        blogid: $scope.blogid
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
        mode: 'getRoomData',
        blogid: $scope.blogid
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
        mode: 'getConfigData',
        blogid: $scope.blogid
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

jnk.controller('TestimonialMaker', ['$scope', '$http', '$mdToast', '$mdDialog', '$httpParamSerializerJQLike', '$window', function($scope, $http, $mdToast, $mdDialog, $httpParamSerializerJQLike, $window)
{
  $scope.creatorshowed = false;
  $scope.postid = 0;
  $scope.sending = false;
  $scope.sended = false;
  $scope.content = {
    client_name: null,
    destination: null,
    msg: null
  };

  $scope.init = function( postid )
  {
    $scope.postid = postid;
  }

  $scope.tglCreator = function(){
    if ($scope.creatorshowed) {
      $scope.creatorshowed = false;
      // Reset
      $scope.content = {
        client_name: null,
        destination: null,
        msg: null
      };
    } else {
      $scope.creatorshowed = true;
    }
  }

  $scope.SendTestimonial = function(){
    $scope.sending = true;
    $scope.sended = false;

    $http({
      method: 'POST',
      url: '/wp-admin/admin-ajax.php?action=traveler',
      headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
      data: $httpParamSerializerJQLike({
        postid: $scope.postid,
        mode: 'createTestimonial',
        content: $scope.content
      })
    }).success(function(r){
      $scope.content = {
        client_name: null,
        destination: null,
        msg: null
      };
      $scope.sending = false;
      $scope.sended = true;
    });

  }
}]);
