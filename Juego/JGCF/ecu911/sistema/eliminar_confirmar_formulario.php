<?php


session_start();
  if ($_SESSION['rol']!= 2 && $_SESSION['rol']!= 3  && $_SESSION['rol']!= 4 && $_SESSION['rol']!= 5 && $_SESSION['rol']!= 6) {
    header("location: ./");
  }
   
    // Llamo a la conexion para consultas sql
    include "../conexion.php";

    //Para el boton eliminar
    if (!empty($_POST)) {

    $idFormulario = $_POST['idFormulario'];

        $foto_db = mysqli_query($conexion,"SELECT galeria FROM formurario_permiso WHERE id_formurario_permiso =  $idFormulario");
        
       

        while($data =mysqli_fetch_array($foto_db) ){

        $ruta_foto_db = "images/".$data['galeria'];

        //Si existe la elimina
         if (isset($data['galeria']) && ($data['galeria'] != "imagen.png")) {
            if (file_exists($ruta_foto_db)) {
               if ($servicio["galeria"] != "imagen.png") {
                       unlink($ruta_foto_db);  
               }
             
        }

    }

        }

       

        
       

        $query_delete2 =mysqli_query($conexion,"DELETE FROM tabla_central WHERE id_permisos =  $idFormulario");
        $query_delete =mysqli_query($conexion,"DELETE FROM formurario_permiso WHERE id_formurario_permiso =  $idFormulario");
       
       //unlink("images/1576501889_411522.jpg");
        
        mysqli_close($conexion); 

        if($query_delete && $query_delete2 ){
            header('location: lista_permisos');

        }else{
            echo "Error al eliminar";
        }

        
    }

    // Recibe tanto por metodo post y get , Para recuperar los datos
    if (empty($_REQUEST['id'])){ // Si quiere poner 1 por defecto tambien te devuelve a lista_permisos

        # Redirecionar, si es que esta vacio
        header('location: lista_permisos');
        
    }else{
        

        $id_permiso = $_REQUEST['id']; // Capturamos el id que llega

        $query = mysqli_query($conexion, "SELECT  p.nombres, p.usuario, mt.tipo_Motivo, fp.nro_horas, fp.galeria, r.nombre_Rol FROM personal p, formurario_permiso fp, roles r , motivo mt
        WHERE  p.fk_rol=r.id_roles  AND fp.fk_motivo_permiso=mt.id_motivo AND fp.fk_nombre_usuario = p.id_personal AND fp.id_formurario_permiso = $id_permiso");
        mysqli_close($conexion);
        $result = mysqli_num_rows($query); // Devuleve una cantidad de filas

        // Si existen datos entonce
        if($result > 0){

            while($data = mysqli_fetch_array($query)){

                $nombre = $data['nombres'];
                $usuario = $data['usuario'];
                $nombre_rol = $data['nombre_Rol'];
                $motivo = $data['tipo_Motivo'];
                $numeroHoras = $data['nro_horas'];
                $direccion = $data['galeria'];


            }
        }else{
            
             # Redirecionar, si es que esta vacio
             header('location: lista_permisos');
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

	<title>Eliminar Permiso</title>
</head>
<body>
	<!--Cargamos con php para llamar varias veces el codigo-->
	<?php include "include/header.php"?>

    <section id="container">
    <div class="data_delete">
   
        <h1>Eliminar Permiso</h1>
        <hr>

        <h2>Esta seguro de eliminar el siguiente registro?</h2>
       

        <p>Usuario: <span> <?php echo $usuario;?> </span> </p>
        <p>Nombre: <span> <?php echo $nombre;?> </span> </p>
        <p>Nombre Rol: <span> <?php echo $nombre_rol;?> </span> </p>
        <p>Tipo motivo: <span> <?php echo $motivo;?> </span> </p>
        <p>Numero de horas: <span> <?php echo $numeroHoras;?> </span> </p>
        <p>Foto:</p>
        <?php echo "<img src='images/". $direccion ."' border='0' width='200' height='100' />"; ?>
        

        <!--Action esta en blanco porque trabajaremos en el mismo archivo-->
        <form method="post" action ="">
            <input type="hidden" name="idFormulario" value= "<?php echo $id_permiso;?>" >
            <a href="lista_permisos.php" class="btn_cancel">Cancelar</a>
            <input type="submit" value="Aceptar" class="btn_ok">
        </form>

    </div>

       
	</section>

	<!--Cargamos con php para llamar varias veces el codigo-->
	<?php include "include/footer.php"?>

</body>
</html>