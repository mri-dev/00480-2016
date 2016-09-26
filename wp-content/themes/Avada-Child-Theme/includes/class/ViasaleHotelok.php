<?php
class ViasaleHotelok extends ViasaleAPIFactory
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

    if(isset($this->arg['sziget']) && !empty($this->arg['sziget']))
    {
      $szid = $this->sziget_ids[$this->arg['sziget']]['id'];
      $search[zones][] = $szid;
    }
    $search['max_hotels'] = 999;
    $search['order'] = 'price|asc';

    // Ajánlatok betöltése
    $terms = $this->getTerms($search);

    if(!empty($terms['hotels']) && true)
    {
      $i = 0;
      foreach ($terms['hotels'] as $key => $hotel)
      { $i++;

        $price = (int)$hotel['price_from'];

        $data[] = array(
          'link'  => get_option('siteurl', '/').'/'.HOTEL_SLUG.'/kanari-szigetek/'.sanitize_title($hotel['zone_list'][2]['name']).'/'.sanitize_title($hotel['zone_list'][3]['name']).'/'.sanitize_title($hotel['hotel_name']).'/'.$hotel['hotel_id'],
          'offer_link'  => get_option('siteurl', '/').'/'.UTAZAS_SLUG.'/kanari-szigetek/'.sanitize_title($hotel['zone_list'][2]['name']).'/'.sanitize_title($hotel['zone_list'][3]['name']).'/'.sanitize_title($hotel['hotel_name']).'/'.$hotel['term_id'],
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
          'total_travel_count' => $hotel['all_term_count'],
          'date_from' => $this->format_date($hotel['date_from']),
          'date_to' => $this->format_date($hotel['date_to']),
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

}
?>
