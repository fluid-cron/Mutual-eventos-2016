<?php					 
$pagenum = isset( $_GET['pagenum'] ) ? absint( $_GET['pagenum'] ) : 1;
$tipo    = isset( $_GET['tipo'] ) ? $_GET['tipo'] : "";
$desde   = isset( $_GET['desde'] ) ? $_GET['desde'] : "";
$hasta   = isset( $_GET['hasta'] ) ? $_GET['hasta'] : "";

$evento_option = get_field('evento_activo','option');
$evento_nombre = $evento_option->post_title;
$evento_activo = $evento_option->post_name;

$where       = "";
$where_count = "";

if( $desde!="" && $hasta!="" && $tipo!="" ) {

	$partes_desde = explode("/",$desde);
	$partes_hasta = explode("/",$hasta);

	$new_desde = $partes_desde[2]."-".$partes_desde[0]."-".$partes_desde[1];
	$new_hasta = $partes_hasta[2]."-".$partes_hasta[0]."-".$partes_hasta[1];

	$where       = ' a.evento="'.$tipo.'" AND b.email=a.email AND a.evento=b.evento AND left(a.fecha,10) BETWEEN "'.$new_desde.'" AND "'.$new_hasta.'" ';
	$where_count = ' WHERE evento="'.$tipo.'" AND left(fecha,10) BETWEEN "'.$new_desde.'" AND "'.$new_hasta.'" ';

} else if( $tipo!="" ) {
	$where       = ' a.evento="'.$tipo.'" AND b.email=a.email AND a.evento=b.evento ';
	$where_count = ' WHERE evento="'.$tipo.'" ';
} else if( $desde!="" && $hasta!="" ) {

	$partes_desde = explode("/",$desde);
	$partes_hasta = explode("/",$hasta);

	$new_desde = $partes_desde[2]."-".$partes_desde[0]."-".$partes_desde[1];
	$new_hasta = $partes_hasta[2]."-".$partes_hasta[0]."-".$partes_hasta[1];

	$where       = ' b.email=a.email AND left(a.fecha,10) BETWEEN "'.$new_desde.'" AND "'.$new_hasta.'" ';
	$where_count = ' WHERE left(fecha,10) BETWEEN "'.$new_desde.'" AND "'.$new_hasta.'" ';	

} else {
	$where       = ' a.evento="'.$evento_activo.'" AND b.email=a.email AND a.evento=b.evento ';
	$where_count = ' WHERE evento="'.$evento_activo.'" ';	
	$tipo = $evento_activo;	
}

$limit  = 10;
$offset = ( $pagenum - 1 ) * $limit;
$total  = $wpdb->get_var( "SELECT COUNT('id') 
		                  FROM {$wpdb->prefix}encuesta_satisfaccion $where_count" );

$num_of_pages = ceil( $total / $limit );

$entries = $wpdb->get_results( "SELECT * 
							    FROM {$wpdb->prefix}encuesta_satisfaccion a,{$wpdb->prefix}inscripcion_eventos b
							    WHERE $where 
							    order by a.id desc
							    LIMIT $offset, $limit" );

$page_links = paginate_links( array(
    'base' => add_query_arg( array( 'pagenum' => '%#%' ) ),
    'format' => '',
    'prev_text' => __( '&laquo;', 'text-domain' ),
    'next_text' => __( '&raquo;', 'text-domain' ),
    'total' => $num_of_pages,
    'current' => $pagenum//,
    //'add_args' => array( 'q' => $q )
));

$tipos = "";
$args = array(
    'post_type' => 'eventos',
    'orderby'   => 'date',
	'order'     => 'DESC'
);
$query = new WP_Query($args);
$c = 0;
if( $query->have_posts() ) {
	while( $query->have_posts() ) {
		$query->the_post();	

		$titulo = get_the_title();
		$slug   = $post->post_name;

		$tipos[$c] = array("titulo"=>$titulo,"slug"=>$slug);
		$c++;

    }
}

?>

<style>
	.subsubsub li {
		display: block !important;
	}	
</style>

<div class="wrap">

	<h1>Encuestas de satisfacción</h1>

	<div id="datepicker"></div>

	<ul class="subsubsub">
		<!--li class="all"><b>Evento actualmente activo</b> : <?php //echo $evento_nombre; ?></li-->
		<li class="all"><b>Total Encuestados</b> : <span class="count" id="publicados_count" ><?php echo $total; ?></span></li>
	</ul>				

	<div class="tablenav top">
		<br class="clear">
	</div>

	<div class="tablenav">

		<div class="alignleft actions">
			<form action="<?php echo admin_url('admin.php'); ?>" method="get" >
				<input type="hidden" name="page" value="mm-encuesta/inicio.php">

			    <select name="tipo">
	                <?php foreach ($tipos as $key): ?>
	                <option <?php if( $key['slug']==$tipo ){ echo "selected='selected'"; } ?> value="<?php echo $key['slug'];?>" ><?php echo $key['titulo'];?></option>
	                <?php endforeach; ?>
		        </select>			    			    

			    <input type="text" size="15" name="desde" id="desde" placeholder="Fecha desde" value="<?php echo $desde;?>" >
			    <input type="text" size="15" name="hasta" id="hasta" placeholder="fecha hasta" value="<?php echo $hasta;?>" >
			    <input type="submit" class="button-secondary" value="Filtrar" >			    

			</form>		 
		</div>	

		<div class="alignleft actions">
			<form id="form_export" action="<?php echo admin_url('admin-ajax.php'); ?>" method="post" >
				<input type="hidden" name="action" value="my_action2" >
				<input type="hidden" name="tipo" value="<?php echo $tipo; ?>" >
				<input type="hidden" name="desde" value="<?php echo @$new_desde; ?>" >
				<input type="hidden" name="hasta" value="<?php echo @$new_hasta; ?>" >
				<input type="button" onclick="getConfirmation();" class="button-primary" value="Exportar Resultados" style="width:150px !important;" >
			</form>
		</div>		

	</div>

	<table class="wp-list-table widefat fixed striped posts">

		<thead>
		<tr>
			<th class="manage-column column-date">Pregunta 1</th>	
			<th class="manage-column column-date">Pregunta 2</th>	
			<th class="manage-column column-date">Pregunta 3</th>	
			<th class="manage-column column-date">Pregunta 4</th>	
			<th class="manage-column column-date">Comentario</th>	
			<th class="manage-column column-date">Nombre</th>	
			<th class="manage-column column-date">Email</th>	
			<th class="manage-column column-date">Fecha</th>	
		</tr>
		</thead>
		<tbody id="the-list">
		<?php if( count($entries)>0 ): ?>
		<?php foreach ($entries as $key): ?>
			<tr class="iedit author-self level-0 post-1 type-post status-publish format-standard hentry category-sin-categoria" >
				<td class="title column-title has-row-actions column-primary page-title" ><?php echo $key->respuesta_1; ?></td>
				<td class="title column-title has-row-actions column-primary page-title" ><?php echo $key->respuesta_2; ?></td>				
				<td class="title column-title has-row-actions column-primary page-title" ><?php echo $key->respuesta_3; ?></td>
				<td class="title column-title has-row-actions column-primary page-title" ><?php echo $key->respuesta_4; ?></td>
				<td class="title column-title has-row-actions column-primary page-title" ><?php echo $key->comentario; ?></td>
				<td class="title column-title has-row-actions column-primary page-title" ><?php echo $key->nombre; ?></td>
				<td class="title column-title has-row-actions column-primary page-title" ><?php echo $key->email; ?></td>
				<td class="title column-title has-row-actions column-primary page-title" ><?php echo $key->fecha; ?></td>
			</tr>
		<?php endforeach; ?>
		<?php else: ?>
			<tr class="iedit author-self level-0 post-1 type-post status-publish format-standard hentry category-sin-categoria" >
				<td colspan="7" style="text-align: center;" class="title column-title has-row-actions column-primary page-title" >No existen datos para esta encuesta</td>
			</tr>			
		<?php endif; ?>
		</tbody>

	</table>

	<?php  
	if ( $page_links ) {
	    echo '<div class="tablenav"><div class="tablenav-pages" style="margin: 1em 0">' . $page_links . '</div></div>';
	}
	?>	

	<div id="ajax-response"></div>
</div>





