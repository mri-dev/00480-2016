<?php
class ViasaleProgramok extends ViasaleAPIFactory
{

  public function __construct( $arg = array() )
  {

  }

  public function getData()
  {
    $data = array();

    for ($i=1; $i <= 4 ; $i++)
    {
      $data[$i] = array(
        'link'  => 'http://viasaletravel.ideafontana.eu/',
        'title' => 'Program #'.$i.' címe',
        'desc'  => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Morbi libero nibh,
        onsectetur sit amet nulla nec, accumsan pellentesque ipsum.
        In hac habitasse platea dictumst. Nam fringilla tristique mauris sit amet ultricies...',
        'price' => '99',
        'price_v' => '€',
        'image' => 'http://viasaletravel.ideafontana.eu/wp-content/uploads/2016/09/imageplaceholder.png'
      );
    }

    return $data;
  }
}
?>
