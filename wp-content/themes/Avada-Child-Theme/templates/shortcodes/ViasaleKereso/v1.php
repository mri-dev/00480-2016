<div class="search-panel v1">
  <div class="search-wrapper">
    <form id="modul-page-searcher-form-v1" action="/<?=KERESO_SLUG?>" method="get">
      <div class="head-labels">
        <ul>
          <li class="title-label"><i class="fa fa-search"></i> Utazáskereső</li>
          <li><input type="radio" <?=($_GET['cat'] == 'lastminute')?'checked="checked"':''?> name="cat" id="cat_lm" value="lastminute"><label class="trans-on" for="cat_lm"><i class="fa fa-percent"></i> Lastminute</label></li>
          <li><input type="radio" <?=($_GET['cat'] == 'firstminute')?'checked="checked"':''?> name="cat" id="cat_fm" value="firstminute"><label class="trans-on" for="cat_fm"><i class="fa fa-percent"></i> Firstminute</label></li>
          <li><input type="radio" <?=($_GET['cat'] == 'prog')?'checked="checked"':''?> name="cat" id="cat_prog" value="prog"><label class="trans-on" for="cat_prog"><i class="fa fa-bicycle"></i> Programok</label></li>
          <li><input type="radio" <?=($_GET['cat'] == 'trans')?'checked="checked"':''?> name="cat" id="cat_trans" value="trans"><label class="trans-on" for="cat_trans"><i class="fa fa-bus"></i> Transzfer</label></li>
        </ul>
      </div>
      <div class="input-holder">
        <div class="inputs">
          <div class="input w40">
            <div class="ico">
              <i class="fa fa-map-marker"></i>
            </div>
            <?php
              $zonak = array();
              if(!empty($_GET['zona'])){
                $zonak = explode(",", $_GET['zona']);
              }
            ?>
            <label for="search_form_place">Melyik régióba utazna?</label>
            <input type="text" id="search_form_place" class="tglwatcher" tglwatcher="zone_multiselect" placeholder="Kanári-szigetek" readonly="readonly">
            <i class="dropdown-ico fa fa-caret-down"></i>
            <input type="hidden" id="zones" name="zona">
            <div class="multi-selector-holder" id="zone_multiselect">
              <div class="selector-wrapper">
                <?
                $lvl = 0;
                foreach($zones as $zone): ?>
                  <div class="lvl-0 zone<?=$zone['id']?>"><input <?=(in_array($zone['id'], $zonak))?'checked="checked"':''?> class="<? if($zone['child_count'] != 0): echo ' has-childs'; endif; ?>" type="checkbox" id="zone_<?=$zone['id']?>" value="<?=$zone['id']?>"> <label for="zone_<?=$zone['id']?>"><?=$zone['name']?></label></div>

                  <? if($zone['children']){ ?>
                    <div class="">
                  <?php
                    foreach($zone['children'] as $zone_d2): ?>
                      <div class="lvl-1 childof<?=$zone['id']?> zone<?=$zone_d2['id']?><? if($zone_d2['child_count'] != 0): echo ' has-childs'; endif; ?>"><input <?=(in_array($zone_d2['id'], $zonak))?'checked="checked"':''?> class="<? if($zone_d2['child_count'] != 0): echo ' has-childs'; endif; ?>" type="checkbox" id="zone_<?=$zone['id']?>_<?=$zone_d2['id']?>" value="<?=$zone_d2['id']?>"> <label for="zone_<?=$zone['id']?>_<?=$zone_d2['id']?>"><?=$zone_d2['name']?></label></div>

                      <? if($zone_d2['children']){ ?>
                        <div class="sub-lvl sub-lvl-of<?=$zone_d2[id]?>">
                      <?php
                        foreach($zone_d2['children'] as $zone_d3): ?>
                        <div class="lvl-2 childof<?=$zone_d2['id']?> zone<?=$zone_d3['id']?><? if($zone_d3['child_count'] != 0): echo ' has-childs'; endif; ?>"><input <?=(in_array($zone_d3['id'], $zonak))?'checked="checked"':''?> class="<? if($zone_d3['child_count'] != 0): echo ' has-childs'; endif; ?>" type="checkbox"id="zone_<?=$zone['id']?>_<?=$zone_d2['id']?>_<?=$zone_d3['id']?>" value="<?=$zone_d3['id']?>"> <label for="zone_<?=$zone['id']?>_<?=$zone_d2['id']?>_<?=$zone_d3['id']?>"><?=$zone_d3['name']?></label></div>

                      <? endforeach; } ?>
                      </div>
                  <? endforeach; } ?>
                </div>
                <? endforeach; ?>
              </div>
            </div>
          </div>
          <div class="input w60 last-item">
            <div class="ico">
              <i class="fa fa-building"></i>
            </div>
            <label for="search_form_hotel" class="trans-on">Hotel</label>
            <input type="text" id="search_form_hotel" name="hotel" value="<?php echo $_GET["hotel"]; ?>" placeholder="Összes Hotel" class="trans-on">
            <input type="hidden" id="hotel_id" name="hid" value="<?php echo $_GET["hid"]; ?>">
            <div class="autocomplete-result-conteiner" id="hotel_autocomplete"></div>
          </div>
          <div class="row-divider"></div>
          <div class="input w20 row-bottom">
            <div class="ico">
              <i class="fa fa-star"></i>
            </div>
            <label for="search_form_kategoria" class="trans-on">Kategória</label>
            <select class="trans-o" id="search_form_kategoria" name="c">
              <option value="" selected="selected">Bármely</option>
              <option value="" disabled="disabled" style="background: #f2f2f2; text-align: center; padding: 10px; font-size: 11px;">Válasszon:</option>
              <? if($hotelStars) foreach ($hotelStars as $star) { ?>
                <option value="<?=$star?>" <?=($_GET['c'] == $star)? 'selected="selected"':''?>>legalább <?=$star?> csillag</option>
              <?  } ?>
            </select>
          </div>
          <div class="input w20 row-bottom">
            <div class="ico">
              <i class="fa fa-coffee"></i>
            </div>
            <label for="search_form_ellatas" class="trans-on">Ellátás</label>
            <select class="trans-o" id="search_form_ellatas" name="e">
              <option value="" selected="selected">Bármely</option>
              <option value="" disabled="disabled" style="background: #f2f2f2; text-align: center; padding: 10px; font-size: 11px;">Válasszon:</option>
              <? if($boardTypes) foreach ($boardTypes as $board_id => $board) { ?>
                <option value="<?=$board_id?>" <?=($_GET['e'] == $board_id)? 'selected="selected"':''?>>Legalább <?=strtolower($board['fullName'])?></option>
              <?  } ?>
            </select>
          </div>
          <div class="input w20 row-bottom">
            <div class="ico">
              <i class="fa fa-calendar"></i>
            </div>
            <label for="search_form_indulas" class="trans-on">Indulás</label>
            <input type="text" class="search-datepicker trans-o" dtp="from" id="search_form_indulas" name="tf" value="<?php if(isset($_GET['tf'])) { echo str_replace('-'," / ", $_GET['tf']); } else { echo date('Y / m / d', strtotime('+1 days')); }  ?>" readonly="readonly">
          </div>
          <div class="input w20 row-bottom last-item">
            <div class="ico">
              <i class="fa fa-calendar"></i>
            </div>
            <label for="search_form_erkezes" class="trans-on">Érkezés</label>
            <input type="text" class="search-datepicker trans-o" dtp="to" id="search_form_erkezes" name="tt" value="<?php if(isset($_GET['tt'])) { echo str_replace('-'," / ", $_GET['tt']); } else { echo date('Y / m / d', strtotime('+30 days')); }  ?>">
          </div>
          <div class="input search-button w20">
            <div class="button-wrapper">
              <button type="submit"><i class="fa fa-search"></i> Keresés</button>
            </div>
          </div>
        </div>
      </div>
      </form>
  </div>
</div>
<script type="text/javascript">
var search_form_uri = {
  'firstminute' : '/utazas-kereso',
  'lastminute' : '/utazas-kereso',
  'prog' : '/program-kereso',
  'trans' : '/transzfer-kereso'
};
// Autocomplete
(function ($) {
    'use strict';

    $('#search_form_hotel').autocomplete({
        serviceUrl: 'http://viasale.net/api/v2/hotels/autocomplete',
        appendTo: '#hotel_autocomplete',
        paramName: 'term',
        params : { "zones": 1 },
        type: 'GET',
        dataType: 'json',
        transformResult: function(response) {
            return {
                suggestions: $.map(response, function(dataItem) {
                    return { value: dataItem.label.toLowerCase().capitalizeFirstLetter(), data: dataItem.value };
                })
            };
        },
        onSelect: function(suggestion) {
          $('#hotel_id').val(suggestion.data);
        },
        onSearchComplete: function(query, suggestions){

        },
        onSearchStart: function(query){
          $('#hotel_id').val('');
          // Pass current selected zones
          $(this).autocomplete().options.params.zones = get_selected_zone_ids();
        },
        onSearchError: function(query, jqXHR, textStatus, errorThrown){
            console.log('Autocomplete error: '+textStatus);
        }
    });

    String.prototype.capitalizeFirstLetter = function() {
        return this.charAt(0).toUpperCase() + this.slice(1);
    }

    function get_selected_zone_ids() {
      var selected = $('#zones').val();
      if(selected == '') return "1";
      return selected;
    }

})(jQuery);
// END: Autocomplete

(function($) {
  var szones = collect_zone_checkbox();
  $('#zones').val(szones);
  bindSearchFormURI();

  var dp_options = {
  	closeText: "bezár",
  	prevText: "vissza",
  	nextText: "előre",
  	currentText: "ma",
  	monthNames: [ "Január", "Február", "Március", "Április", "Május", "Június",
  	"Július", "Augusztus", "Szeptember", "Október", "November", "December" ],
  	monthNamesShort: [ "Jan", "Feb", "Már", "Ápr", "Máj", "Jún",
  	"Júl", "Aug", "Szep", "Okt", "Nov", "Dec" ],
  	dayNames: [ "Vasárnap", "Hétfő", "Kedd", "Szerda", "Csütörtök", "Péntek", "Szombat" ],
  	dayNamesShort: [ "Vas", "Hét", "Ked", "Sze", "Csü", "Pén", "Szo" ],
  	dayNamesMin: [ "V", "H", "K", "Sze", "Cs", "P", "Szo" ],
  	weekHeader: "Hét",
  	dateFormat: "yy / mm / dd",
  	firstDay: 1,
  	isRTL: false,
  	minDate: +1,
  	showMonthAfterYear: true,
  	yearSuffix: "",
    onSelect: function(dt, i){
      if($(this).attr('dtp') == 'from')
      {
        var selected_date = new Date(i.currentYear, i.currentMonth, i.currentDay);

        if(!isNaN(selected_date.getTime())){
            var enddate = selected_date;
            enddate.setDate(selected_date.getDate() + 30);
            $("#search_form_erkezes")
              .val(enddate.toInputFormat())
              .datepicker( "option", "minDate", new Date(i.currentYear, i.currentMonth, i.currentDay) );
        }
      }
    }
  };

  Date.prototype.toInputFormat = function() {
    var yyyy = this.getFullYear().toString();
    var mm = (this.getMonth()+1).toString();
    var dd  = this.getDate().toString();
    return yyyy + " / " + (mm[1]?mm:"0"+mm[0]) + " / " + (dd[1]?dd:"0"+dd[0]);
  };

  $( ".search-datepicker" ).datepicker( dp_options );

  $(window).click(function() {
    if (!$(event.target).closest('.toggler-opener').length) {
      $('.toggler-opener').removeClass('opened toggler-opener');
      $('.tglwatcher.toggled').removeClass('toggled');
    }
  });

  $('form#modul-page-searcher-form-v1 input[type=radio][name=cat]').change(function(e){
    bindSearchFormURI();
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

  $('#zone_multiselect input[type=checkbox]').change(function(){
    var e = $(this);
    var has_child = $(this).hasClass('has-childs');
    var checkin = $(this).is(':checked');

    if(has_child) {
      if(checkin) {
        $('#zone_multiselect .childof'+e.val()+' input[type=checkbox]').prop('checked', false);
        $('#zone_multiselect .sub-lvl.sub-lvl-of'+e.val()).hide();
      } else {
        $('#zone_multiselect .sub-lvl.sub-lvl-of'+e.val()).show();
      }
    }

    var selected_zones = collect_zone_checkbox();

    $('#zones').val(selected_zones);

  });

})( jQuery );

function bindSearchFormURI() {
  var sel_cat_key = jQuery('form#modul-page-searcher-form-v1 input[type=radio][name=cat]:checked').val();
  if(typeof sel_cat_key === 'undefined') return;

  resetSearchInputs();

  switch(sel_cat_key){
    case 'trans':
      transferSearchPrepare();
    break;
    case 'prog':
      programsSearchPrepare();
    break;
  }
  jQuery('form#modul-page-searcher-form-v1').attr('action', search_form_uri[sel_cat_key]);
}

function transferSearchPrepare() {
  jQuery('label[for=search_form_place]').text('Melyik régióba / városba keres transzfert?');

  jQuery('label[for=search_form_hotel]').addClass('inactive');
  jQuery('#search_form_hotel').prop('disabled', true);
  jQuery('label[for=search_form_hotel]').addClass('inactive');
  jQuery('#search_form_kategoria').prop('disabled', true);
  jQuery('label[for=search_form_kategoria]').addClass('inactive');
  jQuery('#search_form_ellatas').prop('disabled', true);
  jQuery('label[for=search_form_ellatas]').addClass('inactive');
  jQuery('#search_form_indulas').prop('disabled', true);
  jQuery('label[for=search_form_indulas]').addClass('inactive');
  jQuery('#search_form_erkezes').prop('disabled', true);
  jQuery('label[for=search_form_erkezes]').addClass('inactive');

  jQuery('#zone_multiselect .lvl-0').addClass('disabled');
}

function programsSearchPrepare() {
  jQuery('label[for=search_form_place]').text('Melyik régióba / városba keres programokat?');

  jQuery('label[for=search_form_hotel]').addClass('inactive');
  jQuery('#search_form_hotel').prop('disabled', true);
  jQuery('label[for=search_form_hotel]').addClass('inactive');
  jQuery('#search_form_kategoria').prop('disabled', true);
  jQuery('label[for=search_form_kategoria]').addClass('inactive');
  jQuery('#search_form_ellatas').prop('disabled', true);
  jQuery('label[for=search_form_ellatas]').addClass('inactive');
  jQuery('#search_form_indulas').prop('disabled', true);
  jQuery('label[for=search_form_indulas]').addClass('inactive');
  jQuery('#search_form_erkezes').prop('disabled', true);
  jQuery('label[for=search_form_erkezes]').addClass('inactive');

  jQuery('#zone_multiselect .lvl-0').addClass('disabled');
}

function resetSearchInputs() {
  jQuery('label[for=search_form_place]').text('Melyik régióba utazna?');

  jQuery('#search_form_hotel').prop('disabled', false);
  jQuery('label[for=search_form_hotel]').removeClass('inactive');
  jQuery('#search_form_kategoria').prop('disabled', false);
  jQuery('label[for=search_form_kategoria]').removeClass('inactive');
  jQuery('#search_form_ellatas').prop('disabled', false);
  jQuery('label[for=search_form_ellatas]').removeClass('inactive');
  jQuery('#search_form_indulas').prop('disabled', false);
  jQuery('label[for=search_form_indulas]').removeClass('inactive');
  jQuery('#search_form_erkezes').prop('disabled', false);
  jQuery('label[for=search_form_erkezes]').removeClass('inactive');

  jQuery('#zone_multiselect .lvl-0').removeClass('disabled');
}

function collect_zone_checkbox()
{
  var arr = [];
  var str = [];
  var seln = 0;

  jQuery('#zone_multiselect input[type=checkbox]').each(function(e,i)
  {
    if(jQuery(this).is(':checked')){
      seln++;
      arr.push(jQuery(this).val());
      str.push(jQuery(this).next('label').text());
    }
  });

  if(seln <= 3 ){
    jQuery('#search_form_place').val(str.join(", "));
  } else {
    jQuery('#search_form_place').val(seln + " zóna kiválasztva");
  }

  return arr.join(",");
}

</script>
