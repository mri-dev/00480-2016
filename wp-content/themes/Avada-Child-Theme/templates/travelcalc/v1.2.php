<?
/**
* Kalkulátor funkcionitás módosulás a JavasScript kódban:
* - A kiválasztott szobatípus árai közül csak azok jelenjenek meg ami a fenti résztvevők számával összhangban van pl gyermekár csak gyermek megjelölése esetén
* - Megrendelés sávban csak akkor nyíljanak le az árak ha kiválasztja a szobatípust
*
* @version v1.1
**/
  $default_room = $ajanlat->getDefaultRoomData();
  $min_adults   = $default_room['min_adults'];
?>
<div class="travel-calculator-container" ng-controller="formValidator">
  <div class="calc-content">
    <div class="calc-head main-head">
        <img src="<?=IFROOT?>/images/palmas_h40_white.png" alt="" />
        <h3>Hányan szeretnének utazni?</h3>
    </div>
    <div class="calc-wrapper">
      <div class="calc-row">
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
        <h3>Válasszon ajánlatainkból:</h3>
        <div id="term-ajanlat-result">

        </div>
      </div>
      <div id="travel-contact">
        <form id="mailsend" onsubmit="return false;" method="post">
        <div class="calc-head">
          <h3>Megrendelés</h3>
          <label>Személyes adatok megadása</label>
        </div>
        <div class="calc-row">
          <div>
              <div id="erremsg"></div>
              <h4>Megrendelő adatai</h4>
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
                  <input type="text" class="datepicker" ng-model="orderer.dob" ng-required ng-pattern="/^((\d{4})[ .-/](\d{2})[ .-/](\d{2})|(\d{2})\/(\d{2})\/(\d{4})|\d{8})$/" id="szuletesi_datum" name="szuletesi_datum" placeholder="2000/01/01" value="">
                </div>
                <div class="cim">
                  <label for="cim">Cím</label>
                  <input type="text" id="cim" name="cim" placeholder="" value="">
                </div>
                <div class="telefon">
                  <label for="telefon">Mobilszám</label>
                  <input type="text" id="telefon" name="telefon" ng-model="orderer.tel" ng-required ng-pattern="" placeholder="+36 XXXXXXXX" value="">
                </div>
                <div class="email">
                  <label for="email">E-mail cím</label>
                  <input type="email" id="email" name="email" ng-model="orderer.mail" ng-pattern="/^[a-z]+[a-z0-9._-]+@[a-z_-]+\.[a-z.]{2,5}$/" ng-required placeholder="mail@example.com" value="">
                </div>
                <div class="comment">
                  <label for="comment">Egyéb megjegyzés</label>
                  <textarea name="comment" id="comment" ng-model="orderer.comment"></textarea>
                </div>
              </div>
              <div id="copyordererdata"><a href="javascript:void(0);"><i class="fa fa-copy"></i> adatok másolása <strong>Utas #1</strong>-hez</a></div>
              <div id="utasok_adatai"></div>
              <div class="accept_aszf">
                <input type="checkbox" class="ccb" id="accept_aszf" ng-model="orderer.aszf" name="accept_aszf" value="1"> <label for="accept_aszf">Elfogadom az <a href="<?=ASZF_URL?>" target="_blank">Általános Szerződési Feltételek</a>et.</label>
              </div>
          </div>
        </div>
        <div class="selected-travel-room">
          <h4>Kiválasztott ajánlat:</h4>
          <div id="selected-travel-room"></div>
        </div>
        <div class="send-mail"><button type="button" ng-show="orderer.dob && orderer.mail && orderer.tel && orderer.aszf" id="mail-sending-btn" class="fusion-button" onclick="ajanlatkeresKuldes();" name="button">Megrendelés küldése <i class="fa fa-envelope-o"></i></button><div class="ng-alert-head" ng-show="!orderer.dob || !orderer.mail || !orderer.tel">
            <i class="fa fa-exclamation-triangle" style="font-size: 14px;"></i><br>
            A megrendeléshez töltse ki helyesen a megrendelőt!
          </div>
          <div class="ng-alert-msg" ng-show="!orderer.dob">
            <i class="fa fa-birthday-cake"></i> Adja meg helyesen a megrendelő születési dátumát.<br>
            <em title="2000 01 01, 2000.01.01, 2000-01-01, 2000/01/01">Formátumok (?)</em>
          </div>
          <div class="ng-alert-msg" ng-show="!orderer.tel">
            <i class="fa fa-mobile"></i> Adja meg helyesen a megrendelő mobilszámát.<br>
            <em title="06xxXXXXXXX, +36xxXXXXXXX">Formátumok (?)</em>
          </div>
          <div class="ng-alert-msg" ng-show="!orderer.mail">
            <i class="fa fa-envelope"></i> Adja meg helyesen a megrendelő e-mail címét.<br>
            <em title="mail@example.com">Formátumok (?)</em>
          </div>
          <div class="ng-alert-msg" ng-show="!orderer.aszf">
            <i class="fa fa-check-square-o"></i> ÁSZF elfogadás.<br>
            <em>Kötelezően el kell fogadnia a megrendeléshez.</em>
          </div>
        </div>
        <div class="action-btns">
          <button type="button" class="print-offer fusion-button" onclick="PrintElem(<?php echo $ajanlat->getTravelID(); ?>);">Ajánlat nyomtatása <i class="fa fa-print" aria-hidden="true"></i></button>
          <button type="button" class="print-offer fusion-button" onclick="downloadOffer(<?php echo $ajanlat->getTravelID(); ?>);">Letöltés <i class="fa fa-file-pdf-o" aria-hidden="true"></i></button>
        </div>
        </form>
      </div>
    </div>
  </div>
</div>
<script>
function PrintElem(tid) {
  var obj = jQuery("#selected-travel-room input").serialize();
  var coded = jQuery.base64.btoa(obj, true);
  var url = '/utazas/print/'+tid+'/?offer='+coded;

  var printw = window.open(url, 'PRINT', 'height=850,width=1200');
  printw.focus();

  setTimeout(function() {
      //printw.print();
      //printw.close();
  }, 1000);

  return true;
}

</script>

<script type="text/javascript">
  var fxrate=<?=$ajanlat->term_data['exchange_rate']?>;
  var termdata = {};
  var adults = 2;
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
                          var agl = agelist[c];
                          if(typeof agl === 'undefined') continue;

                           var bucketminage = parseInt(roomconfig['child_groups'][agelist[c]]['min_age']);
                           var bucketmaxage = parseInt(roomconfig['child_groups'][agelist[c]]['max_age']);
                           if ((parseInt(childarray[c]) < bucketminage ) || (parseInt(childarray[c]) > bucketmaxage)) {
                               valid = 0;
                           };
                       };
                   };
                   if (valid) {
                       jQuery.each(roomconfig['buckets'], function( bucketid, bucket) {
                           if (room['price_types'][bucket['price_type_id']] === undefined) { valid = 0; }
                       });
                       if (valid) { goodconfig = roomconfig; }
                   };
               });
               var total = 0;
               jQuery('.priceLine[data-roompricetypeid]').removeClass('showbucket');
               jQuery.each(goodconfig['buckets'], function( bucketid, bucket) {
                   var ptid = bucket['price_type_id'];
                   jQuery('table[data-roomid="'+roomid+'"] span[data-pricetypeid="'+ptid+'"]').html(bucket['count']+" x").removeClass('rejtve');
                   jQuery('.priceLine[data-roompricetypeid="'+ptid+'"]').addClass('showbucket');
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
                  jQuery('table[data-roomid="'+roomid+'"] tr.priceLine').addClass('rejtve');
                  jQuery('table[data-roomid="'+roomid+'"] input[type=radio]').prop('disabled', false);
           }
           jQuery('table[data-roomid="'+roomid+'"] th.fullPrice').html(result);
       });

   };

function downloadOffer( tid ) {
  var obj = jQuery("#selected-travel-room input").serialize();
  var coded = jQuery.base64.btoa(obj, true);
  document.location.href = '/utazas/download/'+tid+'/?offer='+coded;
}

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
    var mailparam  = jQuery('#mailsend').serialize();

    jQuery.post(
      '<?php echo admin_url('admin-ajax.php'); ?>?action=send_travel_request',
      mailparam,
      function(data){
        var resp = jQuery.parseJSON(data);

        if(resp.error == 0) {
          var redir_conv_url = '/sikeres-megrendeles?z='+resp.passed_params.term.zones[0].id+'&termid='+resp.passed_params.term.id+'&hotel='+resp.passed_params.hotel.name+'&email='+resp.passed_params.email+'&pv='+resp.passed_params.room.price_value;
          mail_sended = 1;
          jQuery('#mail-sending-btn').html('Megrendelés elküldve <i class="fa fa-check-circle"></i>').removeClass('in-progress').addClass('sended');
          jQuery('#mailsend #erremsg').text('');
          // Redirect
          setTimeout(function(){
            document.location.href = redir_conv_url;
          }, 800);

        } else {
          jQuery('#mail-sending-btn').html('Ajánlatkérése küldése <i class="fa fa-envelope-o"></i>').removeClass('in-progress')
          mail_sending_progress = 0;
          if(resp.missing != 0 || resp.missing_elements) {
            jQuery.each(resp.missing_elements, function(i,e){
              jQuery('#mailsend #'+e).addClass('missing');
            });
          }
          jQuery('#mailsend #erremsg').text(resp.msg).css({
            'color' : 'red',
            'lineHeight': 1
          });
          alert(resp.msg);
        }
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

      var headRow = data.tour.hotel.name;
      headRow += " " + parseInt(data.tour.hotel.category) + "* | ";
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
              result += "<tr class='priceLine rejtve' data-roomid='"+roomid+"' data-roompricetypeid='"+pricetypeid+"'>";
              result += "<td>" + pricetype['name'] + "</td>";
              result += "<td><span data-pricetypeid='"+pricetypeid+"' class='szorzo'></span></td>";
              result += "<td class='price'>" + parseFloat(pricetype['price']).toFixed(2) + " €/fő</td>";
              result += "<td class='price phuf'>"+(parseFloat(pricetype['price'])*fxrate).toLocaleString('hu-HU', { maximumFractionDigits: 0, maximumFractionDigits: 2, style: 'currency', currency: 'HUF'})+"/fő</td>";
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

  $('.datepicker').datepicker({
    startView: 'decade',
    format: 'yyyy/mm/dd',
    language: 'hu',
    weekStart: 1,
    immediateUpdates: true,
    autoclose: true
  });

  var selected_room = 0;

  $(document).on( 'change', '.roomcheck', function() {
    var rid = $(this).val();
    var calc_price = $('table.room[data-roomid='+rid+'] .fullPrice').text();
    var room_data = termdata['rooms'][rid];
    var peoples = adults+' felnőtt';
    var maxmember = parseInt(adults) + parseInt(children);
    var utasok_form = '';

    if (children != 0) {
      var c_ages = '';
      $.each(childages, function(i,e){
        c_ages += e+' éves, ';
      });
      c_ages = c_ages.replace(/, $/g,"");
      peoples += ', '+children+' gyermek ('+c_ages+')';
    }

    for (var i = 1; i <= maxmember; i++) {
      utasok_form += utasrowtemp(i);
    }

    // Price toggle
    $('.priceLine:not(.rejtve)').addClass('rejtve');
    $('.priceLine[data-roomid='+rid+']').removeClass('rejtve');

    $('#travel-contact').addClass('show');
    $('#selected-travel-room').html('<div class="room"><input type="hidden" name="term[id]" value="'+termdata.id+'"><input type="hidden" name="term[url]" value="<?=get_option('siteurl').$_SERVER['REQUEST_URI']?>"><input type="hidden" name="term[date_from]" value="'+termdata.date_from+'"><input type="hidden" name="term[date_to]" value="'+termdata.date_to+'"><input type="hidden" name="term[board]" value="'+termdata.board_name+'"><input type="hidden" name="hotel[name]" value="'+termdata.tour.hotel.name+'"><input type="hidden" name="room[name]" value="'+room_data.name+'"><input type="hidden" name="room[price]" value="'+calc_price+'"><input type="hidden" name="room[people]" value="'+peoples+'"><div class="name">'+room_data.name+'<div class="ppl">'+peoples+'</div></div><div class="price">'+calc_price+'</div></div>');
    $('#utasok_adatai').html(utasok_form);

    $('html, body').animate({
         scrollTop: $("#travel-contact").offset().top
     }, 1000);

  });


  $('#copyordererdata').click(function(){
    $('#utasok_adatai .utas-row[data-row=1] #vezeteknev_utas_1').val($('#vezeteknev').val());
    $('#utasok_adatai .utas-row[data-row=1] #keresztnev_utas_1').val($('#keresztnev').val());
    $('#utasok_adatai .utas-row[data-row=1] #szuletesi_datum_utas_1').val($('#szuletesi_datum').val());
    $('#utasok_adatai .utas-row[data-row=1] #megszolitas_utas_1').find("option[value='"+$('#megszolitas').val()+"']").attr('selected',true);

  });

  function utasrowtemp ( i ) {
    return '<div class="utas-row" data-row="'+i+'">'+
      '<h4>Utas #'+i+' adatai:</h4>'+
      '<div class="contact-form">'+
      '<div class="vezeteknev">'+
        '<label for="vezeteknev_utas_'+i+'">Vezetéknév</label>'+
        '<input type="text" name="utasok[vezeteknev][]" id="vezeteknev_utas_'+i+'" placeholder="" value="">'+
      '</div>'+
      '<div class="keresztnev">'+
        '<label for="vezeteknev_utas_'+i+'">Keresztnév</label>'+
        '<input type="text" name="utasok[keresztnev][]" id="keresztnev_utas_'+i+'" placeholder="" value="">'+
      '</div>'+
        '<div class="megszolitas">'+
          '<label for="megszolitas_utas_'+i+'">Megszólítás</label>'+
          '<select name="utasok[megszolitas][]" id="megszolitas_utas_'+i+'">'+
            '<option value="Úr">Úr</option>'+
            '<option value="Hölgy">Hölgy</option>'+
          '</select>'+
        '</div>'+
        '<div class="szuletesi_datum">'+
          '<label for="szuletesi_datum_utas_'+i+'">Születési dátum</label>'+
          '<input type="text" class="datepicker" ng-model="traveller.dob" id="szuletesi_datum_utas_'+i+'" name="utasok[szuletesi_datum][]" placeholder="" value="">'+
        '</div>'+
      '</div>'+
    '</div>';
  }

})(jQuery);
</script>
