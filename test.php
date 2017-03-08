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
	add_menu_page( 'Encuestas de satisfacción', 'Encuestas de satisfacción', 'manage_options', 'mm-encuesta/inicio.php', '', 'dashicons-media-text', 29 );
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

	//require(plugin_dir_path( __FILE__ )."libraries/htmlexcel/HtmlExcel.php");

	global $wpdb;

	$tipo    = isset( $_POST['tipo'] ) ? $_POST['tipo'] : "";
	$desde   = isset( $_POST['desde'] ) ? $_POST['desde'] : "";
	$hasta   = isset( $_POST['hasta'] ) ? $_POST['hasta'] : "";

	header('Content-type: application/vnd.ms-excel; charset=UTF-8');
	header("Content-Disposition: attachment; filename=$tipo.xls");
	header("Pragma: no-cache");
	header("Expires: 0");	

	if( $desde!="" && $hasta!="" && $tipo!="" )
	{
		$where = ' b.email=a.email AND a.evento="'.$tipo.'" AND left(a.fecha,10) BETWEEN "'.$desde.'" AND "'.$hasta.'" ';
	}
	else if( $tipo!="" )
	{
		$where = ' b.email=a.email AND a.evento="'.$tipo.'" ';
	}
	else if( $desde!="" && $hasta!="" )
	{
		$where = ' b.email=a.email AND left(a.fecha,10) BETWEEN "'.$desde.'" AND "'.$hasta.'" ';
	}

	$entries = $wpdb->get_results( "SELECT * 
								    FROM {$wpdb->prefix}encuesta_satisfaccion a,{$wpdb->prefix}inscripcion_eventos b
								    WHERE $where 
								    order by a.id desc" );
	?>
	<table border="1" >
		<tr>
			<td>ID</td>	
			<td>Pregunta 1</td>	
			<td>Pregunta 2</td>	
			<td>Pregunta 3</td>	
			<td>Pregunta 4</td>	
			<td>Nombre</td>	
			<td>Comentario</td>	
			<td>Email</td>	
			<td>Fecha</td>	
		</tr>
		<?php foreach($entries as $key){ ?>
		<tr>
			<td><?php echo $key->id; ?></td>
			<td><?php echo $key->respuesta_1; ?></td>
			<td><?php echo $key->respuesta_2; ?></td>				
			<td><?php echo $key->respuesta_3; ?></td>
			<td><?php echo $key->respuesta_4; ?></td>
			<td><?php echo $key->nombre; ?></td>
			<td><?php echo $key->comentario; ?></td>
			<td><?php echo $key->email; ?></td>
			<td><?php echo $key->fecha; ?></td>
		</tr>   
		<?php } ?> 
	</table>
	<?php
	wp_die();
	die;
}

add_action( 'wp_ajax_my_action2', 'export_encuesta_satisfaccion' );

