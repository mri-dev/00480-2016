<?php
class ViasaleTranszferek extends ViasaleAPIFactory
{
  public $arg = array();
  public $airports = array();
  public $airports_by_zone = array();
  public $zone_datas = array();
  public $zone_ids = array();
  public $transfers = array();
  public $collected_transfers_by_zone = array();

  public function __construct( $arg = array() )
  {
    $this->arg = $arg;
  }

  public function getData()
  {
    $data = array();
    $search = array();

    if(is_array($this->arg['zones']) && !empty($this->arg['zones'])) {
      $this->zone_ids = $this->arg['zones'];
    }

    $this->airports   = $this->getTransferAirports();
    // Zónák szerinti reptér csoporosítás
    $this->groupingAirportsByZone($this->airports);
    // Kiválasztott zónák adatainak letöltése
    $this->syncZoneSet();

    if(!empty($this->zone_ids))
    {
      $gettable_airport_ids = array();

      foreach ($this->zone_ids as $zid) {
        $pid  = $this->zone_datas[$zid]['parent_id'];
        $aids = $this->airports_by_zone[$pid];
        unset($pid);

        if($aids)
        foreach ($aids as $airport_row ) {
          if(!in_array($airport_row['id'], $gettable_airport_ids)){
            $gettable_airport_ids[] = $airport_row['id'];
          }
        }
        unset($aids);
      }

      foreach ($gettable_airport_ids as $aid) {
        $this->collected_transfers_by_zone[$aid] = $this->getTransfers($aid);
      }
      unset($gettable_airport_ids);


      foreach ($this->zone_ids as $zid) {
        $airports = array();
        $pid  = $this->zone_datas[$zid]['parent_id'];
        $aids = $this->airports_by_zone[$pid];
        unset($pid);

        $translist = array();

        if($aids)
        foreach ($aids as $airport_row ) {
          $airport = $this->collected_transfers_by_zone[$airport_row['id']];
          $translist = array_merge($translist, $airport['retail_transfers_to']);
          unset($airport['retail_transfers_to']);
          $airports[$airport_row['id']] = $airport;
        }
        unset($aids);

        $zone =$this->zone_datas[$zid];
        $this->transfers[$zid]                    = $zone;
        $this->transfers[$zid]['zone_parent']     = $this->getZone($zone['parent_id']);
        $this->transfers[$zid]['airports_count']  = count($airports);
        $this->transfers[$zid]['airports']        = $airports;
        $this->transfers[$zid]['transfers']       = $translist;

        unset($translist);
        unset($airports);
      }
    }

    $data = $this->transfers;

    return $data;
  }

  private function syncZoneSet()
  {
    if(empty($this->zone_ids)) return false;

    foreach ( $this->zone_ids as $zid ) {
      $this->zone_datas[$zid] = $this->getZone($zid);
    }
  }

  private function groupingAirportsByZone( $airport_api_array = array() )
  {
    if(!$airport_api_array) return false;

    foreach ( $airport_api_array as $airport ) {
      $this->airports_by_zone[$airport['parent_id']][] = $airport;
    }
  }
}
?>
