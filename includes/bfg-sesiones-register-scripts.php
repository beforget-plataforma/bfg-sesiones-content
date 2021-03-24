<?php
if ( ! defined( 'ABSPATH' ) ) exit;

function bfg_sesiones_script() {
  $profileUserID = bp_displayed_user_id();
  $current_user = wp_get_current_user();
  $termsType = get_terms( array(
    'taxonomy' => 'tipo-sesion',
     'order' => 'DESC',
  ));
  $termsCategory = get_terms( array(
      'taxonomy' => 'categoria-sesion',
      'order' => 'DESC',
  ));

  wp_register_script('bfgSesiones', esc_url(plugins_url('/frontend/dist/bundle.js?v=34', dirname(__FILE__) )), true);
  wp_localize_script('bfgSesiones', 'wp_pageviews_ajax', array(
    'ajax_url' => admin_url('admin-ajax.php'),
    'nonce' => wp_create_nonce( 'wp-pageviews-nonce' ),
    'taxSesionesType' => $termsType,
    'taxSesionesCat' => $termsCategory,
    'currentSite' => gethostname(),
    'is_user_logged_in' => is_user_logged_in()
  ));

  wp_enqueue_script('bfgSesiones');

}

add_action( 'bfg_filter_sesiones_script', 'bfg_sesiones_script' );