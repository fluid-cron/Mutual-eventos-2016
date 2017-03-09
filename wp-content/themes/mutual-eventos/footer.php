<?php wp_footer(); ?>
<footer>
		<div class="container">
			<div class="col-md-12">
				<div class="row">
					<div class="col-md-9 col-sm-9 col-xs-12">
						<img src="<?php echo get_field("logo_footer","option"); ?>" class="logo-footer">
						<a href="<?php echo get_field("footer_url","option");?>" target="_blank" class="url"><?php echo str_replace("http://","",get_field("footer_url","option"));?></a>
						<i class="fa fa-phone" aria-hidden="true"></i>
						<a href="tel:<?php echo get_field("footer_telefono","option");?>"><?php echo get_field("footer_telefono","option");?></a>
					</div>
					<div class="col-md-3 col-sm-3 col-xs-3 hidden-xs">
						<div class="rrss-footer">
							<ul>
								<?php if( get_field("url_facebook","option")!="" ): ?>
								<li>
									<a href="<?php echo get_field("url_footer_facebook","option"); ?>" target="_blank">
										<i class="fa fa-facebook"></i>
									</a>
								</li>
								<?php endif; ?>
								<?php if( get_field("url_twitter","option")!="" ): ?>
								<li>
									<a href="<?php echo get_field("url_footer_twitter","option"); ?>" target="_blank">
										<i class="fa fa-twitter"></i>
									</a>
								</li>
								<?php endif; ?>
								<?php if( get_field("url_youtube","option")!="" ): ?>
								<li>
									<a href="<?php echo get_field("url_footer_youtube","option"); ?>" target="_blank">
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
	</footer>
</body>
</html>
