function activarBotones() {
  $( '#imgEcoObsrimTrim' ).on( 'click', function() {
    $('#popupTitle').html("Imágen Ecografía Primer Trimestre");
    $('#popupBody').html("<img src='img/eco1.png' class='img-fluid' alt='Responsive image'>");
    $('#popupGenerico').modal('show')
  });
  $( '#imgEcoObstSegTrim' ).on( 'click', function() {
    $('#popupTitle').html("Imágen Ecografía Segundo - Tercer Trimestre");
    $('#popupBody').html("<img src='img/eco2.png' class='img-fluid' alt='Responsive image'>");
    $('#popupGenerico').modal('show')
  });
  $( '#imgEcoDoppler' ).on( 'click', function() {
    $('#popupTitle').html("Imágen Ecografía Doppler Materno - Fetal");
    $('#popupBody').html("<img src='img/eco3.png' class='img-fluid' alt='Responsive image'>");
    $('#popupGenerico').modal('show')
  });
 
 $( '#fNacimiento').on('change', function() {
      localStorage.fnac = $("#fNacimiento").val();
      localStorage.edad = calcularEdad();
     $("#edad").val(localStorage.edad);
 });
  
  $( "#fechaMaterna").on('change', function() {
      localStorage.fnac = $("#fechaMaterna").val();
      localStorage.edad = calcularEdadMaterna();
     $("input[name='edad_materna']").val(localStorage.edad);
 });
  

 //obsoleta
// $("input[name='fum']").on('change', function() {
//   localStorage.fum = $(this).val();
//   localStorage.fee = $("input[name='fee']").val();
//   localStorage.eg = calcularEG();
//   $("input[name='fum']").val(localStorage.fum); 
//   $("input[name='eg']").val(localStorage.eg);   
//   if (this.id != "fum-dos"){
//	   $('#fum-dos').datepicker('setValue', localStorage.fum);
//   }
//   if (this.id != "fum-tres"){
//	   $('#fum-tres').datepicker('setValue', localStorage.fum);
//   }
//   if (this.id != "fum-cuatro"){
//	   $('#fum-cuatro').datepicker('setValue', localStorage.fum);
//   }
//   if (this.id != "fum-cinco"){
//	   $('#fum-cinco').datepicker('setValue', localStorage.fum);
//   }
//   if (this.id != "fum-seis"){
//	   $('#fum-seis').datepicker('setValue', localStorage.fum);
//   }
//var semanas = Math.trunc(localStorage.eg)
//	 var dias =  Math.trunc((localStorage.eg - Math.trunc(localStorage.eg)) * 10)

//	 $('#semanasEcoGen').val(semanas);
//  $('#diasEcoGen').val(dias);
//	 $("input[name='eg']").val(localStorage.eg);
//	$('#semanasEcoObs').val(semanas);
//	$('#diasEcoObs').val(dias);
//	$( '#semanasTipoEco' ).val(semanas);
//	$( '#diasTipoEco' ).val(dias);
//	$('#semanasEcoPrim').val(semanas);
//        $( '#diasEcoPrim' ).val(dias);
//	$( '#diasEcoDopp' ).val(dias);
//	$('#semanasEcoDopp').val(semanas); 
	 //borrar los colores de las tarjetas
//	$("#ecografia\\.uno").removeClass("border-primary");
//	$("#ecografia\\.dos").removeClass("border-primary");
//	$("#ecografia\\.doppler").removeClass("border-primary");
	//determinar a quien le pongo el color
//	if (semanas < 15){
//		$("#ecografia\\.uno").addClass("border-primary");
//	}
//	else{
//		$("#ecografia\\.dos").addClass("border-primary");
//		$("#ecografia\\.doppler").addClass("border-primary");
//	}
// });
  
  $("input[name='fee']").on('change', function() {
   localStorage.fum = $("input[name='fum']").val();
   localStorage.fee = $(this).val();
   localStorage.eg = calcularEG();
   $("input[name='fee']").val(localStorage.fee);
   $("#fee").html(localStorage.fee);
   $("input[name='eg']").val(localStorage.eg);
	  
   if (this.id != "fee-dos"){
	   $('#fee-dos').datepicker('setValue', localStorage.fee);
   }
   if (this.id != "fee-tres"){
	   $('#fee-tres').datepicker('setValue', localStorage.fee);
   }
   if (this.id != "fee-cuatro"){
	   $('#fee-cuatro').datepicker('setValue', localStorage.fee);
   }
   if (this.id != "fee-cinco"){
	   $('#fee-cinco').datepicker('setValue', localStorage.fee);
   }
   if (this.id != "fee-seis"){
	   $('#fee-seis').datepicker('setValue', localStorage.fee);
   }
var semanas = Math.trunc(localStorage.eg)
	 var dias =  Math.trunc((localStorage.eg - Math.trunc(localStorage.eg)) * 10)

	 $('#semanasEcoGen').val(semanas);
	$('#diasEcoGen').val(dias);
	 $("input[name='eg']").val(localStorage.eg);
	$('#semanasEcoObs').val(semanas);
	$('#diasEcoObs').val(dias);
	$( '#semanasTipoEco' ).val(semanas);
	$( '#diasTipoEco' ).val(dias);
	$('#semanasEcoPrim').val(semanas);
        $( '#diasEcoPrim' ).val(dias);
	$( '#diasEcoDopp' ).val(dias);
	$('#semanasEcoDopp').val(semanas);
	  
	  //borrar los colores de las tarjetas
	$("#ecografia\\.uno").removeClass("border-primary");
	$("#ecografia\\.dos").removeClass("border-primary");
	$("#ecografia\\.doppler").removeClass("border-primary");
	//determinar a quien le pongo el color
	if (semanas < 15){
		$("#ecografia\\.uno").addClass("border-primary");
	}
	else{
		$("#ecografia\\.dos").addClass("border-primary");
		$("#ecografia\\.doppler").addClass("border-primary");
	}
	  
 });

}
