<?php
/**
* Utazási ajánlat
**/
class ViasaleAjanlat extends ViasaleAPIFactory
{
  const DATEFORMAT = 'Y / m / d';
  public $arg = array();
  public $term_id = false;
  public $term_data = null;
  public $hotel_data = null;

  public function __construct( $id = false, $arg = array() )
  {
    if( !$id || empty($id) ) return $this;

    $this->term_id = $id;
    $this->arg = $arg;

    $this->load();

    return $this;
  }
  public function getTravelID()
  {
    return $this->term_data['term_id'];
  }
  public function getHotelID()
  {
    return $this->term_data['hotel']['id'];
  }
  public function getHotelName()
  {
    return $this->term_data['hotel']['name'];
  }
  public function getDate($what = 'from')
  {
    return date(self::DATEFORMAT, strtotime($this->term_data['date_'.$what]));
  }
  public function getDayDuration()
  {
    $day = 60*60*24;

    $div = strtotime($this->term_data['date_to']) - strtotime($this->term_data['date_from']);

    return $div / $day;
  }
  public function getURISlug( $after_id = '' )
  {
    $seo_title_list = '';
    $zone_list = $this->getHotelZones();

    foreach ($zone_list as $z ) {
      $seo_title_list .= sanitize_title($z).'/';
    }

    $seo_title_list .= sanitize_title($this->getHotelName()).'/';

    if( $after_id != '') {
      $seo_title_list .= $after_id.'/';
    }

    return UTAZAS_SLUG.'/'.$seo_title_list;
  }
  public function getRoomsCount()
  {
    return count($this->term_data['rooms']);
  }
  public function getGPS()
  {
    $hotel = $this->getHotelData($this->term_data['hotel']['id']);
    return array(
      'lat' => (float)$hotel['gpsy'],
      'lng' => (float)$hotel['gpsx']
    );
  }
  public function getRooms()
  {
    return $this->term_data['rooms'];
  }
  public function getBoardName()
  {
    return $this->term_data['board_name'];
  }
  public function getDefaultRoomData()
  {
    foreach ($this->term_data['rooms'] as $room_id => $room)
    {
      if($room['default_room'] == 1) {
        return $room;
      }
    }
    return false;
  }
  public function getMinAdults()
  {
    $a = 999;

    foreach ($this->term_data['rooms'] as $room_id => $room)
    {
      $ma = (int)$room['min_adults'];
      if($ma < $a ) $a = $ma;
    }

    return $a;
  }
  public function getMaxAdults()
  {
    $a = 1;

    foreach ($this->term_data['rooms'] as $room_id => $room)
    {
      $ma = (int)$room['max_adults'];
      if($ma > $a ) $a = $ma;
    }

    return $a;
  }
  public function getChildrenByAdults()
  {
    $c = array();

    if($this->term_data['rooms'])
    foreach ($this->term_data['rooms'] as $i => $r) {
      if($r['adults'])
      foreach ($r['adults'] as $adult_num => $adult_obj) {
        $min = (int)$adult_obj['min_children'];
        $max = (int)$adult_obj['max_children'];

        if(!isset($c[$adult_num]['min'])) {
          $c[$adult_num]['min'] = (int)$min;
        } else {
          if($min < $c[$adult_num]['min']) $c[$adult_num]['min'] = (int)$min;
        }

        if(!isset($c[$adult_num]['max'])) {
          $c[$adult_num]['max'] = (int)$max;
        } else {
          if($max > $c[$adult_num]['max']) $c[$adult_num]['max'] = (int)$max;
        }

      }
    }

    ksort($c);

    return $c;
  }
  public function getDefaultRoomMinChildren()
  {
    $default_room = $this->getDefaultRoomData();

    return (int)$default_room['adults'][$default_room['min_adults']]['min_children'];
  }
  public function getDefaultRoomMaxChildren()
  {
    $default_room = $this->getDefaultRoomData();

    return (int)$default_room['adults'][$default_room['min_adults']]['max_children'];
  }
  public function getStar()
  {
    return (float)$this->term_data['hotel']['category'];
  }
  public function getHotelZones()
  {
    if(!$this->hotel_data['hotels'][0]){ return false; }

    $zones = array();

    foreach ($this->hotel_data['hotels'][0]['zone_list'] as $i => $z) {
      if ($i > 0) {
        $zones[$z['id']] = $z['name'];
      }
    }
    return $zones;
  }
  public function getOfferKey()
  {
    return $this->hotel_data['hotels'][0]['offer'];
  }
  public function getMoreTravelCount()
  {
    return $this->hotel_data['hotels'][0]['more_term_count'];
  }
  /**
  * További hotel ajánlatok
  * @param array $param Paraméterek
  *                     array $except_term_ids kizárt term_id-k
  * @return array Hotel lista API kimenet /terms/hotels=xxx
  **/
  public function getMoreTravel( $param = array() )
  {
    $ajanlatok = array();

    if(!$this->hotel_data['hotels'][0]['more_terms']){ return false; }

    $api_ajanlatok = $this->hotel_data['hotels'][0]['more_terms'];

    foreach ($api_ajanlatok as $k => $a)
    {
      if( isset($param['except_term_ids']) && is_array($param['except_term_ids']) && in_array($a['term_id'], $param['except_term_ids']) ) continue;

      $a['price_from_huf'] = round((float)$a['price_from'] * (float)$this->term_data['exchange_rate']);
      $ajanlatok[] = $a;
    }

    return  $ajanlatok;
  }

  public function getDifferentModes()
  {
    $modes = array();
    $ajanlatok = $this->getMoreTravel();

    $date_from = $this->term_data['date_from'];

    if ($ajanlatok) {
      foreach ($ajanlatok as $aj)
      {
        if( $aj[date_from] != $date_from ) continue;

        if( $aj['term_id'] == $this->getTravelID() ){
          continue;
        }

        $aj['price_diff'] = $aj['price_from'] - $this->getPriceOriginalEUR();

        $modes[] = $aj;
      }
    }

    return $modes;
  }
  public function getPriceOriginalEUR()
  {
    return (float)$this->term_data['price_from'];
  }
  public function getPriceOriginalHUF()
  {
    return round((float)$this->term_data['price_from'] * (float)$this->term_data['exchange_rate']);
  }
  public function getDescriptions()
  {
    return $this->term_data['hotel']['descriptions'];
  }
  public function getBoardType()
  {
    return $this->term_data['board_name'];
  }
  public function getProfilImage()
  {
    return $this->term_data['hotel']['pictures'][0];
  }
  public function getMoreImages()
  {
    $set = array();

    if($this->term_data['hotel']['pictures'])
    foreach ($this->term_data['hotel']['pictures'] as $key => $value) {
      if($key == 0) continue;
      $set[] = $value;
    }
    return $set;
  }

  private function load()
  {
    $this->term_data = $this->getTerm($this->term_id);
    $this->getHotelInfo($this->getHotelID());
  }

  /**
  * Hotel adatlap adatok
  * @uses ViasaleAPIFactory::getHotel($id)
  * @link http://viasale.net/api/v2/hotels/{hotel_id}
  **/
  private function getHotelData($hotel_id)
  {
    return $this->getHotel($hotel_id);
  }

  /**
  * Ajánlatokkal együtt a hotel adatok
  **/
  private function getHotelInfo($hotel_id)
  {
    $this->hotel_data = $this->getTerms(array(
      'hotels' => array($hotel_id),
      'limit' => 999,
      'order' => 'date|asc'
    ));
  }
}
?>
