<div class="item item-index-<?php echo $item_index; ?> island-<?php echo str_replace(' ','',$island_text); ?><?php if($discount): echo ' discounted'; endif; ?>">
  <?php if($discount): ?>
  <div class="discount-info"><span class="d">-<?php echo $discount; ?>%</span></div>
  <?php endif;?>
  <?php if(in_array($offer, array('lastminute', 'firstminute'))): ?>
    <div class="offer">
      <?php if ($offer == 'lastminute'): ?>
        <span class="lm" title="Lastminute">LM</span>
      <?php endif; ?>
      <?php if ($offer == 'firstminute'): ?>
        <span class="fm" title="Firstminute">FM</span>
      <?php endif; ?>
    </div>
  <?php endif; ?>
  <div class="item-wrapper trans-on" onclick="document.location.href='<?php echo $link; ?>';">
    <div class="item-contents trans-on">
      <div class="image orientation-<?=strtolower($image_obj['orientation'])?>">
        <img src="<?php echo $image; ?>" alt="<?php echo $title; ?> &mdash; <?php echo $island_text; ?>" class="trans-on" />
      </div>
      <div class="content-text">
        <div class="titles">
          <div class="title"><?php echo $title; ?><span class="star"><?php echo str_repeat('<i class="fa fa-star star-active"></i>',$star); ?></span></div>
        </div>
        <div class="place">
          <i class="fa fa-map-marker"></i> <span class="island"><?php echo $island_text; ?></span> / <span class="city"><?php echo $place; ?></span>
        </div>
      </div>
      <?php if($features && !empty($features)): ?>
      <div class="features feat-style-v2">
        <div class="feature-wrapper">
          <div class="feat-i-time" title="<?=$features['time']['text']?>">
            <i class="fa fa-clock-o"></i>
            <div class="v">
              <?=$features['time']['value']?>
            </div>
          </div>
          <div class="feat-i-days" title="<?=$features['days']['text']?>">
            <i class="fa fa-calendar"></i>
            <div class="v">
              <?=$features['days']['value']?> nap
            </div>
          </div>
          <div class="feat-i-supply" title="<?=$features['supply']['text']?>">
            <i class="fa fa-cutlery"></i>
            <div class="v">
              <?=$features['supply']['value']?>
            </div>
          </div>
        </div>
      </div>
      <?php endif; ?>
      <div class="actions">
        <div class="price-eur">
          <?php if($discount): ?>
          <div class="previous-price"><span><?php echo $price_origin; ?><?php echo $price_v; ?></span></div>
          <?php endif;?>
          <div class="current-price"><?php echo $price; ?><?php echo $price_v; ?></div>
        </div>
        <div class="price-huf"><?php echo number_format($price_huf, 0, ".", " "); ?> Ft</div>
        <div class="link-read">
          <a href="<?php echo $link; ?>" class="link-btn trans-on">Tovább</a>
        </div>
      </div>
    </div>
  </div>
</div>
