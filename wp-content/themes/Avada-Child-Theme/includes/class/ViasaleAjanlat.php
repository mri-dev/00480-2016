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
  public function getRoomsCount()
  {
    return count($this->term_data['rooms']);
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
  public function getMaxChildren()
  {
    $a = 1;

    return $a;
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
  public function getMoreTravel()
  {
    if(!$this->hotel_data['hotels']['more_terms']){ return false; }

    return $this->hotel_data['hotels']['more_terms'];
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
  public function getProfilImage()
  {
    return $this->term_data['hotel']['pictures'][0];
  }
  public function getMoreImages()
  {
    $set = array();

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
