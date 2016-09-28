<?php get_header(); ?>
<?
	$hotel_id = $wp_query->query_vars['utazas_id'];
	$ajanlat 	= new ViasaleAjanlat($hotel_id);
	$children_by_adults = $ajanlat->getChildrenByAdults();
?>
<div id="content" class="full-width travel-content" stlye="max-width:100%;">
	<div class="hotel-row" stlye="max-width:100%;">
		<div class="hotel-column hotel-column-left">
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
		<div class="hotel-column hotel-column-right">
			<div class="hotel-column-wrapper">
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
				<?php if($offer == 'lastminute' || $offer == 'firstminute'): ?>
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

				<div class="travel-info-box">
					<div class="travel-features">
						<div class="feat"><div class="feat-wrapper" title="Ellátás"><i class="fa fa-cutlery"></i> <div><?php echo $ajanlat->getBoardName(); ?></div></div></div>
						<div class="feat feat-main"><div class="feat-wrapper" title="Az utazás időtartama"><i class="fa fa-calendar"></i> <div><?php echo $ajanlat->getDayDuration()?> nap</div></div></div>
						<div class="feat"><div class="feat-wrapper" title="Elérhető szobatípusok"><i class="fa fa-bed"></i> <div><?php echo $ajanlat->getRoomsCount()?> szobatípus</div></div></div>
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
		</div>
	</div>

	<div class="travel-content-box">
		<div class="hotel-row">
			<div class="hotel-column hotel-column-left">
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
								<div class="more-travel-list">
								<?
									$date_group = false;
									$more_terms = $ajanlat->getMoreTravel(array( 'except_term_ids' => array($ajanlat->getTravelID())));
								?>
								<?php if (!empty($more_terms) && !$more_terms): ?>
									<div class="no-more-travel">
										<h4>Jelenleg nincs további utazási időpontunk.</h4>
										<p>
											Kérjük, hogy nézzen vissza később az aktuális ajánlatokért!
										</p>
									</div>
								<?php else: ?>
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
									<?php foreach ($more_terms as $u): ?>
										<?
												if($u['date_from'] != $date_group ) {
													$date_group = $u['date_from'];
													?>
													<div class="more-travel-row group-header">
														<?php echo $date_group; ?>
													</div>
													<?
												}
										?>
										<div class="more-travel-row">
											<div class="more-travel-date">
												<?php echo $u['date_from']; ?> &mdash; <?php echo $u['date_to']; ?>
											</div>
											<div class="more-travel-board">
												<?php echo $u['board_type']; ?>
											</div>
											<div class="more-travel-duration">
												<?php echo $u['term_duration']; ?> nap
											</div>
											<div class="more-travel-priceplan">
												<div class="price-eur"><?php echo number_format($u['price_from'], 0, ".", " "); ?>€</div>
												<div class="price-huf"><?php echo number_format($u['price_from_huf'], 0, ".", " "); ?> Ft</div>
											</div>
											<div class="more-travel-redirect">
												<a href="/<?php echo $ajanlat->getURISlug($u['term_id']); ?>" class="trans-on">Tovább <i class="fa fa-arrow-circle-right"></i></a>
											</div>
										</div>
									<?php endforeach; ?>
								<?php endif; ?>
								</div>
							</div>
							<div class="tab-pane fade" id="map">
									<div id="travel-gmap"></div>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="hotel-column hotel-column-right">
				<? include(locate_template('templates/travelcalc/v1.php')); ?>
			</div>
		</div>
	</div>
	<?php
		$hotel_gps = $ajanlat->getGPS();
	?>
  <script type="text/javascript">
	var map;
	function initMap() {
		map = new google.maps.Map( document.getElementById( 'travel-gmap' ), {
			center:  new google.maps.LatLng( <?=$hotel_gps['lat']?>, <?=$hotel_gps['lng']?> ),
			zoom:	12,
		});

		console.log(map);
	}
  </script>

</div>
<?php get_footer();
