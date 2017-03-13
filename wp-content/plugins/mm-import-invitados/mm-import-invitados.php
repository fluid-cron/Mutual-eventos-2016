<?php
/**
* Plugin Name: Importar invitados
* Plugin URI: http://www.cronstudio.com
* Description: Importar datos invitados
* Version: 1.0 
* Author: Manuel Meriño
*/

function register_import_invitados()
{
	add_menu_page( 'Import Invitados', 'Import Invitados', 'manage_options', 'mm-import-invitados/inicio.php', '', 'dashicons-media-text', 29 );
}
add_action( 'admin_menu', 'register_import_invitados' );

function my_plugin_admin_init_invitados() {
  
   wp_register_script('script-import-invitados', plugins_url( '/js/script.js', __FILE__ ) , array( 'jquery' ) );
   wp_enqueue_script('script-import-invitados' );  

   wp_register_script('jquery-ui', plugins_url( '/js/jquery-ui.js', __FILE__ ) );
   wp_enqueue_script('jquery-ui' );     

   wp_register_style( 'jquery-ui-css', plugins_url('css/jquery-ui.css', __FILE__) );
   wp_enqueue_style( 'jquery-ui-css' ); 

   wp_register_style( 'myPluginStylesheet', plugins_url('css/stylesheet.css', __FILE__) );
   wp_enqueue_style( 'myPluginStylesheet' ); 

}
add_action( 'admin_enqueue_scripts', 'my_plugin_admin_init_invitados' );

function upload_invitacion_xls() {

	require_once dirname(__FILE__) . '/libraries/PHPExcel-1.8/Classes/PHPExcel/IOFactory.php';

	global $wpdb;

	$evento = $_POST['evento'];

	$time = time();
	$rand = rand(1,98876571);
	$nombre = $rand;	

	$path = $_FILES['archivo']['name'];
	$ext = pathinfo($path, PATHINFO_EXTENSION);

	$fichero_subido = get_template_directory()."/temp/xls/".$nombre."-".date("d-m-Y-h-i-s").".".$ext;

	if (move_uploaded_file($_FILES['archivo']['tmp_name'], $fichero_subido)) {
	    //echo "El fichero es válido y se subió con éxito.\n";
		$contenido_xls = excel_to_array($fichero_subido);

		$n = 0;
		foreach ($contenido_xls as $key) {

			$wpdb->insert(
				$wpdb->prefix.'usuarios_mutual',
				array(
					'nombre'   => $key["nombre"],
					'cargo'    => $key["cargo"],
					'empresa'  => $key["empresa"],
					'email'    => $key["email"],
					'telefono' => $key["telefono"],
					'evento'   => $evento
				),
				array(
					'%s',
					'%s',
					'%s',
					'%s',
					'%s',
					'%s'
				)
			);	

			$n++;	

		}

	}else{
	    //echo "¡Posible ataque de subida de ficheros!\n";
	}

	die;
}
add_action( 'admin_post_nopriv_upload_invitacion_xls', 'upload_invitacion_xls' );
add_action( 'admin_post_upload_invitacion_xls', 'upload_invitacion_xls' );

/*add_action( 'wp_ajax_descargar_ejemplo_xls_invitados', 'descargar_ejemplo_xls_invitados' );

function export_invitador_mutual() {

	require_once dirname(__FILE__) . '/libraries/PHPExcel-1.8/Classes/PHPExcel.php';

	global $wpdb;

	$entries = $wpdb->get_results( "SELECT * FROM {$wpdb->prefix}usuarios_mutual" );

	$objPHPExcel = new PHPExcel();
	$objPHPExcel->getProperties()->setCreator("Mutual de seguridad");					

	$objPHPExcel->setActiveSheetIndex(0);

	$objPHPExcel->getActiveSheet()->setCellValue('A1', 'Nombre');
	$objPHPExcel->getActiveSheet()->setCellValue('B1', 'Cargo');
	$objPHPExcel->getActiveSheet()->setCellValue('C1', 'Empresa');
	$objPHPExcel->getActiveSheet()->setCellValue('D1', 'Email');
	$objPHPExcel->getActiveSheet()->setCellValue('E1', 'Teléfono');
	$objPHPExcel->getActiveSheet()->setCellValue('F1', 'Evento');
	
	$row = 2;
	foreach($entries as $key) {
		
		$col = 0;

		$nombre = $key->nombre;
		$cargo = $key->cargo;
		$empresa = $key->empresa;
		$email = $key->email;
		$telefono = $key->telefono;
		$evento = $key->evento;

		$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col, $row, $nombre);
		$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col+=1, $row, $cargo);
		$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col+=1, $row, $empresa);
		$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col+=1, $row, $email);
		$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col+=1, $row, $telefono);
		$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col+=1, $row, $evento);

		$row++;

	}	

	$objPHPExcel->getActiveSheet()->setTitle('Hoja 1');	
	$objPHPExcel->setActiveSheetIndex(0);	

	// Redirect output to a client’s web browser (Excel5)
	header('Content-Type: application/vnd.ms-excel');
	header('Content-Disposition: attachment;filename="invitados-eventos.xlsx"');
	header('Cache-Control: max-age=0');
	// If you're serving to IE 9, then the following may be needed
	header('Cache-Control: max-age=1');
	// If you're serving to IE over SSL, then the following may be needed
	header ('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
	header ('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT'); // always modified
	header ('Cache-Control: cache, must-revalidate'); // HTTP/1.1
	header ('Pragma: public'); // HTTP/1.0
	$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
	$objWriter->save('php://output');

	//wp_die();
	die;
}
add_action( 'wp_ajax_export_invitador_mutual', 'export_invitador_mutual' );
*/
function excel_to_array($inputFileName,$row_callback=null){
    if (!class_exists('PHPExcel')) return false;
    try {
        $inputFileType = PHPExcel_IOFactory::identify($inputFileName);
        $objReader = PHPExcel_IOFactory::createReader($inputFileType);
        $objPHPExcel = $objReader->load($inputFileName);
    } catch(Exception $e) {
        return ('Error loading file "'.pathinfo($inputFileName,PATHINFO_BASENAME).'": '.$e->getMessage());
    }
    $sheet = $objPHPExcel->getSheet(0); 
    $highestRow = $sheet->getHighestRow(); 
    $highestColumn = $sheet->getHighestColumn();
    $keys = array();
    $results = array();
    if(is_callable($row_callback)){
        for ($row = 1; $row <= $highestRow; $row++){ 
            $rowData = $sheet->rangeToArray('A' . $row . ':' . $highestColumn . $row,null,true,false);
            if ($row === 1){
                $keys = $rowData[0];
            } else {
                $record = array();
                foreach($rowData[0] as $pos=>$value) $record[$keys[$pos]] = $value; 
                $row_callback($record);           
            }
        } 
    } else {            
        for ($row = 1; $row <= $highestRow; $row++){ 
            $rowData = $sheet->rangeToArray('A' . $row . ':' . $highestColumn . $row,null,true,false);
            if ($row === 1){
                $keys = $rowData[0];
            } else {
                $record = array();
                foreach($rowData[0] as $pos=>$value) $record[$keys[$pos]] = $value; 
                $results[] = $record;           
            }
        } 
        return $results;
    }
}




