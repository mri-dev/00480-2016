<div class="search-panel v1">
  <div class="search-wrapper">
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
            <input type="text" id="search_form_place" class="tglwatcher" name="place_text" tglwatcher="zone_multiselect" placeholder="Kanári-szigetek" readonly="readonly">
            <i class="dropdown-ico fa fa-caret-down"></i>
            <input type="hidden" id="zones" name="zones">
            <div class="multi-selector-holder" id="zone_multiselect">
              <div class="selector-wrapper">
                <?
                $lvl = 0;
                foreach($zones as $zone): ?>
                  <div class="lvl-0 zone<?=$zone['id']?>"><input type="checkbox" name="zone[]" id="zone_<?=$zone['id']?>" value="<?=$zone['id']?>"> <label for="zone_<?=$zone['id']?>"><?=$zone['name']?></label></div>

                  <? if($zone['children']){ ?>
                    <div class="">
                  <?php
                    foreach($zone['children'] as $zone_d2): ?>
                      <div class="lvl-1 childof<?=$zone['id']?> zone<?=$zone_d2['id']?>"><input type="checkbox" name="zone[]" id="zone_<?=$zone['id']?>_<?=$zone_d2['id']?>" value="<?=$zone_d2['id']?>"> <label for="zone_<?=$zone['id']?>_<?=$zone_d2['id']?>"><?=$zone_d2['name']?></label></div>

                      <? if($zone_d2['children']){ ?>
                        <div class="sub-lvl">
                      <?php
                        foreach($zone_d2['children'] as $zone_d3): ?>
                        <div class="lvl-2 childof<?=$zone_d2['id']?> zone<?=$zone_d3['id']?>"><input type="checkbox" name="zone[]" id="zone_<?=$zone['id']?>_<?=$zone_d2['id']?>_<?=$zone_d3['id']?>" value="<?=$zone_d3['id']?>"> <label for="zone_<?=$zone['id']?>_<?=$zone_d2['id']?>_<?=$zone_d3['id']?>"><?=$zone_d3['name']?></label></div>

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
            <input type="text" id="search_form_hotel" name="hotel_text" placeholder="Összes Hotel">
            <input type="hidden" id="hotel_id" name="hotel_id">
          </div>
          <div class="row-divider"></div>
          <div class="input w20 row-bottom">
            <div class="ico">
              <i class="fa fa-star"></i>
            </div>
            <label for="search_form_kategoria">Kategória</label>
            <select class="" id="search_form_kategoria" name="kategoria">
              <option value="">Bármely</option>
            </select>
          </div>
          <div class="input w20 row-bottom">
            <div class="ico">
              <i class="fa fa-coffee"></i>
            </div>
            <label for="search_form_ellatas">Ellátás</label>
            <select class="" id="search_form_ellatas" name="ellatas">
              <option value="">Bármely</option>
            </select>
          </div>
          <div class="input w20 row-bottom">
            <div class="ico">
              <i class="fa fa-calendar"></i>
            </div>
            <label for="search_form_indulas">Indulás</label>
            <input type="text" class="datepicker" id="search_form_indulas" name="indulas" value="<?php echo date('Y m d'); ?>" readonly="readonly">
          </div>
          <div class="input w20 row-bottom last-item">
            <div class="ico">
              <i class="fa fa-calendar"></i>
            </div>
            <label for="search_form_erkezes">Érkezés</label>
            <input type="text" class="datepicker" id="search_form_erkezes" name="erkezes" value="<?php echo date('Y m d', strtotime('+30 days')); ?>" readonly="readonly">
          </div>
          <div class="input search-button w20">
            <div class="button-wrapper">
              <button type="button" name="sub"><i class="fa fa-search"></i> Keresés</button>
            </div>
          </div>
        </div>
      </div>
  </div>
</div>
<script type="text/javascript">
(function($) {
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
})( jQuery );

</script>
