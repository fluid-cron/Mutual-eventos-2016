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
		$estado = 1;
		if( asistioEvento($email,$evento) ) {

			if( $email!="" && $evento!="" ) {

				 $certificado = esc_url(get_permalink(get_page_by_title('descarga certificado')))."?email=".$email."&evento=".$evento;
				 $galeria     = esc_url(get_permalink(get_page_by_title('galeria')))."?email=".$email."&evento=".$evento;
				 $encuesta    = esc_url(get_permalink(get_page_by_title('encuesta')))."?email=".$email."&evento=".$evento;

				$posts = get_posts(array(
					'name'      => $evento,
					'post_type' => 'eventos'
				));				
			
				if($posts) {
					foreach($posts as $post) {
						$documentos = get_field('contenedor_archivos');
						$nombre_evento = $post->post_title;

					}
				}		

			}else{
				//echo "No tiene permitido ver tu certificado";
				$estado = 0;
			}

	}else{
		//echo "No tiene permitido ver tu certificado";
		$estado = 0;
	}
	if( $estado==1 ) {

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
					<div class="col-md-1 col-sm-1 hidden-xs">&nbsp;</div>
					<div class="col-md-10 col-sm-10 col-xs-12">
						<h3 class="titulo-descarga-superior"><?php echo strtoupper($nombre_evento); ?></h3>
						<button class="btn-descarga" onclick="location.href='<?php echo $certificado;?>'" >DESCARGAR CERTIFICADO</button>
					</div>
					<div class="col-md-1 col-sm-1 hidden-xs">&nbsp;</div>
				</div>
				<div class="row">
					<div class="col-md-1 col-sm-1 hidden-xs">&nbsp;</div>
					<div class="col-md-10 col-sm-10 col-xs-12">
						<h3 class="titulo-descarga-documentos">DESCARGAR DOCUMENTOS DEL EVENTO</h3>
						<table>
							<tbody>
								<tr>
									<th>Nombre del Archivo</th>
									<th>Descargar</th>
								</tr>
							</tbody>
							<tbody>
								<?php  
									foreach($documentos as $documento) {
										$titulo = $documento['documento']['title'];
										$url    = $documento['documento']['url'];
									
								?>							
								<tr>
									<td><?php echo $titulo; ?></td>
									<td>
										<a href="<?php echo $url; ?>" target="_blank">Descargar</a>
									</td>
								</tr>
								<?php  
									}
								?>								
							</tbody>
						</table>
						<div class="botones">
							<a href="javascript:void(0);" class="btn-ver-encuesta" onclick="location.href='<?php echo $encuesta;?>'" >RESPONDER ENCUESTA</a>
							<a href="javascript:void(0);" class="btn-ver-galeria" onclick="location.href='<?php echo $galeria;?>'" >VER GALERÍA DEL EVENTO</a>
						</div>
					</div>
					<div class="col-md-1 col-sm-1 hidden-xs">&nbsp;</div>
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
						<div class="col-md-1 col-sm-1 hidden-xs">&nbsp;</div>
						<div class="col-md-10 col-sm-10 col-xs-12">
							<h3 class="titulo-descarga-superior">No tienes permitido revisar tu certificado</h3>
						</div>
						<div class="col-md-1 col-sm-1 hidden-xs">&nbsp;</div>
					</div>
					<div class="row">
						<div class="col-md-1 col-sm-1 hidden-xs">&nbsp;</div>
						<div class="col-md-10 col-sm-10 col-xs-12">							
						</div>
						<div class="col-md-1 col-sm-1 hidden-xs">&nbsp;</div>
					</div>
				</div>
			</div>
	</section>	
<?php

}
get_footer();

