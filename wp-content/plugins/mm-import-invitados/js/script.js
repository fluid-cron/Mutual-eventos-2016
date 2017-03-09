jQuery(document).ready(function($) {

   jQuery("#archivo").change(function() {
      jQuery("#uploadfiles").fadeIn();
   });

});	

function getConfirmation() {
   var retVal = confirm("¿Desea exportar los datos?");
   if( retVal == true ){
   	  jQuery("#form_export").submit();
      return true;
   }
   else{
      return false;
   }
}

function uploadFile() {
   jQuery('#archivo').trigger('click');
}

function upload_invitacion() {
   jQuery("#form").submit();
}