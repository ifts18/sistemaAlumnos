<?php require_once('Connections/MySQL.php'); ?>
<?php
//initialize the session
if (!isset($_SESSION)) {
  session_start();
}
if (!function_exists("GetSQLValueString")) {
function GetSQLValueString($theValue, $theType, $theDefinedValue = "", $theNotDefinedValue = "")
{
  if (PHP_VERSION < 6) {
    $theValue = get_magic_quotes_gpc() ? stripslashes($theValue) : $theValue;
  }

  $theValue = function_exists("mysqli_real_escape_string") ? mysqli_real_escape_string(dbconnect(), $theValue) : mysqli_escape_string(dbconnect(), $theValue);

  switch ($theType) {
    case "text":
      $theValue = ($theValue != "") ? "'" . $theValue . "'" : "NULL";
      break;
    case "long":
    case "int":
      $theValue = ($theValue != "") ? intval($theValue) : "NULL";
      break;
    case "double":
      $theValue = ($theValue != "") ? doubleval($theValue) : "NULL";
      break;
    case "date":
      $theValue = ($theValue != "") ? "'" . $theValue . "'" : "NULL";
      break;
    case "defined":
      $theValue = ($theValue != "") ? $theDefinedValue : $theNotDefinedValue;
      break;
  }
  return $theValue;
}
}
mysqli_report(MYSQLI_REPORT_ALL) ;
$fecha = date('Y-m-d');
$idMateria= (int)$_POST["IdMateria"];
$idMateria = GetSQLValueString($idMateria, "int");

$sqlUpdateListadoMateria = "UPDATE lista_materia SET fechaCicloLectivo = '$fecha' WHERE IdListaMateria = $idMateria";
$sqlListadoMateria = "INSERT INTO lista_materia (IdListaMateria, fechaCicloLectivo) VALUES ($idMateria, '$fecha')";
$sqlCheckListadoMateria = "SELECT * FROM lista_materia  WHERE IdListaMateria = $idMateria";
//chequeo si la lista ya existe
$Recordset1 = mysqli_query(dbconnect(),$sqlCheckListadoMateria) or die(mysqli_error(dbconnect()));
$row_Recordset1 = mysqli_fetch_assoc($Recordset1);
if($row_Recordset1) {
  if(!mysqli_query(dbconnect(),$sqlUpdateListadoMateria)) {
    echo ('error al guardar lista'. mysqli_error(dbconnect()));
  }


} else {
  //chequeo si agregar el id salió bien
  if(!mysqli_query(dbconnect(),$sqlListadoMateria)) {
    echo ('error al guardar lista'. mysqli_error(dbconnect()));
  }

}
//grabo en cada alumno_materia_id el id de la listaMateria
// foreach ($_SESSION["listado"] as $student) {
//   $par1= $student["IdAlumno"];

  // mysqli_query(dbconnect(),"UPDATE alumno_materias SET IdListaMateria = $idMateria WHERE IdAlumno = $par1 AND IdMateriaPlan = $idMateria") or printf('error', mysqli_error(dbconnect()));
  
// }

mysqli_query(dbconnect(), "
UPDATE alumno_materias 
SET IdListaMateria = $idMateria
WHERE IdAlumno IN (SELECT IdAlumno FROM (SELECT DISTINCT 
A.IdAlumno, 
A.Apellido, 
A.Nombre, 
A.DNI, 
AM.FechaFirma, 
AM.EsEquivalencia, 
M.Descripcion, 
M.IdMateria, 
A.FechaCreacion
, COUNT(C.IdCorrelativa) AS CorrelativasMateria
, COALESCE(CA.CorrelativasAprobadas, 0) AS CorrelativasAprobadas
, COALESCE(AMF.TotalFirmadas, 0) AS TotalFirmadas
, IF(YEAR(CURDATE()) = YEAR(A.FechaCreacion), 1, 0) AS DeEste
, YEAR(CURDATE()) - YEAR(A.FechaCreacion) AS Antiguedad
FROM alumnos A
INNER JOIN alumno_materias AM ON A.IdAlumno = AM.IdAlumno
INNER JOIN materias_plan MP ON AM.IdMateriaPlan = MP.IdMateriaPlan
INNER JOIN materias M ON MP.IdMateria = M.IdMateria
LEFT JOIN correlativas C ON C.IdMateriaPlan = AM.IdMateriaPlan
LEFT JOIN (
  SELECT AM.IdAlumno, COUNT(C.IdMateriaPlan) AS CorrelativasAprobadas
  FROM correlativas C
  INNER JOIN alumno_materias AM ON 
    AM.IdMateriaPlan = C.IdCorrelativa AND 
    AM.FechaFirma IS NOT NULL 
    AND C.IdMateriaPlan = $idMateria
  GROUP BY AM.IdAlumno
) AS CA ON CA.IdAlumno = A.IdAlumno
LEFT JOIN (
  SELECT AM.IdAlumno, COUNT(AM.IdAlumno) As TotalFirmadas
  FROM alumno_materias AM
  WHERE AM.FechaFirma IS NOT NULL OR AM.EsEquivalencia = 1
  GROUP BY AM.IdAlumno
) AS AMF ON AMF.IdAlumno = A.IdAlumno
WHERE 
M.IdMateria = $idMateria
AND AM.EsEquivalencia = 0 /* No la tiene que haber aprobado por equivalencia */
AND AM.FechaFirma IS NULL /* Obvio que tiene que tener la fechaFirma en NULL */
GROUP BY A.IdAlumno, AM.FechaFirma, AM.EsEquivalencia, M.Descripcion
HAVING CorrelativasAprobadas = CorrelativasMateria /* Chequeamos que la cantidad de correlativas aprobadas sea igual a la requerida por la materia */
AND 
  (M.IdMateria > 1 AND (
    TotalFirmadas > 3 AND
    Antiguedad <= 3 AND
    DeEste = 0
  )) OR (
    M.IdMateria <= 11 AND
    (DeEste = 1 OR Antiguedad <= 1) /* Trae los de este año y el anterior, los demas los sacamos */
  )
ORDER BY A.Apellido ASC) AS QuerySelect) AND IdMateriaPlan = $idMateria
");

// if(isset($_SESSION["trash"])){
//   foreach ($_SESSION["trash"] as $student) {
//     $par2= $student["IdAlumno"];

//    mysqli_query(dbconnect(),"UPDATE alumno_materias SET IdListaMateria = NULL WHERE IdAlumno = $par2 AND IdMateriaPlan = $idMateria") or printf('error', mysqli_error(dbconnect()));
//   }
//   unset($_SESSION["trash"]);
// }
mysqli_free_result($Recordset1);
?>

<h1>Listado guardado con éxito </h1>
<div style="text-align:center;">
    <BR>
    <input type=button onClick="location.href='Direcciones.php'" value='Volver al menu principal'>
</div>
