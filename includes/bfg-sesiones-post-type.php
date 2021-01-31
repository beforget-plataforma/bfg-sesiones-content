<?php
if ( ! defined( 'ABSPATH' ) ) exit;
add_action( 'init', 'bfg_create_post_type_sesiones' );

function bfg_create_post_type_sesiones() {
  $labels = array(
    'name'                => _x( 'Sesiones', 'Post Type General Name', 'bfg-plataform' ),
    'singular_name'       => _x( 'Sesiones', 'Post Type Singular Name', 'bfg-plataform' ),
    'menu_name'           => __( 'Sesiones', 'bfg-plataform' ),
    'parent_item_colon'   => __( 'Sesión Padre', 'bfg-plataform' ),
    'all_items'           => __( 'Todas las Sesiones', 'bfg-plataform' ),
    'view_item'           => __( 'Ver Sesión', 'bfg-plataform' ),
    'add_new_item'        => __( 'Agregar Nueva Sesión', 'bfg-plataform' ),
    'add_new'             => __( 'Agregar Nueva Sesión', 'bfg-plataform' ),
    'edit_item'           => __( 'Editar Sesiones', 'bfg-plataform' ),
    'update_item'         => __( 'Actualizar Sesión', 'bfg-plataform' ),
    'search_items'        => __( 'Buscar Sesiones', 'bfg-plataform' ),
    'not_found'           => __( 'No encontrado', 'bfg-plataform' ),
    'not_found_in_trash'  => __( 'No encontrado en la papelera', 'bfg-plataform' ),
  );
  
  $args = array(
    'labels'              => $labels,
    'supports'            => array( 'title', 'editor', 'excerpt', 'author', 'thumbnail'),
    /* Un Post Type hierarchical es como las paginas y puede tener padres e hijos.
    * Uno sin hierarchical es como los posts
    */
    'hierarchical'        => true,
    'public'              => true,
    'show_ui'             => true,
    'show_in_menu'        => true,
    'rewrite'             => array( 'slug' => 'sesiones'),
		'with_front' => false,
    'show_in_nav_menus'   => true,
    'show_in_admin_bar'   => true,
    'menu_position'       => 5,
    'menu_icon'           => 'dashicons-video-alt3',
    'can_export'          => true,
    'has_archive'         => false,
    'exclude_from_search' => true,
    'publicly_queryable'  => true,
    'capability_type'    => 'page',
    'map_meta_cap'       => true,
  );
  register_post_type( 'sesiones', $args );

}
// function wpa_show_permalinks( $post_link, $post ){
//   if ( is_object( $post ) && $post->post_type == 'sesiones' ){
//       $terms = wp_get_object_terms( $post->ID, 'categoria' );
//       if( $terms ){
//           return str_replace( '%categoria%' , $terms[0]->slug , $post_link );
//       }
//   }
//   return $post_link;
// }
// add_filter( 'post_type_link', 'wpa_show_permalinks', 1, 2 );

// Taxonomía tipo de sesión
function bfg_tipo_sesion() {
  $labels = array(
    'name'              => _x( 'Tipo de sesión', 'Tipo de sesión' ),
    'singular_name'     => _x( 'Tipo de sesión', 'tTipo de sesión' ),
    'search_items'      => __( 'Buscar Tipo de sesión' ),
    'all_items'         => __( 'Todas las Tipo de sesión' ),
    'parent_item'       => __( 'Tipo de sesión Padre' ),
    'parent_item_colon' => __( 'Tipo de sesión Padre:' ),
    'edit_item'         => __( 'Editar Tipo de sesión' ),
    'update_item'       => __( 'Actualizar Tipo de sesión' ),
    'add_new_item'      => __( 'Agregar Nuevo Tipo de sesión' ),
    'new_item_name'     => __( 'Nuevo Tipo de sesión' ),
    'menu_name'         => __( 'Tipo de sesión' ),
  );

  $args = array(
    'hierarchical'      => true,
    'exclude_from_search'   => false,
    'has_archive'           => false,
		'with_front' => false,
    'show_in_rest'          => true,
    'labels'            => $labels,
    'show_ui'           => true,
    'show_admin_column' => true,
    'query_var'         => true,
    'rewrite' => array( 'slug' => 'tipo-de-sesiones' ),
  );

  register_taxonomy( 'tipo-sesion', array( 'sesiones'), $args );
}
add_action( 'init', 'bfg_tipo_sesion' );


// Taxonomía categoría

function bfg_categoria_sesion() {
  $labels = array(
    'name'              => _x( 'Tipo de categoría', 'Categoría' ),
    'singular_name'     => _x( 'Tipo de categoría', 'Categoría' ),
    'search_items'      => __( 'Buscar categoría' ),
    'all_items'         => __( 'Todas las categorías' ),
    'parent_item'       => __( 'Tipo de categoría Padre' ),
    'parent_item_colon' => __( 'Tipo de categoría Padre:' ),
    'edit_item'         => __( 'Editar categoría' ),
    'update_item'       => __( 'Actualizar categoría' ),
    'add_new_item'      => __( 'Agregar Nueva categoría' ),
    'new_item_name'     => __( 'Nuevo categoría' ),
    'menu_name'         => __( 'Categorías' ),
  );

  $args = array(
    'hierarchical'      => true,
    'labels'            => $labels,
    'show_ui'           => true,
    'has_archive'           => false,
		'with_front' => false,
    'show_admin_column' => true,
    'query_var'         => true,
    'rewrite' => array( 'slug' => 'categoria' ),
  );

  register_taxonomy( 'categoria-sesion', array( 'sesiones'), $args );
}
add_action( 'init', 'bfg_categoria_sesion' );

// En posttype.php
/**
 * Flush rewrite rules on activation.
 */
// function rewrite_flush() {
// 	bfg_create_post_type_sesiones();
// 	flush_rewrite_rules();
// }
