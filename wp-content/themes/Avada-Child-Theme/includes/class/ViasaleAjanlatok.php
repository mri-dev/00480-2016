<?php
class ViasaleAjanlatok extends ViasaleAPIFactory
{

  public function __construct( $arg = array() )
  {

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

    for ($i=1; $i <= 7 ; $i++)
    {

      $key = array_rand($szigetek);
      $sziget = $szigetek[$key];

      $data[$i] = array(
        'link'  => 'http://viasaletravel.ideafontana.eu/',
        'island_text' => $sziget,
        'title' => 'Hotel rent #'.$i,
        'star'  => rand(2,4),
        'price' => rand(150, 999),
        'price_v' => '€',
        'image' => $kep[$key]
      );
    }

    return $data;
  }
}
?>
