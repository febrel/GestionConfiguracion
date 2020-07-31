function mostar(){
    document.getElementById("caja").style.display = "block";
    document.getElementById("caja2").style.display = "none";
    
}

function ocultar(){
    document.getElementById("caja").style.display  = "none";
    document.getElementById("caja2").style.display = "block";
    
}

function mostrar_ocultar(){
    var caja = document.getElementById("caja");

    if(caja.style.display=="none"){
        mostar();
        limpiarFormulario();
        
    }else{
        ocultar();
       
    }
}

function limpiarFormulario() {
    document.getElementById("miForm").reset();
  }