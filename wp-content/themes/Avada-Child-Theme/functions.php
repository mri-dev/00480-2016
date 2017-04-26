<?php

define('IFROOT', get_stylesheet_directory_uri());
define('DEVMODE', true);
define('SZIGET_SLUG', 'kanari-szigetek');
define('KERESO_SLUG', 'utazas-kereso');
define('KERESO_PROGRAM_SLUG', 'program-kereso');
define('HOTEL_SLUG', 'hotel');
define('HOTEL_LIST_SLUG', 'szallodak');
define('UTAZAS_SLUG', 'utazas');
define('PROGRAM_SLUG', 'program');
define('GOOGLE_MAP_API_KEY', 'AIzaSyDxeIuQwvCtMzBGo53tV7AdwG6QCDzmSsQ');
define('EUB_URL', 'http://eub.hu/?pcode=29289');
define('NOIMAGE_MID', IMAGES.'/viasale-travel-no-image-500.jpg');
define('NOIMAGE_HD', IMAGES.'/viasale-travel-no-image-1024.jpg');

define('RESOURCES', IFROOT.'/assets' );
//define('RESOURCES', '//cdn.viasaletravel.hu/res' );
define('IMAGES', IFROOT.'/images' );
//define('IMAGES', '//cdn.viasaletravel.hu/images' );

//define('CLONEKEY','OTPTRAVEL');
define('CSSVERSION','201704261500');

if(defined('CLONEKEY') && CLONEKEY == 'OTPTRAVEL') {
  define('ASZF_URL',  get_option('siteurl') . '/files/aszf-170124.pdf');
} else {
  define('ASZF_URL',  get_option('siteurl') . '/files/aszf-170124.pdf');
}

/////////////////////////////////////////
// Includes
require_once "includes/include.php";

function theme_enqueue_styles() {
  global $wp_query;
    wp_enqueue_style( 'avada-parent-stylesheet', get_template_directory_uri() . '/style.css?' . ( (DEVMODE === true) ? time() : '' )  );
    wp_enqueue_style( 'avada-child-stylesheet', IFROOT . '/style.css?' . ( (DEVMODE === true) ? time() : '' ) );
    wp_enqueue_style( 'jquery-ui-str', RESOURCES . '/vendor/jquery-ui-1.12.1/jquery-ui.structure.min.css');
    wp_enqueue_style( 'jquery-ui', RESOURCES . '/vendor/jquery-ui-1.12.1/jquery-ui.theme.min.css');
    wp_enqueue_script('angular', '//ajax.googleapis.com/ajax/libs/angularjs/1.6.1/angular.min.js', array('jquery'), '');
    wp_enqueue_style( 'slick', RESOURCES . '/vendor/slick/slick.css');
    wp_enqueue_style( 'slick-theme', RESOURCES . '/vendor/slick/slick-theme.css');
    wp_enqueue_style( 'datepicker', RESOURCES . '/vendor/datepicker/datepicker3.css');

    wp_enqueue_script('jquery-base64', RESOURCES . '/vendor/jquery.base64/jquery.base64.min.js', array('jquery'), '');
    wp_enqueue_script('jquery-ui', RESOURCES . '/vendor/jquery-ui-1.12.1/jquery-ui.min.js', array('jquery'));
    wp_enqueue_script('mocjax', RESOURCES . '/vendor/autocomplete/scripts/jquery.mockjax.js');
    wp_enqueue_script('autocomplete', RESOURCES . '/vendor/autocomplete/dist/jquery.autocomplete.min.js');
    wp_enqueue_script('slick', RESOURCES . '/vendor/slick/slick.min.js', array('jquery'), '');
    wp_enqueue_script('viasalebase-ang', RESOURCES . '/js/viasalebase.ang.js?t=' . ( (DEVMODE === true) ? time() : '' ) );
    wp_enqueue_script('datepicker', RESOURCES . '/vendor/datepicker/bootstrap-datepicker.js', array('jquery'), '');
    wp_enqueue_script('datepicker-hu', RESOURCES . '/vendor/datepicker/locales/bootstrap-datepicker.hu.js', array('datepicker'), '');

    if (
      (isset($wp_query->query_vars['utazas_id']) && !empty($wp_query->query_vars['utazas_id'])) ||
      (isset($wp_query->query_vars['hotel_id']) && !empty($wp_query->query_vars['hotel_id']))
    ) {
      wp_enqueue_script('g-map','https://maps.googleapis.com/maps/api/js?key='.GOOGLE_MAP_API_KEY.'&callback=initMap', array(), '1.0', true  );
    }

    if(!defined('CLONEKEY'))
    {
      define('ADWORDS_CALL_CONV', true);
      echo '<script type="text/javascript">
      (function(a,e,c,f,g,h,b,d){var k={ak:"858546762",cl:"0IT2CNLR-m8QysSxmQM"};a[c]=a[c]||function(){(a[c].q=a[c].q||[]).push(arguments)};a[g]||(a[g]=k.ak);b=e.createElement(h);b.async=1;b.src="//www.gstatic.com/wcm/loader.js";d=e.getElementsByTagName(h)[0];d.parentNode.insertBefore(b,d);a[f]=function(b,d,e){a[c](2,b,k,d,null,new Date,e)};a[f]()})(window,document,"_googWcmImpl","_googWcmGet","_googWcmAk","script");
      </script>';
    }
}
add_action( 'wp_enqueue_scripts', 'theme_enqueue_styles' );

function datetimepicker_enqueue_styles() {
    wp_enqueue_style( 'datetimepicker', RESOURCES . '/vendor/datetimepicker/datepicker.css?t=' . ( (DEVMODE === true) ? time() : '' ) );
    wp_enqueue_script( 'datetimepicker', RESOURCES . '/vendor/datetimepicker/datepicker.js?t=' . ( (DEVMODE === true) ? time() : '' ) );
}
add_action( 'wp_enqueue_scripts', 'datetimepicker_enqueue_styles' );

function custom_theme_enqueue_scripts() {

  if (defined('CLONEKEY') && CLONEKEY == 'OTPTRAVEL' && DEVMODE === false) {
    $css_prefix = 'otp/';
  }
    //wp_enqueue_style('autocomplete', IFROOT . '/assets/js/autocomplete/content/styles.css');
    wp_enqueue_style( 'viasaletravel-css', RESOURCES . '/css/'.$css_prefix.'viasaletravel'.( (DEVMODE === false) ? '-v'.CSSVERSION : '' ) .'.css?' . ( (DEVMODE === true) ? 't='.time() : '' ) );
    if (defined('CLONEKEY') && CLONEKEY == 'OTPTRAVEL') {
      wp_enqueue_style( 'otptravel-css', RESOURCES . '/css/otptravel'.( (DEVMODE === false) ? '-v'.CSSVERSION : '' ) .'.css?' . ( (DEVMODE === true) ? 't='.time() : '' ) );
    }
}
add_action( 'wp_enqueue_scripts', 'custom_theme_enqueue_scripts', 100 );

function avada_lang_setup() {
	$lang = get_stylesheet_directory() . '/languages';
	load_child_theme_textdomain( 'Avada', $lang );
}
add_action( 'after_setup_theme', 'avada_lang_setup' );

function vs_rewrite_rules() {
    add_rewrite_rule( HOTEL_SLUG.'/([^/]+)/([^/]+)/([^/]+)/([^/]+)/([^/]+)', 'index.php?sziget=$matches[2]&hotel_id=$matches[5]', 'top' );
    add_rewrite_rule( UTAZAS_SLUG.'/'.SZIGET_SLUG.'/([^/]+)/([^/]+)/([^/]+)/([^/]+)', 'index.php?sziget=$matches[1]&varos=$matches[2]&utazas_id=$matches[4]', 'top' );
    add_rewrite_rule( PROGRAM_SLUG.'/([^/]+)/([^/]+)', 'index.php?program_id=$matches[2]', 'top' );
    add_rewrite_rule( UTAZAS_SLUG.'/download/([^/]+)', 'index.php?utazas_id=$matches[1]&templatekey=offlinedownload', 'top' );
    add_rewrite_rule( UTAZAS_SLUG.'/print/([^/]+)', 'index.php?utazas_id=$matches[1]&templatekey=printoffer', 'top' );
}
add_action( 'init', 'vs_rewrite_rules' );

function vs_query_vars($aVars) {
  $aVars[] = "hotel_id";
  $aVars[] = "utazas_id";
  $aVars[] = "sziget";
  $aVars[] = "varos";
  $aVars[] = "program_id";
  $aVars[] = "templatekey";
  return $aVars;
}
add_filter('query_vars', 'vs_query_vars');


function vs_custom_template($template) {
  global $post, $wp_query;

  /* * /
  echo '<pre>';
  print_r($wp_query->query_vars);
  echo '</pre>';
  /* */

  if( isset($wp_query->query_vars['templatekey']) && !empty($wp_query->query_vars['templatekey']) )
  {
    if (function_exists('vs_templatekey_'.$wp_query->query_vars['templatekey'].'_class_body')) {
      add_filter( 'body_class','vs_templatekey_'.$wp_query->query_vars['templatekey'].'_class_body' );
    }
    return get_stylesheet_directory() . '/'.$wp_query->query_vars['templatekey'].'.php';
  }

  if ( isset($wp_query->query_vars['utazas_id']) && !empty($wp_query->query_vars['utazas_id']) ) {
      add_filter( 'body_class','vs_utazas_page_class_body' );
    return get_stylesheet_directory() . '/utazas.php';
  } else
  if(isset($wp_query->query_vars['hotel_id']) && !empty($wp_query->query_vars['hotel_id'])) {
    add_filter( 'body_class','vs_hotel_page_class_body' );
    return get_stylesheet_directory() . '/hotel.php';
  } else
  if(isset($wp_query->query_vars['program_id']) && !empty($wp_query->query_vars['program_id'])) {
    add_filter( 'body_class','vs_program_page_class_body' );
    return get_stylesheet_directory() . '/program.php';
  } else{
    return $template;
  }
}
add_filter( 'template_include', 'vs_custom_template' );

function vs_templatekey_offlinedownload_class_body()
{
  $classes[] = 'viasale-template template-offlinedownload';
  return $classes;
}

function vs_program_page_class_body( $classes ) {
  $classes[] = 'viasale-program-page';
  return $classes;
}

function vs_hotel_page_class_body( $classes ) {
  $classes[] = 'hotel-travel-page';
  return $classes;
}

// Autóbérlés e-mail validáló
function custom_autoberles_email_confirmation_validation_filter( $result, $tag )
{
  $tag = new WPCF7_FormTag( $tag );

  if ( 'cont_email_confirm' == $tag->name ) {
      $your_email = isset( $_POST['cont_email'] ) ? trim( $_POST['cont_email'] ) : '';
      $your_email_confirm = isset( $_POST['cont_email_confirm'] ) ? trim( $_POST['cont_email_confirm'] ) : '';

      if ( $your_email != $your_email_confirm ) {
          $result->invalidate( $tag, "Az Ön által megadott e-mail címek nem egyeznek. Adja meg újra!" );
      }
  }

  return $result;
}
add_filter( 'wpcf7_validate_email', 'custom_autoberles_email_confirmation_validation_filter', 20, 2 );


function vs_title($title) {
  global $wp_query;
  $new = array();

  //$titles = apply_filters( 'pre_get_document_title', '' );
  //echo $titles;

  // Utazás title
  if ( isset($wp_query->query_vars['utazas_id']) && !empty($wp_query->query_vars['utazas_id']) ) {
      $ajanlat = new ViasaleAjanlat($wp_query->query_vars['utazas_id'], array('api_version' => 'v3'));
      $star = $ajanlat->getStar();

      $utazas_title = $ajanlat->getHotelName();
      if(empty($utazas_title)) {
          wp_redirect( get_option('siteurl') );
          exit;
      }
      unset($ajanlat);
      $utazas_title .= str_repeat('*', $star). ' szálloda utazási ajánlat, Kanári-szigetek.';

      $new[] = $utazas_title;
  }

  // Hotel title
  if ( isset($wp_query->query_vars['hotel_id']) && !empty($wp_query->query_vars['hotel_id']) ) {
      $hotel = new ViasaleHotel($wp_query->query_vars['hotel_id']);
      $star = $hotel->getStar();
      $utazas_title = $hotel->getHotelName();
      if(empty($utazas_title)) {
          wp_redirect( get_option('siteurl') );
          exit;
      }
      $utazas_title .= str_repeat('*', $star). ' szálloda adatlap, utazási ajánlatok, Kanári-szigetek.';

      $new[] = $utazas_title;
  }

  // Program title
  if ( isset($wp_query->query_vars['program_id']) && !empty($wp_query->query_vars['program_id']) ) {
      $program = new ViasaleProgram($wp_query->query_vars['program_id']);
      $program_title = $program->getProgramName();

      if(empty($program_title)) {
          wp_redirect( get_option('siteurl') );
          exit;
      }

      $new[] = $program_title;
      $new[] = 'Programok';
  }


  foreach ($title as $t) {
    $new[] = $t;
  }

  return $new;
}
add_filter('document_title_parts', 'vs_title');

$wpseo_loaded_ajanlat = false;
$wpseo_loaded_hotel = false;
$wpseo_loaded_program = false;
function title_fix_yoast( $o ) {
  global $wp_query, $wpseo_loaded_ajanlat, $wpseo_loaded_hotel, $wpseo_loaded_program;

  if ( defined('WPSEO_VERSION') )
  {
    // Utazás title
    if ( isset($wp_query->query_vars['utazas_id']) && !empty($wp_query->query_vars['utazas_id']) )
    {
        $wpseo_loaded_ajanlat = new ViasaleAjanlat($wp_query->query_vars['utazas_id'], array('api_version' => 'v3'));

        $star = $wpseo_loaded_ajanlat->getStar();
        $utazas_title = $wpseo_loaded_ajanlat->getHotelName();
        if(empty($utazas_title)) {
            wp_redirect( get_option('siteurl') );
            exit;
        }
        $utazas_title .= str_repeat('*', $star). ' szálloda utazási ajánlat, Kanári-szigetek.';
        $o = $utazas_title . ' - '.get_option('blogname', true);

        add_filter('wpseo_canonical', 'wpseo_utazas_canonical_mod');
        add_filter('wpseo_metadesc', 'wpseo_utazas_desc_mod');
        add_filter('wpseo_opengraph_image', 'wpseo_utazas_image_meta_mod');
    }

    // Hotel title
    if ( isset($wp_query->query_vars['hotel_id']) && !empty($wp_query->query_vars['hotel_id']) ) {
        $wpseo_loaded_hotel = new ViasaleHotel($wp_query->query_vars['hotel_id']);
        $star = $wpseo_loaded_hotel->getStar();
        $utazas_title = $wpseo_loaded_hotel->getHotelName();
        if(empty($utazas_title)) {
            wp_redirect( get_option('siteurl') );
            exit;
        }
        $utazas_title .= str_repeat('*', $star). ' szálloda adatlap, utazási ajánlatok, Kanári-szigetek.';

        $o = $utazas_title . ' - '.get_option('blogname', true);

        add_filter('wpseo_canonical', 'wpseo_hotel_canonical_mod');
        add_filter('wpseo_metadesc', 'wpseo_hotel_desc_mod');
        add_filter('wpseo_opengraph_image', 'wpseo_hotel_image_meta_mod');
    }

    // Program title
    if ( isset($wp_query->query_vars['program_id']) && !empty($wp_query->query_vars['program_id']) ) {
        $wpseo_loaded_program = new ViasaleProgram($wp_query->query_vars['program_id']);
        $program_title = $wpseo_loaded_program->getProgramName();

        if(empty($program_title)) {
            wp_redirect( get_option('siteurl') );
            exit;
        }

        $o = $program_title.' / Program / Kanári-szigetek'. ' - '.get_option('blogname', true);

        add_filter('wpseo_canonical', 'wpseo_program_canonical_mod');
        add_filter('wpseo_metadesc', 'wpseo_program_desc_mod');
        add_filter('wpseo_opengraph_image', 'wpseo_program_image_meta_mod');
    }

    //$wpseo_loaded_ajanlat = false;
  }

  return $o;
}
add_filter('wpseo_title', 'title_fix_yoast');

/**
* WPSEO - Utazási ajánlat
**/
function wpseo_utazas_canonical_mod($url)
{
  global $wpseo_loaded_ajanlat;

  $url = get_option('siteurl', true).'/'.$wpseo_loaded_ajanlat->getURISlug().$wpseo_loaded_ajanlat->getTravelID();

  return $url;
}

function wpseo_utazas_desc_mod( $desc )
{
  global $wpseo_loaded_ajanlat;

  $data =  $wpseo_loaded_ajanlat->getDescription('hotel');

  $desc = $data[0]['description'];

  return $desc;
}

function wpseo_utazas_image_meta_mod( $img )
{
  global $wpseo_loaded_ajanlat;

  $data =  $wpseo_loaded_ajanlat->getProfilImage();

  $img = $data['url'];

  return $img;
}

/**
* WPSEO - Hotel adatlap
**/
function wpseo_hotel_canonical_mod($url)
{
  global $wpseo_loaded_hotel;

  $url = get_option('siteurl', true).'/'.$wpseo_loaded_hotel->getURISlug().$wpseo_loaded_hotel->getHotelID();

  return $url;
}

function wpseo_hotel_desc_mod( $desc )
{
  global $wpseo_loaded_hotel;

  $desc =  $wpseo_loaded_hotel->getInfo();

  return $desc;
}

function wpseo_hotel_image_meta_mod( $img )
{
  global $wpseo_loaded_hotel;

  $data =  $wpseo_loaded_hotel->getProfilImage();

  $img = $data['url'];

  return $img;
}
/**
* WPSEO - Program adatlap
**/
function wpseo_program_canonical_mod($url)
{
  global $wpseo_loaded_program;

  $url = get_option('siteurl', true).'/program/'.sanitize_title($wpseo_loaded_program->getProgramName()).'/'.$wpseo_loaded_program->getProgramID();

  return $url;
}

function wpseo_program_desc_mod( $desc )
{
  global $wpseo_loaded_program;

  $desc =  $wpseo_loaded_program->getInfo();

  return $desc;
}

function wpseo_program_image_meta_mod( $img )
{
  global $wpseo_loaded_program;

  $data =  $wpseo_loaded_program->getProfilImage();

  $img = $data['url'];

  return $img;
}

function vs_utazas_page_class_body( $classes ) {
  $classes[] = 'utazas-travel-page';
  return $classes;
}
/**
* AJAX REQUESTS
*/
function vs_ajax_requests()
{
  $ajax = new AjaxRequests();
  $ajax->send_travel_request();
}
add_action( 'init', 'vs_ajax_requests' );

// AJAX URL
function get_ajax_url( $function )
{
  return admin_url('admin-ajax.php?action='.$function);
}

/**
*  FUNKCIÓK
* */
// Katalógus hivatkozása
function catalog_url()
{
  //return 'https://issuu.com/viasaletravel/docs/pdf_nagy_viasale_katalogus_2017';
  return get_option('katalogus_link', '#');
}

// Biztosító link
function biztosito_url()
{
  if(defined('CLONEKEY')) {
    switch(CLONEKEY){
		case 'OTPTRAVEL':
			return 'https://ugyfelportal.garancia-online.hu/wps/portal/utas-online?GP_661_forras_site=TRAVEL';
		break;
	}
  }

  return 'http://eub.hu/?pcode=29289';
}

// Mailchimp feliratkozó a fejlécbe
function mailchimp_subscriber_html()
{
  if(defined('CLONEKEY')) {
    return '';
  }

  return '<div class="mailchimp-header-subsc">
    <form action="//viasaletravel.us9.list-manage.com/subscribe/post?u=3e9a92238c6dea060038ac5f3&amp;id=1ec2d250a7" method="post" id="mc-embedded-subscribe-form" name="mc-embedded-subscribe-form" class="validate searchform" target="_blank" novalidate>
      <label for="mce-EMAIL">Hírlevél feliratkozás <i class="fa fa-envelope-o"></i></label>
      <div class="search-table">
        <div class="search-field"><input type="email" value="" name="EMAIL" class="email" id="mce-EMAIL" placeholder="Email cím megadása" required></div>
        <div class="search-button"><input type="submit" value="Küldés" name="subscribe" id="mc-embedded-subscribe" class="button"></div>
      </div>
      <!-- real people should not fill this in and expect good things - do not remove this or risk form bot signups-->
      <div style="position: absolute; left: -5000px;" aria-hidden="true"><input type="text" name="b_3e9a92238c6dea060038ac5f3_1ec2d250a7" tabindex="-1" value=""></div>
    </form>
  </div>';
}

function after_body_tag()
{
  if(defined('CLONEKEY')) {
    return '';
  }

  echo '<div class="request-offer fixed-label hide-on-mobile"><a href="/ajanlatkeres/"><img src="'.IMAGES.'/palmas_h40_white.png"/>' . __('Ajánlatkérés', 'viasale') . '</a></div>';
}
add_action('avada_before_body_content', 'after_body_tag');
?>
