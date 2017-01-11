<?php get_header(); ?>
<?
	$hotel_id  = $wp_query->query_vars['hotel_id'];
	$hotel 	   = new ViasaleHotel($hotel_id);
  $ajanlatok = $hotel->getTravels();
?>
<div id="content" class="full-width travel-content" stlye="max-width:100%;">
	<div class="hotel-row" stlye="max-width:100%;">
		<div class="hotel-column hotel-column-left">
			<div class="image-set-galery">
				<div class="profil">
					<?php $profil = $hotel->getProfilImage(); ?>
					<a href="<?php echo $profil['url']; ?>" class="fusion-lightbox" data-title="<?=$hotel->getHotelName()?>" data-rel="iLightbox[g<?=$hotel->getHotelID()?>]"><img class="img-responsive" src="<?php echo $profil['url']; ?>" alt="<?=$hotel->getHotelName()?>"></a>
				</div>
				<div class="image-set">
					<?php $images = $hotel->getMoreImages(); ?>
					<?php if($images) foreach ($images as $iid => $img): ?>
						<div class="image-set-holder">
						<a href="<?php echo $img['url']; ?>" class="fusion-lightbox" data-title="<?=$hotel->getHotelName()?>" data-rel="iLightbox[g<?=$hotel->getHotelID()?>]"><img class="img-responsive" src="<?php echo $img['url']; ?>" alt="<?=$hotel->getHotelName()?>"></a>
						</div>
					<?php endforeach; ?>
				</div>
			</div>
		</div>
		<div class="hotel-column hotel-column-right">
			<div class="hotel-column-wrapper">
			<div class="hotel-data">
				<h1 class="title"><?=$hotel->getHotelName()?></h1>
				<?php $zones = $hotel->getHotelZones(); if($zones): ?>
				<div class="zone-nav">
					<?php foreach ($zones as $zid => $zona) { ?>
						<span><a href="<?php echo get_option('siteurl', '/').'/'.KERESO_SLUG.'?zona='.$zid; ?>"><?php echo $zona; ?></a></span> <span class="sep">/</span>
					<?php	} ?>
				</div>
				<?php endif; ?>
				<div class="star"><?php echo str_repeat('<i class="fa fa-star star-active"></i>',$hotel->getStar()); ?><?php echo str_repeat('<i class="fa fa-star"></i>',5-$hotel->getStar()); ?></div>
        <div class="main-description">
          <?php echo nl2br($hotel->getInfo()); ?>
        </div>
        <div class="info-links">
          <a href="#travels"><div class="n"><?=$ajanlatok['total_terms_count']?></div> Utazási ajánlat <i class="fa fa-angle-right"></i></a>
        </div>
        <div class="fusion-clearfix"></div>
			</div>
		</div>
		</div>
	</div>

	<div class="travel-content-box">
		<div class="hotel-row">
			<div class="hotel-column hotel-column-full">
				<div class="info-box-container">
          <?php $desc = $hotel->getDescriptions(); ?>
					<div class="fusion-tabs fusion-tabs-1 classic nav-not-justified horizontal-tabs">
						<div class="nav">
							<ul class="nav-tabs">
                <?php
                  if($desc) {
                    $dn = 0;
                    foreach ($desc as $did => $de):
                      $dn++;
                ?>
                <li class="<?=($dn==1)?'active':''?>"><a class="tab-link" data-toggle="tab" href="#<?=sanitize_title($de['name'])?>"><h4 class="fusion-tab-heading"><?=$de['name']?></h4></a></li>
                <?php
                    endforeach;
                  }
                ?>
								<li style="display: none;"><a class="tab-link" data-toggle="tab" href="#map"><h4 class="fusion-tab-heading">Térkép</h4></a></li>
							</ul>
						</div>
						<div class="tab-content">
              <?php
                if($desc) {
                  $dn = 0;
                  foreach ($desc as $did => $de):
                    $dn++;
              ?>
							<div class="tab-pane fade <?=($dn==1)?'active in':''?>" id="<?=sanitize_title($de['name'])?>">
							 <?php echo nl2br($de['description']); ?>
							</div>
              <?php
                  endforeach;
                }
              ?>
							<div class="tab-pane fade" id="map">

							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>

  <a name="travels"></a>
  <div class="utazas-ajanlat-container">
    <div class="hotel-row">
      <div class="hotel-column hotel-column-full">
        <div class="boxed-title title-up title-color-orange">
          <h2 class="box-title">Utazási ajánlatok</h2>
        </div>
        <div class="ajanlat-lista">
          <?php if($ajanlatok['terms'] && $ajanlatok['total_terms_count'] != 0): ?>
          <div class="travels more-travel-list">
            <div class="more-travel-row more-travel-header">
              <div class="more-travel-header-date">
                Időtartam
              </div>
              <div class="more-travel-header-board">
                Ellátás
              </div>
              <div class="more-travel-header-duration">
                Időtartam
              </div>
              <div class="more-travel-header-priceplan">
                Alapár
              </div>
              <div class="more-travel-header-redirect">&nbsp;</div>
            </div>
          <?php
	           $date_group = false;
          ?>
          <?php foreach ($ajanlatok['terms'] as $term): ?>
            <?
                if($term['date_from'] != $date_group ) {
                  $date_group = $term['date_from'];
                  ?>
                  <div class="more-travel-row group-header">
                    <?php echo $date_group; ?>
                  </div>
                  <?
                }
            ?>
            <div class="travel more-travel-row">
                <div class="more-travel-date">
                  <?php echo $term['date_from']; ?> &mdash; <?php echo $term['date_to']; ?>
                  <? if( in_array($term['offer'], array('lastminute', 'firstminute')) ): ?>
                    <?
                      switch ($term['offer']) {
                        case 'lastminute':
                          echo '<span class="label-offer offer-'.$term['offer'].'" title="Lastminte">LM</span>';
                        break;
                        case 'firstminute':
                          echo '<span class="label-offer offer-'.$term['offer'].'" title="Firstminte">FM</span>';
                        break;
                      }
                    ?>
                  <? endif; ?>
                </div>
                <div class="more-travel-board">
                  <?php echo $term['board_type']; ?>
                </div>
                <div class="more-travel-duration">
                  <?php echo $term['term_duration']+1; ?> nap
                </div>
                <div class="more-travel-priceplan">
                  <span class="price-eur"><?php echo number_format($term['price_from'], 0, ".", " "); ?>€</span>
                  <span class="price-huf">(<?php echo number_format($term['price_from_huf'], 0, ".", " "); ?> Ft)</span>
                </div>
                <div class="more-travel-redirect">
                  <a href="<?php echo $term['link']; ?>" class="trans-on">Tovább <i class="fa fa-arrow-circle-right"></i></a>
                </div>
            </div>
          <?php endforeach; ?>
          </div>
          <?php else: ?>
          <div class="no-travel">
            <h3>Jelenleg nincs aktív utazási ajánlat</h3>
            Kérjük, hogy nézzen vissza később.
          </div>
          <?php endif; ?>
        </div>
      </div>
    </div>
  </div>

</div>
<?php get_footer();
