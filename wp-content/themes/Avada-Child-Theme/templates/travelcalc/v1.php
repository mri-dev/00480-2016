<div class="travel-calculator-container">
  <?php
    $default_room = $ajanlat->getDefaultRoomData();
  ?>
  <div class="calc-head">
    <div class="calc-wrapper">
      <h2>Ajánlat kalkulátor</h2>
      <p>
        Számolja ki álomutazását!
      </p>
    </div>
  </div>
  <div class="calc-content">
    <div class="calc-wrapper">
      <div class="calc-row">
        <h3>Hányan szeretnének utazni?</h3>
        <div class="fusion-row">
          <div class="fusion-column fusion-spacing-yes fusion-layout-column fusion-one-half select-form">
            <label for="Adults">Felnőttek</label>
            <select class="" name="Adults" id="Adults">
              <?php
              $min_adults = $ajanlat->getMinAdults();
              $max_adults = $ajanlat->getMaxAdults();
              for($adn = $min_adults; $adn <= $max_adults; $adn++): ?>
              <option value="<?php echo $adn; ?>" <?php if($default_room['min_adults']==$adn): echo 'selected="selected"'; endif; ?>><?php echo $adn; ?></option>
              <?php endfor; ?>
            </select>
          </div>
          <div class="fusion-column fusion-spacing-yes fusion-layout-column fusion-one-half fusion-column-last select-form">
            <label for="Children">Gyermekek</label>
            <select class="" name="Children" id="Children">
              <?php
              $default_room_children_min = $ajanlat->getDefaultRoomMinChildren();
              $default_room_children_max = $ajanlat->getDefaultRoomMaxChildren();
              for($adn = $default_room_children_min; $adn <= $default_room_children_max; $adn++): ?>
              <option value="<?php echo $adn; ?>"><?php echo $adn; ?></option>
              <?php endfor; ?>
            </select>
          </div>
        </div>
      </div>
      <div class="calc-row">
        <h3>Ajánlatok</h3>
        <div id="term-ajanlat-result">

        </div>
      </div>
    </div>
  </div>
</div>

<script type="text/javascript">
(function ($) {
  var offers = [];
  var termid = <?=$ajanlat->getTravelID()?>;
  var adults = <?php echo $default_room['min_adults']?>;
  var children = 0;
  var children_by_adults = {<?php if($children_by_adults){
     $jsarr = '';
      foreach ($children_by_adults as $adults => $minmax):
        $jsarr .= $adults.' : { min: '.$minmax['min'].', max: '.$minmax['max'].' }, ';
      endforeach;
      $jsarr = rtrim($jsarr, ', ');
      echo $jsarr;
  }?>};

  getOffers(termid, adults, children);

  function getOffers( termid, adults, children){
    $('#term-ajanlat-result').html('<i class="fa fa-spinner fa-spin"></i> Ajánlatok betöltése...');

    var child_vars = children_by_adults[adults];

    $.post(
      '<?php echo get_ajax_url('get_term_offer'); ?>',
      {
        termid: termid,
        adults: adults,
        children: children
      }, function(data){
        var datas = $.parseJSON(data);
        console.log(datas);
        buildOffers(datas);
      },"html");

      recreateChildrenSelect(child_vars);
  }

  function recreateChildrenSelect(child_vars) {
    var html = '';
    var min = child_vars.min;
    var max = child_vars.max;
    var is_selected = false;

    for (i = min; i <= max; i++) {
      is_selected = (children == i) ? true : false;
      html += '<option value="'+i+'" '+ ( (is_selected) ? 'selected="selected"' : '' ) +'>'+i+'</option>';
    }

    $('#Children').html(html);
  }

  function buildOffers(obj) {
    var html = '';
    $.each(obj.rooms, function(room_id,room_cfg){
      var room_name = obj.room_info[room_id].name;
      var room_sum_price = obj.price_by_rooms[room_id];
      var configs = obj.room_info[room_id].configs;

      html +=
      '<div class="room">'+
        '<div class="room-head">'+
          '<div class="room-name">'+room_name+'</div>'+
          '<div class="room-price-sum">'+room_sum_price+'€</div>'+
        '</div>'+
        '<div class="configs">';
          $.each(configs, function(cfgid, cnf){
            html +=
            '<div class="cfg">'+
              '<div class="name">'+cnf.name+'</div>'+
              '<div class="part-price">'+cnf.count+' x '+cnf.price+'€</div>'+
              '<div class="sum-price">'+(cnf.price*cnf.count)+'€</div>'+
            '</div>';
          });
      html += '</div>'+
      '</div>';
    });
    $('#term-ajanlat-result').html(html);
  }

  $('#Adults, #Children').change(function(){
    var s_adults = $('#Adults').val();
    var s_children = $('#Children').val();
    children = s_children;
    getOffers(termid, s_adults, s_children);
  });
})(jQuery);
</script>
