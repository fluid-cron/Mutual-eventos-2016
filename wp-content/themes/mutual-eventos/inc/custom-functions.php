<?php

$evento_option = get_field('evento_activo','option');
$evento_nombre = $evento_option->post_title;
$evento_activo = $evento_option->post_name;

add_action( 'wp_ajax_guardarInscripcion', 'guardarInscripcion' );
add_action( 'wp_ajax_nopriv_guardarInscripcion', 'guardarInscripcion' );

function guardarInscripcion() {

	global $wpdb;
	global $evento_activo;
	global $evento_nombre;

	$email = sanitize_text_field($_POST["email"]);

	$query = "SELECT email FROM {$wpdb->prefix}usuarios_mutual WHERE email='$email' AND evento='$evento_activo'";
	$result = $wpdb->get_results($query);

	$usuario_mutual = count($result);

	if( $usuario_mutual>0 ) {

		//echo "existe en la base de mutual puede inscribirse";

		$query = "SELECT email FROM {$wpdb->prefix}inscripcion_eventos WHERE email='$email' AND evento='$evento_activo'";
		$result = $wpdb->get_results($query);

		$usuario_inscripcion = count($result);

		if( $usuario_inscripcion==0 ) {

			//echo "se puede inscribir a evento";

			if( $email!="" && $evento_activo!="" ) {

				$qr = generarQR($email,$evento_activo);

				if( $qr!="error" ) {

					$query = "SELECT * FROM {$wpdb->prefix}usuarios_mutual WHERE email='$email' AND evento='$evento_activo'";
					$result = $wpdb->get_row($query);					
					
					$wpdb->insert(
						$wpdb->prefix.'inscripcion_eventos',
						array(
							'nombre'   => $result->nombre,
							'cargo'    => $result->cargo,
							'empresa'  => $result->empresa,
							'email'    => $email,
							'telefono' => $result->telefono,
							'evento'   => $evento_activo,
							'qr'       => $qr
						),
						array(
							'%s',
							'%s',
							'%s',
							'%s',
							'%s',
							'%s',
							'%s'
						)
					);

					$args = array(
						'post_type'	=> 'eventos',
						'name'		=> $evento_activo
					);
					$the_query = new WP_Query( $args );

					if( $the_query->have_posts() ):
						while( $the_query->have_posts() ) : $the_query->the_post();
							$banner = get_field("header_email");
						endwhile;
					endif;								

					$url = get_template_directory_uri().'/mail/';

					$body    = file_get_contents(get_template_directory_uri().'/mail/index.html');
					$body    = str_replace("[EVENTO]",$evento_nombre,$body);
					$body    = str_replace("[NOMBRE]",$result->nombre,$body);
					$body    = str_replace("[URL]",$url,$body);
					$body    = str_replace("[BANNER]",$banner,$body);
					$body    = str_replace("[QR]",get_template_directory_uri()."/temp/qr/".$qr,$body);
					$headers = array('Content-Type: text/html; charset=UTF-8');

					$mailResult = false;
					$mailResult = wp_mail($email,'Inscripción al evento '.$evento_nombre, $body ,$headers);					

					if( $mailResult ) {
						$mailResult = "ok";
					}else{
						$mailResult = "error";
					}

					$wpdb->insert(
						$wpdb->prefix.'log_mail',
						array(
							'nombre'      => $result->nombre,
							'evento'      => $evento_nombre,
							'evento_slug' => $evento_activo,
							'email'       => $email,
							'banner'      => $banner,
							'estado'      => $mailResult,
							'qr'          => get_template_directory_uri()."/temp/qr/".$qr						),
						array(
							'%s',
							'%s',
							'%s',
							'%s',
							'%s',
							'%s',
							'%s'
						)
					);					

					echo 1;
				}else{
					echo 0;
				}			

			}else{
				echo 0;
			}

		}else{
			//echo "ya esta inscrito a el evento";
			echo 2;
		}

	}else{
		//echo "No existe en la base de mutual, no puede inscribirse";
		echo 3;
	}

	die;

}

//se usa en index para verificar si ya se inscribio al evento actual
function estaInscrito($email,$evento) {

	global $wpdb;
	//global $evento_activo;

	$query = "SELECT email FROM {$wpdb->prefix}inscripcion_eventos WHERE email='$email' and evento='$evento'";
	$result = $wpdb->get_results($query);

	$inscripcion_eventos = count($result);	

	if( $inscripcion_eventos==0 ) {
		return 0;
	}else{
		return 1;
	}

}

add_action( 'wp_ajax_guardarEncuesta', 'guardarEncuesta' );
add_action( 'wp_ajax_nopriv_guardarEncuesta', 'guardarEncuesta' );

function guardarEncuesta() {

	global $wpdb;

	$email       = sanitize_text_field($_POST["email"]);
	$respuesta_1 = sanitize_text_field($_POST["respuesta_1"]);
	$respuesta_2 = sanitize_text_field($_POST["respuesta_2"]);
	$respuesta_3 = sanitize_text_field($_POST["respuesta_3"]);
	$respuesta_4 = sanitize_text_field($_POST["respuesta_4"]);
	$comentario  = sanitize_text_field($_POST["comentario"]);
	$evento      = sanitize_text_field($_POST["evento"]);

	if( $email!="" && $evento!="" ) {

		$query = "SELECT email FROM {$wpdb->prefix}encuesta_satisfaccion WHERE email='$email' and evento='$evento'";
		$result = $wpdb->get_results($query);

		$encuesta_satisfaccion = count($result);	
		
		if( $encuesta_satisfaccion==0 ) {

			$wpdb->insert(
				$wpdb->prefix.'encuesta_satisfaccion',
				array(
					'respuesta_1' => $respuesta_1,
					'respuesta_2' => $respuesta_2,
					'respuesta_3' => $respuesta_3,
					'respuesta_4' => $respuesta_4,
					'comentario'  => $comentario,
					'email'       => $email,
					'evento'      => $evento
				),
				array(
					'%s',
					'%s',
					'%s',
					'%s',
					'%s',
					'%s',
					'%s'
				)
			);												

			echo 1;	
		}else{
			echo 2;
		}

	}else{
		echo 0;
	}

	die;

}

//se usa en page-encuesta para verificar si el ususario ya respondio la encuesta
function respondioEncuesta($email,$evento) {

	global $wpdb;

	$query = "SELECT email 
			  FROM {$wpdb->prefix}encuesta_satisfaccion 
			  WHERE email='$email' and evento='$evento'";			  
	$result = $wpdb->get_results($query);

	$encuesta_satisfaccion = count($result);

	if( $encuesta_satisfaccion==0 ) {

		$query = "SELECT email 
				  FROM {$wpdb->prefix}usuarios_mutual_asistencia 
				  WHERE email='$email' and evento='$evento'";			  
		$result = $wpdb->get_results($query);

		$asistencia = count($result);

		if( $asistencia>0 ) {
			return 0;
		}else{
			return 2;
		}
		
	}else{
		return 1;
	}

}

//se usa para verificar si la persona efectivamente asistio al evento en page-certificado
function asistioEvento($email,$evento) {

	global $wpdb;

	$query = "SELECT email 
			  FROM {$wpdb->prefix}usuarios_mutual_asistencia 
			  WHERE email='$email' and evento='$evento'";			  
	$result = $wpdb->get_results($query);

	$asistio = count($result);

	if( $asistio>0 ) {
		return 1;		
	}else{
		return 0;
	}

}

add_action( 'wp_ajax_generarQR', 'generarQR' );
add_action( 'wp_ajax_nopriv_generarQR', 'generarQR' );

function generarQR($email,$evento) {

	require_once(__DIR__.'/libraries/phpqr/qrlib.php');

	global $wpdb;
	global $evento_nombre;	

	$time = time();
	$rand = rand(1,9887657139864654);
	$qr_name = $rand+$time;	

	$query = "SELECT * FROM {$wpdb->prefix}usuarios_mutual WHERE email='$email' AND evento='$evento'";
	$result = $wpdb->get_row($query);

	if( count($result)>0 ) {

		$nombre  = $result->nombre;
		$empresa = $result->empresa;

		$tempDir = get_template_directory().'/temp/qr/'.$qr_name.".png"; 

		$codeContents  = 'Datos Inscripción'."\n"; 
		$codeContents  .= "Nombre: ".$nombre."\n"; 
		$codeContents  .= "Evento: ".$evento_nombre."\n"; 
		$codeContents  .= "Email: ".$email."\n"; 
		$codeContents  .= "Empresa: ".$empresa."\n"; 

		QRcode::png($codeContents, $tempDir, QR_ECLEVEL_L, 8); 

		return $qr_name.".png";


	}else{
		return "error";
	}

	die;

}

function generarPDF($email,$evento) {

	global $wpdb;	

	require_once(__DIR__.'/libraries/TCPDF/tcpdf.php');

	Class MyPdf extends TCPDF {		
		public function Header() { }
		public function Footer() { }
	}

	$query = "SELECT * FROM {$wpdb->prefix}usuarios_mutual_asistencia WHERE email='$email' AND evento='$evento'";
	$result = $wpdb->get_row($query);

	if( count($result)>0 ) {

	$query = "SELECT * FROM {$wpdb->prefix}inscripcion_eventos WHERE email='$email' AND evento='$evento'";
	$datos_usuario = $wpdb->get_row($query);
	
	$nombre = $datos_usuario->nombre;	

	//datos evento
	$args = array(
		'post_type'		=> 'eventos',
		'name'		=> $evento
	);
	$the_query = new WP_Query( $args );

	if( $the_query->have_posts() ):
		while( $the_query->have_posts() ) : $the_query->the_post();
			$nombre_evento = get_the_title();
			$fecha = get_field('fecha');
		endwhile;
	endif;

	$partes_fecha = explode("/",$fecha);

	$meses = array("Enero","Febrero","Marzo","Abril","Mayo","Junio","Julio","Agosto","Septiembre","Octubre","Noviembre","Diciembre");	
	$n = ltrim($partes_fecha[1], '0');
	$fecha_final = ltrim($partes_fecha[0],'0')." de ".$meses[$n-1]." del ".$partes_fecha[2];

	$dia = ltrim(date("d"),'0');
	$mes = $meses[ltrim(date("m"), '0')-1];
	$anho = date("Y");

	$pdf = new MyPdf(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

	$pdf->SetCreator(PDF_CREATOR);
	$pdf->SetAuthor('Mutual');
	$pdf->SetTitle('Certificado');
	$pdf->SetSubject('PDF');
	$pdf->SetKeywords('PDF');

	//$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

	//$pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
	$pdf->SetMargins(0,0,0); // set the margins 
	$pdf->SetHeaderMargin(0);
	$pdf->SetFooterMargin(0);
	$pdf->SetAutoPageBreak(TRUE, -10);
	$pdf->setCellPaddings(0,0,0,0);

	//$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

	//$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

	//$pdf->setFontSubsetting(true);
	//$pdf->SetFont('courierB', '', 14, '', true);

	$pdf->AddPage('L');
	//$pdf->setTextShadow(array('enabled'=>true, 'depth_w'=>0.2, 'depth_h'=>0.2, 'color'=>array(196,196,196), 'opacity'=>1, 'blend_mode'=>'Normal'));

	//left top width heigth
	//$pdf->Image(get_field("logo_header","option") ,0, 0, 100, 100, 'PNG', '', '', true, 300, '');
	$pdf->Image(get_template_directory()."/assets/images/certificado/logo_mutual.png",123, 24, 50, 0, 'PNG', '', '', true, 300, '');
	$pdf->Image(get_template_directory()."/assets/images/certificado/certificado_asistencia.png",77, 86, 150, 0, 'PNG', '', '', true, 300, '');
	$pdf->Image(get_template_directory()."/assets/images/certificado/logo_mutual.png",27,159, 40, 0, 'PNG', '', '', true, 300, '');


$html = <<<EOF
<table class="uno" border="1" style="background-color:#F6F6F6;" >
	<tr>
		<td width="100%" >
		  	<table border="0" >
				<tr style="line-height: 33px;" >
					<td width="100%" ></td>
				</tr>
				<tr>
					<td width="3%" ></td>
					<td width="93%" >
						<table>
							<tr style="line-height: 33px;" >
								<td width="100%" style="background-color: #8FBE00;" ></td>
							</tr>
							<tr>
								<td width="4.5%" style="background-color: #8FBE00;" ></td>
								<td width="91%" >
									<table border="0">
										<tr style="line-height: 111px;" >
											<td width="100%;" ></td>
										</tr>
										<tr style="line-height: 112px;" ><td width="100%;height:100px;" ></td></tr>
										<tr style="line-height: 60px;" >											
											<td width="100%;" style="text-align: center; font-size: 16px; font-family: Arial; color: #4f5050" >$nombre</td>
										</tr>
										<tr style="line-height: 20px;" >
											<td width="100%;" style="text-align: center; font-size: 16px; font-family: Arial; color: #4f5050" >Asistió a $nombre_evento</td>
										</tr>
										<tr style="line-height: 20px;" >
											<td width="100%;" style="text-align: center; font-size: 16px; font-family: Arial; color: #4f5050" >el $fecha_final</td>
										</tr>
										<tr style="line-height: 80px;" >
											<td width="50%;" style="text-align: left; font-size: 16px; font-family: Arial; color: #4f5050" ></td>
											<td width="50%;" style="text-align: right; font-size: 16px; font-family: Arial; color: #4f5050;" >
												<table>
													<tr style="line-height: 70px;" ><td></td></tr>
													<tr style="line-height: 20px;" >
														<td>
															Santiago, $dia de $mes del 2017&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
														</td>
													</tr>
												</table>
											</td>
										</tr>
									</table>
								</td>
								<td width="4.5%" style="background-color: #8FBE00;" ></td>
							</tr>			
							<tr style="line-height: 33px;" >
								<td width="100%" style="background-color: #8FBE00;" ></td>
							</tr>					
						</table>
					</td>
					<td width="4%" ></td>
				</tr>
				<tr style="line-height: 33px;">
					<td width="100%">

					</td>
				</tr>
		  	</table>
	  	</td>
  	</tr>
</table>	
EOF;

	$pdf->writeHTMLCell(0, 0, '', '', $html, 0, 1, 0, true, '', true);

	$time = time();
	$rand = rand(1,9887657139864654);
	$pdf_name = $rand+$time;	

	//$pdf->Output($pdf_name.".pdf", 'I');	
	$pdf->Output($pdf_name.".pdf", 'D');	

	}else{
		//echo "Imposible imprimir su certificado.";
	}

	die;

}


