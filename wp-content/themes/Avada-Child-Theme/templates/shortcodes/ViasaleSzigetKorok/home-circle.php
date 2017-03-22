<?php
  extract((array)$sziget);

  switch ($param['control']) {
    case 'transfer':
      $url = '/transzfer-kereso/?zona='.$zone_id;
    break;
    case 'program':
        $url = '/program-kereso/?zona='.$zone_id;
      break;
    default:
      $url = get_permalink($ID);
    break;
  }
?>
<div class="circle circle-<?php echo $post_name; ?> <?=($param['active'])?'selected':''?>">
  <div class="circle-inside">
    <? $image = get_the_post_thumbnail_url($ID, 'full'); if($image): ?>
    <div class="image">
      <a href="<?php echo $url;?>"><img src="<?php echo $image; ?> " alt="<?php echo $post_title; ?>" class="trans-on" /></a>
    </div>
    <? endif; ?>
    <div class="title trans-on"><?php echo $post_title; ?></div>
  </div>
</div>
