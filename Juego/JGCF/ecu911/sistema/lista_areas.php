
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
    <link rel="stylesheet" href="css/all.min.css">
    <link rel="stylesheet" href="css/nuevo.css">
    <!--Cargamos con php para llamar varias veces el codigo-->
    <link rel="stylesheet" href="../css/style.css">
    <title>Listado de areas existentes</title>
    <?php include "include/scripts.php"?>
</head>
<body>
	<!--Cargamos con php para llamar varias veces el codigo-->
	<?php include "include/header.php"?>
    <!--Llamamos a la conexion db-->
    <?php include "../conexion.php"; ?>
    <section id="container">
      <h1>Lista de Áreas</h1>
      <a href="registro_area" class="btn_new">Crear Área</a>
      <table>
        <tr>
            <th>Areas Registradas</th>
            <th>Acciones</th>
        </tr>

        <?php 
//paginador

        $sql_registe= mysqli_query($conexion,"SELECT count(*) as total_resgitro from areas");
        $result_register= mysqli_fetch_array($sql_registe);
        $total_resgitro=$result_register['total_resgitro'];
        $por_pagina=5;
        if (empty($_GET['pagina'])) {
            $pagina=1;
        }else{
            $pagina=$_GET['pagina'];
        }

        $desde= ($pagina-1)*$por_pagina;
        $total_paginas=ceil($total_resgitro/$por_pagina);
//fin
        /*=============================Recuperacion de datos=================================*/
        $query = mysqli_query($conexion, "SELECT  * FROM areas 
           order by unidad_administrador asc
           limit $desde,$por_pagina"  );

        mysqli_close($conexion);
# Cuenta cuantas filas devuelve el query
        $result = mysqli_num_rows($query);

        if($result > 0){

            while($data =mysqli_fetch_array($query) ){
                ?>

                <tr>
                   <?php $data['id_areas'];?>
                   <td> <?php echo $data['unidad_administrador']; ?> </td>
                   <td>
                    <a href="editar_areas.php?id=<?php echo $data['id_areas']; ?>" >
                        <i class="editar fas fa-edit"></i></a>
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