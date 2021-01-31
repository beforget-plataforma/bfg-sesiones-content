<?php
if ( ! defined( 'ABSPATH' ) ) exit;

function bfg_sesiones_script() {
  $profileUserID = bp_displayed_user_id();
  $current_user = wp_get_current_user();

  // $obj = get_post_type_object( 'proyectos' );
  // var_dump($obj);

  // wp_register_script('bfgSesiones', esc_url(plugins_url('/frontend/dist/bundle.js', dirname(__FILE__) )), true);
  // wp_register_script('bfgSesiones', 'http://localhost:8080/dist/bundle.js' , '', '', true );
  // wp_localize_script('bfgSesiones', 'bpRestApi', array(
  //   'nonce' => wp_create_nonce( 'wp_rest' ),
  //   'sessionUserID' => $current_user->ID,
  //   'profileUserID' => $profileUserID,
  // ));
  wp_register_script('bfgSesiones', esc_url(plugins_url('/frontend/dist/bundle.js?v=3', dirname(__FILE__) )), true);
  wp_localize_script('bfgSesiones', 'wp_pageviews_ajax', array(
    'ajax_url' => admin_url('admin-ajax.php'),
    'nonce' => wp_create_nonce( 'wp-pageviews-nonce' ),
    'is_user_logged_in' => is_user_logged_in()
  ));

  wp_enqueue_script('bfgSesiones');

}

add_action( 'bfg_filter_sesiones_script', 'bfg_sesiones_script' );