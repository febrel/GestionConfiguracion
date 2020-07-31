<?php
    $host = 'localhost';
    $user = 'root';
    $password = '';
    $db = 'ecu911';

    $conexion = @mysqli_connect($host, $user, $password, $db);
    

    if(!$conexion){
        echo "Error de conexion";
    }else{
       // echo "Conexion Exitosa";
    }
?>