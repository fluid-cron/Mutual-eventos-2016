<?php
/**
 * The template for displaying archive pages
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package Mutual_eventos
 */
get_header(); ?>

		<?php
		if ( have_posts() ) : ?>

			<?php
			
			while ( have_posts() ) : the_post();

	            $titulo = get_the_title();

	            echo "<a href=".get_permalink().">".$titulo."<a/>";

	            $imagen = get_field('imagen');

	            echo '<img src='.$imagen.' alt="">';

	            echo "<br>";

			endwhile;

		endif; ?>

<?php
//get_sidebar();
get_footer();
