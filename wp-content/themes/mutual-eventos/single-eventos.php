<?php
/**
 * The template for displaying all single posts
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/#single-post
 *
 * @package Mutual_eventos
 */
die;
get_header(); ?>

		<?php
		while ( have_posts() ) : the_post();

			$titulo = get_the_title();

            $images = get_field('galeria');
            if( $images ):
                ?><ul>
                    <?php foreach( $images as $image ): ?>
                        <li>
                            <a href="<?php echo $image['url']; ?>">
                                 <img src="<?php echo $image['sizes']['thumbnail']; ?>" alt="<?php echo $image['alt']; ?>" />
                            </a>
                            <p><?php echo $image['caption']; ?></p>
                        </li>
                    <?php endforeach; ?>
                </ul>
            <?php endif;

			$archivos = get_field('contenedor_archivos');
            if( $archivos ):
                ?><ul>
                    <?php foreach( $archivos as $archivo ): 
                    	$file = $archivo["documento"];
                        //print_r($file);
                    ?>
                        <li>
                            <a href="<?php echo $file['url']; ?>">
                                 <?php echo $file['title']; ?>
                            </a>
                            <p><?php echo $file['mime_type']; ?></p>
                        </li>
                    <?php endforeach; ?>
                </ul>
            <?php endif;	
                      		
		endwhile;
		?>

<?php
get_footer();
