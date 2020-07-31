<?php


session_start();
if ( $_SESSION['rol']!= 3  && $_SESSION['rol']!= 4 ) {
    header("location: ./");
}
    // Llamo a la conexion para consultas sql
include "../conexion.php";

    // Que los campos no esten vacios en la pagina web
if (!empty($_POST)) {

    $idtb = $_POST['id_tb'];
    $hd1 = $_POST['hd1'];
    $id_personal1 = $_POST['id_personal1'];
    $mot = $_POST['motivo'];
    $query_update2 =mysqli_query($conexion,"UPDATE tabla_central SET  estado = 0  WHERE id_TTHH = $idtb ") ;


    if ($mot=='Particular 1' || $mot=='Particular 2') {
        $sql_update1 =  mysqli_query($conexion,"UPDATE total_horas SET horas_disponibles ='$hd1'  WHERE id_personal = $id_personal1 ");
    }
   

    mysqli_close($conexion); 
    if($sql_update1){
        header('location: aceptar_permiso');

    }
}

/*=============================Recuperacion de datos=================================*/
        $idtb1 = $_REQUEST['id']; //Capturamos el id que llega
        $query = mysqli_query($conexion, "SELECT  p.id_personal,p.nombres,fp.desde,fp.hasta, fp.nro_horas, mt.tipo_Motivo,fp.galeria from tabla_central tb, formurario_permiso fp, personal p, motivo mt where tb.id_permisos=fp.id_formurario_permiso and tb.id_personal=p.id_personal and mt.id_motivo=fp.fk_motivo_permiso and tb.id_TTHH= $idtb1");
        
        $result = mysqli_num_rows($query); // Devuelve una cantidad de filas

        // Si existen datos entonces
        if($result > 0){

            while($data = mysqli_fetch_array($query)){
                $id_personal1=$data['id_personal'];
                $nombre = $data['nombres'];
                $desde= $data['desde'];
                $hasta= $data['hasta'];
                $nro_horas= $data['nro_horas'];
                $motivo= $data['tipo_Motivo'];

            }

            //restar las horas al usario que le corresponde
            //consultar el id

            $query5 = mysqli_query($conexion, "SELECT horas_disponibles from total_horas where id_personal='$id_personal1'");

                $result5 = mysqli_num_rows($query5); // Devuleve una cantidad de filas


                while ( $data5 =mysqli_fetch_array($query5)) {
                    $horas_disponibles=$data5['horas_disponibles'] ;
                }

                $hd1=$horas_disponibles - $nro_horas;
                mysqli_close($conexion);

            }else{

             # Redirecionar, si es que esta vacio
             header('location: aceptar_permiso.php');
         }


         ?>


         <!DOCTYPE html>
         <html lang="es">
         <head>
            <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
            <link rel="shortcut icon" type="image/x-icon" href="../images/reporte.png" />

            <!--Cargamos con php para llamar varias veces el codigo-->
            <?php include "include/scripts.php"?>

            <title>Aceptar Permiso</title>
        </head>
        <body>
         <!--Cargamos con php para llamar varias veces el codigo-->
         <?php include "include/header.php"?>

         <section id="container">
            <div class="data_delete">

                <h1>Aceptar Permiso</h1>
                <hr>
                <p>Nombre: <span> <?php echo $nombre;?> </span> </p>
                <p>Desde: <span> <?php echo $desde;?> </span> </p>
                <p>Hasta: <span> <?php echo $hasta;?> </span> </p>
                <p>Numeros horas: <span> <?php echo $nro_horas;?> </span> </p>
                <p>Motivo: <span> <?php echo $motivo;?> </span> </p>

                <!--Action esta en blanco porque trabajaremos en el mismo archivo-->
                <form method="post" action ="">
                    <input type="hidden" name="id_personal1" value="<?php echo $id_personal1;?>">
                    <input type="hidden" name="id_tb" value="<?php echo $idtb1;?>">
                    <input type="hidden" name="hd1" value="<?php echo $hd1;?>">
                    <input type="hidden" name="motivo" value="<?php echo $motivo;?>">
                    <a href="aceptar_permiso" class="btn_cancel">Cancelar</a>
                    <input type="submit" value="Aceptar" class="btn_ok">
                </form>
            </div>
        </section>
        <!--Cargamos con php para llamar varias veces el codigo-->
        <?php include "include/footer.php"?>

    </body>
    </html>