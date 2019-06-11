
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
  $palabra = $_REQUEST['palabra'];
  $filtro = $_REQUEST['filtro'];

  switch($filtro){
    case "dni":
            $WhereClause = " WHERE DNI LIKE '%$palabra%' ";
            $OrderBy = " ORDER BY DNI desc";
            break;
    case "apellido":
            $WhereClause = " WHERE Apellido LIKE '%$palabra%' ";
            $OrderBy = " ORDER BY Apellido desc";
            break;
  }
  //busco en la base el alumno por x
  $sql = "SELECT IdAlumno, DNI, Apellido, Nombre, FechaCreacion, Email, Password  from alumnos
         $WhereClause
         $OrderBy";

  $query = mysqli_query(dbconnect(), $sql );
  $row_Recordset1 = mysqli_fetch_assoc($query);

  //flag
  $student_in_allowed_students = FALSE;
  $duplicatedAlumno = [];

  //si ya tengo un listado en memoria y un dni a agregar chequeo que no exista
  if(isset($_SESSION["listado"]) && $palabra) {
   foreach($_SESSION["listado"] as $allowed_student) {
     if ($allowed_student["DNI"] == $palabra) {
       $student_in_allowed_students = TRUE;
     }
   }
  }

  //  mysqli_report(MYSQLI_REPORT_ALL) ;


  if($row_Recordset1 && !$student_in_allowed_students) {
?>
  <table style="width: 100%;" aria-describedby="table_info" role="grid"
  class="table table-striped table-bordered dataTable" cellspacing="0" width="100%">
    <thead>
      <tr style="font-weight: bold">
        <td width="100" align="center">DNI</td>
        <td width="100" align="center">Apellido</td>
        <td width="100" align="center">Nombre</td>
        <td width="15">Seleccionar</td>
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
        <td align="center"><input type="radio" name="agregar"value="<?php echo $row_Recordset1['IdAlumno'] ?>" class="agregarAlumno_idAlumno" /></td>
      </tr>
    <?php } while ($row_Recordset1 = mysqli_fetch_assoc($query));  ?>
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
    <h4>No hay datos para mostrar, asegurese de que los datos sean correctos</h4>
  </div>
<?php }
 ?>
