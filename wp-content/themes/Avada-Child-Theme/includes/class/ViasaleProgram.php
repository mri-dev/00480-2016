<?php
/**
* Program adatok
**/
class ViasaleProgram extends ViasaleAPIFactory
{
  public $arg = array();
  public $program_id = false;
  public $program_data = null;
  public $default_image_tags = array(
    'mid' => 'viasale-travel-no-image-500.jpg',
    'hd' => 'viasale-travel-no-image-1024.jpg'
  );

  public function __construct( $id = false, $arg = array() )
  {
    if( !$id || empty($id) ) return $this;
    $this->setApiVersion('v3');
    parent::__construct();

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
  public function getPrice()
  {
    $price = (int)$this->program_data['price_from'];

    if($price == 0 || !$price || empty($price)) return false;

    return $price;
  }
  public function getDescriptions()
  {
    $infos = false;

    if($this->program_data['descriptions']){
      if(is_array($this->program_data['descriptions'])){
        foreach ($this->program_data['descriptions'] as $key => $value) {
          if($value['name'] == 'Leírás') continue;
          $infos[] = $value;
        }
      } else {
        $infos[] = $this->program_data['descriptions'];
      }
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
    $image = $this->program_data['pictures'][0];

    if(empty($image))
    {
      $image = array(
          'url' => IFROOT.'/images/'.$this->default_image_tags['hd'],
          "size_x" => 1024,
          "size_y" => 768,
          "orientation" => "L"
      );
    }
    return $image;
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

    $this->program_data = $programok['data'][0];
  }
}
?>
