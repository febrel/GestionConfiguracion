
<?php 
session_start();
if ($_SESSION['rol']!= 2 && $_SESSION['rol']!= 3  && $_SESSION['rol']!= 4 && $_SESSION['rol']!= 5 && $_SESSION['rol']!= 6) {
    header("location: ./");
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
    <?php include "include/scripts.php"?>

    <title>Lista Permisos</title>
</head>
<body>
	<!--Cargamos con php para llamar varias veces el codigo-->
	<?php include "include/header.php"?>
    <!--Llamamos a la conexion db-->
    <?php 

    include "../conexion.php"; ?>

    <section id="container">
      <h1>Todos tus Permisos</h1>
      <table>
        <tr>
            <th>Nombres</th>
            <th>Areas</th>
            <th>Rol</th>
            <th>Motivo del permiso</th>
            <th>Desde (Hora o Fecha)</th>
            <th>Hasta (Hora o Fecha)</th>
            <th>Horas pedidas</th>
            <th>Observaciones</th>
            <th>Imagen</th>
            <th>Estado</th>
            <th>Aciones</th>
            
        </tr>
        <?php 

//paginador
        $idUser = $_SESSION['idUser']; 
        $sql_registe= mysqli_query($conexion,"SELECT count(*) as total_resgitro from personal p, areas a, cargos c where p.fk_cargo=c.id_cargos and c.fk_id_areas=a.id_areas and p.id_personal='$idUser'");
        $result_register= mysqli_fetch_array($sql_registe);
        $total_resgitro=$result_register['total_resgitro'];
        $por_pagina=6;
        if (empty($_GET['pagina'])) {
            $pagina=1;
        }else{
            $pagina=$_GET['pagina'];
        }

        $desde= ($pagina-1)*$por_pagina;
        $total_paginas=ceil($total_resgitro/$por_pagina);
//fin
 /*=============================Recuperacion de datos=================================*/       
//consulta del id de areas
# La variable es igual a lo que se envio con el get
        $id_usuario = $_SESSION['idUser'];
        $query1 = mysqli_query($conexion, "SELECT  id_areas  from personal p, areas a, cargos c where p.fk_cargo=c.id_cargos and c.fk_id_areas=a.id_areas and p.id_personal='$idUser' ");
        $data1 =mysqli_fetch_array($query1);
        $id_areas2=$data1['id_areas'];
# Consulta multitabla de rol
        $query = mysqli_query($conexion, "SELECT tb.id_TTHH,p.nombres , a.unidad_administrador, 
            r.nombre_Rol,mt.tipo_Motivo,fp.id_formurario_permiso, fp.desde, fp.hasta, fp.nro_horas,fp.observaciones,fp.galeria ,  IF(tb.estado=1,'En Espera','ERROR') as estado FROM tabla_central tb, personal p, 
            formurario_permiso fp, areas a, roles r, motivo mt WHERE tb.id_personal='$idUser'
            AND tb.id_permisos=fp.id_formurario_permiso and p.id_personal=tb.id_personal and fp.fk_motivo_permiso=mt.id_motivo and
            p.fk_rol=r.id_roles and fp.fk_unidad_administrativa='$id_areas2' and a.id_areas='$id_areas2' and tb.estado=1 order by nombres asc
            limit $desde,$por_pagina");
        mysqli_close($conexion);
# Cuenta cuantas filas devuelve el query
        $result = mysqli_num_rows($query);
        if($result > 0){
            while($data =mysqli_fetch_array($query) ){
                ?>
                <tr>
                   <?php $data['id_formurario_permiso'];
                   $direccion = $data['galeria'];
                   ?>
                   <td> <?php echo $data['nombres']; ?> </td>
                   <td> <?php echo $data['unidad_administrador']; ?> </td>
                   <td> <?php echo $data['nombre_Rol']; ?> </td>
                   <td> <?php echo $data['tipo_Motivo']; ?> </td>
                   <td> <?php echo $data['desde']; ?> </td>
                   <td> <?php echo $data['hasta']; ?> </td>
                   <td> <?php echo $data['nro_horas']; ?> </td>
                   <td> <?php echo $data['observaciones']; ?> </td>
                   <td> <?php echo "<img src='images/". $direccion ."' border='0' width='200' height='100' />"; ?> </td>
                    <td> <?php echo $data['estado']; ?> </td>
                   <td>
                    <a href="eliminar_confirmar_formulario.php?id=<?php echo $data['id_formurario_permiso']; ?>" ><i class="delete fas fa-trash-alt"></i></a>


                </td>     
            </tr>
            <?php
        }
    }
?>
</table>
<div class="paginador">
    <ul>
        <li><a href="?pagina=<?php echo 1;?>">|<<</a></li>
        <li><a href="?pagina=<?php echo $pagina - 1;?>"><<</a></li>
        <?php 
            for ($i=1; $i <= $total_paginas; $i++) { 
                if ($i==$pagina) {
                    echo '<li class="pageSelect">'.$i.'</li>';
                }else{
                    echo '<li><a href="?pagina='.$i.'">'.$i.'</a></li>';
                    
                }
            }
        ?>
        <li><a href="?pagina=<?php echo $pagina +1;?>">>></a></li>
        <li><a href="?pagina=<?php echo $total_paginas;?>">>>|</a></li>
        
    </ul>
</div>
</section>
<!--Cargamos con php para llamar varias veces el codigo-->
<?php include "include/footer.php"?>
</body>
</html>