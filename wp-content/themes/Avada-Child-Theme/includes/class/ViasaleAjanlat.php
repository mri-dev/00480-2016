<?php
/**
* Utazási ajánlat
* @version v3
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

    if(isset($arg['api_version'])) {
      $this->setApiVersion($arg['api_version']);
    }
    parent::__construct();

    $this->term_id = $id;
    $this->arg = $arg;
    $this->load();

    return $this;
  }
  public function getTravelID()
  {
    return $this->term_data['id'];
  }
  public function getHotelID()
  {
    return $this->term_data['tour_id'];
  }
  public function getHotelName()
  {
    return $this->term_data['tour']['name'];
  }
  public function getDate($what = 'from')
  {
    return date(self::DATEFORMAT, strtotime($this->term_data['date_'.$what]));
  }
  public function getDayDuration()
  {
    $day = 60*60*24;

    $div = strtotime($this->term_data['date_to']) - strtotime($this->term_data['date_from']);

    return ($div / $day) + 1;
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

    return UTAZAS_SLUG.'/kanari-szigetek/'.$seo_title_list;
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
    return (float)$this->term_data['tour']['hotel']['category'];
  }
  public function getHotelZones()
  {
    $zones = array();

    $raw_obj = $this->term_data['tour']['hotel']['zone'];

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

  public function getOfferKey()
  {
    return $this->term_data['offer'];
  }
  public function getMoreTravelCount()
  {
    return count($this->term_data['other_terms']);
  }

  public function getMoreTravel( $param = array() )
  {
    $ajanlatok = array();

    $day = 60*60*24;

    $api_ajanlatok = $this->term_data['other_terms'];

    foreach ($api_ajanlatok as $k => $a)
    {
      if( isset($param['except_term_ids']) && is_array($param['except_term_ids']) && in_array($a['id'], $param['except_term_ids']) ) continue;

      $a['price_from_huf'] = round((float)$a['price_from'] * (float)$this->term_data['exchange_rate']);

      $div_tdm = strtotime($a['date_to']) - strtotime($a['date_from']);

      $a['term_duration'] = ($div_tdm / $day) + 1;
      $ajanlatok[] = $a;
    }

    return  $ajanlatok;
  }

  public function getDifferentModes()
  {
    $modes = array();
    $ajanlatok = (array)$this->term_data['other_board_types'];

    if ($ajanlatok) {
      foreach ($ajanlatok as $aj)
      {
        $aj['price_diff'] = $aj['price_from'] - $this->getPriceEUR();

        $modes[] = $aj;
      }
    }

    return $modes;
  }
  public function getPriceOriginalEUR()
  {
    return (float)$this->term_data['price_original'];
  }
  public function getPriceOriginalHUF()
  {
    return round((float)$this->term_data['price_original'] * (float)$this->term_data['exchange_rate']);
  }
  public function getPriceEUR()
  {
    return (float)$this->term_data['price_from'];
  }
  public function getPriceHUF()
  {
    return round((float)$this->term_data['price_from'] * (float)$this->term_data['exchange_rate']);
  }
  public function getDescription($what = 'utazas')
  {
    switch ($what) {
      case 'utazas':
        return (array)$this->term_data['tour']['descriptions'];
      break;
      case 'hotel':
        return (array)$this->term_data['tour']['hotel']['descriptions'];
      break;
    }
  }
  public function getDescriptions( $html_format = true )
  {
    $all = array();

    if ($html_format) {
      $sep = array();
      $sep[] = array(
        'name' => '<div class="sep-title"><i class="fa fa-plane"></i> Információk az utazásról</div>',
        'description' =>'<hr>'
      );
    } else {
      $sep = array();
      $sep[] = array(
        'name' => 'Információk az utazásról',
        'description' =>'<hr>'
      );
    }



    $tour_desc = (array)$this->term_data['tour']['descriptions'];
    $hotel_desc = (array)$this->term_data['tour']['hotel']['descriptions'];

    if ($html_format) {
      $sep2 = array();
      $sep2[] = array(
        'name' => '<div class="sep-title"><i class="fa fa-building-o"></i> Röviden a(z) '.$this->getHotelName().' szállodáról</div>',
        'description' =>'<hr>'
      );
    } else {
      $sep2 = array();
      $sep2[] = array(
        'name' => 'Röviden a(z) '.$this->getHotelName().' szállodáról',
        'description' =>'<hr>'
      );
    }


    $all = array_merge($all, $sep, $tour_desc, $sep2, $hotel_desc);

    return $all;
  }
  public function getBoardType()
  {
    return $this->term_data['board_name'];
  }
  public function getProfilImage()
  {
    return $this->term_data['tour']['hotel']['main_picture'];
  }
  public function getMoreImages()
  {
    $set = array();

    if($this->term_data['tour']['hotel']['pictures'])
    foreach ($this->term_data['tour']['hotel']['pictures'] as $key => $value) {
      if($key == 0) continue;
      $set[] = $value;
    }
    return $set;
  }

  public function getRAWValue($key)
  {
    return $this->term_data[$key];
  }

  private function load()
  {
    $this->term_data = $this->getTerm($this->term_id);
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

}
?>
