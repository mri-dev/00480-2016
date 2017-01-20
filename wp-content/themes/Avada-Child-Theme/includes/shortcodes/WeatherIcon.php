<?php
class WeatherIcon
{
    const SCTAG = 'weather-icon';

    public function __construct()
    {
        add_action( 'init', array( &$this, 'register_shortcode' ) );
    }

    public function register_shortcode() {
        add_shortcode( self::SCTAG, array( &$this, 'do_shortcode' ) );
    }

    public function do_shortcode( $attr, $content = null )
    {
        $output = '';

    	  /* Set up the default arguments. */
        $defaults = apply_filters(
            self::SCTAG.'_defaults',
            array(

            )
        );

        /* Parse the arguments. */
        $attr = shortcode_atts( $defaults, $attr );

        $output .= '<div class="weather-ico-holder">';
          $output .= '<div class="weather-layers">';
            $output .= '<div class="layer1 lay-sunshine-big anim-rotate" anim="rotate" data-rotate="right"></div>';
            $output .= '<div class="layer2 lay-sunshine-short anim-rotate" anim="rotate" data-rotate="right"></div>';
            $output .= '<div class="layer3 lay-sun"></div>';
            $output .= '<div class="layer4 lay-cloud"></div>';
          $output .= '</div>';
        $output .= '</div>';


        /* Return the output of the tooltip. */
        return apply_filters( self::SCTAG, $output );
    }
}

new WeatherIcon();

?>
