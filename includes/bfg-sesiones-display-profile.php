<?php
if ( ! defined( 'ABSPATH' ) ) exit;


/**
 * Filtramos los post que mostramos en Talks por cada usuario
 */
function bfg_sesiones_posts()
{
	$user_id = bp_displayed_user_id();
	$authorId = get_the_author_meta( 'ID' );
	$profileUserID = bp_displayed_user_id();
	$current_user = wp_get_current_user();

	$args = array(
		// 'author'	=>  $profileUserID,
		'post_type' => 'sesiones',
		'meta_query' => array(
			array(
				'key' => 'ponente',
				'value' => $profileUserID,
				'compare' => 'REGEXP'
			)
		)
	);
	$the_query = new WP_Query($args);
	if ($the_query->have_posts()) {
		?>
		<div class="wrapper-post-profile flex bfg-flex-grap">
		<?
		while ($the_query->have_posts()) {
			$the_query->the_post();
			$terms = get_the_terms( $post->ID, 'tipo-sesion' );
			$users = get_field("ponente");
			$userName = xprofile_get_field_data('1', $users[0]->ID);
			$userLastName = xprofile_get_field_data('2', $users[0]->ID);
		?>
			<div class="bfg-item-sesiones">
				<a class="no-color" href="<?php the_permalink(); ?>">
				<div class="bfg-header-cover-sesiones item-profile flex" style="background-color:<?php the_field('brand_color'); ?>">
						<span class="bfg-icon-smile inprofile">
								<img src="<? echo wp_get_attachment_url(171); ?>" alt="">
						</span>
						<hgroup>
							<div class="bfg-container-title item-profile ">
								<?php
									$title = get_the_title();
									$short_title = wp_trim_words( $title, 12, '...' );
								?>
								<h1><? echo $title; ?></h1>
							</div>
								<?
									$args = array( 
										'item_id' => get_the_author_meta('ID'), 
										'object' => '', 
										'type' => '' 
									); 
								?>
								<?php
								$users = get_field("ponente");
								$ponenteName = $users[0]->display_name;
								$ponenteAvatar = get_avatar($users[0]->ID);

								?>
								<div class="bfg-profile-author bfg-icon-small">
								<?
									echo $ponenteAvatar;
								?>
								<span><? echo $userName . ' ' . $userLastName; ?></span>
							</div>
						</hgroup>
					</div>
					<span class="line-divisor <? echo $terms[0]->slug; ?>"></span>
					<div class="bfg-content-inprofile">

							<?php
							the_excerpt();
/* 								echo wp_trim_words( get_the_content(), 30, '...' ); */
							?>

					</div>
					<div class="line footer-date"></div>
					<div class="flex bfg-footer-item">
						<div class="bfg-icon-date inprofile">
										<img src="<? echo wp_get_attachment_url(247); ?>" alt="">
							</div>
							<div class="bfg-block time-footer">
								<p>
									<?php
										$unixtimestamp = strtotime( get_field('hora_de_la_sesion') );
										echo date_i18n( "l d F", $unixtimestamp );
									?>
								</p>
							</div>
					</div>
				</a>
				
			</div>
			<?
		}
		?>
		</div>
		<?
		wp_reset_postdata();
	} else {
		?>
				<aside class="bp-feedback bp-messages info">
						<span class="bp-icon" aria-hidden="true"></span>
						<p>
							Aún no has publicado ninguna sesión.
						</p>
				</aside>
		<?php
	}
	// return $string;
}
add_shortcode('sesiones-posts', 'bfg_sesiones_posts');