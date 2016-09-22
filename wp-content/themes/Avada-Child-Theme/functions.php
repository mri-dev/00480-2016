<?php

define('IFROOT', get_stylesheet_directory_uri());
define('DEVMODE', true);
define('SZIGET_SLUG', 'kanari-szigetek');
define('KERESO_SLUG', 'utazas-kereso');

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
    add_rewrite_rule( 'hotel/'.SZIGET_SLUG.'/([^/]+)/([^/]+)/([^/]+)', 'index.php?sziget=$matches[1]&varos=$matches[2]&hotel_id=$matches[3]', 'top' );
}
add_action( 'init', 'vs_rewrite_rules' );


function vs_query_vars($aVars) {
  $aVars[] = "hotel_id";
  $aVars[] = "sziget";
  $aVars[] = "varos";
  return $aVars;
}
add_filter('query_vars', 'vs_query_vars');


function vs_custom_template($template) {
  global $post, $wp_query;
  if ( isset($wp_query->query_vars['hotel_id']) && !empty($wp_query->query_vars['hotel_id']) ) {
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

/**
*  FUNKCIÓK
* */
// Katalógus hivatkozása
function catalog_url()
{
    return '/';
}
