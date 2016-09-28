<?
  $default_room = $ajanlat->getDefaultRoomData();
  $min_adults   = $default_room['min_adults'];
?>
<div class="travel-calculator-container">
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
            <label for="adults">Felnőttek</label>
            <select class="occupancy" name="adults" id="adults"></select>
          </div>
          <div class="fusion-column fusion-spacing-yes fusion-layout-column fusion-one-half fusion-column-last select-form">
            <label for="children">Gyermekek</label>
            <select class="occupancy" name="children" id="children"></select>
          </div>
        </div>
        <div class="fusion-row rejtve" id="childAgeRow">
          <div class="child-header">
            Válassza ki a gyermek(ek) korát:
          </div>
          <div class="child-age-holder">
            <div class="child-age-column">
              <select class="form-control occupancy" id="childAge1" name="childAge1"></select>
            </div>
            <div class="child-age-column">
              <select class="form-control occupancy" id="childAge2" name="childAge2"></select>
            </div>
            <div class="child-age-column">
              <select class="form-control occupancy" id="childAge3" name="childAge3"></select>
            </div>
            <div class="child-age-column">
              <select class="form-control occupancy" id="childAge4" name="childAge4"></select>
            </div>
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
  var fxrate=<?=$ajanlat->term_data['exchange_rate']?>;
  var termdata = {};
  var adults = <?=$min_adults?>;
  var children = 0;
  var childages = [];

  function priceCalculate()
  {
       var result;
       var childarray = [];
       for ( var i=0; i<children; i++) {
           childarray[i] = parseInt(childages[i]);
       };
       childarray.sort(function(a,b) { return a>b?1:-1 });
       jQuery.each(termdata['rooms'], function (roomid, room ) {
           jQuery('table[data-roomid="'+roomid+'"] span.szorzo').html('');
           // select correct roomconfig
           var configCount = 0;
           if ((room['adults'][adults] !== undefined) && (room['adults'][adults]['children'][children] !== undefined)) { configCount = room['adults'][adults]['children'][children]['config_count']; };
           if (!configCount) { result = ""; }
           else {
               var goodconfig = {};
               jQuery.each(room['adults'][adults]['children'][children]['configs'], function ( configid, roomconfig) {
                   var valid = 1;
                   if (configCount>1) {
                       var agelist = [];
                       jQuery.each(roomconfig['child_groups'], function( agecode, agebucket){
                           for (var x=1; x<=parseInt(agebucket['count']); x++) { agelist.push(agecode); };
                       });
                       agelist.sort();
                       for (var c = 0; c < children; c++) {
                           var bucketminage = parseInt(roomconfig['child_groups'][agelist[c]]['min_age']);
                           var bucketmaxage = parseInt(roomconfig['child_groups'][agelist[c]]['max_age']);
                           if ((parseInt(childarray[c]) < bucketminage ) || (parseInt(childarray[c]) > bucketmaxage)) {
                               valid = 0;
                           };
                       };
                   };
                   if (valid) { goodconfig = roomconfig; };
               });
               var total = 0;
               jQuery.each(goodconfig['buckets'], function( bucketid, bucket) {
                   var ptid = bucket['price_type_id'];
                   jQuery('table[data-roomid="'+roomid+'"] span[data-pricetypeid="'+ptid+'"]').html(bucket['count']+" x").removeClass('rejtve');
                   total += parseInt(bucket['count']) * parseFloat(room['price_types'][ptid]['price']);
               });
               result = total.toFixed(2) + " €";
           };
           if (result=="") {
               result = "Nem foglalható!";
                   jQuery('table[data-roomid="'+roomid+'"]').addClass('rejtve');
                   jQuery('table[data-roomid="'+roomid+'"] tr.priceLine').addClass('rejtve');
           } else {
                  jQuery('table[data-roomid="'+roomid+'"]').removeClass('rejtve');
                   jQuery('table[data-roomid="'+roomid+'"] tr.priceLine').removeClass('rejtve');
           }
           jQuery('table[data-roomid="'+roomid+'"] th.fullPrice').html(result);
       });

   };

function setchildren(){
   var minchildren = 10;
   var maxchildren = 0;
   jQuery.each(termdata['rooms'], function(roomid, room) {
       if (room['adults'][adults] !== undefined) {
           if (room['adults'][adults]['min_children'] < minchildren ) { minchildren = room['adults'][adults]['min_children']; };
           if (room['adults'][adults]['max_children'] > maxchildren ) { maxchildren = room['adults'][adults]['max_children']; };
       }
   });
   jQuery('#children').empty();
   for ( var c = minchildren; c <= maxchildren; c++) {
       jQuery("#children").append(jQuery('<option>',{value:c,text:c}));
   };

   if (children < minchildren) { jQuery("#children").val(minchildren); };
   if (children > maxchildren) { jQuery("#children").val(maxchildren); };

   jQuery('.occupancy').trigger("change");
}

(function ($) {

  var result = "";
  var stopsales = "";
  $.get("<?=$ajanlat->api_uri.ViasaleAPIFactory::TERMS_TAG?>/<?=$ajanlat->getTravelID()?>}", function (data) {
      termdata = data;

      var headRow = data.hotel.name;
      headRow += " " + parseInt(data.hotel.category) + "* | ";
      headRow += data.board_name + " | ";
      headRow += data.date_from + " - " + data.date_to;
      $('#termTitle').html(headRow);

      var minadult = 2;
      var maxadult = 0;
      $.each(data['rooms'], function ( roomid, room ) {
          if (parseInt(room['min_adults']) < minadult) { minadult = parseInt(room['min_adults']); };
          if (parseInt(room['max_adults']) > maxadult) { maxadult = parseInt(room['max_adults']); };
          stopsales = (room['stop_sales']=="1")?"danger":"success";
          result += "<table class='room' data-roomid='"+roomid+"'><tr class='"+stopsales+" room-head' ><th colspan='3'>";
          result += "<span class='glyphicon glyphicon-";
          result += (stopsales=="danger")?"remove":"ok";
          result += "'></span> ";
          result += room['name'] + "</th><th style='text-align: right;' class='fullPrice'></th>";

          $.each(room['price_types'], function ( pricetypeid, pricetype ) {
              result += "<tr class='priceLine'>";
              result += "<td>" + pricetype['name'] + "</td>";
              result += "<td><span data-pricetypeid='"+pricetypeid+"' class='szorzo'></span></td>";
              result += "<td class='price'>" + parseFloat(pricetype['price']).toFixed(2) + "€</td>";
              result += "<td class='price phuf'>"+(parseFloat(pricetype['price'])*fxrate).toLocaleString('hu-HU', { maximumFractionDigits: 0, style: 'currency', currency: 'HUF'})+"</td>";
              result += "</tr>";
          });

          result += "</table>";
      });

      $('#term-ajanlat-result').html(result);

      $("#adults").empty();
      for (var a = minadult; a<= maxadult; a++) {
          $("#adults").append($('<option>',{value:a,text:a}));
      };
      if (adults < minadult) { adults = minadult; };
      if (adults > maxadult) { adults = maxadult; };
      $("#adults").val(adults);

      for (var i=1; i <= 4; i++) {
          $("#childAge"+i).empty();
          for (var c=parseInt(data['min_child_age']); c<=parseInt(data['max_child_age']); c++) {
              $('#childAge'+i).append($('<option>',{value:c,text:c}));
          };
          if ((childages[i-1]) !== undefined) { $("#childAge"+i).val(childages[i-1]); };
      }

      setchildren();
      priceCalculate();

  });

  $('.occupancy').change( function() {
      if (adults != $('#adults').val()) {
          adults = $('#adults').val();
          setchildren();
      };

      if (children != $('#children').val()) {
          children = $('#children').val();
          var childcount = parseInt($('#children').val());
          if (childcount > 0) {
              $("#childAgeRow").removeClass('rejtve');
              for (var i=1; i <= 4; i++) {
                  if (childcount >= i) {
                      $("#childAge"+i).removeClass('rejtve');
                      $("#childAge"+i).parent().removeClass('rejtve');
                  } else {
                      $("#childAge"+i).addClass('rejtve');
                      $("#childAge"+i).parent().addClass('rejtve');
                  }
              }
          } else {
              $("#childAgeRow").addClass('rejtve');
          }
      };

      for (var i=1; i<=children; i++) {
          childages[i-1]=($("#childAge"+i).val())
      };
      priceCalculate();
  });

})(jQuery);
</script>
