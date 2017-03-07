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

	$email = $_POST["email"];

	$query = "SELECT email FROM {$wpdb->prefix}usuarios_mutual WHERE email='$email'";
	$result = $wpdb->get_results($query);

	$usuario_mutual = count($result);

	if( $usuario_mutual>0 ){

		//echo "existe en la base de mutual puede inscribirse";

		$query = "SELECT email FROM {$wpdb->prefix}inscripcion_eventos WHERE email='$email' and evento='$evento_activo'";
		$result = $wpdb->get_results($query);

		$usuario_inscripcion = count($result);

		if( $usuario_inscripcion==0 ){

			//echo "se puede inscribir a evento";

			if( $email!="" && $evento_activo!="" ) {

				$qr = generarQR($email);

				if( $qr!="error" ){

					$query = "SELECT * FROM {$wpdb->prefix}usuarios_mutual WHERE email='$email'";
					$result = $wpdb->get_row($query);					
					
					$wpdb->insert(
						$wpdb->prefix.'inscripcion_eventos',
						array(
							'nombre'  => $result->nombre,
							'email'  => $email,
							'evento' => $evento_activo,
							'qr' => $qr
						),
						array(
							'%s',
							'%s',
							'%s',
							'%s'
						)
					);			
										
					$body    = file_get_contents(get_template_directory_uri().'/mail/index.html');
					$body    = str_replace("[EVENTO]",$evento_nombre,$body);
					$body    = str_replace("[QR]",get_template_directory_uri()."/temp/".$qr,$body);
					$headers = array('Content-Type: text/html; charset=UTF-8');
					wp_mail($email,'Inscripción al evento '.$evento_nombre, $body ,$headers);					

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

add_action( 'wp_ajax_guardarEncuesta', 'guardarEncuesta' );
add_action( 'wp_ajax_nopriv_guardarEncuesta', 'guardarEncuesta' );

function guardarEncuesta() {

	global $wpdb;
	global $evento_activo;
	global $evento_nombre;

	$email       = $_POST["email"];
	$respuesta_1 = $_POST["respuesta_1"];
	$respuesta_2 = $_POST["respuesta_2"];
	$respuesta_3 = $_POST["respuesta_3"];
	$respuesta_4 = $_POST["respuesta_4"];
	$comentario = $_POST["comentario"];

	if( $email!="" && $evento_activo!="" ) {

		$query = "SELECT email FROM {$wpdb->prefix}encuesta_satisfaccion WHERE email='$email' and evento='$evento_activo'";
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
					'evento'      => $evento_activo
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

//se usa en page-encuesta
function respondioEncuesta($email) {

	global $wpdb;
	global $evento_activo;

	$query = "SELECT email FROM {$wpdb->prefix}encuesta_satisfaccion WHERE email='$email' and evento='$evento_activo'";
	$result = $wpdb->get_results($query);

	$encuesta_satisfaccion = count($result);	

	if( $encuesta_satisfaccion==0 ) {
		return 0;
	}else{
		return 1;
	}

}

add_action( 'wp_ajax_generarQR', 'generarQR' );
add_action( 'wp_ajax_nopriv_generarQR', 'generarQR' );

function generarQR($email) {

	require_once(__DIR__.'/libraries/phpqr/qrlib.php');

	global $wpdb;
	global $evento_nombre;	

	$time = time();
	$rand = rand(1,9887657139864654);
	$qr_name = $rand+$time;	

	$query = "SELECT * FROM {$wpdb->prefix}usuarios_mutual WHERE email='$email'";
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

add_action( 'wp_ajax_generarPDF', 'generarPDF' );
add_action( 'wp_ajax_nopriv_generarPDF', 'generarPDF' );

function generarPDF() {

	require_once(__DIR__.'/libraries/TCPDF/tcpdf.php');

	Class MyPdf extends TCPDF{
		
		//Page header
		public function Header() {
			/*
			// Logo
			$image_file = K_PATH_IMAGES.'logo_example.jpg';
			$this->Image($image_file, 10, 10, 15, '', 'JPG', '', 'T', false, 300, '', false, false, 0, false, false, false);
			// Set font
			$this->SetFont('helvetica', 'B', 20);
			// Title
			$this->Cell(0, 15, '<< TCPDF Example 003 >>', 0, false, 'C', 0, '', 0, false, 'M', 'M');
			*/
		}

		// Page footer
		public function Footer() {
			/*
			// Position at 15 mm from bottom
			$this->SetY(-15);
			// Set font
			$this->SetFont('helvetica', 'I', 8);
			// Page number
			$this->Cell(0, 10, 'Page '.$this->getAliasNumPage().'/'.$this->getAliasNbPages(), 0, false, 'C', 0, '', 0, false, 'T', 'M');
			*/
		}

	}

	// create new PDF document
	$pdf = new MyPdf(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

	// set document information
	$pdf->SetCreator(PDF_CREATOR);
	$pdf->SetAuthor('Mutual');
	$pdf->SetTitle('Diploma');
	$pdf->SetSubject('PDF');
	$pdf->SetKeywords('PDF');

	// set default header data
	//$pdf->SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, PDF_HEADER_TITLE.' 001', PDF_HEADER_STRING, array(0,64,255), array(0,64,128));
	//$pdf->setFooterData(array(0,64,0), array(0,64,128));

	// set header and footer fonts
	//$pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
	//$pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));

	// set default monospaced font
	$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

	// set margins
	$pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
	//$pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
	//$pdf->SetFooterMargin(PDF_MARGIN_FOOTER);

	// set auto page breaks
	$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

	// set image scale factor
	$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

	// set some language-dependent strings (optional)
	if (@file_exists(dirname(__FILE__).'/lang/eng.php')) {
		require_once(dirname(__FILE__).'/lang/eng.php');
		$pdf->setLanguageArray($l);
	}

	// ---------------------------------------------------------

	// set default font subsetting mode
	$pdf->setFontSubsetting(true);

	// Set font
	// dejavusans is a UTF-8 Unicode font, if you only need to
	// print standard ASCII chars, you can use core fonts like
	// helvetica or times to reduce file size.
	$pdf->SetFont('dejavusans', '', 14, '', true);

	// Add a page
	// This method has several options, check the source code documentation for more information.
	$pdf->AddPage('L');

	// set text shadow effect
	$pdf->setTextShadow(array('enabled'=>true, 'depth_w'=>0.2, 'depth_h'=>0.2, 'color'=>array(196,196,196), 'opacity'=>1, 'blend_mode'=>'Normal'));

	// Set some content to print
$html = <<<EOF
<!-- EXAMPLE OF CSS STYLE -->
<style>
    h1 {
        color: navy;
        font-family: times;
        font-size: 24pt;
        text-decoration: underline;
    }
    p.first {
        color: #003300;
        font-family: helvetica;
        font-size: 12pt;
    }
    p.first span {
        color: #006600;
        font-style: italic;
    }
    p#second {
        color: rgb(00,63,127);
        font-family: times;
        font-size: 12pt;
        text-align: justify;
    }
    p#second > span {
        background-color: #FFFFAA;
    }
    table.first {
        color: #003300;
        font-family: helvetica;
        font-size: 8pt;
        border-left: 3px solid red;
        border-right: 3px solid #FF00FF;
        border-top: 3px solid green;
        border-bottom: 3px solid blue;
        background-color: #ccffcc;
    }
    td {
        border: 2px solid blue;
        background-color: #ffffee;
    }
    td.second {
        border: 2px dashed green;
    }
    div.test {
        color: #CC0000;
        background-color: #FFFF66;
        font-family: helvetica;
        font-size: 10pt;
        border-style: solid solid solid solid;
        border-width: 2px 2px 2px 2px;
        border-color: green #FF00FF blue red;
        text-align: center;
    }
    .lowercase {
        text-transform: lowercase;
    }
    .uppercase {
        text-transform: uppercase;
    }
    .capitalize {
        text-transform: capitalize;
    }
</style>

<h1 class="title">Example of <i style="color:#990000">XHTML + CSS</i></h1>

<p class="first">Example of paragraph with class selector. <span>Lorem ipsum dolor sit amet, consectetur adipiscing elit. In sed imperdiet lectus. Phasellus quis velit velit, non condimentum quam. Sed neque urna, ultrices ac volutpat vel, laoreet vitae augue. Sed vel velit erat. Class aptent taciti sociosqu ad litora torquent per conubia nostra, per inceptos himenaeos. Cras eget velit nulla, eu sagittis elit. Nunc ac arcu est, in lobortis tellus. Praesent condimentum rhoncus sodales. In hac habitasse platea dictumst. Proin porta eros pharetra enim tincidunt dignissim nec vel dolor. Cras sapien elit, ornare ac dignissim eu, ultricies ac eros. Maecenas augue magna, ultrices a congue in, mollis eu nulla. Nunc venenatis massa at est eleifend faucibus. Vivamus sed risus lectus, nec interdum nunc.</span></p>

<p id="second">Example of paragraph with ID selector. <span>Fusce et felis vitae diam lobortis sollicitudin. Aenean tincidunt accumsan nisi, id vehicula quam laoreet elementum. Phasellus egestas interdum erat, et viverra ipsum ultricies ac. Praesent sagittis augue at augue volutpat eleifend. Cras nec orci neque. Mauris bibendum posuere blandit. Donec feugiat mollis dui sit amet pellentesque. Sed a enim justo. Donec tincidunt, nisl eget elementum aliquam, odio ipsum ultrices quam, eu porttitor ligula urna at lorem. Donec varius, eros et convallis laoreet, ligula tellus consequat felis, ut ornare metus tellus sodales velit. Duis sed diam ante. Ut rutrum malesuada massa, vitae consectetur ipsum rhoncus sed. Suspendisse potenti. Pellentesque a congue massa.</span></p>

<div class="test">example of DIV with border and fill.
<br />Lorem ipsum dolor sit amet, consectetur adipiscing elit.
<br /><span class="lowercase">text-transform <b>LOWERCASE</b> Lorem ipsum dolor sit amet, consectetur adipiscing elit.</span>
<br /><span class="uppercase">text-transform <b>uppercase</b> Lorem ipsum dolor sit amet, consectetur adipiscing elit.</span>
<br /><span class="capitalize">text-transform <b>cAPITALIZE</b> Lorem ipsum dolor sit amet, consectetur adipiscing elit.</span>
</div>

<br />

<table class="first" cellpadding="4" cellspacing="6">
 <tr>
  <td width="30" align="center"><b>No.</b></td>
  <td width="140" align="center" bgcolor="#FFFF00"><b>XXXX</b></td>
  <td width="140" align="center"><b>XXXX</b></td>
  <td width="80" align="center"> <b>XXXX</b></td>
  <td width="80" align="center"><b>XXXX</b></td>
  <td width="45" align="center"><b>XXXX</b></td>
 </tr>
 <tr>
  <td width="30" align="center">1.</td>
  <td width="140" rowspan="6" class="second">XXXX<br />XXXX<br />XXXX<br />XXXX<br />XXXX<br />XXXX<br />XXXX<br />XXXX</td>
  <td width="140">XXXX<br />XXXX</td>
  <td width="80">XXXX<br />XXXX</td>
  <td width="80">XXXX</td>
  <td align="center" width="45">XXXX<br />XXXX</td>
 </tr>
 <tr>
  <td width="30" align="center" rowspan="3">2.</td>
  <td width="140" rowspan="3">XXXX<br />XXXX</td>
  <td width="80">XXXX<br />XXXX</td>
  <td width="80">XXXX<br />XXXX</td>
  <td align="center" width="45">XXXX<br />XXXX</td>
 </tr>
</table>
EOF;

	// Print text using writeHTMLCell()
	$pdf->writeHTMLCell(0, 0, '', '', $html, 0, 1, 0, true, '', true);

	// Close and output PDF document
	// This method has several options, check the source code documentation for more information.
	//$pdf->Output('example_001.pdf', 'I');	

	$time = time();
	$rand = rand(1,9887657139864654);
	$pdf_name = $rand+$time;	

	$tempDir = get_template_directory().'/temp/pdf/'.$pdf_name.".pdf"; 
	$pdf->Output($tempDir, 'F');	

	echo "PDF exito!";

	die;

}


