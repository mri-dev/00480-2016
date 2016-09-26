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
								lista
							</div>
							<div class="tab-pane fade" id="map">
								térkép
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="hotel-column hotel-column-right">
				<div class="travel-calculator-container">
					<?php
						$default_room = $ajanlat->getDefaultRoomData();
					?>
					<div class="calc-head">
						<div class="calc-wrapper">
							<h2>Ajánlat kalkulátor</h2>
							<p>
								Számolja ki álomutazását!
							</p>
						</div>
					</div>
					<div class="calc-content">
						<div class="calc-wrapper">
							<div class="calc-row">
								<h3>Hányan szeretnének utazni?</h3>
								<div class="fusion-row">
									<div class="fusion-column fusion-spacing-yes fusion-layout-column fusion-one-half select-form">
										<label for="Adults">Felnőttek</label>
										<select class="" name="Adults" id="Adults">
											<?php
											$min_adults = $ajanlat->getMinAdults();
											$max_adults = $ajanlat->getMaxAdults();
											for($adn = $min_adults; $adn <= $max_adults; $adn++): ?>
											<option value="<?php echo $adn; ?>" <?php if($default_room['min_adults']==$adn): echo 'selected="selected"'; endif; ?>><?php echo $adn; ?></option>
											<?php endfor; ?>
										</select>
									</div>
									<div class="fusion-column fusion-spacing-yes fusion-layout-column fusion-one-half fusion-column-last select-form">
										<label for="Children">Gyermekek</label>
										<select class="" name="Children" id="Children">
											<?php
											$default_room_children_min = $ajanlat->getDefaultRoomMinChildren();
											$default_room_children_max = $ajanlat->getDefaultRoomMaxChildren();
											for($adn = $default_room_children_min; $adn <= $default_room_children_max; $adn++): ?>
											<option value="<?php echo $adn; ?>"><?php echo $adn; ?></option>
											<?php endfor; ?>
										</select>
									</div>
								</div>
							</div>
              <div class="calc-row">
								<h3>Ajánlatok</h3>
                <div id="term-ajanlat-result">

                </div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
  <script type="text/javascript">
  (function ($) {
    var offers = [];
    var termid = <?=$ajanlat->getTravelID()?>;
    var adults = <?php echo $default_room['min_adults']?>;
    var children = 0;
		var children_by_adults = {<?php if($children_by_adults){
			 $jsarr = '';
			 	foreach ($children_by_adults as $adults => $minmax):
					$jsarr .= $adults.' : { min: '.$minmax['min'].', max: '.$minmax['max'].' }, ';
				endforeach;
				$jsarr = rtrim($jsarr, ', ');
				echo $jsarr;
		}?>};

    getOffers(termid, adults, children);

    function getOffers( termid, adults, children){
      $('#term-ajanlat-result').html('<i class="fa fa-spinner fa-spin"></i> Ajánlatok betöltése...');

			var child_vars = children_by_adults[adults];

			$.post(
        '<?php echo get_ajax_url('get_term_offer'); ?>',
        {
          termid: termid,
          adults: adults,
          children: children
        }, function(data){
          var datas = $.parseJSON(data);
          console.log(datas);
          buildOffers(datas);
        },"html");

				recreateChildrenSelect(child_vars);
    }

		function recreateChildrenSelect(child_vars) {
			var html = '';
			var min = child_vars.min;
			var max = child_vars.max;
			var is_selected = false;

			for (i = min; i <= max; i++) {
				is_selected = (children == i) ? true : false;
		  	html += '<option value="'+i+'" '+ ( (is_selected) ? 'selected="selected"' : '' ) +'>'+i+'</option>';
			}

			$('#Children').html(html);
		}

    function buildOffers(obj) {
      var html = '';
			$.each(obj.rooms, function(room_id,room_cfg){
				var room_name = obj.room_info[room_id].name;
				var room_sum_price = obj.price_by_rooms[room_id];
				var configs = obj.room_info[room_id].configs;

				html +=
				'<div class="room">'+
					'<div class="room-head">'+
						'<div class="room-name">'+room_name+'</div>'+
						'<div class="room-price-sum">'+room_sum_price+'€</div>'+
					'</div>'+
					'<div class="configs">';
						$.each(configs, function(cfgid, cnf){
							html +=
							'<div class="cfg">'+
								'<div class="name">'+cnf.name+'</div>'+
								'<div class="part-price">'+cnf.count+' x '+cnf.price+'€</div>'+
								'<div class="sum-price">'+(cnf.price*cnf.count)+'€</div>'+
							'</div>';
						});
				html += '</div>'+
				'</div>';
			});
      $('#term-ajanlat-result').html(html);
    }

    $('#Adults, #Children').change(function(){
      var s_adults = $('#Adults').val();
      var s_children = $('#Children').val();
			children = s_children;
      getOffers(termid, s_adults, s_children);
    });
  })(jQuery);
  </script>
	<!--
 	<pre>
		<? print_r($ajanlat->term_data); ?>
 	</pre>-->
</div>
<?php get_footer();