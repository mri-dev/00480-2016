<?php get_header(); ?>
<?
	$hotel_id = $wp_query->query_vars['hotel_id'];
	$hotel 	= new ViasaleHotel($hotel_id);
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
			</div>
		</div>
		</div>
	</div>

	<div class="travel-content-box">
		<div class="hotel-row">
			<div class="hotel-column hotel-column-full">
				<div class="info-box-container">
					<div class="fusion-tabs fusion-tabs-1 classic nav-not-justified horizontal-tabs">
						<div class="nav">
							<ul class="nav-tabs">
								<li class="active"><a class="tab-link" data-toggle="tab" href="#info"><h4 class="fusion-tab-heading">Információk</h4></a></li>
								<li><a class="tab-link" data-toggle="tab" href="#map"><h4 class="fusion-tab-heading">Térkép</h4></a></li>
							</ul>
						</div>
						<div class="tab-content">
							<div class="tab-pane fade active in" id="info">
								<?php $desc = $hotel->getDescriptions(); ?>
								<?php if($desc) foreach ($desc as $did => $de): ?>
									<h2><?php echo $de['name']; ?></h2>
									<p>
										<?php echo nl2br($de['description']); ?>
									</p>
								<?php endforeach; ?>
							</div>
							<div class="tab-pane fade" id="map">

							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<?php get_footer();
