<?php 
session_start();
if ($_SESSION['rol']!= 2) {
    header("location: ./");
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <link rel="shortcut icon" type="image/x-icon" href="../images/reporte.png" />
    <!--Cargamos con php para llamar varias veces el codigo-->
    <?php include "include/scripts.php"?>
    <title>Lista Usuarios</title>
</head>
<body>
	<!--Cargamos con php para llamar varias veces el codigo-->
	<?php include "include/header.php"?>
    <!--Llamamos a la conexion db-->
    <?php include "../conexion.php"; ?>
    <section id="container">
        <?php 
        $busqueda= $_REQUEST['busqueda'];
        if (empty($busqueda)) {
            header("location: lista_re_usuarios");
            mysqli_close($conexion);
        }
        ?>
        <form action="lista_re_bus" method="get" class="form_search">
            <input type="text" name="busqueda" id="busqueda" placeholder="Buscar"  autocomplete="off" value="<?php echo $busqueda; ?> " >
            <input type="submit" value="Buscar" class="btn_search">
        </form>
        <table>
            <tr>
                <th>Nombres</th>
                <th>Cedula</th>
                <th>Rol</th>
                <th>Acciones</th>
            </tr>
            <?php 
 //paginador
            $sql_registe= mysqli_query($conexion,"SELECT count(*) as total_resgitro from personal ");
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
            $query = mysqli_query($conexion, "SELECT p.id_personal, p.nombres,p.nro_identificacion, p.correo, p.usuario,c.nombre_cargo, r.nombre_Rol FROM personal p,
                cargos c, roles r WHERE (p.id_personal like '%$busqueda%'
                or p.nombres like '%$busqueda%'
                or r.nombre_Rol like '%$busqueda%'
                ) and p.fk_rol=r.id_roles AND
                p.fk_cargo=c.id_cargos 
                order by nombres asc
                limit $desde,$por_pagina");
            mysqli_close($conexion);

            # Cuenta cuantas filas devuelve el query
            $result = mysqli_num_rows($query);
            if($result > 0){
                while($data =mysqli_fetch_array($query) ){
                    ?>
                    <tr>
                        <td> <?php echo $data['nombres']; ?> </td>
                        <td> <?php echo $data['nro_identificacion']; ?> </td>
                        <td> <?php echo $data['nombre_Rol']; ?> </td>
                        <td>
                            <a href="reporte_individual" class="link_delet">Generar Reporte</a>
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