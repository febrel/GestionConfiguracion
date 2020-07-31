<?php
session_start();
if ($_SESSION['rol']!= 1) {
    header("location: ./");
}
#Llamamos a la conexion db
include "../conexion.php"; 

# Para evitar que los campos vallan vacios
if(!empty($_POST) ){
    if(empty($_POST['nombre']) || empty($_POST['areas'])){
# Si estan vacio llama a la clase msg_error para que acceda al css
        $alert='<p class="msg_error">Todos los campos son obligatorios</p>';
    }else{

# Si estan llenos llama a la clase msg_save para que acceda al css
        $alert='<p class="msg_save"></p>';
            # Creamos variables para almacenar lo que llega del metodo POST
            $id_cargos = $_POST['id_cargos']; # Añadimos 
            $nombre = $_POST['nombre'];
            $id_areas = $_POST['areas'];
# Consulta para evitar que el usuario y correo se repitan al actualizar alguno !, solo permite 1 vez 
            $query = mysqli_query($conexion, "SELECT * FROM cargos WHERE  (nombre_cargo = '$nombre' AND id_cargos !=  $id_cargos)  ");
# Guardo en un array lo que devuelva query
            $result = mysqli_fetch_array($query);            
# Si encontro un registro
            if($result > 0){
                $alert='<p class="msg_error">El cargo ya existe</p>';
            }else{
# Validar la clave, para actualizar sin cambiar la misma
                $sql_update =  mysqli_query($conexion,"UPDATE cargos SET nombre_cargo ='$nombre'  , fk_id_areas='$id_areas' WHERE id_cargos = $id_cargos ");
# Si se inserta en msql
                if(  $sql_update ){
                    $alert='<p class="msg_save">Cargo actualizado</p>';
                    header('location: lista_cargo.php');
                }else{
                    $alert='<p class="msg_error">Error al actualizar Cargo</p>';
                }
            }
        }
        mysqli_close($conexion);
    }
    /*=============================Recuperacion de datos=================================*/
    # Si no existe get 
    if(empty($_GET['id'])){
        # Redirecionar, si es que esta vacio
        header('location: lista_cargo'); 
        mysqli_close($conexion);
    }
# La variable es igual a lo que se envio con el get
    $id_cargos = $_GET['id'];
    # Consulta multitabla con alias entre usuario y rol
    $sql = mysqli_query($conexion, "SELECT * FROM cargos , areas, auxiliar_cargo  where fk_id_areas=id_areas and id_cargos=$id_cargos and fk_id_aux=id_cargo_aux");

    mysqli_close($conexion);
    # Guardo el numero de filas
    $result_sql = mysqli_num_rows($sql);


    # Validar si hay registro 
    if($result_sql == 0){
     header('location: lista_cargo'); 
 }else{


    while($data = mysqli_fetch_array($sql)){

            # Almaceno en variables el resultado de la db
        $id_cargos= $data['id_cargos'];
        $nombres = $data['nombre_cargo'];
        $id_areas = $data['id_areas'];
        $unidad_administrador=$data['unidad_administrador'] ; 
        $id_cargo_aux=$data['id_cargo_aux'];
        $nombre_aux=$data['nombre_cargo_aux'] ; 
    }

    $con=1;
    while ( $con<= 7) {
       if( $id_areas == $con){
          $option3 = '<option value= "'.$id_areas.'" select > '.$unidad_administrador.'</option>';
      }
      $con++;
  }

  $con1=1;
  while ( $con1<= 4) {
   if( $id_cargo_aux == $con1){
      $option1 = '<option value= "'.$id_cargo_aux.'" select > '.$nombre_aux.'</option>';
  }
  $con1++;
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

            <h1>Actualizar Cargos</h1>
            <hr>

            <!-- Si el alert no esta vacio, imprime algo-->
            <div class="alert"> <?php echo isset($alert) ? $alert: '';?></div>

            <form action="" method="post">

                <input type="hidden" name="id_cargos" value="<?php echo $id_cargos; ?>"> <!--Añadimos para enviarle al post idusuario-->

                <label for="nombre">Nombre Cargo</label>
                <input type="text" name="nombre" id="nombre" placeholder="Nombre Completo" value="<?php echo $nombres; ?>">

                <label for="area">Areas registradas</label>

                <!--Hacer un query para consumir los roles desde la db-->
                <?php 
                include "../conexion.php";
                $query_rol = mysqli_query($conexion, "SELECT * FROM areas");
                mysqli_close($conexion);
                    # Cuenta cuantas filas devuelve el query
                $result_rol = mysqli_num_rows($query_rol);

                ?>  

                <select name="areas" id="areas" class="notItemOne">
                    <?php 
                    echo $option3;
                    # Si encuentra algo
                    if($result_rol > 0){

                    # Almacenamos en un array resultado de query
                        while($rol = mysqli_fetch_array($query_rol)){
                            ?>
                            <option value= "<?php echo $rol['id_areas']; ?>"> <?php echo $rol['unidad_administrador'] ?> </option>
                            <?php

                        }
                    }

                    ?>

                </select>

                <label for="area">Jefe Intermedio</label>

                <!--Hacer un query para consumir los roles desde la db-->
                <?php 
                include "../conexion.php";
                $query_rol = mysqli_query($conexion, "SELECT * FROM auxiliar_cargo");
                mysqli_close($conexion);
                    # Cuenta cuantas filas devuelve el query
                $result_rol = mysqli_num_rows($query_rol);

                ?>  

                <select name="cargo" id="cargo" class="notItemOne">
                    <?php 
                    echo $option1;
                    # Si encuentra algo
                    if($result_rol > 0){

                    # Almacenamos en un array resultado de query
                        while($rol = mysqli_fetch_array($query_rol)){
                            ?>
                            <option value= "<?php echo $rol['id_cargo_aux']; ?>"> <?php echo $rol['nombre_cargo'] ?> </option>
                            <?php

                        }
                    }

                    ?>

                </select>


                <input type="submit" value="Actualizar cargo" class="btn_save">

            </form>

        </div>

    </section>

    <!--Cargamos con php para llamar varias veces el codigo-->
    <?php include "include/footer.php"?>

</body>
</html>