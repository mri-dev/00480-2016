<?php
/**
* Utazási ajánlat
**/
class ViasaleAjanlat extends ViasaleAPIFactory
{
  public $arg = array();
  public $term_id = false;
  public $term_data = null;

  public function __construct( $id = false, $arg = array() )
  {
    if( !$id || empty($id) ) return $this;
    
    $this->term_id = $id;
    $this->arg = $arg;

    $this->load();

    return $this;
  }

  private function load()
  {
    $this->term_data = $this->getTerm($this->term_id);
  }
}
?>
