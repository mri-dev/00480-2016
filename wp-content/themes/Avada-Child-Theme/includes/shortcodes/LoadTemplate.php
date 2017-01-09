<?php
class LoadTemplate
{
    const SCTAG = 'load-template';

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
              'file' => false
            )
        );

        /* Parse the arguments. */
        $attr = shortcode_atts( $defaults, $attr );

        if (!$attr['file']) {
          return false;
        }

        $file = $attr['file'] . ".php";

        ob_start();
        include(locate_template($file));
        $output .= ob_get_contents();
        ob_end_clean();

        /* Return the output of the tooltip. */
        return apply_filters( self::SCTAG, $output );
    }
}

new LoadTemplate();

?>
