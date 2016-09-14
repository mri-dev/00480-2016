<?php
class ViasaleSzigetKorok
{
    const SCTAG = 'sziget-korok';

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
        $attr = shortcode_atts( $defaults, $attr );

        // Settings
        $sziget_holder_id = 38;

        $szigetek = get_posts(array(
          'post_type'   => 'page',
          'post_parent' => $sziget_holder_id
        ));

        if($sziget_holder_id)
        {
          $t = new ShortcodeTemplates(__CLASS__.'/home-circle');

          foreach ($szigetek as $sziget)
          {
            $output .= $t->load_template($sziget);
          }
        }

        $output .= '</div>';

        /* Return the output of the tooltip. */
        return apply_filters( self::SCTAG, $output );
    }
}

new ViasaleSzigetKorok();

?>
