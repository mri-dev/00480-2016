<script type="text/javascript">
  (function($){

    $("input[type=radio][name=nyaralas_hossz]").change(function(){
      var val = $(this).val();

      if (val == 'egyéb') {
        $('#nyaralas_hossz_text').removeClass('hided');
        $('#nyaralas_hossz_text').find('input').focus();
      } else {
        $('#nyaralas_hossz_text').addClass('hided');
        $('#nyaralas_hossz_text').find('input').val('');
      }
    });

    $("input[type=radio][name=repter]").change(function(){
      var val = $(this).val();

      if (val == 'Egyéb') {
        $('#repter_egyeb_text').removeClass('hided');
        $('#repter_egyeb_text').find('input').focus();
      } else {
        $('#repter_egyeb_text').addClass('hided');
        $('#repter_egyeb_text').find('input').val('');
      }
    });

    $("select#aj_gyermek").change(function(){
      var val = $(this).val();

      if (val != '0 gyerek') {
        $('#gyerekek_kora_text').removeClass('hided');
        $('#gyerekek_kora_text').find('input').focus();
      } else {
        $('#gyerekek_kora_text').addClass('hided');
        $('#gyerekek_kora_text').find('input').val('');
      }
    });
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
    	//minDate: +1,
      minDate: new Date(),
    	showMonthAfterYear: true,
    	yearSuffix: ""
    };

    $('.datepicker').datepicker( dp_options );
  })(jQuery)
</script>
