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
              'limit'   => 30,
              'sort'   => 'price|asc',
              'max_hotels' => 999,
              'sziget'  => false,
              'showtitle' => false,
              'control' => 0,
              'zones' => ''
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

      // Kereső eredmény params
      if (is_null($this->params['tipus'])) {
        if(isset($_GET[zona]) && !empty($_GET[zona])){
          $this->params[zones] = explode(",",$_GET[zona]);
        }
      }

      if (isset($_GET['sort'])) {
        $this->params['sort'] = $_GET['sort'];
      }

      $c = new ViasaleProgramok( $this->params );
      $t = new ShortcodeTemplates(__CLASS__.'/'.__FUNCTION__.( ($this->template ) ? '-'.$this->template:'' ));

      $data = $c->getData();

      if($this->params['control'] == '1') {
        $o .= '<div class="list-header">
          <form method="get" id="list-filter-form" action="'.KERESO_PROGRAM_SLUG.'">
          <div class="fusion-row">
            <div class="fusion-one-half fusion-layout-column fusion-spacing-yes">
              <div class="fusion-column-wrapper">
                <div class="list-info">
                  <h1><span class="total_result">'.$c->total.' db</span> programot találtunk</h1>
                  <div class="pages">
                    '.$c->total_page.' oldal / <strong>'.$c->current_page.'. oldal</strong>
                  </div>
                </div>
              </div>
            </div>
            <div class="fusion-one-half fusion-layout-column fusion-spacing-yes fusion-column-last">
              <div class="fusion-column-wrapper">
                <div class="list-order">
                  <div class="text">Rendezés:</div>
                  <select id="filterlist" name="sort">
                    <option value="price|asc" '.( ($_GET['sort'] == '' || $_GET['sort'] == 'price|asc') ? 'selected="selected"' : '' ).'>Ár szerint - Növekvő</option>
                    <option value="price|desc" '.(($_GET['sort'] == 'price|desc') ? 'selected="selected"' : '' ).'>Ár szerint - Csökkenő</option>
                  </select>
                </div>
              </div>
            </div>
          </div>
          ';
          foreach ($_GET as $key => $value) { if($key == 'sort') continue;
            $o .= '<input type="hidden" name="'.$key.'" value="'.$value.'" />';
          }
          $o .= '
          </form>
        </div>';
      }

      $o .= '<div class="style-'.$this->template.'">';

      if($data)
      {
        foreach ($data as $d)
        {
          $o .= $t->load_template($d);
        }

        if($this->params['control'] == '1') {
          $o .=  '<div class="pagination">'. $c->pagination(get_option('siteurl').'/'.KERESO_PROGRAM_SLUG) . '</div>';
          $o .= '
          <script>
            (function($){
              $("#filterlist").change(function(){
                $("#list-filter-form").submit();
              });
            })(jQuery);
          </script>
          ';
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
        $apic = new ViasaleAPIFactory();
        $szid = $apic->sziget_ids[$this->params['sziget']]['id'];
        $this->params[zones] = $szid;
        $this->params['limit'] = 999;
        unset($apic);
      }

      // Kereső eredmény params
      if (is_null($this->params['tipus'])) {

        if((isset($_GET[zona]) && !empty($_GET[zona])) && empty($this->params['sziget'])){
          $this->params[zones] = $_GET[zona];
        }

        if(isset($_GET[offers]) && !empty($_GET[offers])){
          $excat = explode(",", $_GET['offers']);
          if (count($excat) == 1) {
            if(in_array($excat[0], array('firstminute', 'lastminute'))){
              $this->params[tipus] = $excat[0];
            }
          } else if(count($excat) > 1) {
            foreach ($excat as $ec) {
              if(in_array($ec, array('firstminute', 'lastminute'))){
                $this->params[tipus] .= $ec.',';
              }
            }
            $this->params[tipus] = rtrim($this->params[tipus],",");
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

        if(isset($_GET[sort])){
          $this->params[sort] = $_GET[sort];
        }

      }

      // /print_r($this->params);

      $c = new ViasaleAjanlatok( $this->params );
      $t = new ShortcodeTemplates(__CLASS__.'/'.__FUNCTION__.( ($this->template ) ? '-'.$this->template:'' ));

      $data = $c->getData();

      if($data)
      {
        if($this->params['control'] == '1') {
          $o .= '<div class="list-header">
            <form method="get" id="list-filter-form" action="'.KERESO_SLUG.'">
            <div class="fusion-row">
              <div class="fusion-one-half fusion-layout-column fusion-spacing-yes">
                <div class="fusion-column-wrapper">
                  <div class="list-info">
                    <h1><span class="total_result">'.$c->total.' db</span> utazási ajánlatot találtunk</h1>
                    <div class="pages">
                      '.$c->total_page.' oldal / <strong>'.$c->current_page.'. oldal</strong>
                    </div>
                  </div>
                </div>
              </div>
              <div class="fusion-one-half fusion-layout-column fusion-spacing-yes fusion-column-last">
                <div class="fusion-column-wrapper">
                  <div class="list-order">
                    <div class="text">Rendezés:</div>
                    <select id="filterlist" name="sort">
                      <option value="price|asc" '.( ($_GET['sort'] == '' || $_GET['sort'] == 'price|asc') ? 'selected="selected"' : '' ).'>Ár szerint - Növekvő</option>
                      <option value="price|desc" '.(($_GET['sort'] == 'price|desc') ? 'selected="selected"' : '' ).'>Ár szerint - Csökkenő</option>
                      <option value="date|asc" '.(($_GET['sort'] == 'date|asc') ? 'selected="selected"' : '' ).'>Dátum szerint - Növekvő</option>
                      <option value="date|desc" '.(($_GET['sort'] == 'date|desc') ? 'selected="selected"' : '' ).'>Dátum szerint - Csökkenő</option>
                      <option value="name|asc" '.(($_GET['sort'] == 'name|asc') ? 'selected="selected"' : '' ).'>Név szerint - Növekvő</option>
                      <option value="name|desc" '.(($_GET['sort'] == 'name|desc') ? 'selected="selected"' : '' ).'>Név szerint - Csökkenő</option>
                      <option value="category|asc" '.(($_GET['sort'] == 'category|asc') ? 'selected="selected"' : '' ).'>Csillag szerint - Növekvő</option>
                      <option value="category|desc" '.(($_GET['sort'] == 'category|desc') ? 'selected="selected"' : '' ).'>Csillag szerint - Csökkenő</option>
                      <option value="board|asc" '.(($_GET['sort'] == 'board|asc') ? 'selected="selected"' : '' ).'>Ellátás szerint - Növekvő</option>
                      <option value="board|desc" '.(($_GET['sort'] == 'board|desc') ? 'selected="selected"' : '' ).'>Ellátás szerint - Csökkenő</option>
                    </select>
                  </div>
                </div>
              </div>
            </div>
            ';
            foreach ($_GET as $key => $value) { if($key == 'sort') continue;
              $o .= '<input type="hidden" name="'.$key.'" value="'.$value.'" />';
            }
            $o .= '
            </form>
          </div>';
        }
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



        if($this->params['control'] == '1') {
          $o .=  '<div class="pagination">'. $c->pagination(get_option('siteurl').'/'.KERESO_SLUG) . '</div>';
          $o .= '
          <script>
            (function($){
              $("#filterlist").change(function(){
                $("#list-filter-form").submit();
              });
            })(jQuery);
          </script>
          ';
        }

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
        if ($this->params['showtitle'])
        {
          $title = 'Összes szálloda';
          if (!empty($_GET['c']))
          {
            $title = 'Összes <span class="stars">' . str_repeat('<i class="fa fa-star"></i>', (int)$_GET['c']).'</span> szálloda';
          }
          if (!empty($_GET['zona']))
          {
            $zona = $c->getZone((int)$_GET['zona'], array('api_version' => 'v2'));
            if ($zona) {
              $title .= ' <span class="sziget">'.$zona['name'].'</span> szigetén';
            }
          } else {
            $title .= ' <span class="sziget">Kanári-szigeteken</span>';
          }

          $o .= '<div class="hotel-list-title">
            <h1>'.$title.'<h1>
            <div class="subline">
              <span class="total">'.$c->total.' db szálloda</span>
            </div>
          </div>';
        }

        $o .= '<div class="style-'.$this->template.'">';

        $i = 0;
        foreach ($data as $d)
        {
          $i++;
          $d['item_index'] = $i;
          $o .= $t->load_template($d);
        }

        $o .= '</div>';
        $o .=  '<div class="pagination">'. $c->pagination(get_option('siteurl').'/'.HOTEL_LIST_SLUG) . '</div>';

      } else {
        $o .= '<div class="no-search-result">
        <h3>Nem találtunk elérhető szállodát.</h3>
        A keresési feltételek alapján nem találtunk szállodákat az Ön részére. <br>
        <small>Próbáljon más szűrőfeltételek alapján is keresni.</small><br>
        </div>';
      }

      return $o;
    }
}

new ViasaleLista();

?>
