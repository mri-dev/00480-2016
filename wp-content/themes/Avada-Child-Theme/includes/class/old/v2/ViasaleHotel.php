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

  public function getTravels()
  {
    $travels = array();

    $ajanlatok = $this->getTerms(array(
      'hotels'  => $this->hotel_id,
      'limit'   => 999,
      'order'   => 'date|asc'
    ));


    if($ajanlatok)
    {
      $exchange_rate = (float)$ajanlatok['exchange_rate'];
      $more = $ajanlatok['hotels'][0]['more_terms'];
      $travels['total_terms_count'] = count($more);
      $first = $ajanlatok['hotels'][0];
      $travels['terms'][] = array(
        'link'            => get_option('siteurl', '/').'/'.UTAZAS_SLUG.'/'.SZIGET_SLUG.'/'.sanitize_title($first['zone_list'][2]['name']).'/'.sanitize_title($first['zone_list'][3]['name']).'/'.sanitize_title($first['hotel_name']).'/'.$first['term_id'],
        'term_id'         => $first['term_id'],
        'board_id'        => $first['board_id'],
        'board_type'      => $first['board_type'],
        'date_from'       => $first['date_from'],
        'date_to'         => $first['date_to'],
        'term_duration'   => $first['term_duration'],
        'price_from'      => $first['price_from'],
        'price_original'  => $first['price_original'],
        'price_from_huf'  => round((float)$first['price_from'] * (float)$exchange_rate),
        'hotel_status'    => $first['hotel_status'],
        'offer'           => /*$first['offer']*/ 'lastminute'
      );
      if($more)
      foreach ($more as $term ) {
        $term['link']           = get_option('siteurl', '/').'/'.UTAZAS_SLUG.'/'.SZIGET_SLUG.'/'.sanitize_title($first['zone_list'][2]['name']).'/'.sanitize_title($first['zone_list'][3]['name']).'/'.sanitize_title($first['hotel_name']).'/'.$term['term_id'];
        $term['price_from_huf'] = round((float)$term['price_from'] * (float)$exchange_rate );
        $travels['terms'][] = $term;
      }
    }

    return $travels;
  }

  private function load()
  {
    $this->hotel_data = $this->getHotel($this->hotel_id);
  }
}
?>
