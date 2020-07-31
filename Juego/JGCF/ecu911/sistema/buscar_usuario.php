
<?php 
session_start();
if ($_SESSION['rol']!= 1) {
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
            header("location: lista_usuario");
            mysqli_close($conexion);
        }

        ?>

        <h1>Lista de Usuarios</h1>
        <a href="registro_usuario" class="btn_new">Crear Usuario</a>
        <form action="buscar_usuario" method="get" class="form_search">
            <input type="text" name="busqueda" id="busqueda" placeholder="Buscar"  autocomplete="off" value="<?php echo $busqueda; ?> " >
            <input type="submit" value="Buscar" class="btn_search">
        </form>
        <table>
            <tr>
                <th>Nombres</th>
                <th>Cedula</th>
                <th>Correo</th>
                <th>Usuario</th>
                <th>Rol</th>
                <th>Cargo</th>
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
            $query = mysqli_query($conexion, "SELECT p.id_personal, p.nombres,p.nro_identificacion, p.correo, p.usuario,c.nombre_cargo, r.nombre_Rol FROM personal p,
                cargos c, roles r WHERE (p.id_personal like '%$busqueda%'
                or p.nombres like '%$busqueda%'
                or p.correo like '%$busqueda%'
                or p.usuario like '%$busqueda%'
                or r.nombre_Rol like '%$busqueda%'
                or c.nombre_cargo like '%$busqueda%') and p.fk_rol=r.id_roles AND
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
                        <td> <?php echo $data['correo']; ?> </td>
                        <td> <?php echo $data['usuario']; ?> </td>
                        <td> <?php echo $data['nombre_Rol']; ?> </td>
                        <td> <?php echo $data['nombre_cargo']; ?> </td>

                        <td>
                            <a href="editar_usuario?id=<?php echo $data["id_personal"]; ?>" class="link_edit">Editar</a>
                            |
                             <a href="eliminar_confirmar_usurio?id=<?php echo $data['id_personal']; ?>" class="link_delet">Eliminar</a>
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