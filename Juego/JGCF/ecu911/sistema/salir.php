<?php
    session_start();
    session_destroy();

    # Redireccionamos a la carpeta
    header('location: ../');

?>