<?php
session_start();
if ($_SESSION['rol']!= 2) {
    header("location: ./");
}
    #Llamamos a la conexion db
include "../conexion.php"; 

    # Para evitar que los campos vallan vacios
if(!empty($_POST)){
    if( empty($_POST['dias']) ){
        # Si estan vacio llama a la clase msg_error para que acceda al css
        $alert='<p class="msg_error">Todos los campos son obligatorios</p>';
    }else{
        # Si estan llenos llama a la clase msg_save para que acceda al css
        $alert='<p class="msg_save"></p>';
            # Creamos variables para almacenar lo que llega del metodo POST
            $id = $_POST['id']; # Añadimos 
            $horasd=$_POST['horasd'];
            $HV=$_POST['dias']; //se recibe en dias
            $HVC=$HV*8; //conversion de los dias en horas
            if ($horasd>=$HVC) {
                include "../conexion.php";
                // Actualizar
                $sql_update =  mysqli_query($conexion,"UPDATE total_horas SET horas_disponibles =horas_disponibles-'$HVC'  WHERE id_personal = $id ");
                if(  $sql_update ){
                    $alert='<p class="msg_save">Se modificico correctamente</p>';
                    header('location: lista_peticion_vacaciones.php');
                }else{
                    $alert='<p class="msg_error">Error </p>';
                }
                mysqli_close($conexion);
            }else{
             $alert='<p class="msg_error">Error supera los días disponibles que tiene </p>';
         }


     }


 }

 /*Recuperacion de datos*/

    # Si no existe get 
 if(empty($_GET['id'])  ){
# Redirecionar, si es que esta vacio
    header('location: lista_peticion_vacaciones'); 
    mysqli_close($conexion);
}

    # La variable es igual a lo que se envio con el get
$id = $_GET['id'];



# Consulta multitabla 
$sql = mysqli_query($conexion, "SELECT p.id_personal,p.nombres, th.horas_disponibles from personal p, total_horas th WHERE th.id_personal='$id' and p.id_personal='$id'");

mysqli_close($conexion);
# Guardo el numero de filas
$result_sql = mysqli_num_rows($sql);


# Validar si hay registro 
if($result_sql == 0){
   header('location: lista_peticion_vacaciones'); 
}else{
    while($data = mysqli_fetch_array($sql)){
# Almaceno en variables el resultado de la db
        $id = $data['id_personal'];
        $nombres = $data['nombres'];
        $horasd = $data['horas_disponibles'];   
    }
}
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <link rel="shortcut icon" type="image/x-icon" href="../images/reporte.png" />
    <!--Cargamos con php para llamar varias veces el codigo-->
    <?php include "include/scripts.php";?>
    <title></title>
</head>
<body>
    <!--Cargamos con php para llamar varias veces el codigo-->
    <?php include "include/header.php"?>
    <section id="container">
        <!--Aqui comiensa ha crear el registro-->
        <div class="form_register">
            <h1></h1>
            <hr>
            <!-- Si el alert no esta vacio, imprime algo-->
            <div class="alert"> <?php echo isset($alert) ? $alert: '';?></div>
            <form action="" method="post">

                <input type="hidden" name="id"  id="id" value="<?php echo $id; ?>"> <!--Añadimos para enviarle al post idusuario-->
                <input type="hidden" name="horasd"  id="horasd" value="<?php echo $horasd; ?>"> 

                <label for="nombre">Nombre</label>
                <input type="text" name="nombre" id="nombre" disabled="true" value="<?php echo $nombres; ?>">
                <label for="nombre">Días de vacacion</label>
                <input type="text" name="dias" id="dias" placeholder="dias" >

                
                <input type="submit" value="Crear" class="btn_save">

            </form>
            
        </div>
    </section>
    <!--Cargamos con php para llamar varias veces el codigo-->
    <?php include "include/footer.php"?>

</body>
</html>