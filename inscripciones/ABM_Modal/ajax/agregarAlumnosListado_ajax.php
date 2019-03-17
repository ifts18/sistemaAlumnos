
<?php require_once('../../Connections/MySQL.php'); ?>
<?php
//initialize the session
if (!isset($_SESSION)) {
  session_start();
}

// verify that the user is admin
if ($_SESSION['MM_UserGroup'] != 'Admin') {
    die("No cuenta con permisos suficientes");
}

// ** Logout the current user. **
$logoutAction = $_SERVER['PHP_SELF']."?doLogout=true";
if ((isset($_SERVER['QUERY_STRING'])) && ($_SERVER['QUERY_STRING'] != "")){
  $logoutAction .="&". htmlentities($_SERVER['QUERY_STRING']);
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

<?php

  //busco en la base el alumno por dni
   $sql = sprintf("SELECT IdAlumno, DNI, Apellido, Nombre
   from alumnos where DNI = %s", GetSQLValueString($_GET['DNI'], "int"));
   $Recordset1 = mysqli_query(dbconnect(),$sql) or die(mysqli_error());
   $row_Recordset1 = mysqli_fetch_assoc($Recordset1);
   $totalRows_Recordset1 = mysqli_num_rows($Recordset1);

   $student_in_allowed_students = FALSE;

   //si ya tengo un listado en memoria y un dni a agregar chequeo que no exista
   if(isset($_SESSION["listado"]) && isset($_REQUEST["DNI"])) {
     foreach($_SESSION["listado"] as $allowed_student) {
       if ($allowed_student["DNI"] == $_REQUEST["DNI"]) {
         $student_in_allowed_students = TRUE;
       }
     }
   }


   if($row_Recordset1 && !$student_in_allowed_students) {
?>
  <input type="hidden" value="<?php echo $row_Recordset1['IdAlumno'] ?>" class="agregarAlumno_idAlumno" />
  <table style="width: 100%;" aria-describedby="table_info" role="grid"
  class="table table-striped table-bordered dataTable" cellspacing="0" width="100%">
    <thead>
      <tr style="font-weight: bold">
        <td width="100" align="center">DNI</td>
        <td width="100" align="center">Apellido</td>
        <td width="100" align="center">Nombre</td>
      </tr>
    </thead>
    <tbody>
      <?php
        do {
      ?>
      <tr >
        <td align="center" class="agregarAlumno_dni"> <h4> <?php echo $row_Recordset1['DNI']; ?></h4></td>
        <td align="left" class="agregarAlumno_apellido"> <h4> <?php echo $row_Recordset1['Apellido'];?></h4></td>
        <td align="left" class="agregarAlumno_nombre"> <h4> <?php echo $row_Recordset1['Nombre']; ?></h4></td>
      </tr>
    <?php } while ($row_Recordset1 = mysqli_fetch_assoc($Recordset1));  ?>
    </tbody>
  </table>
<?php } elseif ($student_in_allowed_students) { ?>
  <div class="alert alert-warning alert-dismissable">
    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
    <h4>Aviso!!!</h4> Alumno ya se encuentra en la lista
  </div>
<?php } else { ?>
	<div class="alert alert-warning alert-dismissable">
    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
    <h4>No hay datos para mostrar, asegurese de que el DNI sea correcto</h4>
  </div>
<?php } ?>
