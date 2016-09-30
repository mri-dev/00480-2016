<div class="item item-index-<?php echo $item_index; ?> island-<?php echo str_replace(' ','',$island_text); ?><?php if($discount): echo ' discounted'; endif; ?>">
  <?php if($discount): ?>
  <div class="discount-info"><span class="d">-<?php echo $discount; ?>%</span></div>
  <?php endif;?>
  <div class="item-wrapper trans-on">
    <div class="item-contents trans-on">
      <div class="image orientation-<?=strtolower($image_obj['orientation'])?>">
        <img src="<?php echo $image; ?>" alt="<?php echo $title; ?> &mdash; <?php echo $island_text; ?>" class="trans-on" />
        <? if(false): ?>
        <div class="offer trans-on">
          <div class="count">
            <?php echo $total_travel_count; ?>
          </div>
          elérhető ajnálat
        </div>
      <? endif; ?>
      </div>
      <div class="titles">
        <div class="title"><?php echo $title; ?></div>
        <div class="star"><?php echo str_repeat('<i class="fa fa-star star-active"></i>',$star); ?><?php echo str_repeat('<i class="fa fa-star"></i>',5-$star); ?></div>
      </div>
      <div class="place">
        <i class="fa fa-map-marker"></i> <span class="island"><?php echo $island_text; ?></span> / <span class="city"><?php echo $place; ?></span>
      </div>
      <div class="actions">
        <div class="list-button">
          <a href="<?php echo $link; ?>" class="link-btn trans-on">Hotel adatlap, ajánlatok <i class="fa fa-arrow-circle-right"></i> </a>
        </div>
        <div class="near-offer">
          <div class="near-date">
            <div class="label">
              Következő út:
            </div>
            <?php echo $date_from; ?> &mdash; <?php echo $date_to; ?>
          </div>
          <div class="current-price"><?php echo $price; ?><?php echo $price_v; ?></div>
          <a href="<?php echo $offer_link; ?>" class="link-btn trans-on">Érdekel az ajánlat!</a>
        </div>
      </div>
    </div>
  </div>
</div>
