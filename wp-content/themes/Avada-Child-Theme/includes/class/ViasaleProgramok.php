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

    if(isset($this->arg['hotels']) && !empty($this->arg['hotels'])){
      $param['hotels'] = array($this->arg['hotels']);
    }

    if(isset($this->arg['zones']) && !empty($this->arg['zones'])) {
      foreach ( $this->arg['zones'] as $zid ) {
        if($zid == '') continue;
        $param['zones'][] = $zid;
      }
    }

    $events = $this->getEvents( $param );

    if($events)
    {
      $i = 0;
      foreach ( $events as $event )
      {
        $i++;
        if(isset($param) && !empty($param['limit']) && (int)$param['limit'] < $i) break;
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
        } else {
          if(is_array($desc) && !empty($desc))
          foreach ($desc as $dkey => $dvalue) {
            if ($dvalue['name'] == 'Leírás') {
              $desc = $dvalue['description'];
              break;
            }
          }
        }

        if (is_array($desc) || $desc === false) {
          if ($desc[0]['description'] != '') {
            $desc = $desc[0]['description'];
          }
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
