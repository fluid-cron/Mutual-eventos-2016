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

$email  = sanitize_text_field($_GET['email']);
$evento = sanitize_text_field($_GET['evento']);

get_header(); ?>

	<?php
		if( $email!="" && $evento!="" ) {

		$posts = get_posts(array(
			'name'      => $evento,
			'post_type' => 'eventos'
		));

		echo "<pre>";
		print_r($posts);
		
		if($posts) {
			foreach($posts as $post) {
				$images = get_field('galeria');
				$documentos = get_field('documento');
				print_r($images);

				foreach ($images as $key) {
					//echo $key['url'];
				}

				/*
				echo $post->post_title."<br>";
				echo $post->post_excerpt."<br>";
				echo $post->post_content."<br>";
				echo get_field('fecha')."<br>";
				echo get_field('lugar')."<br>";
				echo get_field('imagen')."<br>";				
				echo get_permalink($post->ID)."<br>";
				echo '<a href="'.get_permalink($post->ID).'">'.$post->post_title.'</a>';
				*/
			}
		}		

		}else{
			echo "No tiene permitido acceder a esta url";
		}

	?>		
<?php
get_footer();
