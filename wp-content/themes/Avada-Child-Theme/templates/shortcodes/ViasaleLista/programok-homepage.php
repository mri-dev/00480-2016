<div class="item">
  <div class="item-holder">
    <div class="item-wrapper">
      <div class="line-left">
        <div class="image orientation-<?=strtolower($image_obj['o'])?>"><img src="<?php echo $image; ?>" alt="<?php echo $title; ?>"></div>
      </div>
      <div class="line-right">
        <div class="title"><h3><a href="<?php echo $link; ?>"><?php echo $title; ?></a></h3></div>
        <div class="desc"><?php echo $desc; ?></div>
        <div class="action">
          <div class="price"><?php if($price): ?><?php echo $price; ?><?php echo $price_v; ?><?php endif; ?></div>
          <div class="button-link"><a class="trans-on" href="<?php echo $link; ?>">Tovább</a></div>
        </div>
      </div>
    </div>
  </div>
</div>
