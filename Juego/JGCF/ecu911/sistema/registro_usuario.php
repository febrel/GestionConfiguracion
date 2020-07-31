<?php

session_start();
if ($_SESSION['rol']!= 1) {
  header("location: ./");
}

    # Para evitar que los campos vallan vacios
if(!empty($_POST)){

  if(empty($_POST['nombre']) || empty($_POST['nro_identificacion']) || empty($_POST['correo']) || empty($_POST['usuario']) || empty($_POST['clave']) || empty($_POST['rol']) || empty($_POST['cargo'])){

            # Si estan vacio llama a la clase msg_error para que acceda al css
    $alert='<p class="msg_error">Todos los campos son obligatorios</p>';
  }else{

            # Llamamos a clase conexion para sql
   include "../conexion.php";

            # Si estan llenos llama a la clase msg_save para que acceda al css
   $alert='<p class="msg_save"></p>';

            # Creamos variables para almacenar lo que llega del metodo POST
   $nombre = $_POST['nombre'];
   $cedula = $_POST['nro_identificacion'];
   $email = $_POST['correo'];
   $user1 = $_POST['usuario'];
   $clave =md5 ($_POST['clave']);
   $rol = $_POST['rol'];
   $cargo = $_POST['cargo'];


            # Consulta para evitar que el usuario y el correo existan
   $query = mysqli_query($conexion,"SELECT * FROM personal WHERE usuario = '$user1' OR correo = '$email' ");

            # Guardo en un array lo que devuelva query
   $result =mysqli_fetch_array($query);

            # Si encontro un registro
   if($result>0){
    $alert='<p class="msg_error">El correo o el usuario existen</p>';
  }else{
    include "../conexion.php";
    $query_insert = mysqli_query($conexion,"INSERT INTO personal(nombres,nro_identificacion,correo,usuario,pass, fk_rol, fk_cargo) VALUES ('$nombre', '$cedula','$email', '$user1','$clave','$rol' ,'$cargo')");

    $auxid = mysqli_query($conexion,"SELECT p.id_personal from personal p where p.usuario='$user1'");

    $auxguid = mysqli_fetch_array($auxid);
    $auxvar= $auxguid['id_personal'];

    $query_insert = mysqli_query($conexion,"INSERT INTO total_horas(id_personal,horas_disponibles) VALUES ($auxvar,240)");

                # Si seinserta en msql
    if( $query_insert){
      
      $alert='<p class="msg_save">Usuario agregado</p>';
      header("location: lista_usuario.php");

    }else{
      $alert='<p class="msg_error">Error al crear el usuario</p>';
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
  <title>Registro de Usuario</title>
</head>
<body>
	<!--Cargamos con php para llamar varias veces el codigo-->
	<?php include "include/header.php"?>

 <!--Llamamos a la conexion db-->
 <?php include "../conexion.php"; ?>

 <section id="container">
  <!--Aqui comiensa ha crear el registro-->
  <div class="form_register">
    <h1>Registro Usuario</h1>

    <hr>

    <!-- Si el alert no esta vacio, imprime algo-->
    <div class="alert"> <?php echo isset($alert) ? $alert: '';?></div>

    <form action="" method="post">
      <label for="nombre">Nombre</label>
      <input type="text" name="nombre" id="nombre" placeholder="Nombre Completo">

      <label for="nro_identificacion">Cedula</label>
      <input type="nro_identificacion" name="nro_identificacion" id="nro_identificacion" placeholder="Cédula" autocomplete="off">

      <label for="correo">Correo</label>
      <input type="correo " name="correo" id="correo" placeholder="Correo Electronico" autocomplete="off">

      <label for="usuario">Usuario</label>
      <input type="text" name="usuario" id="usuario" placeholder="Usuario" autocomplete="off">

      <label for="clave">Contraseña</label>
      <input type="password" name="clave" id="clave" placeholder="Contraseña">

      <label for="rol">Rol</label>

      <!--Hacer un query para consumir los roles desde la db-->
      <?php 
      include "../conexion.php";
      $query_rol = mysqli_query($conexion, "SELECT * FROM roles where id_roles!=1");
      mysqli_close($conexion);
                    # Cuenta cuantas filas devuelve el query
      $result_rol = mysqli_num_rows($query_rol);

      ?>  

      <select name="rol" id="rol" class="notItemOne">
       <option> </option>
       <?php 

                    # Si encuentra algo
       if($result_rol > 0){

                    # Almacenamos en un array resultado de query
        while($rol = mysqli_fetch_array($query_rol)){
          ?>
          <option value= "<?php echo $rol['id_roles']; ?>"> <?php echo $rol['nombre_Rol'] ?> </option>
          <?php

        }
      }

      ?>

    </select>


    <label for="cargo">Cargo</label>

    <!--Hacer un query para consumir los roles desde la db-->
    <?php 
    include "../conexion.php";
    $query_cargo = mysqli_query($conexion, "SELECT * FROM cargos order by nombre_cargo ASC");
    mysqli_close($conexion); 
               # Cuenta cuantas filas devuelve el query
    $result_cargo = mysqli_num_rows($query_cargo);

    ?>  

    <select name="cargo" id="cargo" class="notItemOne">
      <option> </option>
      <?php 

               # Si encuentra algo
      if($result_cargo > 0){

               # Almacenamos en un array resultado de query
       while($cargo = mysqli_fetch_array($query_cargo)){
         ?>
         <option value= "<?php echo $cargo['id_cargos']; ?>"> <?php echo $cargo['nombre_cargo'] ?> </option>
         <?php

       }

     }

     ?>
     <input type="submit" value="Crear Usuario" class="btn_save">
   </form>

 </div>

</section>

<!--Cargamos con php para llamar varias veces el codigo-->
<?php include "include/footer.php"?>

</body>
</html>