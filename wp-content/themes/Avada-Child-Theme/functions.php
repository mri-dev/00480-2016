<?php

define('IFROOT', get_stylesheet_directory_uri());
define('DEVMODE', true);
define('SZIGET_SLUG', 'kanari-szigetek');
define('KERESO_SLUG', 'utazas-kereso');
define('HOTEL_SLUG', 'hotel');
define('UTAZAS_SLUG', 'utazas');
define('GOOGLE_MAP_API_KEY', 'AIzaSyDxeIuQwvCtMzBGo53tV7AdwG6QCDzmSsQ');

// Includes
require_once "includes/include.php";

function theme_enqueue_styles() {
    wp_enqueue_style( 'avada-parent-stylesheet', get_template_directory_uri() . '/style.css?' . ( (DEVMODE === true) ? time() : '' )  );
    wp_enqueue_style( 'avada-child-stylesheet', IFROOT . '/style.css?' . ( (DEVMODE === true) ? time() : '' ) );
    wp_enqueue_style( 'jquery-ui-str', IFROOT . '/assets/js/jquery-ui-1.12.1/jquery-ui.structure.min.css');
    wp_enqueue_style( 'jquery-ui', IFROOT . '/assets/js/jquery-ui-1.12.1/jquery-ui.theme.min.css');

    wp_enqueue_script('jquery-ui', IFROOT . '/assets/js/jquery-ui-1.12.1/jquery-ui.min.js', array('jquery'));
    wp_enqueue_script('mocjax', IFROOT . '/assets/js/autocomplete/scripts/jquery.mockjax.js');
    wp_enqueue_script('autocomplete', IFROOT . '/assets/js/autocomplete/dist/jquery.autocomplete.min.js');
    wp_enqueue_script('g-map','https://maps.googleapis.com/maps/api/js?key='.GOOGLE_MAP_API_KEY.'&callback=initMap', array(), '1.0', true  );

}
add_action( 'wp_enqueue_scripts', 'theme_enqueue_styles' );

function custom_theme_enqueue_scripts() {
    //wp_enqueue_style('autocomplete', IFROOT . '/assets/js/autocomplete/content/styles.css');
    wp_enqueue_style( 'viasaletravel-css', IFROOT . '/assets/css/viasaletravel.css?' . ( (DEVMODE === true) ? time() : '' ) );
    wp_enqueue_script('viasaletravel-search-js', IFROOT . '/assets/js/viasale-searcher.js?' . ( (DEVMODE === true) ? time() : '' ) );
}
add_action( 'wp_enqueue_scripts', 'custom_theme_enqueue_scripts', 100 );

function avada_lang_setup() {
	$lang = get_stylesheet_directory() . '/languages';
	load_child_theme_textdomain( 'Avada', $lang );
}
add_action( 'after_setup_theme', 'avada_lang_setup' );

function vs_rewrite_rules() {
    add_rewrite_rule( HOTEL_SLUG.'/([^/]+)/([^/]+)/([^/]+)', 'index.php?sziget=$matches[1]&hotel_id=$matches[2]', 'top' );
    add_rewrite_rule( UTAZAS_SLUG.'/'.SZIGET_SLUG.'/([^/]+)/([^/]+)/([^/]+)/([^/]+)', 'index.php?sziget=$matches[1]&varos=$matches[2]&utazas_id=$matches[4]', 'top' );
}
add_action( 'init', 'vs_rewrite_rules' );

function vs_query_vars($aVars) {
  $aVars[] = "hotel_id";
  $aVars[] = "utazas_id";
  $aVars[] = "sziget";
  $aVars[] = "varos";
  return $aVars;
}
add_filter('query_vars', 'vs_query_vars');


function vs_custom_template($template) {
  global $post, $wp_query;
  if ( isset($wp_query->query_vars['utazas_id']) && !empty($wp_query->query_vars['utazas_id']) ) {
      add_filter( 'body_class','vs_utazas_page_class_body' );
    return get_stylesheet_directory() . '/utazas.php';
  } else
  if(isset($wp_query->query_vars['hotel_id']) && !empty($wp_query->query_vars['hotel_id'])) {
    add_filter( 'body_class','vs_hotel_page_class_body' );
    return get_stylesheet_directory() . '/hotel.php';
  } else {
    return $template;
  }
}
add_filter( 'template_include', 'vs_custom_template' );

function vs_hotel_page_class_body( $classes ) {
  $classes[] = 'hotel-travel-page';
  return $classes;
}

function vs_title($title) {
  global $wp_query;
  $new = array();

  if ( isset($wp_query->query_vars['utazas_id']) && !empty($wp_query->query_vars['utazas_id']) ) {
      $ajanlat = new ViasaleAjanlat($wp_query->query_vars['utazas_id']);
      $star = $ajanlat->getStar();

      $utazas_title = $ajanlat->getHotelName();
      $utazas_title .= str_repeat('*', $star);

      $new[] = $utazas_title;
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
  $ajax->get_term_offer();
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
    return '/';
}
