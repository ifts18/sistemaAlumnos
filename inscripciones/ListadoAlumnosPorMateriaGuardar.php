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
$sqlListadoMateria = "INSERT INTO lista_materia (IdListaMateria, fechaCicloLectivo) VALUES ($idMateria, '$fecha')";

if(!mysqli_query(dbconnect(),$sqlListadoMateria)) {
  echo ('error al guardar lista'. mysqli_error(dbconnect()));
}

?>


<?php

foreach ($_SESSION["listado"] as $student) {
  $par1= $student["IdAlumno"];

  mysqli_query(dbconnect(),"UPDATE alumno_materias SET IdListaMateria = $idMateria WHERE IdAlumno = $par1 AND IdMateriaPlan = $idMateria") or printf('error', mysqli_error(dbconnect()));
}
?>

<h1>Listado guardado con éxito </h1>
<div style="text-align:center; position: fixed; bottom: 0; background-color: #fff; left: 0; right: 0; padding-bottom: 10px;">
    <BR>
    <input type=button onClick="location.href='Direcciones.php'" value='Volver al menu principal'>
</div>
