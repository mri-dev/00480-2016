<?php

define('IFROOT', get_stylesheet_directory_uri());
define('DEVMODE', true);
define('SZIGET_SLUG', 'kanari-szigetek');
define('KERESO_SLUG', 'utazas-kereso');
define('HOTEL_SLUG', 'hotel');
define('HOTEL_LIST_SLUG', 'szallodak');
define('UTAZAS_SLUG', 'utazas');
define('PROGRAM_SLUG', 'program');
define('GOOGLE_MAP_API_KEY', 'AIzaSyDxeIuQwvCtMzBGo53tV7AdwG6QCDzmSsQ');
define('RESOURCES', IFROOT.'/assets' );
//define('RESOURCES', '//cdn.viasaletravel.hu/res' );
define('IMAGES', IFROOT.'/images' );
//define('IMAGES', '//cdn.viasaletravel.hu/images' );
define('EUB_URL', 'http://eub.hu/?pcode=29289');
define('NOIMAGE_MID', IMAGES.'/viasale-travel-no-image-500.jpg');
define('NOIMAGE_HD', IMAGES.'/viasale-travel-no-image-1024.jpg');

// Includes
require_once "includes/include.php";

function theme_enqueue_styles() {
  global $wp_query;
    wp_enqueue_style( 'avada-parent-stylesheet', get_template_directory_uri() . '/style.css?' . ( (DEVMODE === true) ? time() : '' )  );
    wp_enqueue_style( 'avada-child-stylesheet', IMGROOT . '/style.css?' . ( (DEVMODE === true) ? time() : '' ) );
    wp_enqueue_style( 'jquery-ui-str', RESOURCES . '/vendor/jquery-ui-1.12.1/jquery-ui.structure.min.css');
    wp_enqueue_style( 'jquery-ui', RESOURCES . '/vendor/jquery-ui-1.12.1/jquery-ui.theme.min.css');
    wp_enqueue_style( 'slick', RESOURCES . '/vendor/slick/slick.css');
    wp_enqueue_style( 'slick-theme', RESOURCES . '/vendor/slick/slick-theme.css');

    wp_enqueue_script('jquery-ui', RESOURCES . '/vendor/jquery-ui-1.12.1/jquery-ui.min.js', array('jquery'));
    wp_enqueue_script('mocjax', RESOURCES . '/vendor/autocomplete/scripts/jquery.mockjax.js');
    wp_enqueue_script('autocomplete', RESOURCES . '/vendor/autocomplete/dist/jquery.autocomplete.min.js');
    wp_enqueue_script('slick', RESOURCES . '/vendor/slick/slick.min.js', array('jquery'), '');
    wp_enqueue_script('basevs', RESOURCES . '/js/base-v1.js?t=' . ( (DEVMODE === true) ? time() : '' ) );

    if (
      (isset($wp_query->query_vars['utazas_id']) && !empty($wp_query->query_vars['utazas_id'])) ||
      (isset($wp_query->query_vars['hotel_id']) && !empty($wp_query->query_vars['hotel_id']))
    ) {
      wp_enqueue_script('g-map','https://maps.googleapis.com/maps/api/js?key='.GOOGLE_MAP_API_KEY.'&callback=initMap', array(), '1.0', true  );
    }
}
add_action( 'wp_enqueue_scripts', 'theme_enqueue_styles' );

function custom_theme_enqueue_scripts() {
    //wp_enqueue_style('autocomplete', IFROOT . '/assets/js/autocomplete/content/styles.css');
    wp_enqueue_style( 'viasaletravel-css', RESOURCES . '/css/viasaletravel.css?' . ( (DEVMODE === true) ? time() : '' ) );
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
}
add_action( 'init', 'vs_rewrite_rules' );

function vs_query_vars($aVars) {
  $aVars[] = "hotel_id";
  $aVars[] = "utazas_id";
  $aVars[] = "sziget";
  $aVars[] = "varos";
  $aVars[] = "program_id";
  return $aVars;
}
add_filter('query_vars', 'vs_query_vars');


function vs_custom_template($template) {
  global $post, $wp_query;

  /*
  echo '<pre>';
  print_r($wp_query->query_vars);
  echo '</pre>';
  */

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
  } else {
    return $template;
  }
}
add_filter( 'template_include', 'vs_custom_template' );

function vs_program_page_class_body( $classes ) {
  $classes[] = 'viasale-program-page';
  return $classes;
}

function vs_hotel_page_class_body( $classes ) {
  $classes[] = 'hotel-travel-page';
  return $classes;
}


function vs_title($title) {
  global $wp_query;
  $new = array();



  //$titles = apply_filters( 'pre_get_document_title', '' );
  //echo $titles;

  // Utazás title
  if ( isset($wp_query->query_vars['utazas_id']) && !empty($wp_query->query_vars['utazas_id']) ) {
      $ajanlat = new ViasaleAjanlat($wp_query->query_vars['utazas_id']);
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
  return 'http://issuu.com/viasaletravel/docs/viasale_travel_online_katalogus_201?e=15904679/34190920';
}

?>
