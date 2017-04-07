<?php
class GAConversationPageSC
{
    const SCTAG = 'GAConversationPage';

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
              'key' => false
            )
        );

        /* Parse the arguments. */
        $attr = shortcode_atts( $defaults, $attr );


        if (!defined('CLONEKEY')) {
          switch ($attr['key']) {
            case 'utazasi_ajanlat_megrendeles':

              $price = (float)$_GET['pv'];
              if($price > 0) {
                $output .= '<!-- Google Code for Megrendeles Conversion Page -->
                <script type="text/javascript">
                /* <![CDATA[ */
                var google_conversion_id = 858546762;
                var google_conversion_language = "en";
                var google_conversion_format = "3";
                var google_conversion_color = "ffffff";
                var google_conversion_label = "iWT9CMvE-m8QysSxmQM";
                var google_conversion_value = '.$price.';
                var google_conversion_currency = "EUR";
                var google_remarketing_only = false;
                /* ]]> */
                </script>
                <script type="text/javascript" src="//www.googleadservices.com/pagead/conversion.js">
                </script>
                <noscript>
                <div style="display:inline;">
                <img height="1" width="1" style="border-style:none;" alt="" src="//www.googleadservices.com/pagead/conversion/858546762/?value='.$price.'&amp;currency_code=EUR&amp;label=iWT9CMvE-m8QysSxmQM&amp;guid=ON&amp;script=0"/>
                </div>
                </noscript>';
              }

            break;

            default:
            break;
          }
        }

        /* Return the output of the tooltip. */
        return apply_filters( self::SCTAG, $output );
    }
}

new GAConversationPageSC();

?>
