<?php
if ( ! defined( 'ABSPATH' ) ) exit;


/**
 * Filtramos los post que mostramos en Talks por cada usuario
 */
function bfg_sesiones_posts_learndash($atts)
{
	$category = $atts['category'];
	$user_id = bp_displayed_user_id();
	$authorId = get_the_author_meta( 'ID' );
	$profileUserID = bp_displayed_user_id();
	$current_user = wp_get_current_user();
	$tipoCategoria = $category;

	$args = array(
		'post_type' => 'sesiones',
		'posts_per_page' => 3,
		'orderbyby' => 'DESC',
		'tax_query' => array(
				array(
					'taxonomy' => 'categoria-sesion',
					'field'    => 'slug',
					'terms'    => $tipoCategoria,
				),
			),
	);
	$the_query = new WP_Query($args);
	$output = '';
	if ($the_query->have_posts()) {
		$output .= '<div class="wrapper-post-profile flex bfg-flex-grap">';
		while ( $the_query->have_posts() ) : $the_query->the_post();
				 ob_start();
				 get_template_part( 'template-parts/content-sesiones-learndash', null, array( 
					'category' => $tipoCategoria));
				 $output .= ob_get_clean();
		endwhile;
		wp_reset_postdata();
		if( $show_archive == 'true' ) {
				 $output .= '<div class="full-width align-right">';
				 $output .= '<a class="button-small inverse" href="' . get_home_url() . '/news">See All Archives</a>';
				 $output .= '</div>';
		}
		$output .= '</div>';
	} 
	return $output;
}
add_shortcode('sesiones-posts-learndash', 'bfg_sesiones_posts_learndash');