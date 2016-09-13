<?php
class ViasaleLista
{
    const SCTAG = 'viasale-lista';
    // Elérhető set-ek
    public $sets = array('program', 'ajanlat');

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
              'set'     => null,
              'tipus'   => null
            )
        );

        /* *
        * Ha nincs olyan set érték az elérhető paraméterek között,amit megadtak,
        * akkor értesíti a felhasználót, hogy hibás set értéket adott meg.
        * */
        if (!in_array($attr['set'], $this->sets)) {
          $output .= '(!)'.__CLASS__.': Nincs ilyen set ('.$attr['set'].') érték, amit megadott.';
        }

        /* Parse the arguments. */
        $attr = shortcode_atts( $defaults, $attr );

        if (!is_null($attr['set']))
        {
          $output .= '<div class="'.self::SCTAG.'-set-'.$attr['set'].'">';
          switch ($attr['set'])
          {
            // PROGRAMOK
            case 'program':
              $output .= $this->programok();
            break;
          }
          $output .= '</div>';
        }

        $output .= '</div>';

        /* Return the output of the tooltip. */
        return apply_filters( self::SCTAG, $output );
    }

    /**
    * PROGRAMOK SET
    **/
    private function programok( $arg = array() )
    {
      $o = '';
      
      $c = new ViasaleProgramok( $arg );
      $t = new ShortcodeTemplates(__CLASS__.'/'.__FUNCTION__);

      $o .= $t->load_template(array('xxx' => 12345));

      return $o;
    }
}

new ViasaleLista();

?>
