<?php

$termid = $wp_query->query_vars['utazas_id'];
$ajanlat 	= new ViasaleAjanlat($termid, array('api_version' =>'v3'));

if(!$ajanlat->getTravelID()) {
    wp_redirect(get_option('siteurl', '/'), 301);
    exit;
}

$params = array();

if (isset($_GET['offer'])) {
  parse_str(base64_decode($_GET['offer']), $params);
}

$zones = $ajanlat->getHotelZones();

$ztext = '';
foreach ($zones as $zid => $zona) {
  $ztext .= $zona.' / ';
}
$ztext = rtrim($ztext, ' / ');

?>
<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title><?php echo $ajanlat->getHotelName(); ?></title>
<link href="https://fonts.googleapis.com/css?family=Didact+Gothic" rel="stylesheet">
<style media="all">
  body,html {
    height: 100%;
    width: 100%;
    padding: 0;
    margin: 0;
    font-family: 'Didact Gothic', sans-serif;
  }
  header img,
  header .title {
    float: left;
  }
  header .title{
    padding-left: 30px;
  }
  header{
    margin-bottom: 20px;
  }
  header img {
    height: 80px;
  }
  header h1{
    margin: 0 0 8px 0;
  }
  header h2 {
    margin: 0;
  }
  .clr {
    clear:both;
  }

  .row {
    display: flex;

  }

  .row > .col {

  }

  .col.data {
    position: relative;
    flex-basis: 65%;
    padding-right: 30px;
  }
  .col.data:after {
    position: absolute;
    content:"";
    right: 10px;
    width: 5px;
    height: 100%;
    background: #dcdcdc;
    top: 25px;
  }
  .col.data p {
    text-align: justify;
  }
  .col.info {
    flex-basis: 35%;
    padding-top: 25px;
  }

  .info .group{
    margin-bottom: 30px;
  }

  .info .group .head {
    background: #8c8c8c;
    text-align: center;
    color: white;
    border: 1px solid #666666;
    padding: 10px;
    font-weight: bold;
    font-size: 18px;
  }
  .info .group .c{
    padding: 5px 0;
  }

  .info .group .col.h{
    flex-basis: 40%;
  }
  .info .group .row {
    margin: 4px 0;
  }
  .info .group .col.v{
    flex-basis: 60%;
    font-weight: bold;
  }
  .info .group.ajanlat {
    text-align: center;
    font-size: 15px;
  }
  .info .group.ajanlat .r {
    margin: 10px 0;
  }
  .info .group.ajanlat .r .v {
    font-weight: bold;
  }

  .ldivider {
    margin: 20px 0;
    border-bottom: 5px solid #bebebe;
    display: block;
  }

  .priceinfo {
    color: #888888;
    font-size: 13px;
  }
  .contact {
    text-align: center;
    margin-top: 40px;
    font-size: 20px;
    color:black;
    line-height: 1.5;
  }
  .contact .phone {
    font-size: 30px;
    font-weight: bold;
  }
  .contact .web {
    font-size: 20px;
    font-weight: bold;
    margin-top: 10px;
  }

  .images .col {
    flex-basis: 20%;
  }

  .images img {
    max-width: 100%;
  }
  .mainimg img {
    max-width: 100%;
  }
</style>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
<script type="text/javascript">
  (function($){

      setTimeout(function() {
          window.print();
          window.close();
      }, 300);
  })(jQuery);
</script>
</head>
<body>
  <header>
    <img src="http://viasaletravel.ideafontana.eu/wp-content/uploads/2016/09/viasale-travel-logo-h120.png" alt="ViaSale Travel">
    <div class="title">
      <h1 style="color: #f7941d;"><?php echo $ajanlat->getHotelName().str_repeat('*', $ajanlat->getStar()); ?></h1>
      <h2 style="color: #cccccc;"><?php echo $ztext; ?></h2>
    </div>
  </header>
  <div class="clr"></div>
  <div class="content">
    <div class="row">
      <div class="col data">
        <h1>Ajánlat leírása</h1>
        <?php
            $desc = $ajanlat->getDescription( 'utazas' );

            if($desc)
            foreach ($desc as $did => $de):
              $des = $de['description'];
              ?>
              <h3><?php echo $de['name']; ?></h3>
              <p><?php echo $des; ?></p>
              <?php
            endforeach;

            $image = $ajanlat->getProfilImage();
            $images = $ajanlat->getMoreImages();

            if($image) {
              ?>
                <div class="mainimg">
                  <img src="<?php echo $image['url']; ?>" alt="">
                </div>
              <?
            }
            if($images){
              echo '<div class="images row">';
              $ii=0;
              foreach ($images as $img) {  if($ii >= 5 ) break; $ii++;
              ?>
              <div class="col">
                <div class="ih">
                  <img src="<?php echo $img['url']; ?>" alt="">
                </div>
              </div>
              <?
              }
              echo '</div>';
            }
        ?>
      </div>
      <div class="col info">
        <div class="group">
          <div class="head">
            Utazás adatai
          </div>
          <div class="c">
            <div class="row">
              <div class="col h">
                Ellátás
              </div>
              <div class="col v">
                <?php echo $params['term']['board']; ?>
              </div>
            </div>
            <div class="row">
              <div class="col h">
                Indulás
              </div>
              <div class="col v">
                <?php echo $params['term']['date_from']; ?>
              </div>
            </div>
            <div class="row">
              <div class="col h">
                Érkezés
              </div>
              <div class="col v">
                <?php echo $params['term']['date_to']; ?>
              </div>
            </div>
            <div class="row">
              <div class="col h">
                Azonosító
              </div>
              <div class="col v">
                #<?php echo $ajanlat->getTravelID(); ?>
              </div>
            </div>
          </div>
        </div>
        <div class="group ajanlat">
          <div class="head">
            Kiválasztott ajánlat
          </div>
          <div class="c">
            <div class="r">
              <div class="h">
                Szoba
              </div>
              <div class="v">
                <?php echo $params['room']['name']; ?>
              </div>
            </div>
            <div class="r">
              <div class="h">
                Utasok
              </div>
              <div class="v">
                <?php echo $params['room']['people']; ?>
              </div>
            </div>
            <div class="r" style="font-size: 24px;">
              <div class="h">
                Utazás ára*
              </div>
              <div class="v">
                <?php echo $params['room']['price']; ?>
              </div>
            </div>
            <div class="r">
              <div class="h">
                Generálva
              </div>
              <div class="v">
                <?php echo date('Y-m-d H:i'); ?>
              </div>
            </div>
          </div>
        </div>
        <div class="ldivider"></div>
        <div class="priceinfo">
          * tájékoztató jellegű. Az ár változhat a repülőjegy árváltozása esetén, így érdemes hamar lefoglalni!
        </div>
        <div class="contact">
          <div class="phone">
            <?php echo get_option('contact_phone', ''); ?>
          </div>
          <div class="email">
            <?php echo get_option('admin_email', ''); ?>
          </div>
          <div class="web">
            <?php echo get_option('siteurl', ''); ?>
          </div>
        </div>
      </div>
    </div>
  </div>
</body>
</html>
