<?php
class ViasaleAjanlatok extends ViasaleAPIFactory
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

    if(isset($this->arg['zones']) && !empty($this->arg['zones'])) {
      $search['zones'] = $this->arg['zones'];
    }

    if(isset($this->arg['hotels']) && !empty($this->arg['hotels'])) {
      foreach ( $this->arg['hotels'] as $hid ) {
        if($hid == '') continue;
        $search['hotels'] = $hid;
      }
    }

    if(isset($this->arg['limit'])) {
      $search['per_page'] = $this->arg['limit'];
    }

    if(isset($this->arg['tipus']) ) {
      $search['offers'] = $this->arg['tipus'];
    }

    if(isset($this->arg['sort'])) {
      $search['sort_by'] = $this->arg['sort'];
    }

    if(isset($this->arg['date_from']) ) {
      $search['date_from'] = $this->arg['date_from'];
    }
    if(isset($this->arg['date_to']) ) {
      $search['date_to'] = $this->arg['date_to'];
    }
    if(isset($this->arg['board_type']) ) {
      $search['board_types'] = $this->arg['board_type'];
    }

    // v2
    if(isset($this->arg['min_star']) ) {
      $search['min_category'] = (int)$this->arg['min_star'];
    }
    // v3
    if(isset($this->arg['categories']) ) {
      $search['categories'] = $this->arg['categories'];
    }


    if(get_query_var('page')) {
      $search['page'] = (int)get_query_var('page');
      $this->current_page = $search['page'];
    }

    // Ajánlatok betöltése
    $terms = $this->getTerms($search);

    // Deviza árfolyam EUR > HUF
    $exchange_rate = (float)$terms['exchange_rate'];
    $this->total_page = (int)$terms['last_page'];
    $this->total = (int)$terms['total'];

    if(!empty($terms['data']) && true)
    {
      $i = 0;
      foreach ($terms['data'] as $key => $hotel)
      { $i++;

        $price = (int)$hotel['best_term']['price_from'];
        $discount = ((int)$hotel['best_term']['price_from'] < (int)$hotel['best_term']['price_original']) ? true : false;

        if ($discount) {
          $origin_price = (int)$hotel['best_term']['price_original'];
          $discount = $this->calc_discount_percent( $origin_price, $price );
        }

        if ( !$hotel['main_picture'] ) {
          $hotel['main_picture']['url'] = NOIMAGE_MID;
        }

        $data[] = array(
          'link'  => get_option('siteurl', '/').'/'.UTAZAS_SLUG.'/'.SZIGET_SLUG.'/'.sanitize_title($hotel['island']['name']).'/'.sanitize_title($hotel['zone']['name']).'/'.sanitize_title($hotel['name']).'/'.$hotel['best_term']['id'],
          'island_text' => $hotel['island']['name'],
          'place' => $hotel['zone']['name'],
          'title' => $hotel['name'],
          'star'  => (int)$hotel['category'],
          'discount' => $discount,
          'price_origin' => $origin_price,
          'price' => $price,
          'price_huf' => round($price * $exchange_rate),
          'price_v' => '€',
          'image' => $hotel['main_picture']['url'],
          'image_obj' => $hotel['main_picture'],
          'offer' => $hotel['best_term']['offer'],
          'features' => array(
            'time' => array('text' => 'Időpont', 'value' => $this->format_date($hotel['best_term']['date_from'])),
            'days' => array('text' => 'Napok száma', 'value' => (int)$hotel['best_term']['term_duration']+1),
            'supply' => array('text' => 'Ellátás', 'value' => $hotel['best_term']['board_name']),
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
