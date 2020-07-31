

<?php 
		function conexion(){
			$servidor="localhost";
			$usuario="root";
			$password="";
			$bd="ecu911";

			$conexion=mysqli_connect($servidor,$usuario,$password,$bd);

			return $conexion;
		}

 ?>