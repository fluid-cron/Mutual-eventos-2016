<?php
$evento_option = get_field('evento_activo','option');
$evento_nombre = $evento_option->post_title;
$evento_activo = $evento_option->post_name;

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
<div class="wrap wpjb">

    <h1> XLS Import <input type="button" onclick="location.href='<?php echo get_template_directory_uri()."/usuarios_asistencia_mutual_ejemplo.xlsx";?>'" class="button-primary" value="DESCARGAR EJEMPLO EXCEL A IMPORTAR" style="width:300px !important;" ></h1> 

    <div id="container" style="position: relative;">        

        <p>Sección en la que importamos la nueva base de asistentes al evento mutual especifico, antes de importar favor de exportar la actual como ejemplo</p>

        <br>
        <select name="data" id="evento">
            <option value="">Seleccionar evento</option>
			<?php  
			foreach ($tipos as $key) {
				echo '<option value="'.$key["slug"].'">'.$key["titulo"].'</option>';
			}
			?>
        </select>
        <br><br>
        <a href="#" id="pickfiles" class="button" style="position: relative; z-index: 1;display: none;">
            <span class="wpjb-upload-empty" onclick="uploadFile();" >Select File</span>
        </a>
        <div id="importlist" style="margin: 15px 0 15px 0; font-size:12px"></div>
        <input id="uploadfiles" style="display: none;" type="button" value="Upload e importar datos" class="button-primary" name="Submit">    

    </div>    

    <form style="display: none;" id="form_upload" action="<?php echo esc_url( admin_url('admin-post.php') ); ?>" method="post" enctype="multipart/form-data" target="myiframe" >
        
        <input type="hidden" name="action" value="upload_asistencia_xls" >
        <input type="hidden" name="evento" id="hidden_evento" value="" >
        <input type="file" name="archivo" id="archivo" accept=".csv, application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, application/vnd.ms-excel" />

    </form>
    <iframe style="display: none;" name="myiframe" id="myiframe" frameborder="0" ></iframe>

</div>

