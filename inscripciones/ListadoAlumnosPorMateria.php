<?php require_once('Connections/MySQL.php'); ?>
<?php

//
//
//***para generar listado de presentismo y la de finales***
//
//

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
?>

<?php
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

function getSubject($id) {
  $query = "select * from terciario.materias_plan where idMateria={$id}";
  $recordset = mysqli_query(dbconnect(), $query) or die(mysqli_error());

  $subject = mysqli_fetch_assoc($recordset);

  return $subject;
}

function getSubjectCorrelatives($subject) {
  $correlatives = [];

  $query = "select * from terciario.correlativas
            where (IdMateriaPlan={$subject['IdMateriaPlan']})";
  $recordset = mysqli_query(dbconnect(), $query) or die(mysqli_error());
  $result = mysqli_fetch_all($recordset, MYSQLI_ASSOC);

  foreach($result as $correlative_result) {
    array_push($correlatives, getSubject($correlative_result['IdCorrelativa']));
  }

  return $correlatives;
}

function getStudents() {
  $query_students = "select * from terciario.alumnos";
  $recordset_students = mysqli_query(dbconnect(),$query_students) or die(mysqli_error());
  $students = mysqli_fetch_all($recordset_students, MYSQLI_ASSOC);

  return $students;
}

function studentHasSign($student, $subject) {
  $hasSign = False;

  $query = "select * from terciario.alumno_materias
            where (IdAlumno={$student['IdAlumno']} and IdMateriaPlan={$subject['IdMateriaPlan']})";
  $recordset = mysqli_query(dbconnect(), $query) or die(mysqli_error());
  $result = mysqli_fetch_assoc($recordset);

  if (isset($result['FechaFirma'])) {
    $hasSign = True;
  }

  return $hasSign;
}

function studentHasCorrelatives($student, $subject_correlatives) {
  $has_correlatives = True;

  foreach($subject_correlatives as $correlative) {
    if (!studentHasSign($student, $correlative)) {
      $has_correlatives = False;
      break;
    }
  }

  return $has_correlatives;
}

$materia_id = $_GET['materia_id'];

$subject = getSubject($materia_id);
$subject_correlatives = getSubjectCorrelatives($subject);

$allowed_student =[];

foreach (getStudents() as $student) {
  if (!studentHasSign($student, $subject) and
    studentHasCorrelatives($student, $subject_correlatives)) {

    array_push($allowed_student, $student);
  }
}

print_r(count(getStudents()));
print_r('foo');
print_r(count($allowed_student));
die();



//$query_alumnos = "select * from terciario.alumnos";
//$recordset_alumnos = mysqli_query(dbconnect(),$query_alumnos) or die(mysqli_error());


//$row_alumnos = mysqli_fetch_assoc($Recordset1);
//print_r(getStudents()); die();

//mysql_select_db($database_MySQL, $MySQL) ;
$query_Recordset1 = "
                    select  a.Apellido, a.DNI, m.Descripcion
                    from terciario.alumnos a inner join alumno_materias am on a.IdAlumno = am.IdAlumno inner join terciario.materias_plan mp on am.IdMateriaPlan = mp.IdMateriaPlan inner join terciario.materias m on mp.idMateria = m.idMateria
                    where  am.FechaFirma is 'NULL'
";
$Recordset1 = mysqli_query(dbconnect(),$query_Recordset1) or die(mysqli_error());
$row_Recordset1 = mysqli_fetch_assoc($Recordset1);
$totalRows_Recordset1 = mysqli_num_rows($Recordset1);
?>

?>



<table width="1000" border="1" align="center">
  <tbody>
    <tr>
      <td width="604" align="center" ><h1> IFTS18 - Listado Alumnos por Materia </h1></td>
      <td width="480" align="center"><h2>Alumno:<?php print $row_Recordset1['Descripcion'] ?>&nbsp;</h2></td>
    </tr>
  </tbody>
</table>

<table width="1103" border="1" align="center">
  <tbody>
    <tr>
      <td width="100" align="center">Apellido</td>
      <td width="100" align="center">DNI</td>
      <td width="100" align="center">Materia</td>
    </tr>
    <?php do { ?>
  <tr>
    <td align="center" <h4><?php echo $row_Recordset1['Apellido']; ?></h4></td>
    <td align="center" <h4><?php echo $row_Recordset1['DNI']; ?></h4></td>
    <td align="center" <h4><?php echo $row_Recordset1['Descripcion']; ?></h4></td>
  </tr>
  <?php } while ($row_Recordset1 = mysqli_fetch_assoc($Recordset1));
  echo $row_Recordset1;
  ?>
</tbody>
</table>
</form>

<div style="text-align:center">
    <BR>
    <input type="submit" />
    <input type=button onClick="location.href='Direcciones.php'" value='Volver al menu principal'>
</div>

<?php
mysqli_free_result($Recordset1);
?>
