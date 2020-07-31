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
  <!--Iniciio-->
  <link rel="stylesheet" type="text/css" href="librerias/bootstrap/css/bootstrap.css">
 <link rel="stylesheet" href="css/all.min.css">
    <link rel="stylesheet" href="css/nuevo.css">

  <script src="librerias/jquery-3.2.1.min.js"></script>
  <script src="js/funciones.js"></script>
  <script src="librerias/bootstrap/js/bootstrap.js"></script>
  <script src="librerias/alertifyjs/alertify.js"></script>
  <script src="librerias/select2/js/select2.js"></script>
  <!--Fin-->

  <title></title>
</head>
<body style="background: #ededed;">

 <!--Cargamos con php para llamar varias veces el codigo-->
 <?php include "include/header.php"?>

 <!--Llamamos a la conexion db-->
 <?php 

 include "../conexion.php"; ?>

 <section id="container" style="background: #ededed;">
  <h1>Resetear Días</h1>
      <table  class="table table-hover">
        <caption>

        </caption>
        <tr>
          <th>Nombre</th>
          <th>Días Año Anterior</th>
          <th>Días Año Actual</th>
          <th>Total Días</th>
          <th>Editar</th>
        </tr>

        <?php 
        /**/

        $sql_registe= mysqli_query($conexion,"SELECT  count(*) as total_resgitro  from personal p, total_horas th where p.id_personal=th.id_personal AND p.estado =1");
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
        /**/


        $sql="SELECT p.id_personal,p.nombres,(th.horas_anteriores/8), (th.horas_disponibles/8) from personal p, total_horas th where p.id_personal=th.id_personal AND p.estado =1 order by nombres asc limit $desde,$por_pagina ";


        $result=mysqli_query($conexion,$sql);
        while($ver=mysqli_fetch_row($result)){ 

          $datos=$ver[0]."||".
          $ver[1]."||".
          $ver[2]."||".
          $ver[3];
          ?>

          <tr>
            <td><?php echo $ver[1] ?></td>
            <td><?php echo $ver[2] ?></td>
            <td><?php echo $ver[3] ?></td>
            <td><?php echo $ver[3] +  $ver[2] ?></td>
            <td>
              <button i class="editar fas fa-edit" data-toggle="modal" data-target="#modalEdicion" onclick="agregaform('<?php echo $datos ?>')">
              </button>
            </td>
          </tr>
          <?php 
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
      
  </section>

  <div class="modal fade" id="modalEdicion" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog modal-sm" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
          <h4 class="modal-title" id="myModalLabel">Actualizar datos</h4>
        </div>
        <div class="modal-body">
          <input type="text" hidden="" id="idpersona" name="">
          <label>Nombre</label>
          <input type="text" name="" id="nombreu" class="form-control input-sm" disabled="true">
          <label>Días Año Anterior</label>
          <input type="text" name="" id="hoursan" class="form-control input-sm">
          <label>Días Año Actual</label>
          <input type="text" name="" id="hoursac" class="form-control input-sm">
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-warning" id="actualizadatos" data-dismiss="modal">Actualizar</button>
        </div>
      </div>
    </div>
  </div>


  <!--Cargamos con php para llamar varias veces el codigo-->
  <?php include "include/footer.php"?>
</body>
</html>

<script type="text/javascript">
  $(document).ready(function(){
    $('#tabla').load('lista_R_dias.php');

  });
</script>

<script type="text/javascript">
  $(document).ready(function(){

    $('#actualizadatos').click(function(){
      actualizaDatos();
    });

  });
</script>