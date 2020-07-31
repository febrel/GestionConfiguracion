<?php
session_start();
if ($_SESSION['rol']!= 1) {
    header("location: ./");
}
# Para evitar que los campos vallan vacios
if(!empty($_POST)){
    if(empty($_POST['nombre'])){
            # Si estan vacio llama a la clase msg_error para que acceda al css
        $alert='<p class="msg_error">Todos los campos son obligatorios</p>';
    }else{
            # Llamamos a clase conexion para sql
       include "../conexion.php";
                # Si estan llenos llama a la clase msg_save para que acceda al css
       $alert='<p class="msg_save"></p>';
                # Creamos variables para almacenar lo que llega del metodo POST
       $nombre = $_POST['nombre'];
                # Consulta para evitar el area exista existan
       include "../conexion.php";
       $query = mysqli_query($conexion,"SELECT * FROM areas WHERE unidad_administrador = '$nombre'  ");
       mysqli_close($conexion);
                # Guardo en un array lo que devuelva query
       $result =mysqli_fetch_array($query);
                # Si encontro un registro
       if($result>0){
        $alert='<p class="msg_error">El área ya esta resgitrada</p>';
    }else{
        include "../conexion.php";
        $query_insert = mysqli_query($conexion,"INSERT INTO areas(unidad_administrador) VALUES ('$nombre')");
                    # Si seinserta en msql
        if( $query_insert){
            $alert='<p class="msg_save">Área agregada</p>';
        }else{
            $alert='<p class="msg_error">Error al crear el área</p>';
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
    <link rel="stylesheet" href="css/all.min.css">
    <link rel="stylesheet" href="css/nuevo.css">
    <!--Cargamos con php para llamar varias veces el codigo-->
    <?php include "include/scripts.php";?>
    <title>Registro de Área</title>
</head>
<body>
	<!--Cargamos con php para llamar varias veces el codigo-->
	<?php include "include/header.php"?>
   <!--Llamamos a la conexion db-->
   <?php include "../conexion.php"; ?>
   <section id="container">
    <!--Aqui comiensa ha crear el registro-->
    <div class="form_register">
        <h1>Registro Área</h1>
        <hr>
        <!-- Si el alert no esta vacio, imprime algo-->
        <div class="alert"> <?php echo isset($alert) ? $alert: '';?></div>
        <form action="" method="post">
            <label for="nombre">Nombre del Área</label>
            <input type="text" name="nombre" id="nombre" placeholder="Ingrese el Nombre Completo" autocomplete="off">
            <input type="submit" value="Incluir nueva Área" class="btn_save">
        </form>        
    </div>

</section>

<!--Cargamos con php para llamar varias veces el codigo-->
<?php include "include/footer.php"?>

</body>
</html>