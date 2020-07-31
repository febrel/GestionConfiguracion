<?php


session_start();
  if ($_SESSION['rol']!= 1) {
    header("location: ./");
  }
   
    // Llamo a la conexion para consultas sql
    include "../conexion.php";

    //Para el boton eliminar
    if (!empty($_POST)) {

        // Evaluar el dato para que no elimine el super usuario
        if( $_POST['idusuario'] == 1){
            header('location: lista_usuario');
            mysqli_close($conexion);
            exit;  
        }

        $idusuario = $_POST['idusuario'];

        //$query_delete =mysqli_query($conexion,"DELETE FROM personal WHERE id_personal = $idusuario") ;
        $query_delete =mysqli_query($conexion,"UPDATE personal SET  estado = 0  WHERE id_personal = $idusuario ") ;
        mysqli_close($conexion); 
        if($query_delete){
            header('location: lista_usuario');

        }else{
            echo "Error al eliminar";
        }

        
    }

    // Recibe tanto por metodo post y get , Para recuperar los datos
    if (empty($_REQUEST['id']) || $_REQUEST['id'] ==1) { // Si quiere poner 1 por defecto tambien te devuelve a lista_usuarios

        # Redirecionar, si es que esta vacio
        header('location: lista_usuario');
        
    }else{
        

        $id_usuario = $_REQUEST['id']; // Capturamos el id que llega

        $query = mysqli_query($conexion, "SELECT  p.nombres, p.usuario,c.nombre_cargo, r.nombre_Rol FROM personal p, cargos c, roles r 
        WHERE p.fk_rol=r.id_roles AND p.fk_cargo=c.id_cargos AND p.id_personal = $id_usuario");
        mysqli_close($conexion);
        $result = mysqli_num_rows($query); // Devuleve una cantidad de filas

        // Si existen datos entonce
        if($result > 0){

            while($data = mysqli_fetch_array($query)){

                $nombre = $data['nombres'];
                $usuario = $data['usuario'];
                $nombre_cargo = $data['nombre_cargo'];
                $nombre_rol = $data['nombre_Rol'];


            }
        }else{
            
             # Redirecionar, si es que esta vacio
             header('location: lista_usuario');
        }
    }

?>


<!DOCTYPE html>
<html lang="es">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <link rel="shortcut icon" type="image/x-icon" href="../images/reporte.png" />

	<!--Cargamos con php para llamar varias veces el codigo-->
	<?php include "include/scripts.php"?>

	<title>Eliminar Usuario</title>
</head>
<body>
	<!--Cargamos con php para llamar varias veces el codigo-->
	<?php include "include/header.php"?>

    <section id="container">
    <div class="data_delete">
   
        <h1>Eliminar Usuario</h1>
        <hr>

        <h2>Esta seguro de eliminar el siguiente registro?</h2>
       

        <p>Usuario: <span> <?php echo $usuario;?> </span> </p>
        <p>Nombre: <span> <?php echo $nombre;?> </span> </p>
        <p>Nombre Rol: <span> <?php echo $nombre_rol;?> </span> </p>
        <p>Nombre Cargo: <span> <?php echo $nombre_cargo;?> </span> </p>
        

        <!--Action esta en blanco porque trabajaremos en el mismo archivo-->
        <form method="post" action ="">
            <input type="hidden" name="idusuario" value= "<?php echo $id_usuario;?>" >
            <a href="lista_usuario.php" class="btn_cancel">Cancelar</a>
            <input type="submit" value="Aceptar" class="btn_ok">
        </form>

    </div>

       
	</section>

	<!--Cargamos con php para llamar varias veces el codigo-->
	<?php include "include/footer.php"?>

</body>
</html>