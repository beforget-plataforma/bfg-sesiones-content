<?php
function getSesionesContentRestApi() {
    $searchTipo = $_GET['tipo'];
    $searchCategory = $_GET['category'];
    $args = array(
      'post_type' => 'sesiones',
      'posts_per_page' => -1,
      'orderbyby' => 'DESC',
    );
    $posts = get_posts($args);
    foreach ( $posts as $post ) {
        setup_postdata( $post );
        $tems = get_term( 1 , 'tipo-sesion' );
        $args = array( 
          'item_id' => get_the_author_meta('ID'),
        ); 
        $dateSesion = get_post_meta($post->ID,'hora_de_la_sesion');
        $date = date("F j, Y", $dateSesion[0]);
        $unixtimestamp = strtotime( $dateSesion[0] );

        $users = get_post_meta($post->ID, 'ponente');
        $ponenteName = get_userdata($users[0][0]);
        $ponenteAvatar = get_avatar($users[0][0]);
        $userName = xprofile_get_field_data('1', $users[0][0]);
        $userLastName = xprofile_get_field_data('2', $users[0][0]);
                
        $listado[] = array(
            'slug' => get_the_terms( $post->ID, 'tipo-sesion' )[0]->slug,
            'tipoSesion' => get_the_terms( $post->ID, 'tipo-sesion' )[0]->slug,
            'categoriaSesion' => get_the_terms( $post->ID, 'categoria-sesion' )[0]->slug,
            'avatar' => ($ponenteName !== false) ? $ponenteAvatar : wp_get_attachment_image( 805 ),
            'nombre' => $post->post_title,
            'content' => wp_strip_all_tags( get_the_content() ),
            'excerpt' => $post->post_excerpt,
            'link' => get_permalink( $post->ID ),
        );
    }
    header("Content-type: application/json");
    echo json_encode( $listado);
    die;
}
function bfg_sesiones_rest_api() {
	do_action('getSesionesContentRestApi');
}
add_shortcode('bfg-sesiones-rest-api', 'bfg_sesiones_rest_api');
?>