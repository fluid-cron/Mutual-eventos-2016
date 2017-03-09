<?php
/**
* Plugin Name: Encuestas de satisfacción
* Plugin URI: http://www.cronstudio.com
* Description: Mostrar/Exportar datos
* Version: 1.0 
* Author: Manuel Meriño
*/
function register_encuesta_satisfaccion()
{
	add_menu_page( 'Encuestas de satisfacción', 'Encuestas de satisfacción', 'manage_options', 'mm-encuesta/inicio.php', '', 'dashicons-media-text', 27 );
}
add_action( 'admin_menu', 'register_encuesta_satisfaccion' );

function plugin_encuesta_satisfaccion_init() {
  
   wp_register_script('script-eventos', plugins_url( '/js/script.js', __FILE__ ) , array( 'jquery' ) );
   wp_enqueue_script('script-eventos' );  

   wp_register_script('jquery-ui', plugins_url( '/js/jquery-ui.js', __FILE__ ) );
   wp_enqueue_script('jquery-ui' );     

   wp_register_style( 'jquery-ui-css', plugins_url('css/jquery-ui.css', __FILE__) );
   wp_enqueue_style( 'jquery-ui-css' ); 

   wp_register_style( 'myPluginStylesheet', plugins_url('css/stylesheet.css', __FILE__) );
   wp_enqueue_style( 'myPluginStylesheet' ); 

}
add_action( 'admin_enqueue_scripts', 'plugin_encuesta_satisfaccion_init' );

function export_encuesta_satisfaccion() {

	require_once dirname(__FILE__) . '/libraries/PHPExcel-1.8/Classes/PHPExcel.php';

	global $wpdb;

	$tipo    = isset( $_POST['tipo'] ) ? $_POST['tipo'] : "";
	$desde   = isset( $_POST['desde'] ) ? $_POST['desde'] : "";
	$hasta   = isset( $_POST['hasta'] ) ? $_POST['hasta'] : "";

	if( $desde!="" && $hasta!="" && $tipo!="" )
	{
		$where = ' b.email=a.email AND a.evento="'.$tipo.'" AND a.evento=b.evento AND left(a.fecha,10) BETWEEN "'.$desde.'" AND "'.$hasta.'" ';
	}
	else if( $tipo!="" )
	{
		$where = ' b.email=a.email AND a.evento="'.$tipo.'" AND a.evento=b.evento ';
	}
	else if( $desde!="" && $hasta!="" )
	{
		$where = ' b.email=a.email AND a.evento=b.evento AND left(a.fecha,10) BETWEEN "'.$desde.'" AND "'.$hasta.'" ';
	}

	$entries = $wpdb->get_results( "SELECT * 
								    FROM {$wpdb->prefix}encuesta_satisfaccion a,{$wpdb->prefix}inscripcion_eventos b
								    WHERE $where 
								    order by a.id desc" );

	$objPHPExcel = new PHPExcel();
	$objPHPExcel->getProperties()->setCreator("Mutual de seguridad");					

	$objPHPExcel->setActiveSheetIndex(0);

	$objPHPExcel->getActiveSheet()->setCellValue('A1', 'ID');
	$objPHPExcel->getActiveSheet()->setCellValue('B1', 'Pregunta 1');
	$objPHPExcel->getActiveSheet()->setCellValue('C1', 'Pregunta 2');
	$objPHPExcel->getActiveSheet()->setCellValue('D1', 'Pregunta 3');
	$objPHPExcel->getActiveSheet()->setCellValue('E1', 'Pregunta 4');
	$objPHPExcel->getActiveSheet()->setCellValue('F1', 'Nombre');
	$objPHPExcel->getActiveSheet()->setCellValue('G1', 'Comentario');
	$objPHPExcel->getActiveSheet()->setCellValue('H1', 'Email');
	$objPHPExcel->getActiveSheet()->setCellValue('I1', 'Fecha');
	
	$row = 2;
	foreach($entries as $key) {
		
		$col = 0;
		
		$id = $key->id;
		$respuesta1 = $key->respuesta_1;
		$respuesta2 = $key->respuesta_2;
		$respuesta3 = $key->respuesta_3;
		$respuesta4 = $key->respuesta_4;
		$nombre = $key->nombre;
		$comentario = $key->comentario;
		$email = $key->email;
		$fecha = $key->fecha;

		$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col, $row, $id);
		$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col+=1, $row, $respuesta1);
		$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col+=1, $row, $respuesta2);
		$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col+=1, $row, $respuesta3);
		$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col+=1, $row, $respuesta4);
		$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col+=1, $row, $nombre);
		$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col+=1, $row, $comentario);
		$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col+=1, $row, $email);
		$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col+=1, $row, $fecha);

		$row++;

	}	

	$objPHPExcel->getActiveSheet()->setTitle('Hoja 1');	
	$objPHPExcel->setActiveSheetIndex(0);	

	// Redirect output to a client’s web browser (Excel5)
	header('Content-Type: application/vnd.ms-excel');
	header('Content-Disposition: attachment;filename="encuesta-'.$tipo.'.xls"');
	header('Cache-Control: max-age=0');
	// If you're serving to IE 9, then the following may be needed
	header('Cache-Control: max-age=1');
	// If you're serving to IE over SSL, then the following may be needed
	header ('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
	header ('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT'); // always modified
	header ('Cache-Control: cache, must-revalidate'); // HTTP/1.1
	header ('Pragma: public'); // HTTP/1.0
	$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
	$objWriter->save('php://output');

	wp_die();
	die;
}

add_action( 'wp_ajax_my_action2', 'export_encuesta_satisfaccion' );

