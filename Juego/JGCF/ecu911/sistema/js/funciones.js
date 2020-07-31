
function agregaform(datos){
	d=datos.split('||');
	$('#idpersona').val(d[0]);
	$('#nombreu').val(d[1]);
	$('#hoursan').val(d[2]);
	$('#hoursac').val(d[3]);
	
}

function actualizaDatos(){


	id=$('#idpersona').val();
	nombre=$('#nombreu').val();
	hoursan=$('#hoursan').val();
	hoursac=$('#hoursac').val();
	
	cadena= "id=" + id +
			"&nombre=" + nombre + 
			"&hoursan=" + hoursan+
			"&hoursac=" + hoursac ;

	$.ajax({
		type:"POST",
		url:"php/actualizaDatos.php",
		data:cadena,
		success:function(r){
			
			if(r==1){
				location.reload();
				alertify.success("Actualizado con exito :)");
			}else{
				alertify.error("Fallo el servidor :(");
			}
		}
	});

}
