<?php 
session_start();
 ?>
 
<!DOCTYPE html>
<html lang="es">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<link rel="shortcut icon" type="image/x-icon" href="../images/reporte.png" />
	<link rel="stylesheet" href="css/estilo.css">
	<!--Cargamos con php para llamar varias veces el codigo-->
	<?php include "include/scripts.php"?>

	<title>Sistema Permisos</title>
</head>
<body style="overflow-x:hidden; ">
	<!--Cargamos con php para llamar varias veces el codigo-->
	<?php include "include/header.php"?>

	<section id="container">

	<h1>Bienvenido al sistema</h1>

	<div class="wrapper">
  <div class="team">
    <div class="team_member">
      <div class="team_img">
        <img src="img/descarga.png" alt="Team_image">
      </div>
      <h3>Información</h3>
      <p class="role">Personal</p>
      <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Est quaerat tempora, voluptatum quas facere dolorum aut cumque nihil nulla harum nemo distinctio quam blanditiis dignissimos.</p>
    </div>
    <div class="team_member">
      <div class="team_img">
        <img src="img/settings-icon.png" alt="Team_image">
      </div>
      <h3>Configuraciones</h3>
      <p class="role">Admin</p>
      <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Est quaerat tempora, voluptatum quas facere dolorum aut cumque nihil nulla harum nemo distinctio quam blanditiis dignissimos.</p></div>
    <div class="team_member">
      <div class="team_img">
        <img src="img/Document_alt.png" alt="Team_image">
      </div>
      <h3>Reportes</h3>
      <p class="role">RRHH</p>
      <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Est quaerat tempora, voluptatum quas facere dolorum aut cumque nihil nulla harum nemo distinctio quam blanditiis dignissimos.</p>
    </div>
  </div>
</div>	
	</section>

	<!--Cargamos con php para llamar varias veces el codigo-->
	<?php include "include/footer.php"?>

</body>
</html>