<?php
define('WP_DEBUG', true);
$pagenum = isset( $_GET['pagenum'] ) ? absint( $_GET['pagenum'] ) : 1;
$tipo    = isset( $_GET['tipo'] ) ? $_GET['tipo'] : "";

$where = $where_count = "";
if( $tipo!="" )
{
	$where = ' AND form_tipo="'.$tipo.'" ';
	$where_count = ' WHERE form_tipo="'.$tipo.'" ';
}

$limit = 10;
$offset = ( $pagenum - 1 ) * $limit;
$total = $wpdb->get_var( "SELECT COUNT('id') 
		                  FROM {$wpdb->prefix}formularios $where_count" );

$num_of_pages = ceil( $total / $limit );

$entries = $wpdb->get_results( "SELECT * 
							    FROM {$wpdb->prefix}formularios,{$wpdb->prefix}formularios_tipos 
							    WHERE form_tipo=form_tipo_id $where 
							    LIMIT $offset, $limit" );

$page_links = paginate_links( array(
    'base' => add_query_arg( array( 'pagenum' => '%#%' ) ),
    'format' => '',
    'prev_text' => __( '&laquo;', 'text-domain' ),
    'next_text' => __( '&raquo;', 'text-domain' ),
    'total' => $num_of_pages,
    'current' => $pagenum,
    'add_args' => array( 'q' => $q )
));

$tipos = $wpdb->get_results( "SELECT * FROM {$wpdb->prefix}formularios_tipos" );

?>
<div class="wrap">

	<h1>Formularios</h1>

	<ul class="subsubsub">
		<li class="all">Total Registros : <span class="count" id="publicados_count" ><?php echo $total; ?></span></li>
	</ul>

	<!--p class="search-box">
		<label class="screen-reader-text" for="post-search-input">Buscar Tweet:</label>
		<input type="search" id="post-search-input" name="s" value="">
		<input type="submit" id="search-submit" class="button" value="Buscar entradas">
	</p-->

	<div class="tablenav top">
		<br class="clear">
	</div>

	<div class="tablenav">
		<div class="alignleft actions">
			<form action="<?php echo admin_url('admin.php'); ?>" method="get">
				<input type="hidden" name="page" value="manager-formularios/inicio.php">
			    <select name="tipo">
		        	<option value="" >Ver todos los formularios</option>
	                <?php foreach ($tipos as $key): ?>
	                <option value="<?php echo $key->form_tipo_id;?>" <?php if( $key->form_tipo_id==$tipo ){ echo "selected='selected'"; } ?> ><?php echo $key->form_tipo_nombre;?></option>
	                <?php endforeach; ?>
		        </select>			    			    
			    <input type="submit" class="button-secondary" value="Filter" >
			</form>		    
		</div>
	</div>

	<table class="wp-list-table widefat fixed striped posts">

		<thead>
		<tr>
			<td class="manage-column column-cb check-column"></td>
			<th class="manage-column column-date">ID</th>	
			<th class="manage-column column-date">Tipo</th>	
			<th class="manage-column column-date">Fecha</th>	
		</tr>
		</thead>
		<tbody id="the-list">
		<?php foreach ($entries as $key): ?>
		<tr class="iedit author-self level-0 post-1 type-post status-publish format-standard hentry category-sin-categoria" >
			<th scope="row" class="check-column"></th>
			<td class="title column-title has-row-actions column-primary page-title" ><?php echo $key->form_id; ?></td>
			<td class="title column-title has-row-actions column-primary page-title" ><?php echo $key->form_tipo_nombre; ?></td>
			<td class="title column-title has-row-actions column-primary page-title" ><?php echo $key->form_fecha; ?></td>
		</tr>
	<?php endforeach; ?>
		</tbody>

	</table>

	<?php  
	if ( $page_links )
	{
	    echo '<div class="tablenav"><div class="tablenav-pages" style="margin: 1em 0">' . $page_links . '</div></div>';
	}
	?>	

	<div id="ajax-response"></div>
</div>





