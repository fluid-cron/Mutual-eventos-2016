<?php
/**
 * The header for our theme
 *
 * This is the template that displays all of the <head> section and everything up until <div id="content">
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package Mutual_eventos
 */

?>
<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
<meta charset="<?php bloginfo( 'charset' ); ?>">
<meta name="viewport" content="width=device-width, user-scalable=no">
<link rel="profile" href="http://gmpg.org/xfn/11">

<?php wp_head(); ?>
</head>

<body>

<header>
		<div class="container">
			<div class="col-md-12">
				<div class="row">
					<div class="col-md-2 col-sm-2 col-xs-3">
						<img src="<?php echo get_field("logo_header","option"); ?>">
					</div>
					<div class="col-md-10 col-sm-10 col-xs-9">
						<div class="rrss-header">
							<span>
								Síguenos en
							</span>
							<ul>
								<?php if( get_field("url_facebook","option")!="" ): ?>
								<li>
									<a href="<?php echo get_field("url_header_facebook","option"); ?>" target="_blank">
										<i class="fa fa-facebook"></i>
									</a>
								</li>
								<?php endif; ?>
								<?php if( get_field("url_twitter","option")!="" ): ?>
								<li>
									<a href="<?php echo get_field("url_header_twitter","option"); ?>" target="_blank">
										<i class="fa fa-twitter"></i>
									</a>
								</li>
								<?php endif; ?>
								<?php if( get_field("url_youtube","option")!="" ): ?>
								<li>
									<a href="<?php echo get_field("url_header_youtube","option"); ?>" target="_blank">
										<i class="fa fa-youtube"></i>
									</a>
								</li>
								<?php endif; ?>
							</ul>
						</div>
					</div>
				</div>
			</div>
		</div>
	</header>
