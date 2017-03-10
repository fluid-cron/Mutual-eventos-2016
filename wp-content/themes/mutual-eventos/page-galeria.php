<?php
/**
 * The template for displaying all pages
 *
 * This is the template that displays all pages by default.
 * Please note that this is the WordPress construct of pages
 * and that other 'pages' on your WordPress site may use a
 * different template.
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package Mutual_eventos
 */

$email  = sanitize_text_field(@$_GET['email']);
$evento = sanitize_text_field(@$_GET['evento']);

get_header(); ?>

	<?php
		$estado=1;
		if( $email!="" && $evento!="" ) {

		$posts = get_posts(array(
			'name'      => $evento,
			'post_type' => 'eventos'
		));
		
			if($posts) {
				foreach($posts as $post) {
					$images = get_field('galeria');

					$nombre_evento = $post->post_title;
					$descripcion   = $post->post_content;
					/*
					echo $post->post_excerpt."<br>";
					echo get_field('fecha')."<br>";
					echo get_field('lugar')."<br>";
					echo get_field('imagen')."<br>";				
					echo get_permalink($post->ID)."<br>";
					echo '<a href="'.get_permalink($post->ID).'">'.$post->post_title.'</a>';
					*/

				}
			}else{
				$estado = 0;
			}

		}else{
			//echo "No tiene permitido acceder a esta url";
			$estado = 0;
		}

	?>		
	<section>
		<div class="container">
			<div class="col-md-12">
				<div class="row">
					<div class="col-md-12">
						<?php if( $estado==1 ): ?>
						<h3 class="titulo-galeria"><?php echo strtoupper($nombre_evento); ?></h3>
						<p><?php echo $descripcion; ?></p>
						<div class="slider-for">
						<?php
							foreach ($images as $key) {
								echo '<div><img src="'.$key['url'].'"></div>';
							}
						?>
						</div>
						<div class="slider-nav">
						<?php  
							foreach ($images as $key) {
								echo '<div><img src="'.$key['sizes']['thumbnail'].'"></div>';
							}
						?>
						</div>
						<?php else: ?>
							<h3 class="titulo-galeria">No tiene permisos para visualizar este evento.</h3>
						<?php endif; ?>
					</div>
				</div>
			</div>
		</div>
	</section>	
<?php
get_footer();
