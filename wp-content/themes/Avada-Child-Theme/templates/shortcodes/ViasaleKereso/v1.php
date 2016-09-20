<div class="search-panel v1">
  <div class="search-wrapper">
    <form  action="/utazas-kereso" method="get">
      <div class="head-labels">
        <ul>
          <li class="title-label"><i class="fa fa-search"></i> Utazáskereső</li>
          <li><input type="radio" name="cat" id="cat_lm" value="lm"><label class="trans-on" for="cat_lm"><i class="fa fa-percent"></i> Lastminute</label></li>
          <li><input type="radio" name="cat" id="cat_fm" value="fm"><label class="trans-on" for="cat_fm"><i class="fa fa-percent"></i> Firstminute</label></li>
          <li><input type="radio" name="cat" id="cat_prog" value="prog"><label class="trans-on" for="cat_prog"><i class="fa fa-bicycle"></i> Programok</label></li>
          <li><input type="radio" name="cat" id="cat_trans" value="trans"><label class="trans-on" for="cat_trans"><i class="fa fa-bus"></i> Transfer</label></li>
        </ul>
      </div>
      <div class="input-holder">
        <div class="inputs">
          <div class="input w40">
            <div class="ico">
              <i class="fa fa-map-marker"></i>
            </div>
            <label for="search_form_place">Melyik régióba utazna?</label>
            <input type="text" id="search_form_place" class="tglwatcher" tglwatcher="zone_multiselect" placeholder="Kanári-szigetek" readonly="readonly">
            <i class="dropdown-ico fa fa-caret-down"></i>
            <input type="hidden" id="zones" name="zona">
            <div class="multi-selector-holder" id="zone_multiselect">
              <div class="selector-wrapper">
                <?
                $lvl = 0;
                foreach($zones as $zone): ?>
                  <div class="lvl-0 zone<?=$zone['id']?>"><input class="<? if($zone['child_count'] != 0): echo ' has-childs'; endif; ?>" type="checkbox" id="zone_<?=$zone['id']?>" value="<?=$zone['id']?>"> <label for="zone_<?=$zone['id']?>"><?=$zone['name']?></label></div>

                  <? if($zone['children']){ ?>
                    <div class="">
                  <?php
                    foreach($zone['children'] as $zone_d2): ?>
                      <div class="lvl-1 childof<?=$zone['id']?> zone<?=$zone_d2['id']?><? if($zone_d2['child_count'] != 0): echo ' has-childs'; endif; ?>"><input class="<? if($zone_d2['child_count'] != 0): echo ' has-childs'; endif; ?>" type="checkbox" id="zone_<?=$zone['id']?>_<?=$zone_d2['id']?>" value="<?=$zone_d2['id']?>"> <label for="zone_<?=$zone['id']?>_<?=$zone_d2['id']?>"><?=$zone_d2['name']?></label></div>

                      <? if($zone_d2['children']){ ?>
                        <div class="sub-lvl sub-lvl-of<?=$zone_d2[id]?>">
                      <?php
                        foreach($zone_d2['children'] as $zone_d3): ?>
                        <div class="lvl-2 childof<?=$zone_d2['id']?> zone<?=$zone_d3['id']?><? if($zone_d3['child_count'] != 0): echo ' has-childs'; endif; ?>"><input class="<? if($zone_d3['child_count'] != 0): echo ' has-childs'; endif; ?>" type="checkbox"id="zone_<?=$zone['id']?>_<?=$zone_d2['id']?>_<?=$zone_d3['id']?>" value="<?=$zone_d3['id']?>"> <label for="zone_<?=$zone['id']?>_<?=$zone_d2['id']?>_<?=$zone_d3['id']?>"><?=$zone_d3['name']?></label></div>

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
            <label for="search_form_hotel">Hotel</label>
            <input type="text" id="search_form_hotel" name="hotel" placeholder="Összes Hotel">
            <input type="hidden" id="hotel_id" name="hid">
            <div class="autocomplete-result-conteiner" id="hotel_autocomplete"></div>
          </div>
          <div class="row-divider"></div>
          <div class="input w20 row-bottom">
            <div class="ico">
              <i class="fa fa-star"></i>
            </div>
            <label for="search_form_kategoria">Kategória</label>
            <select class="" id="search_form_kategoria" name="c">
              <option value="" selected="selected">Bármely</option>
              <option value="" disabled="disabled" style="background: #f2f2f2; text-align: center; padding: 10px; font-size: 11px;">Válasszon:</option>
              <? if($hotelStars) foreach ($hotelStars as $star) { ?>
                <option value="<?=$star?>">legalább <?=$star?> csillag</option>
              <?  } ?>
            </select>
          </div>
          <div class="input w20 row-bottom">
            <div class="ico">
              <i class="fa fa-coffee"></i>
            </div>
            <label for="search_form_ellatas">Ellátás</label>
            <select class="" id="search_form_ellatas" name="e">
              <option value="" selected="selected">Bármely</option>
              <option value="" disabled="disabled" style="background: #f2f2f2; text-align: center; padding: 10px; font-size: 11px;">Válasszon:</option>
              <? if($boardTypes) foreach ($boardTypes as $board_id => $board) { ?>
                <option value="<?=$board_id?>"><?=$board['fullName']?></option>
              <?  } ?>
            </select>
          </div>
          <div class="input w20 row-bottom">
            <div class="ico">
              <i class="fa fa-calendar"></i>
            </div>
            <label for="search_form_indulas">Indulás</label>
            <input type="text" class="search-datepicker" dtp="from" id="search_form_indulas" name="tt" value="<?php echo date('Y / m / d', strtotime('+1 days')); ?>" readonly="readonly">
          </div>
          <div class="input w20 row-bottom last-item">
            <div class="ico">
              <i class="fa fa-calendar"></i>
            </div>
            <label for="search_form_erkezes">Érkezés</label>
            <input type="text" class="search-datepicker" dtp="to" id="search_form_erkezes" name="tf" value="<?php echo date('Y / m / d', strtotime('+30 days')); ?>">
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
            selected_date.setDate(selected_date.getDate() + 7);
            $("#search_form_erkezes").val(selected_date.toInputFormat());
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
