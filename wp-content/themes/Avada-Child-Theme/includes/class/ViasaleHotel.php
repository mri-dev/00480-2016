<?php
/**
* Hotel adatok
**/
class ViasaleHotel extends ViasaleAPIFactory
{
  public $arg = array();
  public $hotel_id = false;
  public $hotel_data = null;

  public function __construct( $id = false, $arg = array() )
  {
    if( !$id || empty($id) ) return $this;

    $this->hotel_id = $id;
    $this->arg = $arg;

    $this->load();

    return $this;
  }
  public function getHotelID()
  {
    return $this->hotel_data['id'];
  }
  public function getHotelName()
  {
    return $this->hotel_data['name'];
  }
  public function getURISlug( $after_id = '' )
  {
    $seo_title_list = '';
    $zone_list = $this->getHotelZones();

    if($zone_list)
    foreach ($zone_list as $z ) {
      $seo_title_list .= sanitize_title($z).'/';
    }

    $seo_title_list .= sanitize_title($this->getHotelName()).'/';

    if( $after_id != '') {
      $seo_title_list .= $after_id.'/';
    }

    return HOTEL_SLUG.'/'.$seo_title_list;
  }
  public function getGPS()
  {
    $hotel = $this->getHotelData($this->hotel_data);
    return array(
      'lat' => (float)$hotel['gpsy'],
      'lng' => (float)$hotel['gpsx']
    );
  }
  public function getStar()
  {
    return (float)$this->hotel_data['category'];
  }
  public function getHotelZones()
  {
    if(!$this->hotel_data['zone_list']){ return false; }

    $zones = array();

    foreach ($this->hotel_data['zone_list'] as $i => $z) {
      if ($i > 0) {
        $zones[$z['id']] = $z['name'];
      }
    }
    return $zones;
  }
  public function getDescriptions()
  {
    $infos = false;

    if($this->hotel_data['descriptions'])
    foreach ($this->hotel_data['descriptions'] as $key => $value) {
      if($value['name'] == 'Leírás') continue;
      $infos[] = $value;
    }

    return $infos;
  }
  public function getInfo()
  {
    $info = false;

    if($this->hotel_data['descriptions'])
    foreach ($this->hotel_data['descriptions'] as $key => $value) {
      if($value['name'] != 'Leírás') continue;
      $info = $value['description'];
    }

    return $info;
  }
  public function getProfilImage()
  {
    return $this->hotel_data['pictures'][0];
  }
  public function getMoreImages()
  {
    $set = array();

    if($this->hotel_data['pictures'])
    foreach ($this->hotel_data['pictures'] as $key => $value) {
      if($key == 0) continue;
      $set[] = $value;
    }
    return $set;
  }

  private function load()
  {
    $this->hotel_data = $this->getHotel($this->hotel_id);
  }
}
?>
