<?php
/**
 * Mutual eventos functions and definitions
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package Mutual_eventos
 */
if ( ! function_exists( 'mutual_eventos_setup' ) ) :
function mutual_eventos_setup() {
	/*
	 * Make theme available for translation.
	 * Translations can be filed in the /languages/ directory.
	 * If you're building a theme based on Mutual eventos, use a find and replace
	 * to change 'mutual-eventos' to the name of your theme in all the template files.
	 */
	//load_theme_textdomain( 'mutual-eventos', get_template_directory() . '/languages' );

	// Add default posts and comments RSS feed links to head.
	add_theme_support( 'automatic-feed-links' );

	/*
	 * Let WordPress manage the document title.
	 * By adding theme support, we declare that this theme does not use a
	 * hard-coded <title> tag in the document head, and expect WordPress to
	 * provide it for us.
	 */
	add_theme_support( 'title-tag' );

	/*
	 * Enable support for Post Thumbnails on posts and pages.
	 *
	 * @link https://developer.wordpress.org/themes/functionality/featured-images-post-thumbnails/
	 */
	add_theme_support( 'post-thumbnails' );

	// This theme uses wp_nav_menu() in one location.
	/*register_nav_menus( array(
		'menu-1' => esc_html__( 'Primary', 'mutual-eventos' ),
	) );
	*/
	/*
	 * Switch default core markup for search form, comment form, and comments
	 * to output valid HTML5.
	 */
	/*add_theme_support( 'html5', array(
		'search-form',
		'comment-form',
		'comment-list',
		'gallery',
		'caption',
	) );*/

	// Set up the WordPress core custom background feature.
	/*add_theme_support( 'custom-background', apply_filters( 'mutual_eventos_custom_background_args', array(
		'default-color' => 'ffffff',
		'default-image' => '',
	) ) );*/

	// Add theme support for selective refresh for widgets.
	//add_theme_support( 'customize-selective-refresh-widgets' );
}
endif;
add_action( 'after_setup_theme', 'mutual_eventos_setup' );

/**
 * Set the content width in pixels, based on the theme's design and stylesheet.
 *
 * Priority 0 to make it available to lower priority callbacks.
 *
 * @global int $content_width
 */
/*function mutual_eventos_content_width() {
	$GLOBALS['content_width'] = apply_filters( 'mutual_eventos_content_width', 640 );
}
add_action( 'after_setup_theme', 'mutual_eventos_content_width', 0 );
*/
/**
 * Register widget area.
 *
 * @link https://developer.wordpress.org/themes/functionality/sidebars/#registering-a-sidebar
 */
/*function mutual_eventos_widgets_init() {
	register_sidebar( array(
		'name'          => esc_html__( 'Sidebar', 'mutual-eventos' ),
		'id'            => 'sidebar-1',
		'description'   => esc_html__( 'Add widgets here.', 'mutual-eventos' ),
		'before_widget' => '<section id="%1$s" class="widget %2$s">',
		'after_widget'  => '</section>',
		'before_title'  => '<h2 class="widget-title">',
		'after_title'   => '</h2>',
	) );
}
add_action( 'widgets_init', 'mutual_eventos_widgets_init' );
*/

/**
 * Implement the Custom Header feature.
 */
//require get_template_directory() . '/inc/custom-header.php';

/**
 * Custom template tags for this theme.
 */
//require get_template_directory() . '/inc/template-tags.php';

/**
 * Custom functions that act independently of the theme templates.
 */
//require get_template_directory() . '/inc/extras.php';

/**
 * Customizer additions.
 */
//require get_template_directory() . '/inc/customizer.php';

/**
 * Load Jetpack compatibility file.
 */
//require get_template_directory() . '/inc/jetpack.php';
show_admin_bar(false);
require get_template_directory() . '/inc/custom-script-css.php';
require get_template_directory() . '/inc/custom-post.php';
require get_template_directory() . '/inc/custom-functions.php';
