<?php
class ViasaleWeathers
{
    const SCTAG = 'viasale-weather';

    public $sziget_cities = array(
      'tenerife' => 'Playa de las Americas',
      'lanzarote' => 'Puerto del Carmen',
      'fuerteventura' => 'Corralejo',
      'gran-canaria' => 'Maspalomas'
    );

    public function __construct()
    {
        wp_enqueue_script('weather', RESOURCES . '/js/weather.js?t=' . ( (DEVMODE === true) ? time() : '' ) , array('jquery'));
        add_action( 'init', array( &$this, 'register_shortcode' ) );
    }

    public function register_shortcode() {
        add_shortcode( self::SCTAG, array( &$this, 'do_shortcode' ) );
    }

    public function do_shortcode( $attr, $content = null )
    {
        $output = '<div class="'.self::SCTAG.'-holder">';

    	  /* Set up the default arguments. */
        $defaults = apply_filters(
            self::SCTAG.'_defaults',
            array(
              'sziget' => false
            )
        );
        /* Parse the arguments. */
        $attr = shortcode_atts( $defaults, $attr );
        $hash = md5(microtime());
        $output .= '<div class="weather weather-forecast4-view"><div class="weather-holder" id="weather_foreacast'.$hash.'"></div></div>';
        $output .= $this->loadWeatherScript($this->sziget_cities[$attr['sziget']], 'weather_foreacast'.$hash);
        $output .= '</div>';


        /* Return the output of the tooltip. */
        return apply_filters( self::SCTAG, $output );
    }

    private function loadWeatherScript( $city_name = '', $target = '' )
    {
      $s = "<script>
          (function($){
            var weekdays = new Array(7);
            weekdays[0] = 'Vasárnap';
            weekdays[1] = 'Hétfő';
            weekdays[2] = 'Kedd';
            weekdays[3] = 'Szerda';
            weekdays[4] = 'Csütörtök';
            weekdays[5] = 'Péntek';
            weekdays[6] = 'Szombat';

            Weather.getDailyWeather( '".$city_name."', function(w){
              var wapp = '';
              console.log(w);

              if(w.cod == '200') {
                $.each(w.list, function(i,e){
                  var wday = new Date(e.dt*1000);
                  var day = wday.getDay();
                  wapp += '<div class=\"weather-day\">'+
                  '<div class=\"wico\"><img src=\"".IMAGES."/weather/ico/'+e.weather[0].icon.replace('n', 'd')+'.png\"/></div>'+
                  '<div class=\"day\">'+weekdays[day]+'</div>'+
                  '<div class=\"temp\">'+Math.ceil(e.temp.max)+'&deg;C</div>'+ 
                  '</div>';
                });
                $('#".$target."').html(wapp);
              }
            } );
          })(jQuery);
      </script>";

      return $s;
    }
}

new ViasaleWeathers();

?>
