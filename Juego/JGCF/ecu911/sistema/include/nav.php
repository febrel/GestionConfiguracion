<nav>
	<ul>
		<li><a href="../index.php">Inicio</a></li>
		<?php 
		if ($_SESSION['rol']==1) {
				 	# code...
			?>
			<li class="principal">
				<a href="#">Catalogo</a>
				<ul>
					<li><a href="lista_areas">Añadir Area</a></li>
					<li><a href="lista_roles">Añadir Roles</a></li>
					<li><a href="lista_cargo">Añadir Cargos</a></li>
				</ul>
			</li>

			<li class="principal">
				<a href="#">Usuarios</a>
				<ul>
					<li><a href="registro_usuario">Nuevo Usuario</a></li>
					<li><a href="lista_usuario">Lista de Usuarios</a></li>
				</ul>
			</li>
		<?php } ?>

		<?php 
		if ($_SESSION['rol']==2 || $_SESSION['rol']==3 || $_SESSION['rol']==4 || $_SESSION['rol']==5 || $_SESSION['rol']==6) {

			?>
			<li class="principal">
				<a href="#">Formulario Permisos</a>
				<ul>
					<li><a href="registro_permiso">Nuevo Permiso</a></li>
					<li><a href="lista_permisos">Lista de Permisos</a></li>
					<li><a href="lista_estado_permisos">Lista Estado de Permisos</a></li>
					<?php 
					if ($_SESSION['rol']==2 || $_SESSION['rol']==3) {
						?>
						<li><a href="lista_permiso_tU.php">Lista Global de todos los permiso</a></li>
					<?php } ?>
					
					<?php 
					include "../conexion.php";
					$nombrec=$_SESSION['cargo'];
					$query = mysqli_query ($conexion, "SELECT count(*) as c from auxiliar_cargo aux where aux.nombre_cargo_aux='$nombrec';");
					$data =mysqli_fetch_array($query);
					mysqli_close($conexion);
					$cont=$data['c'];

					if ($cont==1) {

						?>
						<li><a href="lista_permiso_jefe_intermedio">Visulización</a></li>
					<?php } ?>
				</ul>
			</li>
		<?php } ?>
		
		<?php 
		if ($_SESSION['rol']==2 || $_SESSION['rol']==3 || $_SESSION['rol']==4) {

			?>
			<li class="principal">
				<a href="#">Permisos recibidos</a>
				<ul>
					<li><a href="aceptar_permiso">Aceptar permisos</a></li>

				</ul>
			</li>

		<?php } ?>
		<?php 
		if ( $_SESSION['rol']==2) {

			?>
			<li class="principal">
				<a href="#">Reportes</a>
				<ul>
					<li><a href="lista_R_dias.php">Reseteo de días disponibles</a></li>
					<li><a href="lista_re_areas" >Reporte Global</a></li>
					<li><a href="lista_re_usuarios" >Reporte Individual</a></li>
				</ul>
			</li>

		<?php } ?>

		<li class="decirhora">
			<?php 
			include "../conexion.php";
			$id_user1=$_SESSION['idUser'];
			$query = mysqli_query ($conexion, "SELECT * from total_horas where id_personal=$id_user1;");
			$data =mysqli_fetch_array($query);
			$horasdisponibles=$data['horas_disponibles']/8 + $data['horas_anteriores']/8;
			mysqli_close($conexion);
			?>
			<input class="inhours" type="text" name="" value="Cuenta con <?php echo $horasdisponibles?> días" disabled="true">
		</li>
	</ul>
</nav>