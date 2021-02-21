<?php
/*
Plugin Name: BFG Sesiones Content
Plugin URI:
Description: Post type personalizado para el contenido de tipo Sesiones
Version:     1.0
Author:      Beforget
Author URI:  https://beforget.com/
License:     GPL2
License URI: https://www.gnu.org/licenses/gpl-2.0.html
*/

if ( ! defined( 'ABSPATH' ) ) exit;

require_once plugin_dir_path(__FILE__) . 'includes/bfg-sesiones-style-register.php';
require_once plugin_dir_path(__FILE__) . 'includes/bfg-sesiones-post-type.php';
require_once plugin_dir_path(__FILE__) . 'includes/bfg-sesiones-display-profile.php';
require_once plugin_dir_path(__FILE__) . 'includes/bfg-sesiones-register-scripts.php';
require_once plugin_dir_path(__FILE__) . 'includes/bfg-sesiones-shortcode.php';
require_once plugin_dir_path(__FILE__) . 'includes/bfg-sesiones-display-learndash.php';
require_once plugin_dir_path(__FILE__) . 'includes/bfg-sesiones-api.php';
require_once plugin_dir_path(__FILE__) . 'includes/bfg-sesiones-rest-api.php';

// register_activation_hook(__FILE__, 'rewrite_flush');