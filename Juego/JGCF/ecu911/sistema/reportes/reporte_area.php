<?php 

include '../include/plantillapdf.php';
include '../../conexion.php';

# La variable es igual a lo que se envio con el get
$id_area = $_GET['id'];

$query1 = mysqli_query($conexion, "SELECT  * from areas where id_areas=$id_area; ");
$data1 =mysqli_fetch_array($query1);

$id_areas1= $data1['id_areas'];

$query3 = mysqli_query($conexion, "SELECT * from personal p, roles r, cargos c where              r.id_roles= 4 and c.fk_id_areas= $id_areas1 and p.fk_cargo=c.id_cargos and p.fk_rol=r.id_roles;");
$data3 =mysqli_fetch_array($query3);



$query = mysqli_query($conexion, "SELECT tb.id_TTHH,p.nro_identificacion as ced ,p.nombres , a.unidad_administrador, 
  r.nombre_Rol, sum(fp.nro_horas)/8 as dias , if(tb.estado=0,'Aceptado','Rechazado') as Estado
  FROM tabla_central tb, personal p, 
  formurario_permiso fp, areas a, roles r, motivo mt WHERE tb.id_personal=fp.fk_nombre_usuario
  AND tb.id_permisos=fp.id_formurario_permiso and p.id_personal=tb.id_personal and fp.fk_motivo_permiso=mt.id_motivo and
  p.fk_rol=r.id_roles and fp.fk_unidad_administrativa=$id_areas1 and a.id_areas=$id_areas1 and tb.estado=0 group by tb.id_personal"  );
/*
$query3 = mysqli_query($conexion, "SELECT tb.id_TTHH,p.nro_identificacion as ced ,p.nombres , a.unidad_administrador, 
  r.nombre_Rol, sum(fp.nro_horas)/8 as dias , if(tb.estado=0,'Aceptado','Rechazado') as Estado
  FROM tabla_central tb, personal p, 
  formurario_permiso fp, areas a, roles r, motivo mt WHERE tb.id_personal=fp.fk_nombre_usuario
  AND tb.id_permisos=fp.id_formurario_permiso and p.id_personal=tb.id_personal and fp.fk_motivo_permiso=mt.id_motivo and
  p.fk_rol=r.id_roles and fp.fk_unidad_administrativa=$id_areas1 and a.id_areas=$id_areas1 and tb.estado=2 group by tb.id_personal"  );*/

mysqli_close($conexion);
            # Cuenta cuantas filas devuelve el query



$pdf = new PDF();
$pdf->AliasNbPages();
$pdf->AddPage();
//
$pdf->SetFillColor(232,232,232);
$pdf->SetFont('Arial','B',12);
$pdf->Cell(66,6,'',0,1,'C');

//


$pdf->SetFillColor(232,232,232);
$pdf->SetFont('Arial','B',12);
$pdf->Cell(15,6,'Area:',0,0,'C',0);
$pdf->Cell(117,6,$data1['unidad_administrador'],0,1,'C');

$pdf->SetFillColor(232,232,232);
$pdf->SetFont('Arial','B',12);
$pdf->Cell(55,6,'Nombre del Responsable:',0,0,'C',0);
$pdf->Cell(50,6,$data3['nombres'],0,1,'C');
//
$pdf->SetFillColor(232,232,232);
$pdf->SetFont('Arial','B',12);
$pdf->Cell(66,10,'',0,1,'C');

$pdf->SetFillColor(232,232,232);
$pdf->SetFont('Arial','B',12);
$pdf->Cell(40,10,'Permisos Aceptados',0,1,'C');
//

$pdf->SetFillColor(232,232,232);
$pdf->SetFont('Arial','B',12);
$pdf->Cell(35,6,utf8_decode('Identificación '),1,0,'C',1);
$pdf->Cell(100,6,'Nombres ',1,0,'C',1);
$pdf->Cell(30,6,utf8_decode('Días Pedidos'),1,0,'C',1);
$pdf->Cell(30,6,'Estado',1,1,'C',1);


$pdf->SetFont('Arial','',10);

while($data =mysqli_fetch_array($query) ){

  $pdf->Cell(35,6,$data['ced'],1,0,'');
  $pdf->Cell(100,6,$data['nombres'],1,0,'');
  $pdf->Cell(30,6,$data['dias'],1,0,'C');
  $pdf->Cell(30,6,$data['Estado'],1,1,'C');

}


//
$pdf->SetFillColor(232,232,232);
$pdf->SetFont('Arial','B',12);
$pdf->Cell(66,10,'',0,1,'C');
/*
$pdf->SetFillColor(232,232,232);
$pdf->SetFont('Arial','B',12);
$pdf->Cell(40,10,'Permisos Rechazados',0,1,'C');
//

$pdf->SetFillColor(232,232,232);
$pdf->SetFont('Arial','B',12);
$pdf->Cell(35,6,utf8_decode('Identificación '),1,0,'C',1);
$pdf->Cell(100,6,'Nombres ',1,0,'C',1);
$pdf->Cell(30,6,utf8_decode('Días'),1,0,'C',1);
$pdf->Cell(30,6,'Estado',1,1,'C',1);


$pdf->SetFont('Arial','',10);

while($data3 =mysqli_fetch_array($query3) ){
  

  $pdf->Cell(35,6,$data3['ced'],1,0,'');
  $pdf->Cell(100,6,$data3['nombres'],1,0,'');
  $pdf->Cell(30,6,$data3['dias'],1,0,'C');
  $pdf->Cell(30,6,$data3['Estado'],1,1,'C');

}
*/
$pdf->Output();


?>

