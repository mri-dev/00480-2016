<?php
class ViasaleAPIFactory
{
  const ZONES_TAG       = 'zones';
  const TERMS_TAG       = 'terms';
  const HOTELS_TAG      = 'hotels';
  const TRANSFER_TAG    = 'transfers';
  const EVENTS_TAG      = 'events';
  const HOTELTERMS_TAG  = 'hotels/%d/terms';
  public $api_base      = 'http://viasale.net/api/';
  public $api_version   = 'v2';
  public $api_uri       = '';
  public $date_format   = 'Y / m / d';

  // Settings
  protected $streamer_timeout = 30;
  public $term_durration_groups = array(
    0 => array(
      'name' => '1 hétnél rövidebb',
      'min' => 1,
      'max' => 5
    ),
    1 => array(
      'name' => '1 hetes',
      'min' => 6,
      'max' => 8
    ),
    2 => array(
      'name' => '1 hétnél hosszabb',
      'min' => 9,
      'max' => 12
    ),
    3 => array(
      'name' => '2 hét vagy több',
      'min' => 13,
      'max' => 15
    )
  );

  // Store
  public $zones_max_level   = 0;
  public $childed_zones_id  = array();
  public $child_groups      = array();

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

  public function __construct( )
  {
    $this->build_api_uri();
    return $this;
  }

  public function setApiVersion($version = false)
  {
    $this->api_version = $version;
    return $this;
  }

  private function build_api_uri()
  {
    $this->api_uri = $this->api_base . $this->api_version.'/';

    return $this;
  }

  /**
  * Visszaadja egy sziget zóna ID sziget slug-ját.
  **/
  public function thisZoneIDSzigetSlug($id = 0)
  {
    if($this->sziget_ids)
    foreach ($this->sziget_ids as $sziget => $sz ) {
      if($id == $sz['id']) return $sziget;
    }

    return false;
  }

  /**
  * Hotel adatok
  **/
  public function getHotel($id)
  {
    $uri = $this->api_uri . self::HOTELS_TAG.'/'.$id;

    //echo $uri;

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

    //echo $uri;

    $result = json_decode($this->load_api_content($uri), JSON_UNESCAPE_UNICODE);

    if(!$result || empty($result)) return false;

    return $result;
  }

  /**
  * Transzfer repterek
  **/
  public function getTransferAirports( $params = array() )
  {
    // Get search params
    $data   = array();
    $query  = array();


    $query = $this->build_search($query);
    $uri = $this->api_uri . self::TRANSFER_TAG.'/'.$query;
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

  /**
  * Transzferek repterektől
  **/
  public function getTransfers( $airport_zone_id = false, $params = array() )
  {
    // Get search params
    $data   = array();
    $query  = array();

    if(!$airport_zone_id) return $data;

    $query['pickup_zone'] = $airport_zone_id;

    $query = $this->build_search($query);
    $uri = $this->api_uri . self::TRANSFER_TAG.'/'.$query;
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

  /**
  * Utazási ajánlatok
  **/
  public function getTerms( $params = array() )
  {
    $data = array();

    // Get search params
    $query = array();

    $query = array_replace($query, $params);

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

  /**
  * Hotel Utazási ajánlatok
  **/
  public function getHotelTerms( $hotelid = 0, $params = array() )
  {
    $data = array();

    // Get search params
    $query = array();

    $query = array_replace($query, $params);

    $query = $this->build_search($query);

    $uri = $this->api_uri . sprintf(self::HOTELTERMS_TAG, $hotelid).'/'.$query;

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

  public function getHotels( $param = array() )
  {
    $data = array();

    // Get search params
    $query = array(
      'page' => 1,
      'per_page' => 30
    );

    $query = array_replace($query, $param);

    //print_r($query);

    $query = $this->build_search($query);

    $uri = $this->api_uri . self::HOTELS_TAG.'/'.$query;

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

  public function getZone( $zone_id, $params = array() )
  {
    // Get search params
    $query = array();
    $query = $this->build_search($query);

    if (isset($params['api_version'])) {
      $this->api_version = 'v2';
      $this->build_api_uri();
    }

    $uri = $this->api_uri . self::ZONES_TAG.'/'.$zone_id;

    $result = json_decode($this->load_api_content($uri), JSON_UNESCAPE_UNICODE);

    if(!$result || empty($result)) return false;

    return $result;
  }

  /**
  * Nyers zónaadatok letöltése
  */
  protected function getZones()
  {
    $data = array();
    $this->child_groups = array();
    $this->childed_zones_id = array();

    // Get search params
    $query = array();
    $query = $this->build_search($query);

    $uri = $this->api_uri . self::ZONES_TAG.'/'.$query;

    //echo $uri . '<br>';

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

  /**
  * Programok listája
  **/
  public function getEvents( $params = array() )
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
      $query['per_page'] = $params['limit'];
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
    if(isset($params['page']) && !empty($params['page'])) {
      $query['page'] = $params['page'];
    }

    $query = $this->build_search($query);

    $uri = $this->api_uri . self::EVENTS_TAG.'/'.$query;

    //echo $uri . '<br>';

    $result = json_decode($this->load_api_content($uri), JSON_UNESCAPE_UNICODE);

    if(!$result || empty($result)) return false;


    $rdata = $result['data'];
    unset($result['data']);
    $data = $result;

    if($rdata)
    foreach ($rdata as $k => $r )
    {
      if(isset($params['id']) && $params['id'] != $r['id']) continue;
      $data['data'][] = $r;
    }
    unset($result);

    return $data;
  }

  public function load_term_durration_counts()
  {
    $groups = array();

    $date_from = date('Y-m-d', strtotime('+1 days'));
    $date_to = date('Y-m-d', strtotime('+60 days'));

    if( isset($_GET['tf']) && !empty($_GET['tf']) ){
      $date_from =  str_replace(' / ', '-', $_GET['tf']);
    }

    if( isset($_GET['tt']) && !empty($_GET['tt']) ){
      $date_to = str_replace(' / ', '-', $_GET['tt']);
    }

    $date_src = '&date_from='.$date_from.'&date_to='.$date_to;

    $list = $this->term_durration_groups;

    $this->setApiVersion('v3');
    $this->build_api_uri();

    $url = $this->api_uri . self::TERMS_TAG . '?per_page=1'.$date_src;

    foreach ((array)$list as $key => $group)
    {
      $url .= '&min_duration='.$group['min'].'&max_duration='.$group['max'];
      $result = json_decode($this->load_api_content( $url ), JSON_UNESCAPE_UNICODE);
      $group['term_result_num'] = (int)$result['total'];
      $groups[$key] = $group;
    }

    return $groups;
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

  public function getZonesToIDSet($zone_list_arr = array())
  {
    $set = array();
    if(empty($zone_list_arr)) return array();

    foreach ($zone_list_arr as $z) {
      $set[] = $z['id'];
    }

    return $set;
  }

  public function format_date($date)
  {
    return date($this->date_format, strtotime($date));
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

  public function calc_discount_percent( $origin = 0, $new = 0 )
  {
    $d = false;

    if($origin == 0 || $new == 0) return $d;

    $d = 100 - ($new / ($origin / 100));

    $d = floor($d);

    return $d;
  }
}
?>
