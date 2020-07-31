<?php
session_start();
if ($_SESSION['rol']!= 1) {
    header("location: ./");
}

#Llamamos a la conexion db
include "../conexion.php"; 

# Para evitar que los campos vallan vacios
if(!empty($_POST)){
    if(empty($_POST['nombre']) ){

# Si estan vacio llama a la clase msg_error para que acceda al css
        $alert='<p class="msg_error">Todos los campos son obligatorios</p>';
    }else{
# Si estan llenos llama a la clase msg_save para que acceda al css
        $alert='<p class="msg_save"></p>';
            # Creamos variables para almacenar lo que llega del metodo POST
            $id_areas = $_POST['id_areas']; # Añadimos 
            $nombre = $_POST['nombre'];
            # Consulta para evitar que el usuario y correo se repitan al actualizar alguno !, solo permite 1 vez 
            $query = mysqli_query($conexion, "SELECT * FROM areas WHERE  (unidad_administrador = '$nombre' AND id_areas !=  $id_areas)  ");
            # Guardo en un array lo que devuelva query
            $result = mysqli_fetch_array($query);
            # Si encontro un registro
            if($result > 0){
                $alert='<p class="msg_error">El área ya existe</p>';
            }else{
            # Validar la clave, para actualizar sin cambiar la misma
                $sql_update =  mysqli_query($conexion,"UPDATE areas SET unidad_administrador ='$nombre'  WHERE id_areas = $id_areas ");
                # Si se inserta en msql
                if(  $sql_update ){
                    $alert='<p class="msg_save">Área actualizada</p>';
                    header('location: lista_areas.php');
                }else{
                    $alert='<p class="msg_error">Error al actualizar el Área</p>';
                }
            }
        }
        mysqli_close($conexion);
    }
    /*Recuperacion de datos*/
    # Si no existe get 
    if(empty($_GET['id'])){
        # Redirecionar, si es que esta vacio
        header('location: lista_areas'); 
        mysqli_close($conexion);
    }
    # La variable es igual a lo que se envio con el get
    $id_areas = $_GET['id'];
    # Consulta multitabla 
    $sql = mysqli_query($conexion, "SELECT a.id_areas, a.unidad_administrador from areas a where id_areas=$id_areas");
    mysqli_close($conexion);
    # Guardo el numero de filas
    $result_sql = mysqli_num_rows($sql);
    # Validar si hay registro 
    if($result_sql == 0){
       header('location: lista_areas'); 
   }else{
        while($data = mysqli_fetch_array($sql)){
    # Almaceno en variables el resultado de la db
            $id_areas = $data['id_areas'];
            $nombres = $data['unidad_administrador'];
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
    <title>Actualizar Area</title>
</head>
<body>
    <!--Cargamos con php para llamar varias veces el codigo-->
    <?php include "include/header.php"?>
    <section id="container">
        <!--Aqui comiensa ha crear el registro-->
        <div class="form_register">

            <h1>Actualizar Área</h1>
            <hr>
            <!-- Si el alert no esta vacio, imprime algo-->
            <div class="alert"> <?php echo isset($alert) ? $alert: '';?></div>
            <form action="" method="post">
                <input type="hidden" name="id_areas" value="<?php echo $id_areas; ?>"> <!--Añadimos para enviarle al post idusuario-->
                <label for="nombre">Nombre Área</label>
                <input type="text" name="nombre" id="nombre" placeholder="Nombre Completo" value="<?php echo $nombres; ?>">
                <input type="submit" value="Actualizar Área" class="btn_save">
            </form>
        </div>
    </section>
    <!--Cargamos con php para llamar varias veces el codigo-->
    <?php include "include/footer.php"?>

</body>
</html>