<?php

	require_once "conexion.php";
	$conexion=conexion();
	$id=$_POST['id'];
	$n=$_POST['nombre'];
	$a=$_POST['hoursan']*8;
	$b=$_POST['hoursac']*8;

	$sql="UPDATE total_horas set 
	horas_anteriores='$a', 
	horas_disponibles='$b'
				where id_personal='$id'";
	echo $result=mysqli_query($conexion,$sql);

 ?>


 