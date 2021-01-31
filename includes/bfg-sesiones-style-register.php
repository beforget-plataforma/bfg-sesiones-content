<?
if ( ! defined( 'ABSPATH' ) ) exit;

function wpdocs_register_plugin_styles_sessiones() {
	wp_register_style( 'bfg-style-sesiones', plugins_url( '/bfg-sesiones-content/assets/css/bfg-style.css' ) );
	wp_enqueue_style( 'bfg-style-sesiones' );
}
// Register style sheet.
add_action( 'wp_enqueue_scripts', 'wpdocs_register_plugin_styles_sessiones' );