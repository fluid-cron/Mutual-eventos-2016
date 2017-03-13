jQuery(document).ready(function($) {

   jQuery("#archivo").change(function() {
      jQuery("#uploadfiles").fadeIn();
   });

   jQuery("#uploadfiles").click(function() {
      var evento_selected = jQuery("#evento").val();
      jQuery("#hidden_evento").val(evento_selected);
      jQuery("#form_upload").submit();
   });

   jQuery("#evento").change(function(){
      jQuery("#pickfiles").show();
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
   var evento = jQuery("#evento").val();
   if( evento!="" ) {
      jQuery('#archivo').trigger('click');
   }else{
      jQuery("#importlist").text("Debe seleccionar un evento");
   }
}