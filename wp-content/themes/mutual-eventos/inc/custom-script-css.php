<?php

//add_action( 'init', 'my_script_css_enqueuer' );
add_action( 'wp_enqueue_scripts', 'script_enqueue' );

function script_enqueue() {
   wp_register_script( "validate_script", get_template_directory_uri().'/js/jquery.validate.js');
   wp_register_script( "events_script", get_template_directory_uri().'/js/scripts.js');
   wp_localize_script( 'events_script', 'ajax', array( 'url' => admin_url( 'admin-ajax.php' )));        

   wp_enqueue_script( 'jquery' );
   wp_enqueue_script( 'validate_script' );
   wp_enqueue_script( 'events_script' );
}

/**
 * Enqueue scripts and styles.
 */
add_action( 'wp_enqueue_scripts', 'css_enqueue' );
function css_enqueue() {
	wp_enqueue_style( 'mutual-eventos-style', get_stylesheet_uri() );
	//wp_enqueue_script( 'mutual-eventos-navigation', get_template_directory_uri() . '/js/navigation.js', array(), '20151215', true );
	//wp_enqueue_script( 'mutual-eventos-skip-link-focus-fix', get_template_directory_uri() . '/js/skip-link-focus-fix.js', array(), '20151215', true );
	//if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
	//	wp_enqueue_script( 'comment-reply' );
	//}
}