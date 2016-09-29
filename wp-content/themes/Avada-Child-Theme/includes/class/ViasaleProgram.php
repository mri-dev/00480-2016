<?php
/**
* Program adatok
**/
class ViasaleProgram extends ViasaleAPIFactory
{
  public $arg = array();
  public $program_id = false;
  public $program_data = null;

  public function __construct( $id = false, $arg = array() )
  {
    if( !$id || empty($id) ) return $this;

    $this->program_id = $id;
    $this->arg = $arg;

    $this->load();

    return $this;
  }
  public function getProgramID()
  {
    return $this->program_data['id'];
  }
  public function getProgramName()
  {
    return $this->program_data['name'];
  }
  public function getProgramZones()
  {
    if(!$this->program_data['zones']){ return false; }

    $zones = array();

    foreach ($this->program_data['zones'] as $i => $z) {
      if ($i > 0) {
        $zones[$z['id']] = $z['name'];
      }
    }
    return $zones;
  }
  public function getDescriptions()
  {
    $infos = false;

    if($this->program_data['descriptions'])
    foreach ($this->program_data['descriptions'] as $key => $value) {
      if($value['name'] == 'Leírás') continue;
      $infos[] = $value;
    }

    return $infos;
  }
  public function getInfo()
  {
    $info = false;

    if($this->program_data['descriptions'])
    foreach ($this->program_data['descriptions'] as $key => $value) {
      if($value['name'] != 'Leírás') continue;
      $info = $value['description'];
    }

    return $info;
  }
  public function getProfilImage()
  {
    return $this->program_data['pictures'][0];
  }
  public function getMoreImages()
  {
    $set = array();

    if($this->program_data['pictures'])
    foreach ($this->program_data['pictures'] as $key => $value) {
      if($key == 0) continue;
      $set[] = $value;
    }
    return $set;
  }

  private function load()
  {
    $programok = $this->getEvents(array( 'id' => $this->program_id, 'limit' => 999));

    $this->program_data = $programok[0];
  }
}
?>
