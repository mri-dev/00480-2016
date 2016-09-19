<?php
class ViasaleKereso
{
    const SCTAG = 'viasale-kereso';
    // Elérhető set-ek
    public $params = array();

    public function __construct()
    {
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

            )
        );

        /* Parse the arguments. */
        $attr           = shortcode_atts( $defaults, $attr );
        $this->params   = $attr;

        $searcher = new ViasaleKeresok( $this->params );
        $t = new ShortcodeTemplates(__CLASS__.'/v1');

        //////////////////////////////////////////////
        $this->params[zones] = $searcher->getZonesTree();
        $this->params[zone_deep] = $searcher->getZonesDeeps() - $searcher->min_zone_deep;
        $this->params[boardTypes] = $searcher->getBoardTypes();
        $this->params[hotelStars] = $searcher->getHotelStars();
        /*
        echo '<pre>';
        print_r($this->params[zones]);
        echo '</pre>';
        */
        //////////////////////////////////////////////

        $output .= $t->load_template($this->params);
        $output .= '</div>';

        /* Return the output of the tooltip. */
        return apply_filters( self::SCTAG, $output );
    }
}

new ViasaleKereso();

?>
