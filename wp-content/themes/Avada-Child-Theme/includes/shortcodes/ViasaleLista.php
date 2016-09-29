<?php
class ViasaleLista
{
    const SCTAG = 'viasale-lista';
    // Elérhető set-ek
    public $params = array();
    public $sets = array('program', 'ajanlat', 'hotel', 'transzfer');
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
              'max_hotels' => 999,
              'sziget'  => false
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
            // HOTEL
            case 'hotel':
              $output .= $this->hotel();
            break;
            // TRANSZFER
            case 'transzfer':
              $output .= $this->transzfer();
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
      $t = new ShortcodeTemplates(__CLASS__.'/'.__FUNCTION__.( ($this->template ) ? '-'.$this->template:'' ));

      $data = $c->getData();

      $o .= '<div class="style-'.$this->template.'">';

      if($data)
      {
        foreach ($data as $d)
        {
          $o .= $t->load_template($d);
        }

      } else {
        $o .= '<div class="no-search-result">
        <h3>Nem találtunk elérhető programokat.</h3>
        A keresési feltételek alapján nem találtunk programokat az Ön részére. <br>
        <small>Próbáljon más szűrőfeltételek alapján is keresni.</small>
        </div>';
      }

      $o .= '</div>';

      return $o;
    }
    /**
    * AJÁNLAT SET
    **/
    private function ajanlat()
    {
      global $wp_query;
      $o = '';

      // Sziget listázás
      if(isset($this->params['sziget']) && !empty($this->params['sziget']))
      {
        $szid = $this->sziget_ids[$this->params['sziget']]['id'];
        $this->params[zones][] = $szid;
      }

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
      } else {
        $o .= '<div class="no-search-result">
        <h3>Nem találtunk elérhető ajánlatokat.</h3>
        A keresési feltételek alapján nem találtunk ajánlatokat az Ön részére. <br>
        <small>Próbáljon más szűrőfeltételek alapján is keresni.</small>
        </div>';
      }

      return $o;
    }

    /**
    * TRANSZFER SET
    **/
    private function transzfer()
    {
      $o = '';

      if(isset($_GET[zona]) && !empty($_GET[zona])){
        $this->params[zones] = explode(",",$_GET[zona]);
      }

      $c = new ViasaleTranszferek( $this->params );
      $t = new ShortcodeTemplates(__CLASS__.'/'.__FUNCTION__.( ($this->template ) ? '-'.$this->template:'' ));

      $data = $c->getData();

      //echo '<pre>'; print_r($data); echo '</pre>';

      if($data && !empty($c->collected_transfers_by_zone))
      {
        $o .= '<div class="style-'.$this->template.'">';

        $i = 0;
        foreach ($data as $d)
        {
          $i++;
          $d['item_index'] = $i;

          $o .= $t->load_template($d);
        }

        $o .= '</div>';
      } else {
        $o .= '<div class="no-search-result">
        <h3>Nem találtunk elérhető transzfert.</h3>
        A keresési feltételek alapján nem találtunk transzfert az Ön részére. <br>
        Figyeljen arra, hogy a célállomás csak város lehet, így szigetre nem kereshet. <br>
        <small>Próbáljon más szűrőfeltételek alapján is keresni.</small><br>
        </div>';
      }

      return $o;
    }

    /**
    * HOTEL SET
    **/
    private function hotel()
    {
      $o = '';

      $c = new ViasaleHotelok( $this->params );
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
        }

        $o .= '</div>';
      }

      return $o;
    }
}

new ViasaleLista();

?>
