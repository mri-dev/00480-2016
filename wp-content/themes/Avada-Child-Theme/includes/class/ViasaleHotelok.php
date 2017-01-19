<?php
class ViasaleHotelok extends ViasaleAPIFactory
{
  public $arg = array();
  public $total_page = 0;
  public $current_page = 1;
  public $total = 0;

  public function __construct( $arg = array() )
  {
    $this->setApiVersion('v3');
    parent::__construct();

    $this->arg = $arg;
  }

  public function getData()
  {
    $data = array();
    $search = array();

    //print_r($this->arg);

    //$search = array_merge($search, $this->arg);


    if(isset($this->arg['sziget']) && !empty($this->arg['sziget']))
    {
      $szid = $this->sziget_ids[$this->arg['sziget']]['id'];
      $search[zones] = $szid;
      $this->arg['limit'] = 999;
    }


    if(isset($_GET['zona']) && !empty($_GET['zona'])) {
      $search['zones'] = $_GET['zona'];
    }

    if(isset($_GET['c']) && !empty($_GET['c'])) {
      $search['categories'] = (int)$_GET['c'];
    }

    if(get_query_var('page')) {
      $search['page'] = (int)get_query_var('page');
      $this->current_page = $search['page'];
    }

    $search['per_page'] = $this->arg['limit'];

    // Ajánlatok betöltése
    $terms = $this->getHotels($search);

    $this->total_page = (int)$terms['last_page'];
    $this->total = (int)$terms['total'];

    if(!empty($terms['data']) && true)
    {
      $i = 0;
      foreach ($terms['data'] as $hotel)
      { $i++;

        $price = (int)$hotel['price_from'];

        if ( !$hotel['main_picture'] ) {
          $hotel['main_picture']['url'] = NOIMAGE_MID;
        }

        $data[] = array(
          'link'  => get_option('siteurl', '/').'/'.HOTEL_SLUG.'/kanari-szigetek/'.sanitize_title($hotel['island']['name']).'/'.sanitize_title($hotel['zone']['name']).'/'.sanitize_title($hotel['name']).'/'.$hotel['id'],
          //'offer_link'  => get_option('siteurl', '/').'/'.UTAZAS_SLUG.'/kanari-szigetek/'.sanitize_title($hotel['zone_list'][2]['name']).'/'.sanitize_title($hotel['zone_list'][3]['name']).'/'.sanitize_title($hotel['hotel_name']).'/'.$hotel['term_id'],
          'island_text' => $hotel['island']['name'],
          'place' => $hotel['zone']['name'],
          'title' => $hotel['name'],
          'star'  => (int)$hotel['category'],
          //'discount' => $discount,
          //'price_origin' => $origin_price,
          //'price' => $price,
          //'price_huf' => round($price * $exchange_rate),
          //'price_v' => '€',
          'image' => $hotel['main_picture']['url'],
          'image_obj' => $hotel['main_picture'],
          //'total_travel_count' => $hotel['all_term_count'],
          //'date_from' => $this->format_date($hotel['date_from']),
          //'date_to' => $this->format_date($hotel['date_to']),
          'features' => array(
            //'time' => array('text' => 'Időpont', 'value' => $this->format_date($hotel['date_from'])),
            //'days' => array('text' => 'Napok száma', 'value' => $hotel['term_duration']),
            //'supply' => array('text' => 'Ellátás', 'value' => $hotel['board_type']),
            //'transport' => array('text' => 'Ajánlatok száma', 'value' => $hotel['term_count']),
          )
        );

      }
    }

    return $data;
  }

  public function pagination( $base = '' )
  {
    return paginate_links( array(
    	'base'   => $base.'%_%',
    	'format'  => '/%#%/',
    	'current' => max( 1, get_query_var('page') ),
    	'total'   => $this->total_page
    ) );
  }

}
?>
