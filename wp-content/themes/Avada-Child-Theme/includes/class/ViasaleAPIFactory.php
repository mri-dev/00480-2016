<?php
class ViasaleAPIFactory
{
  const ZONES_TAG   = 'zones';
  const TERMS_TAG   = 'terms';
  const HOTELS_TAG   = 'hotels';
  public $api_uri   = 'http://viasale.net/api/v2/';
  public $date_format = 'Y / m / d';

  // Settings
  protected $streamer_timeout = 30;

  // Store
  public $zones_max_level = 0;
  public $childed_zones_id = array();
  public $child_groups = array();

  public $boardTypeMap = [
     '1' => [ 'shortName' => "SC", 'fullName' => 'Ellátás nélkül' ],
     '2' => [ 'shortName' => "BB", 'fullName' => 'Reggeli' ],
     '3' => [ 'shortName' => "HB", 'fullName' => 'Félpanzió' ],
     '4' => [ 'shortName' => "FB", 'fullName' => 'Teljes ellátás' ],
     '5' => [ 'shortName' => "AI", 'fullName' => 'All inclusive' ],
     '6' => [ 'shortName' => "AI+", 'fullName' => 'All inclusive plus' ]
  ];
  public $sziget_ids = array(
    'tenerife' =>       array( 'id' => 3, 'name' => 'Tenerife'),
    'gran-canaria' =>   array( 'id' => 6, 'name' => 'Gran Canari'),
    'fuerteventura' =>  array( 'id' => 10, 'name' => 'Fuerteventura'),
    'lanzarote' =>      array( 'id' => 8, 'name' => 'Lanzarote'),
  );

  public $hotel_stars = [2, 3, 4, 5];

  public function __construct()
  {

  }

  /**
  * Hotel adatok
  **/
  public function getHotel($id)
  {
    $uri = $this->api_uri . self::HOTELS_TAG.'/'.$id;

    $result = json_decode($this->load_api_content($uri), JSON_UNESCAPE_UNICODE);

    if(!$result || empty($result)) return false;

    return $result;
  }

  /**
  * Utazási ajánlat adatok
  **/
  public function getTerm($id)
  {
    $uri = $this->api_uri . self::TERMS_TAG.'/'.$id;

    $result = json_decode($this->load_api_content($uri), JSON_UNESCAPE_UNICODE);

    if(!$result || empty($result)) return false;

    return $result;
  }

  /**
  * Utazási ajánlatok
  **/
  public function getTerms( $params = array() )
  {
    $data = array();

    // Get search params
    $query = array();

    if(isset($params['zones']) && !empty($params['zones'])){
      $query['zones'] = implode(",", $params['zones']);
    }
    if(isset($params['hotels']) && !empty($params['hotels'])){
      $query['hotels'] = implode(",", $params['hotels']);
    }
    if(isset($params['limit'])) {
      $query['limit'] = $params['limit'];
    }
    if(isset($params['offers'])) {
      $query['offers'] = $params['offers'];
    }
    if(isset($params['max_hotels'])) {
      $query['max_hotels'] = $params['max_hotels'];
    }
    if(isset($params['order'])) {
      $query['sort_by'] = $params['order'];
    }
    if(isset($params['date_from']) && !empty($params['date_from'])) {
      $query['date_from'] = $params['date_from'];
    }
    if(isset($params['date_to']) && !empty($params['date_to'])) {
      $query['date_to'] = $params['date_to'];
    }
    if(isset($params['board_type']) && !empty($params['board_type'])) {
      $query['min_board'] = $params['board_type'];
    }
    if(isset($params['min_star']) && !empty($params['min_star'])) {
      $query['min_category'] = $params['min_star'];
    }

    $query = $this->build_search($query);

    $uri = $this->api_uri . self::TERMS_TAG.'/'.$query;

    //echo $uri . '<br>';

    $result = json_decode($this->load_api_content($uri), JSON_UNESCAPE_UNICODE);

    if(!$result || empty($result)) return false;

    foreach ($result as $k => $r )
    {
      $data[$k] = $r;
    }
    unset($result);

    return $data;
  }

  public function format_date($date)
  {
    return date($this->date_format, strtotime($date));
  }

  /**
  * Nyers zónaadatok letöltése
  */
  protected function getZones()
  {
    $data = array();

    // Get search params
    $query = array();
    $query = $this->build_search($query);

    $uri = $this->api_uri . self::ZONES_TAG.'/'.$query;

    $result = json_decode($this->load_api_content($uri), JSON_UNESCAPE_UNICODE);

    if(!$result || empty($result)) return false;

    foreach ($result as $r )
    {
      if($r['level'] > $this->zones_max_level) $this->zones_max_level = (int)$r['level'];
      if($r['parent_id']) {
        $this->childed_zones_id[$r['parent_id']]++;
        $this->child_groups[$r['parent_id']][] = $r['id'];
      }
      $data[$r['id']] = $r;
    }

    return $data;
  }

  protected function getZoneChild( $raw, $parent_id = 0 )
  {
    $child = array();

    if($raw == '' or !is_array($raw)) return $child;

    $group = $this->child_groups[$parent_id];

    if($group)
    foreach ($raw as $id => $r) {
      if(in_array($id, $group))
      {
        $children = $this->getZoneChild($raw, $r['id']);
        $r['child_count'] = ($children) ? count($children) : 0;
        $r['children'] = ($children) ? $children : false;
        unset($children);
        $child[] = $r;
      }
    }

    return $child;
  }

  public function getZonesDeeps()
  {
    return $this->zones_max_level;
  }

  private function load_api_content( $exc_uri = false )
  {
    if (!$exc_uri) {
      return false;
    }

    $ch = curl_init();
  	curl_setopt($ch, CURLOPT_URL, $exc_uri);
  	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
  	curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $this->streamer_timeout);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
    curl_setopt( $ch, CURLOPT_ENCODING, "UTF-8" );

    if($errno = curl_errno($ch)) {
        $error_message = curl_strerror($errno);
        error_log("cURL error ({$errno}):\n {$error_message}");
        curl_close($ch);
      	return false;
    } else {
      	$data = curl_exec($ch);
        curl_close($ch);
        return $data;
    }
  }

  public function getBoardTypes()
  {
    return $this->boardTypeMap;
  }

  public function getHotelStars()
  {
    return $this->hotel_stars;
  }

  private function build_search( $array = array() )
  {
    if(empty($array)) return '';

    return '?'.http_build_query($array);
  }
}
?>
