<?php
class DButtonSC
{
    const SCTAG = 'dbutton';

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
              'style' => 'orange',
              'text' => '',
              'link' => '',
              'icon' => false
            )
        );

        /* Parse the arguments. */
        $attr = shortcode_atts( $defaults, $attr );

        $link = $attr['link'];

        if (strpos($link, '%get_') !== false) {
          preg_match_all('/(\%get\_[a-z]*)/', $link, $findget);
          $getlist = $findget[0];
          foreach ((array)$getlist as $gt) {
            $rget = str_replace('%get_', '', $gt);
            $get = $_GET[$rget];
            if($get == '') $get = '';
            $link = str_replace($gt, $get, $link);
          }
        }

        $output .= '<a href="'.$link.'" class="fusion-button button-flat button-pill button-large button-style-'.$attr['style'].'">';
          $output .= '<span class="fusion-button-text">'.$attr['text'].'</span>';
          if ($attr['icon']) {
            $output .= '<i class="fa fa-'.$attr['icon'].' button-icon-right"></i>';
          }
        $output .= '</a>';


        /* Return the output of the tooltip. */
        return apply_filters( self::SCTAG, $output );
    }
}

new DButtonSC();

?>
