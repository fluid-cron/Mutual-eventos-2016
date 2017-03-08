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

$email  = sanitize_text_field($_GET["email"]);
$evento = sanitize_text_field($_GET["evento"]);

if( $email!="" && $evento!="" ) {
	generarPDF($email,$evento);
}else{
	echo "Imposible descargar su info";
}
die;
?>