<?php

session_start();
if ($_SESSION['rol']!= 1) {
  header("location: ./");
}
#Llamamos a la conexion db
include "../conexion.php"; 
# Para evitar que los campos vallan vacios
if(!empty($_POST)){
  if(empty($_POST['nombre']) || empty($_POST['correo']) || empty($_POST['usuario']) || empty($_POST['rol']) || empty($_POST['cargo'])){
# Si estan vacio llama a la clase msg_error para que acceda al css
    $alert='<p class="msg_error">Todos los campos son obligatorios</p>';
  }else{
# Si estan llenos llama a la clase msg_save para que acceda al css
    $alert='<p class="msg_save"></p>';
            # Creamos variables para almacenar lo que llega del metodo POST
            $id_personal = $_POST['id_personal']; # Añadimos 
            $nombre = $_POST['nombre'];
            $email = $_POST['correo'];
            $user = $_POST['usuario'];
            $clave =md5 ($_POST['clave']);
            $rol = $_POST['rol'];
            $cargo= $_POST['cargo'];
            # Consulta para evitar que el usuario y correo se repitan al actualizar alguno !, solo permite 1 vez 
            $query = mysqli_query($conexion, "SELECT * FROM personal WHERE  (usuario = '$user' AND id_personal !=  $id_personal) OR (correo = '$email' AND id_personal !=  $id_personal) ");
            # Guardo en un array lo que devuelva query
            $result = mysqli_fetch_array($query);
            # Si encontro un registro
            if($result > 0){
              $alert='<p class="msg_error">El correo o el usuario existen</p>';
            }else{
              # Validar la clave, para actualizar sin cambiar la misma
              if (empty($_POST['clave'])) {
                $sql_update =  mysqli_query($conexion,"UPDATE personal SET nombres ='$nombre', correo='$email', usuario='$user', fk_rol ='$rol', fk_cargo ='$cargo'  WHERE id_personal = $id_personal ");
              }else{
                $sql_update =  mysqli_query($conexion,"UPDATE personal SET nombres ='$nombre', correo='$email', usuario='$user',pass='$clave', fk_rol ='$rol', fk_cargo ='$cargo'  WHERE id_personal = $id_personal ");
              }
              # Si se inserta en msql
              if(  $sql_update ){
                $alert='<p class="msg_save">Usuario actualizado</p>';
                header('location: lista_usuario.php');
              }else{
                $alert='<p class="msg_error">Error al actualizar usuario</p>';
              }
            }
          }
          mysqli_close($conexion);
        }
/*=============================Recuperacion de datos=================================*/
    # Si no existe get 
        if(empty($_GET['id'])){

        # Redirecionar, si es que esta vacio
          header('location: lista_usuario');
          mysqli_close($conexion); 
        }
    # La variable es igual a lo que se envio con el get
        $id_personal = $_GET['id'];
    # Consulta multitabla 
        $sql = mysqli_query($conexion, "SELECT u.id_personal, u.nombres, u.correo, u.usuario, (u.fk_rol) AS idrol, (r.nombre_Rol) AS rol, (c.id_cargos) as idcargo, (c.nombre_cargo) as cargo FROM personal u INNER JOIN roles r, cargos c where u.fk_rol = r.id_roles and id_personal= $id_personal and c.id_cargos = u.fk_cargo");
    # Guardo el numero de filas
        $result_sql = mysqli_num_rows($sql);
  //contador para saber cuantos roles y cargos estan disponibles
        //roles
        $controles= mysqli_query($conexion,"SELECT count(*) as cont from roles");
        $data5 =mysqli_fetch_array($controles);
        $contr=$data5['cont']+1;
        //cargos
        $contcargos= mysqli_query($conexion,"SELECT count(*) as cont from cargos");
        $data6 =mysqli_fetch_array($contcargos);
        $contc=$data6['cont']+1;
        mysqli_close($conexion);
# Validar si hay registro 
        if($result_sql == 0){
         header('location: lista_usuario'); 
       }else{
        $option = '';
        while($data = mysqli_fetch_array($sql)){
# Almaceno en variables el resultado de la db
          $id_personal = $data['id_personal'];
          $nombres = $data['nombres'];
          $correo = $data['correo'];
          $usuario = $data['usuario'];
          $idrol = $data['idrol'];
          $rol = $data['rol'];
          $idcargo = $data['idcargo'];
          $cargo = $data['cargo'];
//seleccion del combobox correspondiente para poder editar
          $con=1;
          while ( $con<=$contr) {
           if( $idrol == $con){
            $option3 = '<option value= "'.$idrol.'" select > '.$rol.'</option>';
          }
          $con++;
        }
        $con1=1;
        while ( $con1<= $contc) {
         if( $idcargo == $con1){
          $option1 = '<option value= "'.$idcargo.'" select > '.$cargo.'</option>';
        }
        $con1++;
      }
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
    <title>Actualizar  Usuario</title>
  </head>
  <body>
    <!--Cargamos con php para llamar varias veces el codigo-->
    <?php include "include/header.php"?>

    <section id="container">
      <!--Aqui comiensa ha crear el registro-->
      <div class="form_register">
        <h1>Actualizar Usuario</h1>
        <hr>
        <!-- Si el alert no esta vacio, imprime algo-->
        <div class="alert"> <?php echo isset($alert) ? $alert: '';?></div>
        <form action="" method="post">
          <input type="hidden" name="id_personal" value="<?php echo $id_personal; ?>"> <!--Añadimos para enviarle al post idusuario-->
          <label for="nombre">Nombre</label>
          <input type="text" name="nombre" id="nombre" placeholder="Nombre Completo" value="<?php echo $nombres; ?>">
          <label for="correo">Correo</label>
          <input type="correo " name="correo" id="correo" placeholder="Correo Electronico" value="<?php echo $correo; ?>">
          <label for="usuario">Usuario</label>
          <input type="text" name="usuario" id="usuario" placeholder="Usuario" value="<?php echo $usuario; ?>">
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
            <?php 
            echo $option3;
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
          $query_cargo = mysqli_query($conexion, "SELECT * FROM cargos");
                    # Cuenta cuantas filas devuelve el query
          $result_cargo = mysqli_num_rows($query_rol);

          ?>  

          <select name="cargo" id="cargo" class="notItemOne">
            <?php 
            echo $option1;
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
          </select>
          <input type="submit" value="Actualizar Usuario" class="btn_save">
        </form>
      </div>
    </section>
    <!--Cargamos con php para llamar varias veces el codigo-->
    <?php include "include/footer.php"?>
  </body>
  </html>