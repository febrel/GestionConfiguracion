
<?php 
session_start();
if ($_SESSION['rol']!= 2 and $_SESSION['rol']!= 3) {
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
			header("location: lista_permiso_tU");
			mysqli_close($conexion);
		}

		?>

		<form action="buscador_LRPTU" method="get" class="form_search">
			<input type="text" name="busqueda" id="busqueda" placeholder="Buscar"  autocomplete="off" value="<?php echo $busqueda; ?> " >
			<input type="submit" value="Buscar" class="btn_search">
		</form>
		<table>
			<tr>
				<th>Nombre</th>
				<th>Usuario</th>
				<th>Cargo</th>
				<th>Area a la que pertenece</th>
				<th>Estado del permiso</th>
			</tr>

			<?php 

            //paginador
			$sql_registe= mysqli_query($conexion,"SELECT count(*) as total_resgitro from 
				formurario_permiso fp, personal p, tabla_central tb, cargos car, areas a WHERE fp.id_formurario_permiso=tb.id_permisos and tb.id_personal=p.id_personal and fp.fk_nombre_usuario=p.id_personal and p.fk_cargo=car.id_cargos and car.fk_id_areas= a.id_areas ORDER by a.unidad_administrador ");

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
			$sql="SELECT p.nombres, p.usuario, car.nombre_cargo, a.unidad_administrador 
			, IF(tb.estado=0,'Aceptado',IF (tb.estado=2,'Rechazado','En Espera')) as estado from 
			formurario_permiso fp, personal p, tabla_central tb, cargos car, areas a WHERE (
			p.nombres like '%$busqueda%' or
			 a.unidad_administrador like '%$busqueda%') and fp.id_formurario_permiso=tb.id_permisos and tb.id_personal=p.id_personal and fp.fk_nombre_usuario=p.id_personal and p.fk_cargo=car.id_cargos and car.fk_id_areas= a.id_areas ORDER by a.unidad_administrador  asc limit $desde,$por_pagina; ";


			$result=mysqli_query($conexion,$sql);
			while($ver=mysqli_fetch_row($result)){ 

				$datos=$ver[0]."||".
				$ver[1]."||".
				$ver[2]."||".
				$ver[3]."||".
				$ver[4];
				?>

				<tr>
					<td><?php echo $ver[0] ?></td>
					<td><?php echo $ver[1] ?></td>
					<td><?php echo $ver[2] ?></td>
					<td><?php echo $ver[3] ?></td>
					<td><?php echo $ver[4] ?></td>
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
		</div>
	</section>

	<!--Cargamos con php para llamar varias veces el codigo-->
	<?php include "include/footer.php"?>

</body>
</html>