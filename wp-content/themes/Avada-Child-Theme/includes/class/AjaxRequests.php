<?php

class AjaxRequests
{
  public function __construct()
  {
    return $this;
  }

  public function get_term_offer()
  {
    add_action( 'wp_ajax_'.__FUNCTION__, array( $this, 'getTravelOfferContent'));
  }

  public function getTravelOfferContent()
  {
    extract($_POST);
    $offered_rooms  = array();
    $offer_data     = array();
    $rooms_price    = array();

    if(!$termid) return false;

    // Utazás betöltése
    $ajanlat = new ViasaleAjanlat($termid);

    // Összes elérhető szoba
    $rooms = $ajanlat->getRooms();

    foreach ($rooms as $room_id => $room )
    {
      $part = $room['adults'][$adults];

      if($part && ($children >= $part['min_children'] && $children <= $part['max_children'])) {
        $offered_rooms[$room_id] = $part['children'][$children];
      }
    }

    if($offered_rooms) {
      foreach ( $offered_rooms as $off_room_id => $off_room ) {
        $price = 0;

        if($off_room['config_count'] == 1) {
          if($off_room['configs']){
            foreach ($off_room['configs'] as $cfg_id => $cfg) {
              foreach ($cfg['buckets'] as $bucket) {
                $bucket_price = (float)$rooms[$off_room_id]['price_types'][$bucket['price_type_id']]['price'];
                $price += $bucket_price * $bucket['count'];
              }
            }
          }
        }else {
          $price = -1;
        }

        $rooms_price[$off_room_id] = $price;
      }
    }

    $offer_data['rooms'] = $offered_rooms;
    $offer_data['price_by_rooms'] = $rooms_price;

    //echo json_encode($offer_data);
    print_r($offer_data);

    die();
  }

}
