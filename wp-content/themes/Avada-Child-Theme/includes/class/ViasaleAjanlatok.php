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

    //$search['zones'][] = 3;
    if(isset($this->arg['limit'])) {
      $search['limit'] = $this->arg['limit'];
      //$search['limit'] = ;
    }

    if(isset($this->arg['tipus']) && $this->arg['tipus'] == 'lastminute') {
      $search['max_hotels'] = $this->arg['limit'];
      $search['limit'] = 1;
    }

    if(isset($this->arg['tipus'])) {
      $search['offers'] = $this->arg['tipus'];
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
          'link'  => 'http://viasaletravel.ideafontana.eu/',
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
