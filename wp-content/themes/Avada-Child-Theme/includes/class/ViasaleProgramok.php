<?php
class ViasaleProgramok extends ViasaleAPIFactory
{
  public $arg = array();
  public $default_image_tag = 'viasale-travel-no-image-500.jpg';

  public function __construct( $arg = array() )
  {
    $this->arg = $arg;
  }

  public function getData()
  {
    $data = array();

    $param = array();
    if(isset($this->arg['limit']) && !empty($this->arg['limit'])){
      $param['limit'] = (int)$this->arg['limit'];
    }

    if(isset($this->arg['id']) && !empty($this->arg['id'])){
      $param['id'] = (int)$this->arg['id'];
    }

    $events = $this->getEvents( $param );

    if($events)
    {
      $i = 0;
      foreach ( $events as $event )
      {
        $i++;
        if(isset($param) && $param['limit'] < $i) break;
        //if(empty($event['pictures'][0]['url'])) continue;

        $image_obj = array(
          'w' => 500,
          'h' => 375,
          'o' => 'L'
        );
        $image = IFROOT.'/images/'.$this->default_image_tag;

        if(!empty($event['pictures'][0]['url'])){
          $image = $event['pictures'][0]['url'];
          $image_obj = array(
            'w' => $event['pictures'][0]['size_x'],
            'h' => $event['pictures'][0]['size_y'],
            'o' => $event['pictures'][0]['orientation']
          );
        }

        $desc   = $event['descriptions'];

        if(is_array($desc) && empty($desc)) {
          $desc = false;
        }

        $data[] = array(
          'link'  => get_option('siteurl').'/'.PROGRAM_SLUG.'/'.sanitize_title($event['name']).'/'.$event['id'],
          'title' => $event['name'],
          'desc'  => $desc,
          'price' => (float)$event['price_from'],
          'price_v' => '€',
          'image' => $image,
          'images' => $event['pictures'],
          'image_obj' => $image_obj
        );
      }
      unset($events);
    }

    return $data;
  }
}
?>
