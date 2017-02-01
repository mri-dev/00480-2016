<?php
class ViasaleProgramok extends ViasaleAPIFactory
{
  public $arg = array();
  public $default_image_tag = 'viasale-travel-no-image-500.jpg';
  public $total_page = 0;
  public $current_page = 1;
  public $total = 0;

  public function __construct( $arg = array() )
  {
    $this->setApiVersion('v3');
    parent::__construct();
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


    if(isset($this->arg['sort']) && !empty($this->arg['sort'])){
      $param['order'] = $this->arg['sort'];
    }

    if(isset($this->arg['zones']) && !empty($this->arg['zones'])) {
      foreach ( $this->arg['zones'] as $zid ) {
        if($zid == '') continue;
        $param['zones'][] = $zid;
      }
    }

    if(get_query_var('page')) {
      $param['page'] = (int)get_query_var('page');
      $this->current_page = $param['page'];
    }

    $events = $this->getEvents( $param );

    //print_r($events);
    $this->total_page = (int)$events['last_page'];
    $this->total = (int)$events['total'];

    if($events['data'])
    {
      $i = 0;
      foreach ( $events['data'] as $event )
      {
        $i++;
        //if(isset($param) && !empty($param['limit']) && (int)$param['limit'] < $i) break;
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

  public function pagination( $base = '' )
  {
    return paginate_links( array(
    	'base'   => $base.'%_%',
    	'format'  => '/%#%/',
    	'current' => max( 1, get_query_var('page') ),
    	'total'   => $this->total_page
    ) );
  }

}
?>
