
<?php 
    # Si no existe secion redirecciona a login, para hacer privada

    # Activamos secion, y la inicializamos
   # session_start();

if (empty($_SESSION['active'])) {

          # Redireccionamos a la carpeta, retrocede
	header('location: ../');
}

?>

<header>
	<div class="header">

		<h1>Sistema Permisos</h1>
		<div class="optionsBar">
			<!--Llamamos la funcion de hora del archivo functions.php-->
			<p>Ecuador, <?php echo fechaC();?></p>
			<span>|</span>
			<!-- codigo de notificaciones-->
			<?php if ($_SESSION['rol']== 4){ ?>
				<a href="./aceptar_permiso.php" >
					<div class="contenernum">
						<img  src="../images/noti.png" h>
						<div class="texto-encima">
							<?php 
							$idUser=$_SESSION['idUser'];
						//codigo de notificacion solo aparesca el numero segun el usuario 
							include "../conexion.php";
							$query1 = mysqli_query($conexion, "SELECT  id_areas  from personal p, areas a, cargos c where p.fk_cargo=c.id_cargos and c.fk_id_areas=a.id_areas and p.id_personal='$idUser' ");


							$data1 =mysqli_fetch_array($query1);
							$id_areas2=$data1['id_areas'];


				            # Consulta multitabla de rol
							$query = mysqli_query($conexion, "SELECT count(*) FROM tabla_central tb, personal p, 
								formurario_permiso fp, areas a, roles r, motivo mt WHERE tb.id_personal=fp.fk_nombre_usuario
								AND tb.id_permisos=fp.id_formurario_permiso and p.id_personal=tb.id_personal and fp.fk_motivo_permiso=mt.id_motivo and
								p.fk_rol=r.id_roles and fp.fk_unidad_administrativa='$id_areas2' and a.id_areas='$id_areas2' and tb.estado=1 ");

							mysqli_close($conexion);
							while($data =mysqli_fetch_array($query) ){
								$hola=$data['count(*)'];

							}
							if ($hola>0) {
								?>
								<b title="Tiene <?php echo $hola; ?> notificaciones de permisos"><?php echo $hola; ?></b>
							<?php }else{} 
				           		//final del codigo de notificaciones?>
				           	</div>
				           </div>
				       </a >

				   <?php }else if ($_SESSION['rol']== 3 || $_SESSION['rol']== 2) {?>    
				   		<a href="./lista_permiso_tU" >
					<div class="contenernum">
						<img  src="../images/noti.png" h>
						<div class="texto-encima">
							<?php 
							$idUser=$_SESSION['idUser'];
						//codigo de notificacion solo aparesca el numero segun el usuario 
							include "../conexion.php";
							

				            # Consulta multitabla de rol
							$query = mysqli_query($conexion, "SELECT count(*)  from tabla_central");

							mysqli_close($conexion);
							while($data =mysqli_fetch_array($query) ){
								$hola=$data['count(*)'];

							}
							if ($hola>0) {
								?>
								<b title="Tiene <?php echo $hola; ?> notificaciones de permisos"><?php echo $hola; ?></b>
							<?php }else{} 
				           		//final del codigo de notificaciones?>
				           	</div>
				           </div>
				       </a >


				   <?php } else{?>
				   	<a href="./lista_estado_permisos.php" >
				   		<div class="contenernum">
				   			<img  src="../images/noti.png" h>
				   			<div class="texto-encima">
				   				<?php 
				   				$idUser=$_SESSION['idUser'];
						//codigo de notificacion solo aparesca el numero segun el usuario 
				   				include "../conexion.php";


				            # Consulta multitabla de rol
				   				$query = mysqli_query($conexion, "SELECT count(*) from tabla_central where id_personal='$idUser' and (estado=0 or estado=2)"  );

				   				mysqli_close($conexion);
				   				while($data =mysqli_fetch_array($query) ){
				   					$hola=$data['count(*)'];

				   				}
				   				if ($hola>0) {
				   					?>
				   					<b title="Tiene <?php echo $hola; ?> notificaciones de permisos"><?php echo $hola; ?></b>
				   				<?php }else{} 
				           		//final del codigo de notificaciones?>
				           	</div>
				           </div>
				       </a >
				   <?php } ?>

				   <span>|</span>
				   <!--Llamamos el user de la base de datos que ingresamos-->
				   <img class="photouser" src="img/user.png" alt="Usuario"> 
				  
				   <span class="user"> <?php echo $_SESSION['user'].'  --  '.$_SESSION['roles'];?> </span>

				   <a href="salir.php" ><img class="close" src="img/salir.png" alt="Salir del sistema" title="Salir"></a>


				</div>
			</div>

			<!--Cargamos con php para llamar varias veces el codigo-->
			<?php include "nav.php"?>
		</header>

