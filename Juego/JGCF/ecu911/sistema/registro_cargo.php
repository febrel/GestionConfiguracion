<?php
session_start();
if ($_SESSION['rol']!= 1) {
    header("location: ./");
}
    # Para evitar que los campos vallan vacios
if(!empty($_POST)){
    if(empty($_POST['nombre']) || empty($_POST['areas']) || empty($_POST['aux_cargo'])){
            # Si estan vacio llama a la clase msg_error para que acceda al css
        $alert='<p class="msg_error">Todos los campos son obligatorios</p>';
    }else{
          # Llamamos a clase conexion para sql
       include "../conexion.php";
            # Si estan llenos llama a la clase msg_save para que acceda al css
       $alert='<p class="msg_save"></p>';
            # Creamos variables para almacenar lo que llega del metodo POST
       $nombre = $_POST['nombre'];
       $area= $_POST['areas'];
       $aux_cargo= $_POST['aux_cargo'];
            # Consulta para evitar el area exista existan
       $query = mysqli_query($conexion,"SELECT * FROM cargos WHERE nombre_cargo = '$nombre'  ");
       mysqli_close($conexion);
            # Guardo en un array lo que devuelva query
       $result =mysqli_fetch_array($query);
            # Si encontro un registro
       if($result>0){
        $alert='<p class="msg_error">El cargo ya esta resgitrado</p>';
    }else{
        include "../conexion.php";
        $query_insert = mysqli_query($conexion,"INSERT INTO cargos(nombre_cargo,fk_id_areas, fk_id_aux) VALUES ('$nombre','$area','$aux_cargo')");
        if( $query_insert){
            $alert='<p class="msg_save">Cargo agregado</p>';
        }else{
            $alert='<p class="msg_error">Error al crear el cargo</p>';
        }
    }
}
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <link rel="shortcut icon" type="image/x-icon" href="../images/reporte.png" />
    <!--Cargamos con php para llamar varias veces el codigo-->
    <?php include "include/scripts.php";?>
    <title>Registro de Cargo</title>
</head>
<body>
	<!--Cargamos con php para llamar varias veces el codigo-->
	<?php include "include/header.php"?>
   <!--Llamamos a la conexion db-->
   <?php include "../conexion.php"; ?>
   <section id="container">
    <!--Aqui comiensa ha crear el registro-->
    <div class="form_register">
        <h1>Registro Cargo</h1>
        <hr>
        <!-- Si el alert no esta vacio, imprime algo-->
        <div class="alert"> <?php echo isset($alert) ? $alert: '';?></div>
        <form action="" method="post">
            <label for="nombre">Nombre del Cargo</label>
            <input type="text" name="nombre" id="nombre" placeholder="Ingrese el Nombre Completo" autocomplete="off">
            <label for="area">Areas registradas</label>
            <!--Hacer un query para consumir los roles desde la db-->
            <?php 
            $query_rol = mysqli_query($conexion, "SELECT * FROM areas");
                    # Cuenta cuantas filas devuelve el query
            $result_rol = mysqli_num_rows($query_rol);
            ?>  
            <select name="areas" id="areas" class="notItemOne">
                <?php                 
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
            <label for="aux_cargo">Jefe Intermedio Existentes</label>
            <!--Hacer un query para consumir los roles desde la db-->
            <?php 
            $query_rol = mysqli_query($conexion, "SELECT * FROM auxiliar_cargo");
                    # Cuenta cuantas filas devuelve el query
            $result_rol = mysqli_num_rows($query_rol);
            ?>  
            <select name="aux_cargo" id="aux_cargo" class="notItemOne">
                <?php                 
                    # Si encuentra algo
                if($result_rol > 0){
                    # Almacenamos en un array resultado de query
                    while($rol = mysqli_fetch_array($query_rol)){
                        ?>
                        <option value= "<?php echo $rol['id_cargo_aux']; ?>"> <?php echo $rol['nombre_cargo_aux'] ?> </option>
                        <?php
                    }
                }
                ?>                
            </select>
            <input type="submit" value="Incluir nuevo Cargo" class="btn_save">
        </form>        
    </div>
</section>

<!--Cargamos con php para llamar varias veces el codigo-->
<?php include "include/footer.php"?>

</body>
</html>