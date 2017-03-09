<?php

add_action( 'wp_enqueue_scripts', 'script_enqueue' );

function script_enqueue() {
   wp_register_script( "validate_script", get_template_directory_uri().'/js/jquery.validate.js');
   wp_register_script( "events_script", get_template_directory_uri().'/js/scripts.js');
   wp_localize_script( 'events_script', 'ajax', array( 'url' => admin_url( 'admin-ajax.php' ))); 
   wp_register_script( "events_bootstrap", get_template_directory_uri().'/assets/js/bootstrap.min.js');
   wp_register_script( "events_slick", get_template_directory_uri().'/assets/js/slick.min.js');       

   wp_enqueue_script( 'jquery' );
   wp_enqueue_script( 'validate_script' );
   wp_enqueue_script( 'events_bootstrap' );
   wp_enqueue_script( 'events_slick' );
   wp_enqueue_script( 'events_script' );
}

/**
 * Enqueue scripts and styles.
 */
add_action( 'wp_enqueue_scripts', 'css_enqueue' );
function css_enqueue() {
   wp_enqueue_style( 'mutual-eventos-stylewp1212', get_stylesheet_uri(),'','1.0' );
	wp_enqueue_style( 'mutual-eventos-bootstrap1212', get_template_directory_uri().'/assets/css/bootstrap.min.css','','3.3.7' );
	wp_enqueue_style( 'mutual-eventos-style1212', get_template_directory_uri().'/assets/css/styles.css','','1.0' );
	wp_enqueue_style( 'mutual-eventos-font1212', get_template_directory_uri().'/assets/css/fonts.css','','1.0' );
   wp_enqueue_style( 'mutual-eventos-font-awesoma1212', get_template_directory_uri().'/assets/css/font-awesome.css','','1.0' );
   wp_enqueue_style( 'mutual-eventos-slick1212', get_template_directory_uri().'/assets/css/slick.css','','1.0' );
	wp_enqueue_style( 'mutual-eventos-slick-theme-1212', get_template_directory_uri().'/assets/css/slick-theme.css','','1.0' );
}