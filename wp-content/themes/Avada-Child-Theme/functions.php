<?php

define('IFROOT', get_stylesheet_directory_uri());
define('DEVMODE', true);

// Includes
require_once "includes/include.php";

function theme_enqueue_styles() {
    wp_enqueue_style( 'avada-parent-stylesheet', get_template_directory_uri() . '/style.css?' . ( (DEVMODE === true) ? time() : '' )  );
    wp_enqueue_style( 'avada-child-stylesheet', IFROOT . '/style.css?' . ( (DEVMODE === true) ? time() : '' ) );

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

/**
*  FUNKCIÓK
* */
// Katalógus hivatkozása
function catalog_url()
{
    return '/';
}
