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

    $szigetek = array(
      0 => 'Tenerife',
      1 => 'Gran Canaria',
      2 => 'Fuerteventura',
      3 => 'Lanzarote'
    );
    $kep = array(
        0 => 'http://viasaletravel.ideafontana.eu/wp-content/uploads/2016/09/ARC_15_002_tenerife.jpg',
        1 => 'http://viasaletravel.ideafontana.eu/wp-content/uploads/2016/09/02aSolBarbacan-GeneralPool_grancanaria.jpg',
        2 => 'http://viasaletravel.ideafontana.eu/wp-content/uploads/2016/09/Pool3Pan_fuerteventura.jpg',
        3 => 'http://viasaletravel.ideafontana.eu/wp-content/uploads/2016/09/5306324966_4cb48288a0_o_lanzarote.jpg'
    );

    $limit = ($this->arg[limit]) ? $this->arg[limit] : 5;

    for ($i=1; $i <= $limit ; $i++)
    {
      $discount = false;
      $key = array_rand($szigetek);
      $sziget = $szigetek[$key];

      $price = rand(390, 1200);

      if ($price > 800) {
        $origin_price = $price;
        $discount     = rand(5, 25);

        $price = round($price - ($price/ 100 * $discount));
      }

      $data[$i] = array(
        'link'  => 'http://viasaletravel.ideafontana.eu/',
        'island_text' => $sziget,
        'place' => 'Los Cristianos',
        'title' => 'Hotel rent #'.$i,
        'star'  => rand(2,4),
        'discount' => $discount,
        'price_origin' => $origin_price,
        'price' => $price,
        'price_huf' => round($price * 314),
        'price_v' => '€',
        'image' => $kep[$key],
        'features' => array(
          'time' => array('text' => 'Időpont', 'value' => '2016-10-01'),
          'days' => array('text' => 'Napok száma', 'value' => 8),
          'supply' => array('text' => 'Ellátás', 'value' => 'Teljes ellátás'),
          'transport' => array('text' => 'Közlekedés', 'value' => 'Repülő'),
        )
      );
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
