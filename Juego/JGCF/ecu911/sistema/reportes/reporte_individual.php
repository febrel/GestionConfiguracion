<?php 

include '../include/plantillapdf.php';
include '../../conexion.php';


$id_per = $_GET['id'];


$query1 = mysqli_query($conexion, "SELECT  nro_identificacion as ced,nombres,id_areas, unidad_administrador  from personal p, areas a, cargos c where p.fk_cargo=c.id_cargos and c.fk_id_areas=a.id_areas and p.id_personal=$id_per ");

$data1 =mysqli_fetch_array($query1);
$unidadr=$data1['unidad_administrador'];

$id_areas3=$data1['id_areas'];

$query3 = mysqli_query($conexion, "SELECT * from personal p ,cargos c, roles r where
c.fk_id_areas=$id_areas3 and r.id_roles=4 and p.fk_cargo=c.id_cargos and p.fk_rol=4 ;");

$data3 =mysqli_fetch_array($query3);





$query = mysqli_query($conexion, "SELECT p.nombres, r.nombre_Rol,fp.fecha_emision ,mt.tipo_Motivo, fp.desde, fp.hasta, (fp.nro_horas)/8 as dias, IF(tb.estado=0,'Aceptado','Rechazado') as estado FROM tabla_central tb, personal p, formurario_permiso fp, areas a, roles r, motivo mt WHERE tb.id_personal=$id_per AND tb.id_permisos=fp.id_formurario_permiso and p.id_personal=tb.id_personal and fp.fk_motivo_permiso=mt.id_motivo and p.fk_rol=r.id_roles and fp.fk_unidad_administrativa=$id_areas3 and a.id_areas=$id_areas3 and (tb.estado=0 or tb.estado=2)"  );



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
$pdf->Cell(13,10,'Area:',0,0,'',0);
$pdf->SetFont('Arial','',12);
$pdf->Cell(80,10,$data1['unidad_administrador'],0,0,'');

$pdf->SetFillColor(232,232,232);
$pdf->SetFont('Arial','B',12);
$pdf->Cell(50,10,'Nombre del Responsable:',0,0,'',0);
$pdf->SetFont('Arial','',12);
$pdf->Cell(0,10,$data3['nombres'],0,1,'C');

//
$pdf->SetFont('Arial','B',12);
$pdf->Cell(50,10,utf8_decode('Identificación'),0,0,'',0);

$pdf->SetFont('Arial','',12);
$pdf->Cell(30,10,$data1['ced'],0,1,'C');
//
$pdf->SetFont('Arial','B',12);
$pdf->Cell(50,10,'Nombre del Personal:',0,0,'',0);

$pdf->SetFont('Arial','',12);
$pdf->Cell(30,10,$data1['nombres'],0,1,'C');
//
$pdf->SetFillColor(232,232,232);
$pdf->SetFont('Arial','B',12);
$pdf->Cell(66,10,'',0,1,'C');

$pdf->SetFillColor(232,232,232);
$pdf->SetFont('Arial','B',12);
$pdf->Cell(40,10,'Permisos Emitidos',0,1,'');
//

$pdf->SetFillColor(232,232,232);
$pdf->SetFont('Arial','B',12);

$pdf->Cell(40,6,utf8_decode('Fecha de Emisión'),1,0,'C',1);
$pdf->Cell(35,6,'Motivo ',1,0,'C',1);
$pdf->Cell(30,6,'Inicio',1,0,'C',1);
$pdf->Cell(30,6,'Fin',1,0,'C',1);
$pdf->Cell(30,6,utf8_decode('Días'),1,0,'C',1);
$pdf->Cell(30,6,utf8_decode('Estado'),1,1,'C',1);


$pdf->SetFont('Arial','',10);

while($data =mysqli_fetch_array($query) ){

  
  $pdf->Cell(40,6,$data['fecha_emision'],1,0,'');
  $pdf->Cell(35,6,$data['tipo_Motivo'],1,0,'');
  $pdf->Cell(30,6,$data['desde'],1,0,'');
  $pdf->Cell(30,6,$data['hasta'],1,0,'');
  $pdf->Cell(30,6,$data['dias'],1,0,'');
  $pdf->Cell(30,6,$data['estado'],1,1,'');
}



$pdf->Output();


?>

