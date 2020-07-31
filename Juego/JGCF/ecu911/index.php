<?php
    $alert = '';
     # Activamos secion, y la inicializamos
     session_start();

    if (!empty($_SESSION['active'])) {
        
          # Redireccionamos a la carpeta
          header('location: sistema/');
    }else {
       #Si no hace la seccion verifica los campos, si no estan vacios 
    if (!empty($_POST)) {
       
        if (empty($_POST['username']) || empty($_POST['userpass']) ) {
            $alert = "Ingrese su usuario y su clave";
        }else{

            # Requiero usar el archivo
            require_once "conexion.php";

            # Guardo el usuario y la clave uso clase y funciones para encriptar y  para eviar inyecion de sql
            $user = mysqli_real_escape_string($conexion, $_POST['username']);
            $pass = md5(mysqli_real_escape_string($conexion,  $_POST['userpass'])); # Cambiar en la base md5


            # Consulta SQL para validar acceso
            $query = mysqli_query ($conexion, "SELECT * FROM personal p, roles r, cargos c WHERE usuario= '$user'  AND pass = '$pass' AND fk_rol= r.id_roles and c.id_cargos=p.fk_cargo and  estado=1");
            mysqli_close($conexion);
            # Guardar en la variable el numero
            $result = mysqli_num_rows($query);

            if($result > 0){
                # Guarda todo en un array
                $data = mysqli_fetch_array($query);
               
                # Ponemos los nombres de los campos de la db
                $_SESSION['active'] = true;
                $_SESSION['idUser'] = $data['id_personal']; 
                $_SESSION['nombre'] = $data['nombres'];
                $_SESSION['nroCedula'] = $data['nro_identificacion'];
                $_SESSION['email'] = $data['correo'];
                $_SESSION['user'] = $data['usuario'];
                $_SESSION['rol'] = $data['fk_rol'];
                $_SESSION['roles'] = $data['nombre_Rol'];
                $_SESSION['cargo'] = $data['nombre_cargo'];

                # Redireccionamos a la carpeta
                 header('location: sistema/');
              
            }else{
                $alert = "Datos incorrectos";
                # Destruimos la seccion
                session_destroy();
            }

        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
	<title>ECU 911</title>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" >
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" type="image/x-icon" href="images/reporte.png">


    <!-- Nuestro css-->
    <link rel="stylesheet" type="text/css" href="css/bootstrap.min.css" >
    <link rel="stylesheet" type="text/css" href="css/index.css" >
    

</head>
<body>
	
	<div class="modal-dialog text-center">
        <div class="col-sm-8 main-section">
            <div class="modal-content">
                <div class="col-12 user-img">
                    <img src="images/user.png" />
                </div>
                <form class="col-12"  method="post" action="">
                    <div class="form-group" >
                        <img src="images/person.svg" alt="icon name">
                        <input type="text" class="form-control" placeholder="Nombre de usuario" autocomplete="off" name="username"/>
                    </div>

                    
                    <div class="form-group" >
                       <img src="images/lock-locked.svg" alt="icon name">
                       <input type="password" class="form-control" placeholder="Contraseña" name="userpass" autocomplete="off"/>
                    </div>
                    
					<!-- Si el alert no esta vacio, imprime algo-->
                    <div class="text-white"" > <?php echo isset($alert) ? $alert: ''; ?> </div>
                    <button  type="submit" class="btn btn-danger"><i class="fas fa-sign-in-alt" value="Ingresar"></i>  Ingresar </button>
                </form> 
            </div>
        </div>
    </div>

</body>
</html>