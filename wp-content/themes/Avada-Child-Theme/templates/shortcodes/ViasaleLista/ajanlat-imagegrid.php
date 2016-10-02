<div class="item item-index-<?php echo $item_index; ?> <?php if($item_index%5 == 0) echo 'big'; ?> island-<?php echo str_replace(' ','',$island_text); ?>">
  <div class="item-wrapper" onclick="document.location.href='<?php echo $link; ?>';">
    <div class="overlay trans-on"></div>
    <div class="image orientation-<?=strtolower($image_obj['orientation'])?>">
      <img src="<?php echo $image; ?>" alt="<?php echo $title; ?> &mdash; <?php echo $island_text; ?>" class="trans-on" />
    </div>
    <div class="titles">
      <div class="island"><?php echo $island_text; ?></div>
      <div class="title"><?php echo $title; ?></div>
    </div>
    <div class="prices">
      <div class="star"><?php echo str_repeat('<i class="fa fa-star"></i>',$star); ?></div>
      <div class="price"><?php echo $price; ?><?php echo $price_v; ?></div>
      <div class="action">
        <a href="<?php echo $link; ?>" class="link-btn trans-on">Megnézem</a>
      </div>
    </div>
  </div>
</div>
