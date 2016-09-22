<?php get_header(); ?>
<?
	$hotel_id = $wp_query->query_vars['hotel_id'];
	$ajanlat 	= new ViasaleAjanlat($hotel_id);
?>
<div id="content" class="full-width travel-content" stlye="max-width:100%;">
	<div class="fusion-row" stlye="max-width:100%;">
		<div class="fusion-two-third fusion-layout-column fusion-spacing-no">
			<div class="image-set-galery">
				<div class="profil">
					<?php $profil = $ajanlat->getProfilImage(); ?>
					<a href="<?php echo $profil['url']; ?>" class="fusion-lightbox" data-title="<?=$ajanlat->getHotelName()?>" data-rel="iLightbox[g<?=$ajanlat->getTravelID()?>]"><img class="img-responsive" src="<?php echo $profil['url']; ?>" alt="<?=$ajanlat->getHotelName()?>"></a>
				</div>
				<div class="image-set">
					<?php $images = $ajanlat->getMoreImages(); ?>
					<?php foreach ($images as $iid => $img): ?>
						<div class="image-set-holder">
						<a href="<?php echo $img['url']; ?>" class="fusion-lightbox" data-title="<?=$ajanlat->getHotelName()?>" data-rel="iLightbox[g<?=$ajanlat->getTravelID()?>]"><img class="img-responsive" src="<?php echo $img['url']; ?>" alt="<?=$ajanlat->getHotelName()?>"></a>
						</div>
					<?php endforeach; ?>
				</div>
			</div>
		</div>
		<div class="fusion-one-third fusion-layout-column fusion-spacing-no fusion-column-last">
			<div class="hotel-data">
				<h1 class="title"><?=$ajanlat->getHotelName()?></h1>
				<?php $zones = $ajanlat->getHotelZones(); if($zones): ?>
				<div class="zone-nav">
					<?php foreach ($zones as $zid => $zona) { ?>
						<span><a href="<?php echo get_option('siteurl', '/').'/'.KERESO_SLUG.'?zona='.$zid; ?>"><?php echo $zona; ?></a></span> <span class="sep">/</span>
					<?php	} ?>
				</div>
				<?php endif; ?>
				<div class="star"><?php echo str_repeat('<i class="fa fa-star star-active"></i>',$ajanlat->getStar()); ?><?php echo str_repeat('<i class="fa fa-star"></i>',5-$ajanlat->getStar()); ?></div>
				<?php $offer = $ajanlat->getOfferKey(); ?>
				<?php if($offer != 'standard'): ?>
					<div class="offer-label lb-<?=$offer?>"><?php
						switch($offer){
							case 'lastminute':
								echo 'LM';
							break;
							case 'firstminute':
								echo 'FM';
							break;
						}
					?></div>
					<div class="fusion-clearfix"></div>
				<?php endif; ?>
				<div class="trans-date">
					<label>Utazás ideje</label>
					<div class="date"><?=$ajanlat->getDate('from')?> &mdash; <?=$ajanlat->getDate('to')?></div>
					<a href="#more-travel" class="more-trans">További időpontok (<?=$ajanlat->getMoreTravelCount()?>) »</a>
				</div>

				<div class="travel-offer">
						<div class="fusion-row">
							<div class="fusion-one-half fusion-layout-column fusion-spacing-no fusion-column-last">
								<div class="prices">
									<label>Alapár:</label>
									<div class="origin p-eur"><span class="eur-price"><?php echo number_format($ajanlat->getPriceOriginalEUR(), 0, '.', ' '); ?>€</span></div>
									<div class="origin p-huf"><span class="huf-price"><?php echo number_format($ajanlat->getPriceOriginalHUF(), 0, '.', ' '); ?> Ft</span></div>
								</div>
							</div>
							<div class="fusion-one-half fusion-layout-column fusion-spacing-no fusion-column-last">
								<div class="travel-info">
									<label>Utazás száma</label>
									<div class="travel-number"><?=$ajanlat->getTravelID()?></div>
									<a href="/eub-utazasbiztositas-feltetelek/" class="info-link">EUB utazásbiztosítás</a>
									<a href="/utazasi-szerzodes/" class="info-link">Utazási szerződés</a>
								</div>
							</div>
						</div>
				</div>

			</div>
		</div>
	</div>

	<div class="travel-content-box">
		<div class="fusion-row">
			<div class="fusion-two-third fusion-layout-column fusion-spacing-yes">
				<div class="info-box-container">
					<div class="fusion-tabs fusion-tabs-1 classic nav-not-justified horizontal-tabs">
						<div class="nav">
							<ul class="nav-tabs">
								<li class="active"><a class="tab-link" data-toggle="tab" href="#info"><h4 class="fusion-tab-heading">Információk</h4></a></li>
								<li><a class="tab-link" data-toggle="tab" href="#more-travel"><h4 class="fusion-tab-heading">További időpontok</h4></a></li>
								<li><a class="tab-link" data-toggle="tab" href="#map"><h4 class="fusion-tab-heading">Térkép</h4></a></li>
							</ul>
						</div>
						<div class="tab-content">
							<div class="tab-pane fade active in" id="info">
								<?php $desc = $ajanlat->getDescriptions(); ?>
								<?php foreach ($desc as $did => $de): ?>
									<h2><?php echo $de['name']; ?></h2>
									<p>
										<?php echo nl2br($de['description']); ?>
									</p>
								<?php endforeach; ?>
							</div>
							<div class="tab-pane fade" id="more-travel">
								lista
							</div>
							<div class="tab-pane fade" id="map">
								térkép
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="fusion-one-third fusion-layout-column fusion-spacing-yes fusion-column-last">
				<div class="travel-calculator-container">
					kalkulátor
				</div>
			</div>
		</div>
	</div>
	<!--
 	<pre>
		<? print_r($ajanlat->term_data); ?>
 	</pre>-->
</div>
<?php get_footer();
