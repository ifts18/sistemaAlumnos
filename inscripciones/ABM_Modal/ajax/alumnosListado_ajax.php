
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
?>


<?php

	$action = (isset($_REQUEST["action"])&& $_REQUEST["action"] !=NULL)?$_REQUEST["action"]:'';
	if($action == "ajax"){
		$reload = 'Equivalencias.php';
		//consulta principal para recuperar los datos

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

    $materia_id = $_REQUEST["materia"];
    $subject = getSubject($materia_id);
    $subject_correlatives = getSubjectCorrelatives($subject);


      $allowed_student =[];

      foreach (getStudents() as $student) {
        if (!studentHasSign($student, $subject) and
          studentHasCorrelatives($student, $subject_correlatives)) {

          array_push($allowed_student, $student);
        }
      }

    if(isset($_SESSION["listado"])) {
      $_SESSION["listado"] = $allowed_student;
    }

    #print_r($allowed_student);
    $subjectDetails = getSubjectDetails($materia_id);

		if ($allowed_student) {
      asort($allowed_student);
      foreach ($allowed_student as $student): ?>
      <tr>
        <td width="500"> <?php echo '<pre>' . print_r($allowed_student) . '</pre>'; ?></td>
        <td width="150"  align="center"><h4><?php echo $student['DNI']; ?></h4></td>
        <td width="400"  align="center"><h4><?php echo $student['Apellido'] . " " . $student['Nombre']; ?></h4></td>
        <td idth="700" align="center"><h4></h4></td>
        <td width="100" align="center" class="actions">
          <BR>
        <button class="quitarBtn" data-alumno-id=<?php echo $student['IdAlumno']; ?>>Quitar</button>
        </td>
      </tr>
    <?php endforeach;

		} else {
			?>
			<div class="alert alert-warning alert-dismissable">
              <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
              <h4>Aviso!!!</h4> No hay datos para mostrar
            </div>
			<?php
		}
	} elseif ($action == "borrar") {
    function DeleteAlumnoFromResult($listado, $id) {
      foreach ($listado as $key => $val) {
        if ($val["IdAlumno"] === $id) {
          unset($listado[$key]);
        }
      }
      return $listado;
    }

    if(isset($_REQUEST['id'])) {
      $_SESSION["listado"] = DeleteAlumnoFromResult($_SESSION["listado"],$_REQUEST['id']);
    }

    if ($_SESSION["listado"]) {
      foreach ($_SESSION["listado"] as $student): ?>
      <tr>
        <td width="150"  align="center"><h4><?php echo $student["DNI"]; ?></h4></td>
        <td width="400"  align="center"><h4><?php echo $student["Apellido"] . " " . $student["Nombre"]; ?></h4></td>
        <td idth="700" align="center"><h4></h4></td>
        <td width="100" align="center" class="actions">
          <BR>
        <button class="quitarBtn" data-alumno-id=<?php echo $student["IdAlumno"]; ?>>Quitar</button>
        </td>
      </tr>
    <?php endforeach;

		}
  } elseif ($action == "agregar") {
    $student_in_allowed_students = FALSE;
      // se supone que el alert ya salio antes en caso de ser duplicado

      foreach($_SESSION["listado"] as $allowed_student) {
        if ($allowed_student["IdAlumno"] == $_REQUEST["agregarAlumno"]["IdAlumno"]) {
          $student_in_allowed_students = TRUE;
        }
      }

      if (!$student_in_allowed_students) {
        $_SESSION["listado"][] = $_REQUEST["agregarAlumno"];
      }


    if ($student_in_allowed_students === FALSE) {
      asort($_SESSION["listado"]);
      foreach ($_SESSION["listado"] as $student): ?>
      <tr>
        <td width="150"  align="center"><h4><?php echo $student["DNI"]; ?></h4></td>
        <td width="400"  align="center"><h4><?php echo $student["Apellido"] . " " . $student["Nombre"]; ?></h4></td>
        <td idth="700" align="center"><h4></h4></td>
        <td width="100" align="center" class="actions">
          <BR>
        <button class="quitarBtn" data-alumno-id=<?php echo $student["IdAlumno"]; ?>>Quitar</button>
        </td>
      </tr>
    <?php endforeach;

  } else {
    asort($_SESSION["listado"]);
    foreach ($_SESSION["listado"] as $student): ?>
    <tr>
      <td width="150"  align="center"><h4><?php echo $student["DNI"]; ?></h4></td>
      <td width="400"  align="center"><h4><?php echo $student["Apellido"] . " " . $student["Nombre"]; ?></h4></td>
      <td idth="700" align="center"><h4></h4></td>
      <td width="100" align="center" class="actions">
        <BR>
      <button class="quitarBtn" data-alumno-id=<?php echo $student["IdAlumno"]; ?>>Quitar</button>
      </td>
    </tr>
  <?php endforeach;
   }
}

?>
