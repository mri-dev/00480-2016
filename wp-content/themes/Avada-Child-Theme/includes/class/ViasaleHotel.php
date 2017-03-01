<?php
/**
* Hotel adatok
* @version v3
**/
class ViasaleHotel extends ViasaleAPIFactory
{
  public $arg = array();
  public $hotel_id = false;
  public $hotel_data = null;

  public function __construct( $id = false, $arg = array() )
  {
    parent::__construct();
    if( !$id || empty($id) ) return $this;

    if(isset($arg['api_version'])) {
      $this->setApiVersion($arg['api_version']);
    }
    parent::__construct();

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

    return HOTEL_SLUG.'/kanari-szigetek/'.$seo_title_list;
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
    $zones = array();

    $raw_obj = $this->hotel_data['zone'];

    $has_parent = true;

    $current = $raw_obj;

    while( $has_parent ) {
      $zones[$current['id']] = $current['name'];
      if(isset($current['parent'])) {
        $current = $current['parent'];
      }
      else {
        $current = false;
        $has_parent = false;
      }
    }

    $zones = array_reverse($zones, true);

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

  public function getTravels()
  {
    $travels = array();

    $arg = array(
      'page' => 1,
      'sort_by' => 'price|asc',
    );

    if(get_query_var('page')) {
      $arg['page'] = (int)get_query_var('page');
    }

    $terms = $this->getHotelTerms($this->getHotelID(), $arg);
    $travels['total_terms_count'] = $terms['total'];
    $travels['total_page'] = (int)$terms['last_page'];

    if($terms['total'] != 0)
    foreach ($terms['data'] as $t) {
      $travels['terms'][] = new ViasaleAjanlat( $t['id'], array( 'api_version' => 'v3') );
    }

    return $travels;
  }

  private function load()
  {
    $this->hotel_data = $this->getHotel($this->hotel_id);
  }
}
?>
