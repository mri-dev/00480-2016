<?php
class ViasaleLista
{
    const SCTAG = 'viasale-lista';
    // Elérhető set-ek
    public $params = array();
    public $sets = array('program', 'ajanlat');
    public $type = null;
    public $template = 'standard';

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
              'tipus'   => null,
              'stilus'  => 'standard',
              'limit'   => 1,
              'order'   => 'price|asc',
              'max_hotels' => 999
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
        $attr           = shortcode_atts( $defaults, $attr );
        $this->params   = $attr;
        $this->type     = $attr['tipus'];
        $this->template = $attr['stilus'];

        if (!is_null($attr['set']))
        {
          $output .= '<div class="'.self::SCTAG.'-set-'.$attr['set'].'">';
          switch ($attr['set'])
          {
            // PROGRAMOK
            case 'program':
              $output .= $this->programok();
            break;
            // AJÁNLAT
            case 'ajanlat':
              $output .= $this->ajanlat();
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

      $c = new ViasaleProgramok( $this->params );
      $t = new ShortcodeTemplates(__CLASS__.'/'.__FUNCTION__);

      $data = $c->getData();

      if($data)
      foreach ($data as $d)
      {
        $o .= $t->load_template($d);
      }

      return $o;
    }
    /**
    * AJÁNLAT SET
    **/
    private function ajanlat()
    {
      $o = '';

      // Kereső eredmény params
      if (is_null($this->params['tipus'])) {

        if(isset($_GET[zona]) && !empty($_GET[zona])){
          $this->params[zones] = explode(",",$_GET[zona]);
        }

        if(isset($_GET[cat]) && !empty($_GET[cat])){
          if(in_array($_GET[cat], array('firstminute', 'lastminute'))){
            $this->params[tipus] = $_GET[cat];
          }
        }

        if(isset($_GET[hid]) && !empty($_GET[hid])){
          $this->params[hotels] = explode(",",$_GET[hid]);
        }

        if(isset($_GET[tt])){
          $this->params[date_to] = str_replace(' / ', '-', $_GET[tt]);
        }

        if(isset($_GET[tf])){
          $this->params[date_from] = str_replace(' / ', '-', $_GET[tf]);
        }

        if(isset($_GET[e])){
          $this->params[board_type] = $_GET[e];
        }

        if(isset($_GET[c])){
          $this->params[min_star] = $_GET[c];
        }

      }

      $c = new ViasaleAjanlatok( $this->params );
      $t = new ShortcodeTemplates(__CLASS__.'/'.__FUNCTION__.( ($this->template ) ? '-'.$this->template:'' ));

      $data = $c->getData();

      if($data)
      {
        $o .= '<div class="style-'.$this->template.'">';

        $i = 0;
        foreach ($data as $d)
        {
          $i++;
          $d['item_index'] = $i;

          $o .= $t->load_template($d);

          if($this->template == 'imagegrid') {
            if($i == 7) $i = 0;
          }
        }

        $o .= '</div>';
      }

      return $o;
    }
}

new ViasaleLista();

?>
