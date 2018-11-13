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
  $query = "select * from terciario.materias_plan where IdMateria={$id}";
  $recordset = mysqli_query(dbconnect(), $query) or die(mysqli_error(dbconnect()));
  $subject = mysqli_fetch_assoc($recordset);
  return $subject;
}

function getSubjectDetails($id) {
  $query = "select * from terciario.materias where IdMateria={$id}";
  $recordset = mysqli_query(dbconnect(), $query) or die(mysqli_error(dbconnect()));
  $subjectDetails = mysqli_fetch_assoc($recordset);
  return $subjectDetails;
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
  $query_students = "select Apellido, Nombre, DNI, IdAlumno from terciario.alumnos";
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
$materia_id = $_POST['materia'];
$subject = getSubject($materia_id);
$subject_correlatives = getSubjectCorrelatives($subject);
$allowed_student =[];

foreach (getStudents() as $student) {
  if (!studentHasSign($student, $subject) and
    studentHasCorrelatives($student, $subject_correlatives)) {

    array_push($allowed_student, $student);
  }
}

#print_r($allowed_student);
$subjectDetails = getSubjectDetails($materia_id);


?>

<html lang="es">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
  </head>
  <body>


<table width="1000" border="1" align="center">
  <tbody>
    <tr>
      <td width="604" align="center" ><h1> IFTS18 - Listado Alumnos por Materia </h1></td>
      <td width="480" align="center"><h2><?php print $subjectDetails['Descripcion'] ?>&nbsp;</h2></td>
    </tr>
  </tbody>
</table>
<div id="result" ></div>
<table width="1103" border="1" align="center">
  <tbody>
    <tr>
      <td width="100" align="center">DNI</td>
      <td width="100" align="center">Apellido y Nombre</td>
      <td width="700" align="center"></td>
    </tr>
  <?php asort($allowed_student);
  foreach ($allowed_student as $student): ?>
  <tr>
    <td align="center"><h4><?php echo $student['DNI']; ?></h4></td>
    <td align="center"><h4><?php echo $student['Apellido'] . " " . $student['Nombre']; ?></h4></td>
    <td align="center"><h4></h4></td>
    <td align="center" class="actions">
      <BR>
    <input
      data-alumno-id=<?php echo $student['IdAlumno']; ?>
      type=button
      onClick="this.deleteStudent(<?php echo $student['IdAlumno']; ?>)"
      value='Quitar' name="borrar"> 
    </td>
  </tr>
<?php endforeach; ?>
</tbody>
</table>
<div style="text-align:center">
    <BR>
    <input type=button onClick="location.href='Direcciones.php'" value='Volver al menu principal'>  
    <input type=button onClick="" value='Agregar a la lista'> 
    <input type="submit" />
</div>

  <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
  <script src="ABM_Modal/js/jquery.min.js"></script>

  <script>

//    $.ajax({
  //    data: {"$student['IdAlumno']"},
    //  type: "Get",
      //dataType: "json",
     // url : "ListadoAlumnosPorMateria.php", 
   // })

     $(document).ready(function(){
      function deleteStudent(student) {
        $.ajax({
            url: 'ListadoAlumnosPorMateriaQuitar.php',
            data: "id=<?php echo $student['IdAlumno']; ?>&listado=<?php echo $allowed_student; ?>",
            success: function(data) {
                $('#result').remove();
                $('#result').html(data);
            }
        });
      }
   });
  }
  </script>
        
 </body>
</html>
