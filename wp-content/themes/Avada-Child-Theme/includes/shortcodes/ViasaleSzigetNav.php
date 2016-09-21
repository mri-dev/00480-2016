<?php
class ViasaleSzigetNav extends ViasaleKeresok
{
    const SCTAG = 'viasale-sziget-nav';

    public function __construct()
    {
        add_action( 'init', array( &$this, 'register_shortcode' ) );
    }

    public function register_shortcode() {
        add_shortcode( self::SCTAG, array( &$this, 'do_shortcode' ) );
    }

    public function do_shortcode( $attr, $content = null )
    {
        global $post;

        $output = '<div class="'.self::SCTAG.'-holder">';

    	  /* Set up the default arguments. */
        $defaults = apply_filters(
            self::SCTAG.'_defaults',
            array(
            )
        );

        /* Parse the arguments. */
        $attr = shortcode_atts( $defaults, $attr );

        $t = new ShortcodeTemplates(__CLASS__.'/default');

        $attr['zones'] = $this->getZonesTree();
        $attr['sziget_slug'] = $post->post_name;

        $output .= $t->load_template($attr);

        $output .= '</div>';

        /* Return the output of the tooltip. */
        return apply_filters( self::SCTAG, $output );
    }
}

new ViasaleSzigetNav();

?>
