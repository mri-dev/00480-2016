<?php
class ViasaleKeresok extends ViasaleAPIFactory
{
  // A zóna mélységének kontrollálása
  protected $min_zone_deep = 2;

  public function __construct( $arg = array() )
  {

  }

  /**
  * Zóna konvertálása fa strukturába
  */
  public function getZonesTree()
  {
    $zones = array();
    $temp_zones = array();

    $raw_result_array   = $this->getZones();
    $zones_max_level    = $this->zones_max_level;
    $childed_zones_id   = $this->childed_zones_id;

    if(!$raw_result_array) return $zones;

    foreach ($raw_result_array as $zone)
    {
      if($zone['parent_id']) continue;

      $children = $this->getZoneChild($raw_result_array, $zone['id']);
      $zone['child_count'] = count($children);
      $zone['children'] = ($children) ? $children : false;
      unset($children);

      $zones[] = $zone;
    }


    return $zones;
  }

}
?>
