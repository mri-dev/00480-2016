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
      <div id="travel-contact">
        <form id="mailsend" onsubmit="return false;" method="post">
        <div class="calc-head">
          <h3>Ajánlatkérés</h3>
          <label>Személyes adatok megadása</label>
        </div>
        <div class="calc-row">
          <div>
              <div class="contact-form">
                <div class="megszolitas">
                  <label for="megszolitas">Megszólítás</label>
                  <select name="megszolitas" id="megszolitas">
                    <option value="Úr">Úr</option>
                    <option value="Hölgy">Hölgy</option>
                  </select>
                </div>
                <div class="vezeteknev">
                  <label for="vezeteknev">Vezetéknév</label>
                  <input type="text" name="vezeteknev" id="vezeteknev" placeholder="" value="">
                </div>
                <div class="keresztnev">
                  <label for="vezeteknev">Keresztnév</label>
                  <input type="text" name="keresztnev" id="keresztnev" placeholder="" value="">
                </div>
                <div class="szuletesi_datum">
                  <label for="szuletesi_datum">Születési dátum</label>
                  <input type="text" class="datepicker" id="szuletesi_datum" name="szuletesi_datum" placeholder="" value="">
                </div>
                <div class="cim">
                  <label for="cim">Cím</label>
                  <input type="text" class="datepicker" id="cim" name="cim" placeholder="" value="">
                </div>
                <div class="telefon">
                  <label for="telefon">Telefonszám</label>
                  <input type="text" id="telefon" name="telefon" placeholder="+36 XX XXXXXX" value="">
                </div>
                <div class="email">
                  <label for="email">E-mail cím</label>
                  <input type="email" id="email" name="email" placeholder="" value="">
                </div>
              </div>
          </div>
        </div>
        <div class="selected-travel-room">
          <h4>Kiválasztott ajánlat:</h4>
          <div id="selected-travel-room"></div>
        </div>
        <div class="send-mail">
          <button type="button" id="mail-sending-btn" class="fusion-button" onclick="ajanlatkeresKuldes();" name="button">Ajánlatkérés küldése <i class="fa fa-envelope-o"></i></button>
        </div>
        </form>
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
                   jQuery('table[data-roomid="'+roomid+'"] input[type=radio]').prop('disabled', true);
           } else {
                  jQuery('table[data-roomid="'+roomid+'"]').removeClass('rejtve');
                   jQuery('table[data-roomid="'+roomid+'"] tr.priceLine').removeClass('rejtve');
                   jQuery('table[data-roomid="'+roomid+'"] input[type=radio]').prop('disabled', false);
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

function resetRoomCheck() {
  jQuery('table.room input[type=radio]').prop('checked', false);
  jQuery('#travel-contact').removeClass('show');
}

var mail_sending_progress = 0;
var mail_sended = 0;
function ajanlatkeresKuldes()
{
  if(mail_sending_progress == 0 && mail_sended == 0){
    jQuery('#mail-sending-btn').html('Küldés folyamatban <i class="fa fa-spinner fa-spin"></i>').addClass('in-progress');
    jQuery('#mailsend .missing').removeClass('missing');

    mail_sending_progress = 1;
    var mailparam  = jQuery('#mailsend').serializeArray();

    jQuery.post(
      '<?php echo admin_url('admin-ajax.php'); ?>?action=send_travel_request',
      mailparam,
      function(data){
        var resp = jQuery.parseJSON(data);
        if(resp.error == 0) {
          mail_sended = 1;
          jQuery('#mail-sending-btn').html('Ajánlatkérése elküldve <i class="fa fa-check-circle"></i>').removeClass('in-progress').addClass('sended');
        } else {
          jQuery('#mail-sending-btn').html('Ajánlatkérése küldése <i class="fa fa-envelope-o"></i>').removeClass('in-progress')
          mail_sending_progress = 0;
          if(resp.missing != 0) {
            jQuery.each(resp.missing_elements, function(i,e){
              jQuery('#mailsend #'+e).addClass('missing');
            });
          }
          alert(resp.msg);
        }

        console.log(resp);
      }
    );
  }
}

function trimChar(string, charToRemove) {
    while(string.charAt(0)==charToRemove) {
        string = string.substring(1);
    }
    while(string.charAt(string.length-1)==charToRemove) {
        string = string.substring(0,string.length-1);
    }
    return string;
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
          result += "<input type='radio' id='sroom"+roomid+"' class='roomcheck' name='room_selected' value='"+roomid+"'/><label for='sroom"+roomid+"'>";
          result += "<i class='fa fa-";
          result += (stopsales=="danger")?"times":"check";
          result += "'></i> ";
          result += room['name'] + "</label></th><th style='text-align: right;' class='fullPrice'></th>";

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
      resetRoomCheck();
      priceCalculate();
  });

  var selected_room = 0;

  $(document).on( 'change', '.roomcheck', function() {
    var rid = $(this).val();
    var calc_price = $('table.room[data-roomid='+rid+'] .fullPrice').text();
    var room_data = termdata['rooms'][rid];
    var peoples = adults+' felnőtt';

    if (children != 0) {
      var c_ages = '';
      $.each(childages, function(i,e){
        c_ages += e+' éves, ';
      });
      c_ages = c_ages.replace(/, $/g,"");
      peoples += ', '+children+' gyermek ('+c_ages+')';

      console.log(childages);
    }

    $('#travel-contact').addClass('show');
    $('#selected-travel-room').html('<div class="room"><input type="hidden" name="term[id]" value="'+termdata.term_id+'"><input type="hidden" name="term[url]" value="<?=get_option('siteurl').$_SERVER['REQUEST_URI']?>"><input type="hidden" name="term[date_from]" value="'+termdata.date_from+'"><input type="hidden" name="term[date_to]" value="'+termdata.date_to+'"><input type="hidden" name="term[board]" value="'+termdata.board_name+'"><input type="hidden" name="hotel[name]" value="'+termdata.hotel.name+'"><input type="hidden" name="room[name]" value="'+room_data.name+'"><input type="hidden" name="room[price]" value="'+calc_price+'"><input type="hidden" name="room[people]" value="'+peoples+'"><div class="name">'+room_data.name+'<div class="ppl">'+peoples+'</div></div><div class="price">'+calc_price+'</div></div>');
  });

})(jQuery);
</script>
