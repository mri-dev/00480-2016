<?php
class ViasaleAPIFactory
{
  const ZONES_TAG   = 'zones';
  public $api_uri   = 'http://viasale.net/api/v2/';

  // Settings
  protected $streamer_timeout = 30;

  // Store
  public $zones_max_level = 0;
  public $childed_zones_id = array();
  public $child_groups = array();

  public function __construct()
  {

  }

  /**
  * Nyers zónaadatok letöltése
  */
  protected function getZones()
  {
    $data = array();

    $uri = $this->api_uri . self::ZONES_TAG.'/';

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
}
?>
