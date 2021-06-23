<?php
if ( ! defined( 'ABSPATH' ) ) exit;


/**
 * Filtramos los post que mostramos en Talks por cada usuario
 */
function sesiones_posts()
{
	$user_id = bp_displayed_user_id();
	$profileUserID = bp_displayed_user_id();
	$count = 0;
	$countRest = 0;
	$existPonent = false;

	$args = array(
		'post_type' => 'sesiones',
		'posts_per_page' => -1,
		'orderbyby' => 'DESC',
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
			$ponente = get_post_meta( get_the_ID(), 'ponente', true);
			$participantes = get_post_meta( get_the_ID(), 'ponente', true);
	
			?>
  <? if($participantes): ?>
		<?
			if(false !== array_search($profileUserID, $participantes)) {
				$existPonent = true;
				?>
				<div class="bfg-item-sesiones">
					<a class="no-color" href="<?php the_permalink(); ?>">
						<div class="bfg-header-cover-sesiones item-profile flex"
							style="background-color:<?php the_field('brand_color'); ?>">
							<span class="bfg-icon-smile inprofile">
								<img src="<? echo wp_get_attachment_url(171); ?>" alt="">
							</span>
							<hgroup>
								<div class="bfg-container-title item-profile ">
									<?php
										$title = get_the_title();
										$short_title = wp_trim_words( $title, 12, '...' );
									?>
									<h1>
										<? echo $title; ?>
									</h1>
								</div>
								<?
									$args = array( 
										'item_id' => get_the_author_meta('ID')
									); 
								?>
								<?php
									$users = get_field("ponente");
									$ponenteName = $users[0]->display_name;
									$userName = xprofile_get_field_data('1', $participantes[0]);
									$userLastName = xprofile_get_field_data('2', $participantes[0]);
									$ponenteAvatar = get_avatar($participantes[0]);

									?>
								<div class="bfg-profile-author bfg-icon-small">
									<?
										echo $ponenteAvatar;
									?>
									<span>
										<? echo $userName . ' ' . $userLastName; ?>
									</span>
								</div>
							</hgroup>
						</div>
						<span class="line-divisor <? echo $terms[0]->slug; ?>"></span>
						<div class="bfg-content-inprofile">
							<?php
								the_excerpt();
							?>
						</div>
						<div class="line footer-date"></div>
						<div class="flex bfg-date-sesion">
							<div class="bfg-date-wrapper">
								<div class="bfg-icon-date inprofile">
									<img src="<? echo wp_get_attachment_url(247); ?>" alt="">
								</div>
								<div class="bfg-block time-footer">
										<?php
											$unixtimestamp = strtotime( get_field('hora_de_la_sesion') );
											echo date_i18n( "d / m / Y", $unixtimestamp );
										?>
								</div>
							</div>
							<div class="bfg-miembros-proyecto flex bfg-flex-grap">
								<?
									$index = 0;
									foreach($participantes as $userID){
										$userName = xprofile_get_field_data('1', $userID);
										$args = array( 
											'item_id' => $userID
										);
										if($index != 0) {
											echo bp_core_fetch_avatar($args);
										}
										$index ++;
									}
								?>
							</div>
						</div>
					</a>
				</div>
			<?
		} else {
			// print($countRest);
			// print($the_query->found_posts);
			$countRest ++;
		}
	?>
  <? else: ?>
		
		
  <? endif; ?>
		<? if((($count + 1) == $the_query->found_posts) && !$existPonent){ ?>
			<aside class="bp-feedback bp-messages info">
				<span class="bp-icon" aria-hidden="true"></span>
				<p>
					Aún no has publicado ninguna sesión.
				</p>
			</aside>
			<?
			}
			$count ++;
		?>
  <? } 
	?>
</div>
<?
	wp_reset_postdata();
} else {
		?>
			<!-- <aside class="bp-feedback bp-messages info">
				<span class="bp-icon" aria-hidden="true"></span>
				<p>
					Aún no has publicado ninguna sesión.
				</p>
			</aside> -->
		<?php
	}
}
add_shortcode('sesiones-posts', 'sesiones_posts');