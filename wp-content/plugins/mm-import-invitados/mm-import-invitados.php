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

