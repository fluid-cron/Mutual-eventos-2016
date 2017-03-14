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
		if( $email!="" && $evento!="" ) {

		$res = respondioEncuesta($email,$evento);

		if( $res==0 ) {

			$posts = get_posts(array(
				'name'      => $evento,
				'post_type' => 'eventos'
			));

			if($posts) {
				foreach($posts as $post) {
					$nombre_evento = $post->post_title;
					$banner = get_field("imagen");
				}
			}

	?>

<section>
		<div class="container">
			<div class="col-md-12">
				<div class="row">
					<div class="col-md-12">
						<img src="<?php echo $banner; ?>"/>
					</div>
				</div>
				<div class="row" id="encuesta-content" >
					<div class="col-md-12">
						<h3 class="titulo-encuesta">Te invitamos a responder de 1 a 7, donde 7 se asocia a la mejor calificación, las siguientes preguntas:</h3>
						<span class="encuesta">Las preguntas marcadas con * son obligatorias</span>
						<form class="dissapear" id="form-encuesta" method="post">
						<div class="preguntas">									
							<p>1. ¿Fue de tu interés el tema del evento? *</p>
							<ul class="preguntas-calificacion">
								<li>
									<label>Si</label>
									<input type="radio" value="si" name="respuesta_1">
								</li>
								<li>
									<label>No</label>
									<input type="radio" value="no" name="respuesta_1">
								</li>
							</ul>								
							<p>2. ¿Qué te pareció el evento al que asististe, califícalo? *</p>
							<ul class="preguntas-calificacion">
								<li>
									<label>1</label>
									<input type="radio" value="1" name="respuesta_2">
								</li>
								<li>
									<label>2</label>
									<input type="radio" value="2" name="respuesta_2">
								</li>
								<li>
									<label>3</label>
									<input type="radio" value="3" name="respuesta_2">
								</li>
								<li>
									<label>4</label>
									<input type="radio" value="4" name="respuesta_2">
								</li>
								<li>
									<label>5</label>
									<input type="radio" value="5" name="respuesta_2">
								</li>			
								<li>
									<label>6</label>
									<input type="radio" value="6" name="respuesta_2">
								</li>
								<li>
									<label>7</label>
									<input type="radio" value="7" name="respuesta_2">
								</li>																					
							</ul>												
							<p>3. ¿Te gustaría recibir información sobre otros eventos que realizaremos? *</p>
							<ul class="preguntas-calificacion">
								<li>
									<label>Si</label>
									<input type="radio" value="si" name="respuesta_3">
								</li>
								<li>
									<label>No</label>
									<input type="radio" value="no" name="respuesta_3">
								</li>															
							</ul>
							<p>4. ¿Has asistido a otro evento como estos de Mutual de Seguridad? *</p>
							<ul class="preguntas-calificacion">
								<li>
									<label>Si</label>
									<input type="radio" value="si" name="respuesta_4">
								</li>
								<li>
									<label>No</label>
									<input type="radio" value="no" name="respuesta_4">
								</li>								
							</ul>							
							<p>4. ¿Comentarios y sugerencias? Por favor escribe a continuación: </p>
							<textarea rows="4" name="comentario" placeholder="Escribir un comentario..."></textarea>
							<button id="send-button" class="btn-enviar">ENVIAR</button>
							
						</div>
						<input name="email" type="hidden" value="<?php echo $email; ?>">
						<input name="action" type="hidden" value="guardarEncuesta" >
						<input name="evento" type="hidden" value="<?php echo $evento;?>" >
						</form>
					</div>					
				</div>

				<div class="row" id="gracias-encuesta" style="display: none;" >
					<div class="col-md-12">
						<h3 class="titulo-encuesta">Encuesta enviada con éxito</h3>
					</div>
				</div>					
				<div class="row" id="ya-respondida-encuesta" style="display: none;">
					<div class="col-md-12">
						<h3 class="titulo-encuesta">Encuesta ya respondida</h3>
					</div>
				</div>				

			</div>
		</div>
	</section>

	<?php		
		}else if($res==2){
	?>

	<section>
		<div class="container">
			<div class="col-md-12">
				<div class="row">
					<div class="col-md-12">
						<img src="<?php echo get_field("banner_principal","option"); ?>"/>
					</div>
				</div>
				<div class="row">
					<div class="col-md-12">
						<h3 class="titulo-encuesta">No tienes permitido responder esta encuesta</h3>
					</div>
				</div>
			</div>
		</div>
	</section>

	<?php
		}else{
	?>
	<section>
		<div class="container">
			<div class="col-md-12">
				<div class="row">
					<div class="col-md-12">
						<img src="<?php echo get_field("banner_principal","option"); ?>"/>
					</div>
				</div>
				<div class="row">
					<div class="col-md-12">
						<h3 class="titulo-encuesta">Encuesta ya respondinda</h3>
					</div>
				</div>
			</div>
		</div>
	</section>		
	<?php
		}
		}else{
	?>
	<section>
		<div class="container">
			<div class="col-md-12">
				<div class="row">
					<div class="col-md-12">
						<img src="<?php echo get_field("banner_principal","option"); ?>"/>
					</div>
				</div>
				<div class="row">
					<div class="col-md-12">
						<h3 class="titulo-encuesta">Encuesta no disponible</h3>
					</div>
				</div>
			</div>
		</div>
	</section>		

	<?php
		}
	?>	


<?php
get_footer();
