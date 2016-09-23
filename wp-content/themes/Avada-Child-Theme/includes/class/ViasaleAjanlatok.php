<?php
class ViasaleAjanlatok extends ViasaleAPIFactory
{
  public $arg = array();

  public function __construct( $arg = array() )
  {
    $this->arg = $arg;
  }

  public function getData()
  {
    $data = array();
    $search = array();

    if(isset($this->arg['zones']) && !empty($this->arg['zones'])) {
      foreach ( $this->arg['zones'] as $zid ) {
        if($zid == '') continue;
        $search['zones'][] = $zid;
      }
    }

    if(isset($this->arg['hotels']) && !empty($this->arg['hotels'])) {
      foreach ( $this->arg['hotels'] as $hid ) {
        if($hid == '') continue;
        $search['hotels'][] = $hid;
      }
    }

    if(isset($this->arg['limit'])) {
      $search['limit'] = $this->arg['limit'];
    }

    if(isset($this->arg['tipus']) && $this->arg['tipus'] == 'lastminute') {
      $search['max_hotels'] = $this->arg['limit'];
      $search['limit'] = 1;
    }

    if(isset($this->arg['tipus']) ) {
      $search['offers'] = $this->arg['tipus'];
    }

    if(isset($this->arg['max_hotels']) ) {
      $search['max_hotels'] = $this->arg['max_hotels'];
    }

    if(isset($this->arg['order'])) {
      $search['order'] = $this->arg['order'];
    }

    if(isset($this->arg['date_from']) ) {
      $search['date_from'] = $this->arg['date_from'];
    }
    if(isset($this->arg['date_to']) ) {
      $search['date_to'] = $this->arg['date_to'];
    }
    if(isset($this->arg['board_type']) ) {
      $search['board_type'] = $this->arg['board_type'];
    }
    if(isset($this->arg['min_star']) ) {
      $search['min_star'] = $this->arg['min_star'];
    }

    // Ajánlatok betöltése
    $terms = $this->getTerms($search);
    // Deviza árfolyam EUR > HUF
    $exchange_rate = (float)$terms['exchange_rate'];

    if(!empty($terms['hotels']) && true)
    {
      $i = 0;
      foreach ($terms['hotels'] as $key => $hotel)
      { $i++;

        $price = (int)$hotel['price_from'];

        $data[] = array(
          'link'  => get_option('siteurl', '/').'/'.UTAZAS_SLUG.'/kanari-szigetek/'.sanitize_title($hotel['zone_list'][2]['name']).'/'.sanitize_title($hotel['zone_list'][3]['name']).'/'.sanitize_title($hotel['hotel_name']).'/'.$hotel['term_id'],
          'island_text' => $hotel['zone_list'][2]['name'],
          'place' => $hotel['zone_list'][3]['name'],
          'title' => $hotel['hotel_name'],
          'star'  => (int)$hotel['hotel_category'],
          'discount' => $discount,
          'price_origin' => $origin_price,
          'price' => $price,
          'price_huf' => round($price * $exchange_rate),
          'price_v' => '€',
          'image' => $hotel['picture']['url'],
          'features' => array(
            'time' => array('text' => 'Időpont', 'value' => $this->format_date($hotel['date_from'])),
            'days' => array('text' => 'Napok száma', 'value' => $hotel['term_duration']),
            'supply' => array('text' => 'Ellátás', 'value' => $hotel['board_type']),
            //'transport' => array('text' => 'Ajánlatok száma', 'value' => $hotel['term_count']),
          )
        );

      }
    }

    return $data;

  }

  private function calc_discount_percent( $origin = 0, $new = 0 )
  {
    $d = false;

    if($origin == 0 || $new == 0) return $d;

    $d = 100 - ($new / ($origin / 100));

    $d = floor($d);

    return $d;
  }

}
?>
