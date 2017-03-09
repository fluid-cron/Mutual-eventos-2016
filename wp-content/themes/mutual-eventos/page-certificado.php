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
		if( asistioEvento($email,$evento) ) {

		if( $email!="" && $evento!="" ) {

			 $certificado = esc_url(get_permalink(get_page_by_title('descarga certificado')))."?email=".$email."&evento=".$evento;
			 $galeria     = esc_url(get_permalink(get_page_by_title('galeria')))."?email=".$email."&evento=".$evento;
			 $encuesta     = esc_url(get_permalink(get_page_by_title('encuesta')))."?email=".$email."&evento=".$evento;

?>
			<a href="<?php echo $certificado;?>" >Descargar certificado</a>
			<br>
			<a href="<?php echo $galeria;?>" >Ver galeria</a>
			<br>
			<a href="<?php echo $encuesta;?>" >Reponder encuesta</a>

<?php

		$posts = get_posts(array(
			'name'      => $evento,
			'post_type' => 'eventos'
		));

		echo "<pre>";
		
		if($posts) {
			foreach($posts as $post) {
				$documentos = get_field('contenedor_archivos');
				print_r($documentos);

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
			echo "No tiene permitido ver tu certificado";
		}
	}else{
		echo "No tiene permitido ver tu certificado";
	}

	?>		
<?php
get_footer();
