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

if(!isset($_POST['listado'])) {
  $allowed_student =[];

  foreach (getStudents() as $student) {
    if (!studentHasSign($student, $subject) and
      studentHasCorrelatives($student, $subject_correlatives)) {

      array_push($allowed_student, $student);
    }
  }
}

if(isset($_POST['listado'])) {
  $allowed_student = json_decode($_POST['listado'], true);
}

function DeleteAlumnoFromResult($listado, $id) {
  foreach ($listado as $key => $val) {
    if ($val['IdAlumno'] === $id) {
      unset($listado[$key]);
    }
  }
  return $listado;
}
if(isset($_POST['id'])) {
  $allowed_student = DeleteAlumnoFromResult($allowed_student,$_POST['id']);
}

$subjectDetails = getSubjectDetails($materia_id);

?>

<html lang="es">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
    <script  type="text/javascript" src="ABM_Modal/js/jquery.min.js"></script>
    <link rel="stylesheet" href="style.css" type="text/css" media="screen" />
    <link rel="stylesheet" href="print.css" type="text/css" media="print" />
    <link rel="stylesheet" href="print-presencia.css" type="text/css" media="print" />

  </head>
  <body>
  <div id="result">
    <table class="customers-header">
      <tbody>
        <tr>
          <td class="title"><h1> IFTS18 - Listado Alumnos por Materia </h1></td>
          <td><h2><?php print $subjectDetails['Descripcion'] ?>&nbsp;</h2></td>
        </tr>
      </tbody>
    </table>

  <table class="printcustomers" id="printable-table">
    <tbody>
      <td>DNI</td>
      <td>
        <div class="name">Apellido y Nombre</div>
      </td>
      </td>
      <td>
        <table id="customers-anidada">
          <tr>
            <div class="titulo">Primer Parcial</div>
            <tr>
              <td>Calificación</td>
              <td>Recuperatorio</td>
            </tr>
          </tr>
        </table>
      </td>
      <td>
        <table id="customers-anidada">
          <tr>
          <div class="titulo">Segundo Parcial</div>
            <tr>
              <td>Calificación</td>
              <td>Recuperatorio</td>
            </tr>
          </tr>
        </table>
      </td>

      <?php asort($allowed_student);
      foreach ($allowed_student as $student): ?>
      <tr>
        <td><h4><?php echo $student['DNI']; ?></h4></td>
        <td><h4><?php echo $student['Apellido'] . " " . $student['Nombre']; ?></h4></td>
        <td class="espacio-1"></td>
        <td class="espacio-2"></td>
        <td class="actions">
          <BR>
        <button class="quitarBtn quitar button button1" data-alumno-id=<?php echo $student['IdAlumno']; ?>>Quitar</button>
        </td>
      </tr>
      <?php endforeach; ?>
      </tbody>
      </table>

      <div style="text-align:center; position: fixed; bottom: 0; background-color: #fff; left: 0; right: 0; padding-bottom: 10px;">
          <BR>
        <div class="noprint">
          <button class="button button1" type=button onClick="location.href='Direcciones.php'">Volver al menu principal</button>
          <input type="hidden" name=IdMateria value="<?php $_POST['materia'];?>">
          <button class="button button1" type="button" onclick="imprimirHoja()" >Imprimir Listado De Presencia</button>
          <button class="button button1" type="button" onClick="window.print()">Imprimir Listado Para Parciales</button>
        </div>
    </div>

<script>
    function imprimirHoja(){
      var elemento = document.getElementById("printable-table")
        elemento.class.add("print")
        window.print()
    }

    $(document).ready(function(){
     $('.quitarBtn').each(function () {
       const $this = $(this);
       $this.on('click', function(){
         const studentId = $this.attr('data-alumno-id');
         $.ajax({
            url: 'ListadoAlumnosPorMateria.php',
            type: 'POST',
            data: 'id='+ studentId + '&materia=<?php echo $materia_id ?>' + '&listado=<?php echo json_encode($allowed_student) ?>',
            success: function(data) {
              $('#result').html(data);
            }
          });
        });
      });
    });
</script>

  </body>
</html>
