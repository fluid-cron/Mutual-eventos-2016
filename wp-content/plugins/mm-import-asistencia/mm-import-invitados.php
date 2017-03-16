<?php
/**
* Plugin Name: Importar asistencia
* Plugin URI: http://www.cronstudio.com
* Description: Importar datos asistentes
* Version: 1.0 
* Author: Manuel Meriño
*/

function register_import_asistentes()
{
	add_menu_page( 'Import Asistentes', 'Import Asistentes', 'manage_options', 'mm-import-asistencia/inicio.php', '', 'dashicons-media-text', 28 );
}
add_action( 'admin_menu', 'register_import_asistentes' );

function my_plugin_admin_init_asistentes() {
  
   wp_register_script('script-import-asistentes', plugins_url( '/js/script.js', __FILE__ ) , array( 'jquery' ) );
   wp_enqueue_script('script-import-asistentes' );  

   wp_register_script('jquery-ui', plugins_url( '/js/jquery-ui.js', __FILE__ ) );
   wp_enqueue_script('jquery-ui' );     

   wp_register_style( 'jquery-ui-css', plugins_url('css/jquery-ui.css', __FILE__) );
   wp_enqueue_style( 'jquery-ui-css' ); 

   wp_register_style( 'myPluginStylesheet', plugins_url('css/stylesheet.css', __FILE__) );
   wp_enqueue_style( 'myPluginStylesheet' ); 

}
add_action( 'admin_enqueue_scripts', 'my_plugin_admin_init_asistentes' );

function upload_asistencia_xls() {

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
		$contenido_xls = excel_to_array2($fichero_subido);

		$n = 0;
		foreach ($contenido_xls as $key) {

            $email = preg_replace('/\s+/', '', $key["email"]);
            $email = str_replace('&nbsp;',"",$email);

			$wpdb->insert(
				$wpdb->prefix.'usuarios_mutual_asistencia',
				array(
					'email'    => $email,
					'evento'   => $evento
				),
				array(
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
add_action( 'admin_post_nopriv_upload_asistencia_xls', 'upload_asistencia_xls' );
add_action( 'admin_post_upload_asistencia_xls', 'upload_asistencia_xls' );

function excel_to_array2($inputFileName,$row_callback=null) {
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




