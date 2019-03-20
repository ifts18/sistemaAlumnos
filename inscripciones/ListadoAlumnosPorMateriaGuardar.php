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
?>


<h1>Listado guardado con éxito </h1>

<?php
$query_Recordset1 = sprintf("select a.Apellido, a.DNI , m.Descripcion, am.FechaFirma, am.IdAlumnoMateria
                     from
                        terciario.materias m
                            inner join terciario.materias_plan mp on mp.IdMateria = m.IdMateria
                            inner join terciario.alumno_materias am on m.IdMateria = am.IdMateriaPlan
                            inner join terciario.alumnos a on a.IdAlumno = am.IdAlumno
                    where a.IdAlumno = %s", GetSQLValueString($_POST['IdMateria'], "int"));

$Recordset1 = mysqli_query(dbconnect(),$query_Recordset1) or die(mysqli_error());
$row_Recordset1 = mysqli_fetch_assoc($Recordset1);
?>

<?php
do {

    if(($_POST['idmesa'.(string)$row_Recordset1['IdAlumnoMateria']])==='1')
    {
        $par2 = $_POST['idAlumnoMateria'.(string)$row_Recordset1['IdAlumnoMateria']];
        $par2 = "'".$par2."'";
        $par1 = $row_Recordset1['IdAlumnoMateria'];

        mysqli_query(dbconnect(),"UPDATE terciario.alumno_materias SET FechaFirma = $par2 WHERE IdAlumnoMateria=  $par1 ");
    } else if (($_POST['idmesa'.(string)$row_Recordset1['IdAlumnoMateria']])==='2') {
        $par1 = $row_Recordset1['IdAlumnoMateria'];
        mysqli_query(dbconnect(),"UPDATE terciario.alumno_materias SET FechaFirma = NULL WHERE IdAlumnoMateria=  $par1 ");
    }
} while ($row_Recordset1 = mysqli_fetch_assoc($Recordset1));

 //header("Location: " . "Direcciones.php" );
?>
<div style="text-align:center">
    <BR>
    <input type=button onClick="location.href='Direcciones.php'" value='Volver al menu principal'>
</div>
