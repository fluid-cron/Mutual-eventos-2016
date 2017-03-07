<?php get_header(); ?>
<?php

	$email = $_GET["email"];

	$posts = get_posts(array(
		'name'      => $evento_activo,
		'post_type' => 'eventos'
	));

	if($posts) {
		foreach($posts as $post) {
			echo "Evento activo"."<br>";
			echo $post->post_title."<br>";
			echo $post->post_excerpt."<br>";
			echo $post->post_content."<br>";
			echo get_field('fecha')."<br>";
			echo get_field('lugar')."<br>";
			echo get_field('imagen')."<br>";
			echo get_permalink($post->ID)."<br>";
			echo '<a href="'.get_permalink($post->ID).'">'.$post->post_title.'</a>';
		}
	}

?>
<br>
<a class="btn-enviar" href="javascript:void(0);">Quiero ir!</a>

<form method="post" id="form-eventos" style="display: none;" >
  <input type="text" name="email" placeholder="Email*" value="<?php echo $email;?>" >
  <input type="hidden" name="action" value="guardarInscripcion" >
  <input type="button" class="btn-enviar" name="btn_enviar" value="Quiero ir!" >
</form>

<div id="gracias-inscrito" style="display: none;" >
	Inscrito con exito.
</div>

<div id="gracias-ya-inscrito" style="display: none;" >
	Ya te encuentras inscrito en el evento.
</div>

<div id="gracias-no-mutual" style="display: none;" >
	No puedes inscribirte al evento.
</div>

<div id="gracias-error" style="display: none;" >
	Error.
</div>

<?php
get_footer();

