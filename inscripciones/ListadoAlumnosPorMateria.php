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

// function getSubject($id) {
//   $query = "select * from terciario.materias_plan where IdMateria={$id}";
//   $recordset = mysqli_query(dbconnect(), $query) or die(mysqli_error(dbconnect()));
//   $subject = mysqli_fetch_assoc($recordset);
//   return $subject;
// }
//
// function getSubjectDetails($id) {
//   $query = "select * from terciario.materias where IdMateria={$id}";
//   $recordset = mysqli_query(dbconnect(), $query) or die(mysqli_error(dbconnect()));
//   $subjectDetails = mysqli_fetch_assoc($recordset);
//   return $subjectDetails;
// }
//
// function getSubjectCorrelatives($subject) {
//   $correlatives = [];
//
//   $query = "select * from terciario.correlativas
//             where (IdMateriaPlan={$subject['IdMateriaPlan']})";
//   $recordset = mysqli_query(dbconnect(), $query) or die(mysqli_error());
//   $result = mysqli_fetch_all($recordset, MYSQLI_ASSOC);
//
//   foreach($result as $correlative_result) {
//     array_push($correlatives, getSubject($correlative_result['IdCorrelativa']));
//   }
//
//   return $correlatives;
// }
//
// function getStudents() {
//   $query_students = "select Apellido, Nombre, DNI, IdAlumno from terciario.alumnos";
//   $recordset_students = mysqli_query(dbconnect(),$query_students) or die(mysqli_error());
//   $students = mysqli_fetch_all($recordset_students, MYSQLI_ASSOC);
//
//   return $students;
// }
//
// function studentHasSign($student, $subject) {
//   $hasSign = False;
//
//   $query = "select * from terciario.alumno_materias
//             where (IdAlumno={$student['IdAlumno']} and IdMateriaPlan={$subject['IdMateriaPlan']})";
//   $recordset = mysqli_query(dbconnect(), $query) or die(mysqli_error());
//   $result = mysqli_fetch_assoc($recordset);
//
//   if (isset($result['FechaFirma'])) {
//     $hasSign = True;
//   }
//
//   return $hasSign;
// }
//
// function studentHasCorrelatives($student, $subject_correlatives) {
//   $has_correlatives = True;
//
//   foreach($subject_correlatives as $correlative) {
//     if (!studentHasSign($student, $correlative)) {
//       $has_correlatives = False;
//       break;
//     }
//   }
//
//   return $has_correlatives;
//   }
//
 $materia_id = $_POST['materia'];
// $subject = getSubject($materia_id);
// $subject_correlatives = getSubjectCorrelatives($subject);
//
// if(!isset($_POST['listado'])) {
//   $allowed_student =[];
//
//   foreach (getStudents() as $student) {
//     if (!studentHasSign($student, $subject) and
//       studentHasCorrelatives($student, $subject_correlatives)) {
//
//       array_push($allowed_student, $student);
//     }
//   }
// }
//
// if(isset($_POST['listado'])) {
//   $allowed_student = json_decode($_POST['listado'], true);
// }
//
// function DeleteAlumnoFromResult($listado, $id) {
//   foreach ($listado as $key => $val) {
//     if ($val['IdAlumno'] === $id) {
//       unset($listado[$key]);
//     }
//   }
//   return $listado;
// }
// if(isset($_POST['id'])) {
//   $allowed_student = DeleteAlumnoFromResult($allowed_student,$_POST['id']);
// }
//
// function AddAlumnoFromResult($listado, $student) {
//   $allowed_students = json_decode($_POST['listado'], true);
//   $student_in_allowed_students = FALSE;
//
//   // print_r($allowed_students); print_r($student);
//   foreach($allowed_students as $allowed_student) {
//     if ($allowed_student["IdAlumno"] == $student["IdAlumno"]) {
//       $student_in_allowed_students = TRUE;
//     }
//   }
//
//   if (!$student_in_allowed_students) {
//     $allowed_students[] = $student;
//   }
//
//   return $allowed_students;
// }
//
// if(isset($_POST['agregarAlumno'])) {;
//   $student = json_decode($_POST['agregarAlumno'], true);
//
//   $allowed_student = AddAlumnoFromResult($allowed_student, $student);
// }
// #print_r($allowed_student);

// obtengo los datos de la materia solamente
 $subjectDetails = getSubjectDetails($materia_id);
function getSubjectDetails($id) {
  $query = "select * from terciario.materias where IdMateria={$id}";
  $recordset = mysqli_query(dbconnect(), $query) or die(mysqli_error(dbconnect()));
  $subjectDetails = mysqli_fetch_assoc($recordset);
  return $subjectDetails;
}
?>

<html lang="es">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="ABM_Modal/css/bootstrap.min.css" rel="stylesheet">
    <link href="ABM_Modal/css/dataTables.bootstrap.css" rel="stylesheet">
    <link href="ABM_Modal/css/bootstrap-select.min.css" rel="stylesheet">
    <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
    <script  type="text/javascript" src="ABM_Modal/js/jquery.min.js"></script>
    <script src="ABM_Modal/js/bootstrap.min.js"></script>

  </head>
  <body>
    <?php include("ABM_Modal/modal/modal_ListadoAgregarAlumno.php");?>

    <div id="result">

      <table width="1000" border="1" align="center">
        <tbody>
          <tr>
            <td width="604" align="center" ><h1> IFTS18 - Listado Alumnos por Materia </h1></td>
            <td width="480" align="center"><h2><?php print $subjectDetails['Descripcion'] ?>&nbsp;</h2></td>
          </tr>
        </tbody>
      </table>
      <table width="1103" border="1" align="center" style="margin-bottom: 80px;">
        <thead>
          <tr>
            <td width="150" align="center">DNI</td>
            <td width="300" align="center">Apellido y Nombre</td>
            <td width="700" align="center"></td>
            <td width="100" align="center"></td>
          </tr>
        </thead>
        <tbody id="listado"></tbody>
      </table>

      <div style="text-align:center; position: fixed; bottom: 0; background-color: #fff; left: 0; right: 0; padding-bottom: 10px;">
          <BR>
          <input type=button onClick="location.href='Direcciones.php'" value='Volver al menu principal'>
          <input type="hidden" name=IdMateria value="<?php $_POST['materia'];?>">
          <button type="button" class="btn btn-info" data-toggle="modal" data-target="#dataAgregar">
            <i class='glyphicon glyphicon-edit'></i> Agregar Alumno
          </button>
      </div>
    </div>

    <script>
    $(document).ready(function(){
      var alumnoAAgregar = {};

      $('#dataAgregar').on('hide.bs.modal', function(e){
          //console.log('asasassas',  $(this).parent())
            $(this).parent().trigger('reset');
            $(this).find('#alumnos_busqueda').empty();
        }) ;

      //obtengo listado inicial de alumnos que pueden cursar
      $.ajax({
  			url:'./ABM_Modal/ajax/alumnosListado_ajax.php',
  			data: {'action': 'ajax', 'materia': <?php echo $materia_id ?>},
  			success:function(data){
  				$("#listado").html(data);
  			}
  		});

      //eliminar alumno de la lista x id
      $(document).on("click",".quitarBtn",function(){
        const $this = $(this);
        const studentId = $this.attr('data-alumno-id');
        //console.log($this, studentId)
        $.ajax({
           url: './ABM_Modal/ajax/alumnosListado_ajax.php',
           type: 'POST',
           data: {'action': 'borrar', 'materia': <?php echo $materia_id ?>, 'id': studentId},
           success: function(data) {
             $("#listado").html(data);
           }
         });
      });

      // buscar alumno por dni en el modal
      $('#dataAgregar').on('show.bs.modal', function (event) {
        console.log(event)
        var modal = $(this);
        modal.find('.modal-title').text('Buscar alumno por DNI ');
        const submitButton = modal.find('.btn-primary');
        modal.find('#materia_id').val(<?php echo $materia_id ?>);
        //const listadoAlumnosJS = <?php //echo json_encode($allowed_student) ?>;

        var DNI = modal.find('#dniBuscar');

        $(DNI).on('change', function() {
          var valorABuscar = DNI.val();

          $.ajax({
             url: 'ABM_Modal/ajax/agregarAlumnosListado_ajax.php',
             type: 'GET',
             data: 'DNI='+ valorABuscar,
             success: function(data) {
               var tempDatos = {};
               $('#alumnos_busqueda').html(data);
               tempDatos['IdAlumno'] = modal.find('.agregarAlumno_idAlumno').val();
               tempDatos['DNI'] = modal.find('.agregarAlumno_dni').text().trim();
               tempDatos['Apellido'] = modal.find('.agregarAlumno_apellido').text().trim();
               tempDatos['Nombre'] = modal.find('.agregarAlumno_nombre').text().trim();
               alumnoAAgregar = tempDatos;
             }
           });
        });
      });

      //agregar el alumno a la lista
      $('#actualidarDatos').submit(function( event ) {
        event.preventDefault();
        var modal = $(this);
        $.ajax({
          type: "POST",
          url: "./ABM_Modal/ajax/alumnosListado_ajax.php",
          data: {'action': 'agregar', 'materia': <?php echo $materia_id ?>, 'agregarAlumno': alumnoAAgregar},
          success: function(data){
            //console.log(data)
            $("#listado").html(data);
          }
        });
        modal.find('#datos_error').html('<div class="alert alert-success" role="alert"><button type="button" class="close" data-dismiss="alert">&times;</button><strong>Alumno Agregado</strong></div>')
      });

    });

    </script>

  </body>
</html>
