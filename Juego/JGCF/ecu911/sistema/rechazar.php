<?php
session_start();
if ( $_SESSION['rol']!= 3  && $_SESSION['rol']!= 4 ) {
    header("location: ./");
}
// Llamo a la conexion para consultas sql
include "../conexion.php";

if (!empty($_POST)) {
    $idtb = $_POST['id_tb'];
    $obs= $_POST['obs'];
    $query_delete =mysqli_query($conexion,"UPDATE tabla_central SET   estado = 2, observaciones='$obs'  WHERE id_TTHH = $idtb ") ;
    mysqli_close($conexion); 
    if($query_delete){
        header('location: aceptar_permiso');
    }
}
/*=============================Recuperacion de datos=================================*/
        $idtb1 = $_REQUEST['id']; // Capturamos el id que llega
        $query = mysqli_query($conexion, "SELECT p.nombres,fp.desde,fp.hasta, fp.nro_horas, mt.tipo_Motivo from tabla_central tb, formurario_permiso fp, personal p, motivo mt where tb.id_permisos=fp.id_formurario_permiso and tb.id_personal=p.id_personal and mt.id_motivo=fp.fk_motivo_permiso and tb.id_TTHH= $idtb1");
        mysqli_close($conexion);
        $result = mysqli_num_rows($query); // Devuleve una cantidad de filas
        // Si existen datos entonce
        if($result > 0){
            while($data = mysqli_fetch_array($query)){
                $nombre = $data['nombres'];
                $desde= $data['desde'];
                $hasta= $data['hasta'];
                $nro_horas= $data['nro_horas'];
                $motivo= $data['tipo_Motivo'];
            }
        }else{
             # Redirecionar, si es que esta vacio
           header('location: aceptar_permiso');
       }
       ?>
       <!DOCTYPE html>
       <html lang="es">
       <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <link rel="shortcut icon" type="image/x-icon" href="../images/reporte.png" />
        <!--Cargamos con php para llamar varias veces el codigo-->
        <?php include "include/scripts.php"?>
        <title>Rechazar Permiso</title>
    </head>
    <body>
        <!--Cargamos con php para llamar varias veces el codigo-->
        <?php include "include/header.php"?>
        <section id="container">
            <div class="data_delete">
                <h1>Rechazar Permiso</h1>
                <hr>
                <p>Nombre: <span> <?php echo $nombre;?> </span> </p>
                <p>Desde: <span> <?php echo $desde;?> </span> </p>
                <p>Hasta: <span> <?php echo $hasta;?> </span> </p>
                <p>Numeros horas: <span> <?php echo $nro_horas;?> </span> </p>
                <p>Motivo: <span> <?php echo $motivo;?> </span> </p>
                <!--Action esta en blanco porque trabajaremos en el mismo archivo-->
                <form method="post" action ="">
                    <div class="mover">
                        <label>Observaciones</label>
                        <textarea class="text" name ="obs" id="obs" style="border-radius: 10px;" rows="3" cols="50" ></textarea> 
                    <input type="hidden" name="id_tb" value= "<?php echo $idtb1;?>" >
                    <a href="aceptar_permiso" class="btn_cancel">Cancelar</a>
                    <input type="submit" value="Aceptar" class="btn_ok">
                    </div>
                </form>
            </div>
        </section>
        <!--Cargamos con php para llamar varias veces el codigo-->
        <?php include "include/footer.php"?>
    </body>
    </html>