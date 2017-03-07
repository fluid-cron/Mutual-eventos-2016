<?php
/**
* Plugin Name: Inscripciones a los Eventos
* Plugin URI: http://www.cronstudio.com
* Description: Mostrar/Exportar datos de los formularios de Hogar de Cristo
* Version: 1.0 
* Author: Manuel Meriño
*/

function register_eventos()
{
	add_menu_page( 'Inscritos a los Eventos', 'Inscritos a los Eventos', 'manage_options', 'manager-eventos/inicio.php', '', 'dashicons-media-text', 30 );
}
add_action( 'admin_menu', 'register_eventos' );

add_action( 'admin_enqueue_scripts', 'my_plugin_admin_init' );
function my_plugin_admin_init() {
  
   wp_register_script('script-eventos', plugins_url( '/js/script.js', __FILE__ ) , array( 'jquery' ) );
   wp_enqueue_script('script-eventos' );  

   wp_register_script('jquery-ui', plugins_url( '/js/jquery-ui.js', __FILE__ ) );
   wp_enqueue_script('jquery-ui' );     

   wp_register_style( 'jquery-ui-css', plugins_url('css/jquery-ui.css', __FILE__) );
   wp_enqueue_style( 'jquery-ui-css' ); 

   wp_register_style( 'myPluginStylesheet', plugins_url('css/stylesheet.css', __FILE__) );
   wp_enqueue_style( 'myPluginStylesheet' ); 

}

function export()
{

	global $wpdb;

	$tipo    = isset( $_POST['tipo'] ) ? $_POST['tipo'] : "";
	$desde   = isset( $_POST['desde'] ) ? $_POST['desde'] : "";
	$hasta   = isset( $_POST['hasta'] ) ? $_POST['hasta'] : "";

	header('Content-type: application/vnd.ms-excel');	
	header("Content-Disposition: attachment; filename=$tipo.xls");
	header("Pragma: no-cache");
	header("Expires: 0");	

	if( $desde!="" && $hasta!="" && $tipo!="" )
	{

		$where       = ' evento="'.$tipo.'" AND left(fecha,10) BETWEEN "'.$desde.'" AND "'.$hasta.'" ';

	}
	else if( $tipo!="" )
	{
		$where       = ' evento="'.$tipo.'" ';
	}
	else if( $desde!="" && $hasta!="" )
	{

		$where       = ' left(fecha,10) BETWEEN "'.$desde.'" AND "'.$hasta.'" ';

	}	

	$entries = $wpdb->get_results( "SELECT * 
								    FROM {$wpdb->prefix}inscripcion_eventos
								    WHERE $where 
								    order by id desc" );	

	?>
		<table border="1" >
		<tr>
			<td>Id</td>
			<td>Nombre</td>
			<td>Email</td>
			<td>Fecha</td>
		</tr>
		<?php foreach($entries as $key){ ?>
			<tr>
				<td><?php echo $key->id; ?></td>
				<td><?php echo utf8_decode($key->nombre); ?></td>
				<td><?php echo $key->email; ?></td>
				<td><?php echo $key->fecha; ?></td>
			</tr>   
		<?php } ?> 
		</table>
	<?php	

	wp_die();
	die;
}

add_action( 'wp_ajax_my_action', 'export' );

