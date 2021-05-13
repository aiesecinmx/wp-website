<?php
/**
 * Astra Child Theme functions and definitions
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package Astra Child
 * @since 1.0.0
 */

/**
 * Define Constants
 */
define( 'CHILD_THEME_ASTRA_CHILD_VERSION', '1.0.0' );

/**
 * Enqueue styles
 */
function child_enqueue_styles() {

	wp_enqueue_style( 'astra-child-theme-css', get_stylesheet_directory_uri() . '/style.css', array('astra-theme-css'), CHILD_THEME_ASTRA_CHILD_VERSION, 'all' );

}

add_action( 'wp_enqueue_scripts', 'child_enqueue_styles', 15 );

add_action( 'elementor_pro/forms/validation/tel', function( $field, $record, $ajax_handler ) {
	$removed_dashes = str_replace('-', '', $field['value']);
	if ( preg_match( '/^[0-9]{10}$/', $removed_dashes ) !== 1 ) {
		$ajax_handler->add_error( $field['id'], "El n\u{00FA}mero de tel\u{00E9}fono debe tener 10 d\u{00ED}gitos." );
  }
}, 10, 3 );

add_action( 'elementor_pro/forms/validation/password', function( $field, $record, $ajax_handler ) {
	if ( preg_match( '/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).{8,}$/', $field['value'] ) !== 1 ) {
		$ajax_handler->add_error( $field['id'], "La contrase\u{00F1}a debe tener m\u{00ED}nimo 8 caracteres e incluir may\u{00FA}sculas, min\u{00FA}sculas y n\u{00FA}meros" );
  }
}, 10, 3 );
