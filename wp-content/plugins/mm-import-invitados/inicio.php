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
    
    <h1>
        Import 
    </h1>
    
    <form method="post" enctype="multipart/form-data">

        <div id="container" style="position: relative;">

            <br>

            <select name="data" id="wpjb-data-import">
                <option value="">Seleccionar evento</option>
    			<?php  
    			foreach ($tipos as $key) {
    				echo '<option value="'.$key["slug"].'">'.$key["titulo"].'</option>';
    			}
    			?>
            </select>

            <br><br>

            <a href="#" id="pickfiles" class="button" style="position: relative; z-index: 1;">
                <span class="wpjb-upload-empty" onclick="uploadFile();" >Select File</span>
            </a>

            <div id="filelist" style="margin: 15px 0 15px 0; font-size:12px">
    	        <div>
    	        	
    	        </div>
            </div>

            <input id="uploadfiles" style="display: none;" type="button" value="Upload e importar datos" class="button-primary" name="Submit">    

        </div>

    </form>

    <form style="display: none;" id="form" action="upload_file" method="POST" enctype="multipart/form-data" target="iframe" >
        <input type="file" name="archivo" id="archivo" accept=".csv, application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, application/vnd.ms-excel" />
    </form>
    <iframe style="display: none;" name="iframe" frameborder="0" ></iframe>        

</div>

