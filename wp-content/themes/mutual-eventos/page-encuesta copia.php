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
	?>
		<form class="dissapear" id="form-encuesta" method="post">

			<input name="email" type="hidden" value="<?php echo $email; ?>">
			<input name="action" type="hidden" value="guardarEncuesta" >
			<input name="evento" type="hidden" value="<?php echo $evento;?>" >

			<div class="questions">
				
				<div class="question">
					<p>1. ¿Pregunta 1? *</p>
					<ul>
						<li>
							<label>1</label>
							<input type="radio" value="1" name="respuesta_1">
						</li>
						<li>
							<label>2</label>
							<input type="radio" value="2" name="respuesta_1">
						</li>
						<li>
							<label>3</label>
							<input type="radio" value="3" name="respuesta_1">
						</li>
						<li>
							<label>4</label>
							<input type="radio" value="4" name="respuesta_1">
						</li>
					</ul>
				</div>
				
				<div class="question">
					<p>2. ¿Pregunta 2? *</p>
					<ul>
						<li>
							<label>Sí</label>
							<input type="radio" value="si" name="respuesta_2">
						</li>
						<li>
							<label>No</label>
							<input type="radio" value="no" name="respuesta_2">
						</li>
					</ul>
				</div>

				<div class="question">
					<p>3. ¿Pregunta 3? *</p>
					<ul>
						<li>
							<label>1</label>
							<input type="radio" value="1" name="respuesta_3">
						</li>
						<li>
							<label>2</label>
							<input type="radio" value="2" name="respuesta_3">
						</li>
						<li>
							<label>3</label>
							<input type="radio" value="3" name="respuesta_3">
						</li>
						<li>
							<label>4</label>
							<input type="radio" value="4" name="respuesta_3">
						</li>
					</ul>
				</div>

				<div class="question">
					<p>4. ¿Pregunta 4? *</p>
					<ul>
						<li>
							<label>1</label>
							<input type="radio" value="1" name="respuesta_4">
						</li>
						<li>
							<label>2</label>
							<input type="radio" value="2" name="respuesta_4">
						</li>
						<li>
							<label>3</label>
							<input type="radio" value="3" name="respuesta_4">
						</li>
						<li>
							<label>4</label>
							<input type="radio" value="4" name="respuesta_4">
						</li>
					</ul>
				</div>			
							
			</div><!-- .questions -->

			<p>¿Comentarios y sugerencias? Por favor escribe a continuación:</p>

			<textarea rows="6" name="comentario" placeholder="Escribir un comentario..."></textarea>

			<input id="send-button" type="button" value="ENVIAR RESPUESTA" >

		</form>	
	<?php		
		}else if($res==2){
	?>
		<div>No tienes permitido responder esta encuesta</div>
	<?php
		}else{
	?>
		<div>Encuesta ya respondinda</div>
	<?php
		}
		}else{
	?>
		<div>Encuesta no disponible</div>
	<?php
		}
	?>	


<?php
get_footer();
