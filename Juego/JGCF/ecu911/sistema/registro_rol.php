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
   $query = mysqli_query($conexion,"SELECT * FROM roles WHERE nombre_Rol = '$nombre'  ");
   mysqli_close($conexion);
            # Guardo en un array lo que devuelva query
   $result =mysqli_fetch_array($query);
            # Si encontro un registro
   if($result>0){
    $alert='<p class="msg_error">El rol ya esta resgitrado</p>';
  }else{
    include "../conexion.php";
                   # Si seinserta en msql
    $query_insert = mysqli_query($conexion,"INSERT INTO roles(nombre_Rol) VALUES ('$nombre')");
    if( $query_insert){
      $alert='<p class="msg_save">Rol agregado</p>';
    }else{
      $alert='<p class="msg_error">Error al crear el rol</p>';
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
  <title>Registro de Rol</title>
</head>
<body>
	<!--Cargamos con php para llamar varias veces el codigo-->
	<?php include "include/header.php"?>
 <!--Llamamos a la conexion db-->
 <?php include "../conexion.php"; ?>
 <section id="container">
  <!--Aqui comiensa ha crear el registro-->
  <div class="form_register">
    <h1>Registro Rol</h1>
    <hr>
    <!-- Si el alert no esta vacio, imprime algo-->
    <div class="alert"> <?php echo isset($alert) ? $alert: '';?></div>
    <form action="" method="post">
      <label for="nombre">Nombre del Rol</label>
      <input type="text" name="nombre" id="nombre" placeholder="Ingrese el Nombre Completo" autocomplete="off">
      <input type="submit" value="Incluir nuevo Rol" class="btn_save">
    </form>
  </div>
</section>
<!--Cargamos con php para llamar varias veces el codigo-->
<?php include "include/footer.php"?>

</body>
</html>