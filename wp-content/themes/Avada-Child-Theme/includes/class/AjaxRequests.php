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
    $room_info      = array();

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


        $room_info[$room_id] = array(
          'name' => $rooms[$room_id]['name'],
          'min_adults' => $rooms[$room_id]['min_adults'],
          'max_adults' => $rooms[$room_id]['max_adults'],
          'prices' => $rooms[$room_id]['price_types'],
          'config' => $buckets
        );
      }

    }

    if($offered_rooms) {
      foreach ( $offered_rooms as $off_room_id => $off_room ) {
        $price = 0;
        $buckets = array();

        if($off_room['config_count'] == 1) {
          if($off_room['configs']){
            foreach ($off_room['configs'] as $cfg_id => $cfg) {
              foreach ($cfg['buckets'] as $bucket) {
                $bucket_price = (float)$rooms[$off_room_id]['price_types'][$bucket['price_type_id']]['price'];
                $price += $bucket_price * $bucket['count'];

                $buckets[] = array(
                  'name'  => $rooms[$off_room_id]['price_types'][$bucket['price_type_id']]['name'],
                  'count' => $bucket['count'],
                  'price' => $rooms[$off_room_id]['price_types'][$bucket['price_type_id']]['price']
                );

              }
            }
          }

          $room_info[$off_room_id]['configs'] = $buckets;
          
        }else {
          $price = -1;
        }

        $rooms_price[$off_room_id] = $price;
      }
    }

    $offer_data['rooms'] = $offered_rooms;
    $offer_data['room_info'] = $room_info;
    $offer_data['price_by_rooms'] = $rooms_price;

    echo json_encode($offer_data);
    //print_r($offer_data);

    die();
  }

}
