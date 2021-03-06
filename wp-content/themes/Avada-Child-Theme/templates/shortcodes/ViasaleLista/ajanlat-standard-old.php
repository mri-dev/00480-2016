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
        <?php if($features && !empty($features)): ?>
        <div class="features trans-on">
          <div class="feature-wrapper">
          <?php foreach ($features as $feat_key => $feature): ?>
            <div class="feat-title"><?php echo $feature['text']?></div>
            <div class="feat-value"><?php echo $feature['value']?></div>
            <div class="feat-divider"></div>
          <?php endforeach; ?>
          </div>
        </div>
        <?php endif; ?>
      </div>
      <div class="content-text">
        <div class="titles">
          <div class="title"><?php echo $title; ?><span class="star"><?php echo str_repeat('<i class="fa fa-star star-active"></i>',$star); ?></span></div>
        </div>
        <div class="place">
          <i class="fa fa-map-marker"></i> <span class="island"><?php echo $island_text; ?></span> / <span class="city"><?php echo $place; ?></span>
        </div>
      </div>
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
