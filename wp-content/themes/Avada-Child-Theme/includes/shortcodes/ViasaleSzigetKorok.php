<?php
class ViasaleSzigetKorok extends ViasaleAPIFactory
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
        $base_class = '';
    	  /* Set up the default arguments. */
        $defaults = apply_filters(
            self::SCTAG.'_defaults',
            array(
              'control' => 'sziget'
            )
        );

        /* Parse the arguments. */
        $attr = shortcode_atts( $defaults, $attr );

        if($attr['control'] != 'sziget') {
          $base_class .= 'control-'.$attr['control'].' ';
        }


        // Settings
        $sziget_holder_id = 38;

        $output = '<div class="'.self::SCTAG.'-holder '.$base_class.'">';

        $szigetek = get_posts(array(
          'post_type'   => 'page',
          'post_parent' => $sziget_holder_id,
          'orderby'   => 'menu_order',
          'order'     => 'ASC'
        ));

        if($sziget_holder_id)
        {
          $t = new ShortcodeTemplates(__CLASS__.'/home-circle');

          foreach ($szigetek as $sziget)
          {
            $sziget->zone_id = (int)$this->sziget_ids[$sziget->post_name]['id'];

            if($attr['control'] != 'sziget') {
              $attr['active'] = ((int)$_GET['zona'] === $sziget->zone_id) ? true : false;
            }

            $output .= $t->load_template(array(
              'sziget' => $sziget,
              'param' => $attr,
            ));
          }
        }

        $output .= '</div>';

        /* Return the output of the tooltip. */
        return apply_filters( self::SCTAG, $output );
    }
}

new ViasaleSzigetKorok();

?>
