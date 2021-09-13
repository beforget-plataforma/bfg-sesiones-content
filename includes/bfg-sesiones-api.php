<?php
function searchPostContent() {
  $searchTipo = $_POST['searchTipo'];
  $searchCategory = $_POST['searchCategory'];
  $type = $_POST['type'];
  $tipoSesion = explode(",", $searchTipo);
  $tipoCategoria = explode(",", $searchCategory);

  $args = array(
      'post_type' => 'sesiones',
      'posts_per_page' => -1,
      'orderbyby' => 'DESC',
      'tax_query' => array(
        'relation' => 'OR',
          array(
            'taxonomy' => 'tipo-sesion',
            'field'    => 'slug',
            'terms'    => $tipoSesion,
          ),
          array(
            'taxonomy' => 'categoria-sesion',
            'field'    => 'slug',
            'terms'    => $tipoCategoria,
          ),
        ),
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
        $ponentesList = [];
        foreach ($users as $key => $value) {
          foreach ($value as $key2 => $value3) {
            array_push($ponentesList, get_avatar($value3)); 
          }
        }

        $listado[] = array(
            'objeto' =>$post,
            'slug' => get_the_terms( $post->ID, 'tipo-sesion' )[0]->slug,
            'tipoSesion' => get_the_terms( $post->ID, 'tipo-sesion' )[0]->slug,
            'categoriaSesion' => get_the_terms( $post->ID, 'categoria-sesion' )[0]->slug,
            'id'   => $post->ID,
            'bgColor' => get_post_meta($post->ID, 'brand_color'),
            'smileIcon' => wp_get_attachment_url(171),
            'dateIcon' => get_stylesheet_directory_uri() . '/assets/images/bfg-icon-date.png',
            'avatar' => ($ponenteName !== false) ? $ponenteAvatar : wp_get_attachment_image( 805 ),
            'nombre' => $post->post_title,
            'authorDefault' => 'BeForGet',
            'author' => $ponenteName->display_name,
            'authorName' => $userName,
            'authorLastName' => $userLastName,
            'imagen' => get_the_post_thumbnail($post->ID),
            'content' => wp_trim_words( $post->post_content, 30, '...' ),
            'excerpt' => wp_trim_words( $post->post_excerpt, 40 ),
            'link' => get_permalink( $post->ID ),
            'date' => date_i18n( "d / m / Y", $unixtimestamp ),
            'type' => $post->post_type,
            'ponentes' => $ponentesList
        );
    }
    header("Content-type: application/json");
    echo json_encode( $listado);
    die;
}
add_action('wp_ajax_nopriv_searchPostContent', 'searchPostContent');
add_action('wp_ajax_searchPostContent', 'searchPostContent');
?>