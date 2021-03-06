<?php
  $searcher = new Searcher();
  $tax = get_queried_object();
  $tax_url = get_term_link($tax);
  $url = site_url();
  $action = $url.'/'.UTAZAS_SLUG;
  if ($tax->taxonomy == 'utazas_kategoria') {
    //$action = $tax_url;
  }
  $utazaskatterm = get_terms(array(
    'taxonomy' => 'utazas_kategoria'
  ));
?>
<form class="" action="<?=$action?>" method="get" id="searcher-form">
<div class="wrapper">
  <div class="keywords">
    <div class="iwrapper">
      <label for="src_search"><?php echo __('Keresés', 'jnk'); ?>:</label>
      <div class="input-wrapper">
        <div class="ico"><i class="fas fa-search"></i></div>
        <input type="text" name="search" value="<?=$_GET['search']?>" id="src_search">
      </div>
    </div>
  </div>
  <div class="type">
    <div class="iwrapper">
      <label for="src_search"><?php echo __('Típus', 'jnk'); ?></label>
      <div class="input-wrapper select-wrapper">
        <select class="" name="type">
          <?php foreach ((array)$utazaskatterm as $ukt): if(!in_array($ukt->slug, array('egyeni', 'csoportos', 'individual', 'group'))) continue; ?>
            <option value="<?=$ukt->slug?>" <?=($_GET['type'] == $ukt->slug)?'selected="selected"':''?>><?php echo $ukt->name; ?></option>
          <?php endforeach; ?>
        </select>
      </div>
    </div>
  </div>
  <div class="ellatas">
    <div class="iwrapper">
      <label for="ellatas_multiselect_text"><?=__('Ellátás', 'jnk')?></label>
      <div class="input-wrapper">
        <div class="tglwatcher-wrapper">
          <input type="text" readonly="readonly" id="ellatas_multiselect_text" class="form-control tglwatcher" tglwatcher="ellatas_multiselect" placeholder="<?=__('Mindegy', 'jnk')?>" value="">
        </div>
        <input type="hidden" id="ellatas_multiselect_ids" name="el" value="<?=$_GET['el']?>">
        <div class="multi-selector-holder" tglwatcherkey="ellatas_multiselect" id="ellatas_multiselect">
          <div class="selector-wrapper">
            <? $ellatasok = $searcher->getSelectors('utazas_ellatas'); ?>
            <?php if ($ellatasok): ?>
              <?php
              $ellatasok_selected = ($_GET['el'] == '') ? array() : explode(",", $_GET['el']);
              ?>
              <?php foreach ($ellatasok as $k): ?>
              <div class="selector-row">
                <input type="checkbox" tglwatcherkey="ellatas_multiselect" <?=(in_array($k->term_id, $ellatasok_selected))?'checked="checked"':''?> htxt="<?=$k->name?>" id="stat_<?=$k->term_id?>" value="<?=$k->term_id?>"> <label for="stat_<?=$k->term_id?>"><?=$k->name?>
                  <?php if (get_locale() === DEFAULT_LANGUAGE): ?>
                   <span class="n">(<?=$k->count?>)</span>
                  <?php endif; ?></label>
              </div>
              <?php endforeach; ?>
            <?php endif; ?>
          </div>
        </div>
      </div>
    </div>
  </div>
  <div class="datetime">
    <div class="iwrapper">
      <label for="src_datetime"><?php echo __('Időpont', 'jnk'); ?></label>
      <div class="input-wrapper">
        <div class="ico"><i class="far fa-calendar-alt"></i></div>
        <input type="text" name="date" readonly="readonly" value="<?=$_GET['date']?>" placeholder="<?=__('Bármikor', 'jnk')?>" id="src_datetime">
      </div>
    </div>
  </div>
  <div class="location">
    <div class="iwrapper">
      <label for="searcher_city"><?=__('Úti cél', 'jnk')?></label>
      <div class="input-wrapper">
        <div class="ico"><i class="fas fa-globe"></i></div>
        <input type="text" id="searcher_city" name="cities" class="form-control" autocomplete="off" value="<?=$_GET['cities']?>" placeholder="<?=__('Összes város', 'jnk')?>">
        <div id="searcher_city_autocomplete" class="selector-wrapper"></div>
        <input type="hidden" name="ci" id="searcher_city_ids" value="">
      </div>
    </div>
  </div>
  <div class="durations">
    <div class="iwrapper">
      <label for="duration_multiselect_text"><?=__('Utazás hossza', 'jnk')?></label>
      <div class="input-wrapper">
        <div class="tglwatcher-wrapper">
          <input type="text" readonly="readonly" id="duration_multiselect_text" class="form-control tglwatcher" tglwatcher="duration_multiselect" placeholder="<?=__('Összes', 'jnk')?>" value="">
        </div>
        <input type="hidden" id="duration_multiselect_ids" name="dur" value="<?=$_GET['dur']?>">
        <div class="multi-selector-holder" tglwatcherkey="duration_multiselect" id="duration_multiselect">
          <div class="selector-wrapper">
            <? $durations = $searcher->getSelectors('utazas_duration'); ?>
            <?php if ($durations): ?>
              <?php
              $durations_selected = ($_GET['dur'] == '') ? array() : explode(",", $_GET['dur']);
              ?>
              <?php foreach ($durations as $k): ?>
              <div class="selector-row">
                <input type="checkbox" tglwatcherkey="duration_multiselect" <?=(in_array($k->term_id, $durations_selected))?'checked="checked"':''?>  htxt="<?=$k->name?>" id="stat_<?=$k->term_id?>" value="<?=$k->term_id?>"> <label for="stat_<?=$k->term_id?>"><?=$k->name?>
                  <?php if (get_locale() === DEFAULT_LANGUAGE): ?>
                   <span class="n">(<?=$k->count?>)</span>
                  <?php endif; ?></label>
              </div>
              <?php endforeach; ?>
            <?php endif; ?>
          </div>
        </div>
      </div>
    </div>
  </div>

  <div class="utazasmod">
    <div class="iwrapper">
      <label for="utazasmod_multiselect_text"><?=__('Utazás módja', 'jnk')?></label>
      <div class="input-wrapper">
        <div class="tglwatcher-wrapper">
          <input type="text" readonly="readonly" id="utazasmod_multiselect_text" class="form-control tglwatcher" tglwatcher="utazasmod_multiselect" placeholder="<?=__('Összes', 'jnk')?>" value="">
        </div>
        <input type="hidden" id="utazasmod_multiselect_ids" name="um" value="<?=$_GET['um']?>">
        <div class="multi-selector-holder" tglwatcherkey="utazasmod_multiselect" id="utazasmod_multiselect">
          <div class="selector-wrapper">
            <? $ellatasok = $searcher->getSelectors('utazas_mod'); ?>
            <?php if ($ellatasok): ?>
              <?php
              $ellatasok_selected = ($_GET['um'] == '') ? array() : explode(",", $_GET['um']);
              ?>
              <?php foreach ($ellatasok as $k): ?>
              <div class="selector-row">
                <input type="checkbox" tglwatcherkey="utazasmod_multiselect" <?=(in_array($k->term_id, $ellatasok_selected))?'checked="checked"':''?> htxt="<?=$k->name?>" id="stat_<?=$k->term_id?>" value="<?=$k->term_id?>"> <label for="stat_<?=$k->term_id?>"><?=$k->name?>
                  <?php if (get_locale() === DEFAULT_LANGUAGE): ?>
                   <span class="n">(<?=$k->count?>)</span>
                  <?php endif; ?></label>
              </div>
              <?php endforeach; ?>
            <?php endif; ?>
          </div>
        </div>
      </div>
    </div>
  </div>

  <div class="szolgaltatasok">
    <div class="iwrapper">
      <label for="utazasszolg_multiselect_text"><?=__('Szolgáltatások', 'jnk')?></label>
      <div class="input-wrapper">
        <div class="tglwatcher-wrapper">
          <input type="text" readonly="readonly" id="utazasszolg_multiselect_text" class="form-control tglwatcher" tglwatcher="utazasszolg_multiselect" placeholder="<?=__('Mindegy', 'jnk')?>" value="">
        </div>
        <input type="hidden" id="utazasszolg_multiselect_ids" name="szolg" value="<?=$_GET['szolg']?>">
        <div class="multi-selector-holder" tglwatcherkey="utazasszolg_multiselect" id="utazasszolg_multiselect">
          <div class="selector-wrapper">
            <? $ellatasok = $searcher->getSelectors('utazas_szolgaltatasok'); ?>
            <?php
            $ellatasok_selected = ($_GET['szolg'] == '') ? array() : explode(",", $_GET['szolg']);
            ?>
            <?php if ($ellatasok): ?>
              <?php foreach ($ellatasok as $k): ?>
              <div class="selector-row">
                <input type="checkbox" tglwatcherkey="utazasszolg_multiselect" <?=(in_array($k->term_id, $ellatasok_selected))?'checked="checked"':''?> htxt="<?=$k->name?>" id="stat_<?=$k->term_id?>" value="<?=$k->term_id?>"> <label for="stat_<?=$k->term_id?>"><?=$k->name?>
                  <?php if (get_locale() === DEFAULT_LANGUAGE): ?>
                   <span class="n">(<?=$k->count?>)</span>
                  <?php endif; ?></label>
              </div>
              <?php endforeach; ?>
            <?php endif; ?>
          </div>
        </div>
      </div>
    </div>
  </div>
  <div class="pre-srcbutton">&nbsp;</div>
  <div class="srcbutton">
    <div class="iwrapper">
      <button type="submit"><?php echo __('Keresés','jnk'); ?></button>
      <a href="<?=$action?>" class="research"><?php echo __('új keresés indítása','jnk'); ?></a>
    </div>
  </div>
</div>
</form>

<script type="text/javascript">
  (function($){
    $(window).click(function() {
      if (!$(event.target).closest('.toggler-opener').length) {
        $('.toggler-opener').removeClass('opened toggler-opener');
        $('.tglwatcher.toggled').removeClass('toggled');
      }
    });

    collect_checkbox('ellatas_multiselect');
    collect_checkbox('duration_multiselect');
    collect_checkbox('utazasmod_multiselect');
    collect_checkbox('utazasszolg_multiselect');

    $( "#datepicker" ).datepicker( $.datepicker.regional[ "hu" ] );
    $('#src_datetime').datepicker({
      minDate: 0
    });

    $('#options-toggler').click(function(){
      var toggled = ($(this).data('options-tgl') == '0') ? false : true ;

      if (toggled) {
        $(this).data('options-tgl', 0);
        $(this).find('i').removeClass('fa-caret-down').addClass('fa-caret-right');
        $('form[role=searcher] .options-selects .secondary-param').removeClass('show');
      }else {
        $(this).find('i').removeClass('fa-caret-right').addClass('fa-caret-down');
        $('form[role=searcher] .options-selects .secondary-param').addClass('show');
        $(this).data('options-tgl', 1);
      }
    });

    $('.pricebind').bind("keyup", function(event) {
       if(event.which >= 37 && event.which <= 40){
        event.preventDefault();
       }
       var $this = $(this);
       var num = $this.val().replace(/\./gi, "");
       var num2 = num.split(/(?=(?:\d{3})+$)/).join(".");
       $this.val(num2);
    });

    $('form[role=searcher] input[type=radio]').change(function(i,e){
      var rid = $(this).val();
      if(rid == 67) {
        $('form[role=searcher] label[for=searcher_city]').text('Város (kerület)');
      } else {
          $('form[role=searcher] label[for=searcher_city]').text('Város');
      }
    });

    $('.tglwatcher').click(function(event){
      event.stopPropagation();
      event.preventDefault();
      var e = $(this);
      var target_id = e.attr('tglwatcher');
      var opened = e.hasClass('toggled');

      if(opened) {
        e.removeClass('toggled');
        $('#'+target_id).removeClass('opened toggler-opener');
      } else {
        e.addClass('toggled');
        $('#'+target_id).addClass('opened toggler-opener');
      }
    });

    $('form[role=searcher] input[data-options]').change(function()
    {
      var e = $(this);
      var checkin = $(this).is(':checked');
      var selected = collect_options(false);
      $('#options').val(selected);
    });

    $('.multi-selector-holder input[type=checkbox]').change(function()
    {
      var e = $(this);
      var checkin = $(this).is(':checked');
      var tkey = e.attr('tglwatcherkey');
      var selected = collect_checkbox(tkey, false);
      $('#'+tkey+'_ids').val(selected);
    });

    /* Autocompleter */
    /* */
    var src_current_region = 0;
    $("#searcher-form input[name='rg']").change(function(){
      var sl = $(this).val();
      src_current_region = sl;
    });
    $('#searcher_city').autocomplete({
        serviceUrl: '/wp-admin/admin-ajax.php?action=city_autocomplete',
        appendTo: '#searcher_city_autocomplete',
        paramName: 'search',
        params : { "region": get_current_regio() },
        type: 'GET',
        dataType: 'json',
        transformResult: function(response) {
            return {
                suggestions: $.map(response, function(dataItem) {
                  console.log(dataItem);
                    //return { value: dataItem.label.toLowerCase().capitalizeFirstLetter(), data: dataItem.value };
                    return { value: dataItem.label, data: dataItem.value };
                })
            };
        },
        onSelect: function(suggestion) {
          $('#searcher_city_ids').val(suggestion.data);
        },
        onSearchComplete: function(query, suggestions){

        },
        onSearchStart: function(query){
          $(this).autocomplete().options.params.region = get_current_regio();
        },
        onSearchError: function(query, jqXHR, textStatus, errorThrown){
            console.log('Autocomplete error: '+textStatus);
        }
    });
    /* */

     function get_current_regio() {
       return $("#searcher-form input[name=rg]:checked").val();
     }

    String.prototype.capitalizeFirstLetter = function() {
      return this;
      //return this.charAt(0).toUpperCase() + this.slice(1);
    }
    /* E:Autocompleter */

  })(jQuery);

  function collect_options( loader )
  {
    var arr = [];

    jQuery('form[role=searcher] input[data-options]').each(function(e,i)
    {
      if(jQuery(this).is(':checked') && !jQuery(this).is(':disabled')){
        arr.push(jQuery(this).val());
      }
    });

    return arr.join(",");
  }

  function collect_checkbox(rkey, loader)
  {
    var arr = [];
    var str = [];
    var seln = 0;

    jQuery('#'+rkey+' input[type=checkbox]').each(function(e,i)
    {
      if(jQuery(this).is(':checked') && !jQuery(this).is(':disabled')){
        seln++;
        arr.push(jQuery(this).val());
        str.push(jQuery(this).attr('htxt'));
      }

      if(loader) {
        var e = jQuery(this);
        var has_child = jQuery(this).hasClass('has-childs');
        var checkin = jQuery(this).is(':checked');
        var lvl = e.data('lvl');
        var parent = e.data('parentid');

        var cnt_child = jQuery('#'+rkey+' .childof'+parent+' input[type=checkbox]:checked').length;

        if(cnt_child == 0) {
          jQuery('#'+rkey+' .zone'+parent+' input[type=checkbox]').prop('disabled', false);
        } else {
          jQuery('#'+rkey+' .childof'+parent).addClass('show');
          jQuery('#'+rkey+' .zone'+parent+' input[type=checkbox]').prop('checked', true).prop('disabled', true);
        }
      }
    });

    if(seln <= 3 ){
      jQuery('#'+rkey+'_text').val(str.join(", "));
    } else {
      jQuery('#'+rkey+'_text').val(seln + " <?=__('kiválasztva', 'gh')?>");
    }

    return arr.join(",");
  }
</script>
