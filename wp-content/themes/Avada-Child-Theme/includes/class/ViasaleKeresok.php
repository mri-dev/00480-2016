<?php
class ViasaleKeresok extends ViasaleAPIFactory
{
  // A zóna mélységének kontrollálása
  public $min_zone_deep = 1;

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

    if(!$raw_result_array) return $zones;

    foreach ($raw_result_array as $zone)
    {
      if($zone['level'] != $this->min_zone_deep ) continue;

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
