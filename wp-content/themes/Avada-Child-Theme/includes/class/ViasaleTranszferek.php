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
    parent::__construct();
    $this->arg = $arg;
  }

  public function getData()
  {
    $is_sziget_city_list = false;
    $data = array();
    $search = array();

    if(is_array($this->arg['zones']) && !empty($this->arg['zones'])) {
      $this->zone_ids = $this->arg['zones'];
    }

    if($sziget_zone_id = $this->thisZoneIDSzigetSlug($this->zone_ids[0])) {
        $is_sziget_zone_id = $this->zone_ids[0];
    }

    if(isset($this->arg['sziget']) && !empty($this->arg['sziget'])) {
      $is_sziget_city_list = true;
    }

    if($is_sziget_zone_id) {
      $is_sziget_city_list = true;
    }

    if($is_sziget_city_list) {
      $sziget_id = false;

      if($this->sziget_ids[$this->arg['sziget']]['id']) {
        $sziget_id = $this->sziget_ids[$this->arg['sziget']]['id'];
      }
      if($is_sziget_zone_id) {
        $sziget_id = $is_sziget_zone_id;
      }

      if ($sziget_id) {
        $zona_lista = $this->getZones();
        $sziget_varosok = $this->getZoneChild($zona_lista, $sziget_id);
        $sziget_varosok_ids = $this->getZonesToIDSet($sziget_varosok);

        unset($sziget_varosok);
        unset($zona_lista);

        $this->zone_ids = $sziget_varosok_ids;
      }
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
          $airport  = $this->collected_transfers_by_zone[$airport_row['id']];
          $trans    = array();

          if($airport['retail_transfers_to'])
          foreach ($airport['retail_transfers_to'] as $tr ) {
            if($tr['dropoff_zone_id'] == $zid) $trans[] = $tr;
          }

          unset($airport['retail_transfers_to']);
          $translist = array_merge($translist, $trans);

          $airports[$airport_row['id']] = $airport;
          $airports[$airport_row['id']]['count'] += count($trans);
        }
        unset($trans);
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
