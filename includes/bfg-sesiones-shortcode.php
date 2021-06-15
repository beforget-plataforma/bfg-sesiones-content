<?php
if ( ! defined( 'ABSPATH' ) ) exit;


/**
 * Filtramos los post que mostramos en Talks por cada usuario
 */
function bfg_sesiones_shortcode($atts)
{
	$category = $atts['category'];
	$layout = $atts['layout'];
	$title = $atts['title'];
	$user_id = bp_displayed_user_id();
	$authorId = get_the_author_meta( 'ID' );
	$profileUserID = bp_displayed_user_id();
	$current_user = wp_get_current_user();
	$tipoCategoria = $category;

  $odsCategory = xprofile_get_field_data('341', $current_user->ID, $multi_format = 'comma');
  $odsCategoryText = xprofile_get_field_data('341', $current_user->ID);
	$classSlide = '';

	$layoutLibrery = ["list", "block". "slide"];
	$templateLayout = "";
	
	if($layout === 'list') {
		$templateLayout = 'template-parts/content-sesiones-list';
	} else if($layout === 'slide'){
		$templateLayout = 'template-parts/content-sesiones-slide';
		$classSlide = 'flex bfg-flex-grap bfg-slick-slider bfg-slide-sesiones';
	} else {
		$templateLayout = 'template-parts/content-sesiones-list';

	}
	$splitStringTemp = [];
	$categorySplit = explode(",", $odsCategory);
	foreach ($categorySplit as &$valor) {
		$splitString = explode('categoria/', $valor);
		$textCategpryArray = explode('/"', $splitString[1]);
		array_push($splitStringTemp, $textCategpryArray[0]);
	}

	foreach ($splitStringTemp as &$valor) {
		$tempTerms[] = $valor;
	}
	$args = array(
		'post_type' => 'sesiones',
		'posts_per_page' => 10,
		'hide_empty' => false,
		'orderby' => 'slug',
		'order' => 'ASC',
	);
	$the_query = new WP_Query($args);
	$termsText = implode(' ', $odsCategoryText);
	$output = '';

	if ($the_query->have_posts()) {
		$output .= '<div class="bfg-shorcode-container bb-block-header dash-bfg-slide">';
		$output .= '<div class="elementor-widget-container">';
		$output .= '<div class="wrapper-post-profile  '.$classSlide.'">';
		while ( $the_query->have_posts() ) : $the_query->the_post();
				 ob_start();
				 get_template_part( $templateLayout );
				 $output .= ob_get_clean();
		endwhile;
		wp_reset_postdata();
		if( $show_archive == 'true' ) {
				 $output .= '<div class="full-width align-right">';
				 $output .= '<a class="button-small inverse" href="' . get_home_url() . '/news">See All Archives</a>';
				 $output .= '</div>';
		}
		$output .= '</div>';
		$output .= '</div>';
		$output .= '</div>';
	} 
	do_action('bfg_filter_sesiones_slick_script');
	return $output;
}

add_shortcode('sesiones-shortcode', 'bfg_sesiones_shortcode');