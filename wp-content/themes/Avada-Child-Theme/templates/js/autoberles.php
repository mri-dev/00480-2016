<script type="text/javascript">
  (function($){
    var dp_options = {
    	closeText: "bezár",
    	prevText: "vissza",
    	nextText: "előre",
    	currentText: "ma",
      timeText: "Időpont",
      hourText: "Óra",
      minuteText: "Perc",
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
    	yearSuffix: "",
	    sliderAccessArgs: { touchonly: false }
    };
    $('.datepicker').datetimepicker( dp_options );
  })(jQuery)
</script>
