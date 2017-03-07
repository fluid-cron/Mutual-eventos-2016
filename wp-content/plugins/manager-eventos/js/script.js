jQuery(document).ready(function($) 
{

	jQuery( "#desde" ).datepicker({ changeMonth: true, changeYear: true, yearRange: '1950:<?php echo date("Y");?>' });
	jQuery( "#hasta" ).datepicker({ changeMonth: true, changeYear: true, yearRange: '1950:<?php echo date("Y");?>' });

});	

function getConfirmation()
{
   var retVal = confirm("¿Desea exportar los datos?");
   if( retVal == true ){
   	  jQuery("#form_export").submit();
      return true;
   }
   else{
      return false;
   }
}