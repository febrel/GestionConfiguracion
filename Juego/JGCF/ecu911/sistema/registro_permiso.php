<?php

session_start();
if ($_SESSION['rol']!= 2 && $_SESSION['rol']!= 3  && $_SESSION['rol']!= 4 && $_SESSION['rol']!= 5 && $_SESSION['rol']!= 6) {
    header("location: ./");
}

/*=============================Recuperacion de datos=================================*/
    # La variable es igual a lo que se envio con el get
$id_usuario = $_SESSION['idUser'];

     # Llamamos a clase conexion para sql
include "../conexion.php";

    # Consulta multitabla con alias entre usuario y rol
$sql = mysqli_query($conexion, "SELECT * FROM cargos , areas, personal p, roles, total_horas th where fk_id_areas=id_areas and fk_cargo=id_cargos and fk_rol=id_roles and p.id_personal =$id_usuario and th.id_personal=$id_usuario");

mysqli_close($conexion);
    # Guardo el numero de filas
$result_sql = mysqli_num_rows($sql);


    # Validar si hay registro 
if($result_sql == 0){
       //header('location: lista_cargo.php'); 
}else{


    while($data = mysqli_fetch_array($sql)){

            # Almaceno en variables el resultado de la db

        $id_personal = $data["id_personal"];
        $nombres_personal = $data["nombres"];
        $id_cargos= $data['id_cargos'];
        $nombre_cargos = $data['nombre_cargo'];
        $id_areas = $data['id_areas'];
        $unidad_administrador=$data['unidad_administrador'] ; 
        $horas_disponibles = $data["horas_disponibles"] +  $data["horas_anteriores"];

    }
    
}

    # Para evitar que los campos vallan vacios
if(!empty($_POST)){
        //Agregar mas OJO
    if(empty($_POST['motivo'])){


            # Si estan vacio llama a la clase msg_error para que acceda al css
        $alert='<p class="msg_error">Todos los campos son obligatorios</p>';
    }else{

        //Captura el envio  del formulario Hora
        $horaDesde = $_POST['horaDesde'];
        $horaHasta = $_POST['horaHasta'];
        

        // Utilizo para trasformar a formato date
        $horaUno=new DateTime($horaDesde);
        $horaDos=new DateTime($horaHasta);

        $dateInterval = $horaUno->diff($horaDos);
            
        $totalHours = (int) $dateInterval->format('%H');
        $totalMinutes = (int) $dateInterval->format('%i');
        $finaltiempo = (double) ($totalHours.'.'.$totalMinutes);
          


        // Capturo el envio del formulario fecha
        $fechaDesde = $_POST['fechaDesde']; 
        $fechaHasta = $_POST['fechaHasta'];
         

        $date1 = new DateTime($fechaDesde);
        $date2 = new DateTime($fechaHasta);

        $diff = $date1->diff($date2);
        $diff->format('%d');
        $totalDays = $diff->days;
        $finalFecha = (double) ($totalDays * 8);
            

        $finalesHoras;


        /*Para capturar la fecha*/
        date_default_timezone_set('America/Guayaquil'); 
        $fechaActual = date('Y-m-d');

        /*=====================================Para manejar foto==========================*/ 

            // Captura la foto
            //$foto = addslashes(file_get_contents($_FILES['foto']["name"]));
        $foto = $_FILES['foto']["name"];

        $fecha = new DateTime();

            //Validar foto, sea diferente de vacios, y se concantena _ con la fecha
            //Si hay una foto real concatenas la fecha y su nombre, de lo contrario conserva el valor de foto por defecto
        $nombreArchivo = ($foto != "") ? $fecha->getTimestamp() . "_" . $_FILES["foto"]["name"] : "imagen.png";

            //Nombre que php devuelve cuando se selecione
        $tmpFoto = $_FILES["foto"]["tmp_name"];

        if ($tmpFoto != "") {
            //Copia al servidor junto con el nombre del archivo
            move_uploaded_file($tmpFoto, "images/".$nombreArchivo);
        }

            # Llamamos a clase conexion para sql
        include "../conexion.php";

            # Si estan llenos llama a la clase msg_save para que acceda al css
        $alert='<p class="msg_save"></p>';

        # Creamos variables para almacenar lo que llega del metodo POST

        $motivo_enviado = $_POST['motivo'];
        $descripcion_enviado = $_POST['descripcion'];
        $horas_disponibles=$_POST['horas_disponibles'];

        $car1 =  $_POST['car1'];
        $car2 =  $_POST['car2'];
        $car3 =  $_POST['car3'];
        $car4 =  $_POST['car4'];

       


        if (!empty($car1)) { 
            $descripcion_parentesco = $car1;
            
        } elseif (!empty($car2)) {
            $descripcion_parentesco = $car2;

        } elseif (!empty($car3)) {
            $descripcion_parentesco = $car3;

        }elseif (!empty($car4)) {
            $descripcion_parentesco = $car4;
        }



        // Variables para capturar el rango de permiso
        $desde;
        $hasta;


            // Condicion para fecha y horas
        if( $horaDesde == null && $horaHasta == null){
            $finalesHoras = $finalFecha;
            $desde =  $fechaDesde;
            $hasta =  $fechaHasta;
                //echo "Ingresaste fecha ".$finalesHoras;
        }else if($fechaDesde == null && $fechaHasta == null){
            $finalesHoras = $finaltiempo;
            $desde = $horaDesde;
            $hasta = $horaHasta;

        }


            if ($horas_disponibles >= $finalesHoras) {
                 # Llamamos a clase conexion para sql
                include "../conexion.php";
                $query_insert = mysqli_query($conexion,"INSERT INTO formurario_permiso(fecha_emision,fk_nombre_usuario,fk_unidad_administrativa,fk_cargos,desde, hasta, nro_horas,fk_motivo_permiso,observaciones,galeria,descripcion_parentesco_institucion) VALUES ('$fechaActual', '$id_personal','$id_areas', '$id_cargos','$desde','$hasta' ,' $finalesHoras',' $motivo_enviado','$descripcion_enviado','$nombreArchivo','$descripcion_parentesco')");

                
            //consultar el id
                $query5 = mysqli_query($conexion, "SELECT * from formurario_permiso");

                $result5 = mysqli_num_rows($query5); // Devuelve una cantidad de filas

                while ( $data5 =mysqli_fetch_array($query5)) {
                    $id_fp=$data5['id_formurario_permiso'] ;
                }
                
                $id_forp= $id_fp;
                $estadotb=1;

            //fin de la consulta
                $query_insert1 = mysqli_query($conexion,"INSERT INTO tabla_central(id_personal,id_permisos,estado) VALUES ('$id_personal','$id_forp','$estadotb')");

                mysqli_close($conexion);

                # Si seinserta en msql
                if( $query_insert){
                    $alert='<p class="msg_save">Permiso agregado</p>';
                      header("location: lista_permisos.php");
                }else{
                    $alert='<p class="msg_error">Error al crear el permiso</p>';
                }
            }else{
                $alert='<p class="msg_error">Supero sus dias disponibles</p>';

            }
        }
    }
    ?>

    <!DOCTYPE html>
    <html lang="es">

    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <link rel="shortcut icon" type="image/x-icon" href="../images/reporte.png" />
        <script src="ckeditor/ckeditor.js"></script>
        <script src="js/oculta.js"></script>
        <script src="js/app.js"></script>

        <!--Cargamos con php para llamar varias veces el codigo-->
        <?php include "include/scripts.php";?>
        <title>Registro de Permiso</title>
    </head>
    <body onload="ocultar();">
       <!--Cargamos con php para llamar varias veces el codigo-->
       <?php include "include/header.php"?>

       <!--Llamamos a la conexion db-->
       <?php include "../conexion.php"; ?>

       <section id="container">
        <!--Aqui comiensa ha crear el registro-->
        <div class="form_permiso">
            <h1>Formulario de Permiso</h1>

            <hr>

            <!-- Si el alert no esta vacio, imprime algo-->
            <div class="alert"> <?php echo isset($alert) ? $alert: '';?></div>

            <form action="" method="post" id="miForm" enctype="multipart/form-data">
             <input type="hidden" name="horas_disponibles"  id="horas_disponibles" value="<?php echo $horas_disponibles; ?>"> 
             <p class="fechaEstilo">Fecha <?php echo fechaC();?></p> <br>

             <label for="nombre">Nombre</label>
             <input type="text" name="nombre" id="nombre" placeholder="Nombre Completo" readonly="readonly" value="<?php echo $nombres_personal; ?>"> <br>

             <label for="area">Unidad Administrativa</label>
             <input type="area" name="area" id="area" placeholder="Unidad Administrativa" readonly="readonly" value="<?php echo $unidad_administrador; ?>">

             <label for="cargo">Cargo</label>
             <input  type="cargo" name="cargo" id="cargo" placeholder="Cargo" readonly="readonly" value="<?php echo $nombre_cargos; ?>"> <br>

             <div class="cambia">
                <input type="checkbox"  value="ocultar"  onclick="mostrar_ocultar()" checked >Cambia Opción
            </div>

            <div id="caja">
                <label>Fecha desde</label>
                <input class="text" type="date" id="fechaDesde" name="fechaDesde">

                <label>Fecha Hasta</label>
                <input class="clasefecha" type="date" id="fechaHasta" name="fechaHasta"> <br>
            </div>

            <div id="caja2">
                <label>Hora Desde: </label>
                <input type="time" id ="horaDesde" name="horaDesde">

                <label>Hora Hasta: </label>
                <input class="claseHora" type="time" id="horaHasta" name="horaHasta">
            </div>

            <label for="rol">Motivo Permiso</label>

            <!--Hacer un query para consumir los roles desde la db-->
            <?php 

                     # Llamamos a clase conexion para sql
            include "../conexion.php";

            $query_motivo = mysqli_query($conexion, "SELECT * FROM motivo");
            mysqli_close($conexion);

                    # Cuenta cuantas filas devuelve el query
            $result_motivo = mysqli_num_rows($query_motivo);

            ?>  

            <select onchange="yesnoCheck(this);" name="motivo" id="motivo" class="notItemOne">
                <?php 

                    # Si encuentra algo
                if($result_motivo > 0){

                    # Almacenamos en un array resultado de query
                    while($motivo = mysqli_fetch_array($query_motivo)){
                        ?>
                        <option value= "<?php echo $motivo ['id_motivo']; ?>"> <?php echo $motivo ['tipo_Motivo']?> </option>
                        <?php

                    }
                }

                ?>

            </select>


            <div id="ifYes" style="display: none;">
            <label>Parentesco</label> <input type="text" id="car1" name="car1" /><br />
            </div>


            <div id="ifYes2" style="display: none;">
            <label>Parentesco</label> <input type="text" id="car2" name="car2" /><br />
            </div>

            <div id="ifYes3" style="display: none;">
            <label >Descripción</label> <input type="text" id="car3" name="car3" /><br />
            </div>

            <div id="ifYes4" style="display: none;">
            <label>Nombre de la Institución</label> <input type="text" id="car4" name="car4" /><br />
            </div>

            <div class= "controlFoto">
                <label for="">Foto</label>
                <input type="file" class="" accept="image/*" name="foto" id="foto">
            </div>
            <br>

            <label>Observaciones</label>
            <textarea class="text" name ="descripcion" id="descripcion" style="border-radius: 5px; width: 100%;" rows="3" cols="50" ></textarea>

            <input type="submit" value="Crear Permiso" class="btn_save">
        </form>

    </div>

</section>

<!--Cargamos con php para llamar varias veces el codigo-->
<?php include "include/footer.php"?>


</body>
</html>