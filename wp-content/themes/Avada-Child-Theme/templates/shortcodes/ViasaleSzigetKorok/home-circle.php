
<div class="circle circle-<?php echo $post_name; ?>">
  <div class="circle-inside">
    <? $url   = get_permalink($ID); ?>
    <? $image = get_the_post_thumbnail_url($ID, 'full'); if($image): ?>
    <div class="image">
      <a href="<?php echo $url;?>"><img src="<?php echo $image; ?> " alt="<?php echo $post_title; ?>" class="trans-on" /></a>
    </div>
    <? endif; ?>
    <div class="title trans-on"><?php echo $post_title; ?></div>
  </div>
</div>
