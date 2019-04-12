
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

//mysqli_report(MYSQLI_REPORT_ALL) ;
?>


<?php

	$action = (isset($_REQUEST["action"])&& $_REQUEST["action"] !=NULL)?$_REQUEST["action"]:'';

	if($action == "ajax"){
    $allowed_student =[];

    //para cargar desde base o cargar de 0 por correlativas
    $idMateria = GetSQLValueString($_REQUEST["materia"], "int");
    $sqlCheckListExist = "SELECT * FROM lista_materia WHERE IdListaMateria = '$idMateria'";
    $Recordset0 = mysqli_query(dbconnect(),$sqlCheckListExist) or die(mysqli_error(dbconnect()));
    $resultarr0 = mysqli_fetch_assoc($Recordset0);

    //si la lista existe cargo los que esten asociados
    if($resultarr0) {
      $sqlSelectStudentsWithIdLista = "SELECT * from alumno_materias where IdListaMateria = '$idMateria'";
      $Recordset2 = mysqli_query(dbconnect(),$sqlSelectStudentsWithIdLista) or die(mysqli_error(dbconnect()));
      $resultarr = mysqli_fetch_all($Recordset2, MYSQLI_ASSOC);
      //$allowed_student = $resultarr;
      //$allowed_student = [];
      foreach($resultarr as $alumno) {
        $sqlGetStudentsWithIdLista ="select * from alumnos where IdAlumno = {$alumno['IdAlumno']}";
        $Recordset3 = mysqli_query(dbconnect(),$sqlGetStudentsWithIdLista) or die(mysqli_error(dbconnect()));
        $allowed_student[] = mysqli_fetch_assoc($Recordset3);
      }
      mysqli_free_result($Recordset2);
      mysqli_free_result($Recordset0);
    }
    if(!$resultarr0) {
      //sino cargo a todos los que esten habilitados (con correlativas las que tienen)

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
    }

		if ($allowed_student) {
      $_SESSION["listado"] = $allowed_student;
      usort($_SESSION["listado"], function ($item1, $item2) {
          return $item1['Apellido'] <=> $item2['Apellido'];
      });
      $counter = 1;
      foreach (  $_SESSION["listado"] as $student): ?>
      <tr>
        <!-- <td width="150"  align="center"><h4><?php //var_dump($student); ?></h4></td> -->
        <td width="50"  align="center"><h4><?php echo $counter; ?></h4></td>
        <td width="150"  align="center"><h4><?php echo $student['DNI']; ?></h4></td>
        <td width="400"  align="left" style="padding-left: 7px"><h4><?php echo $student['Apellido'] . " " . $student['Nombre']; ?></h4></td>
        <td width="700" align="center" class="noprint">
        </td>
        <td width="700" align="center" class="print-presencia-col">
          <table>
            <tr><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td></tr>
            <tr><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td></tr>
          </table>
        </td>
        <td width="200" align="center" class="print-parcial-col">
          <table><tr><td>&nbsp;</td><td>&nbsp;</td></tr></table>
        </td>
        <td width="200" align="center" class="print-parcial-col">
          <table><tr><td>&nbsp;</td><td>&nbsp;</td></tr></table>
        </td>
        <td width="100" align="center" class="actions noprint">
        <button class="quitarBtn btn btn-danger"  type="button" data-alumno-id=<?php echo $student['IdAlumno']; ?>>Quitar</button>
        </td>
      </tr>
      <?php ++$counter;  ?>
    <?php endforeach;

		} else {
			?>
      <tr><td colspan="5">
			<div class="alert alert-warning ">
              <h4>Aviso!!!</h4> No hay datos para mostrar
              <p>Puede agregar alumnos via el botón "Agregar Alumno"</p>
            </div>
      </td></tr>
			<?php
		}
	} elseif ($action == "borrar") {
    //borra de la lista temporarea
    function DeleteAlumnoFromResult($listado, $id) {
      foreach ($listado as $key => $val) {
        if ($val["IdAlumno"] === $id) {
          unset($listado[$key]);
        }
      }
      return $listado;
    }

    //agrego a array para borrarlo en la base si es que ya tenia listado
    function DeleteAlumnoFromDBList($listado, $id) {
      foreach ($listado as $key => $val) {
        if ($val["IdAlumno"] === $id) {
          return $listado[$key];
        }
      }
      return $listadoTrash;
    }


    if(isset($_REQUEST['id'])) {
      $_SESSION["trash"][] = DeleteAlumnoFromDBList($_SESSION["listado"], $_REQUEST['id']);
      $_SESSION["listado"] = DeleteAlumnoFromResult($_SESSION["listado"],$_REQUEST['id']);
    }

    if ($_SESSION["listado"]) {
      usort($_SESSION["listado"], function ($item1, $item2) {
          return $item1['Apellido'] <=> $item2['Apellido'];
      });
      $counter = 1;
      foreach ($_SESSION["listado"] as $student): ?>
      <tr>
        <!-- <td width="150"  align="center"><h4><?php var_dump($_SESSION["listado"]); ?></h4></td> -->
        <td width="50"  align="center"><h4><?php echo $counter; ?></h4></td>
        <td width="150"  align="center"><h4><?php echo $student["DNI"]; ?></h4></td>
        <td width="400"  align="left" style="padding-left: 7px"><h4><?php echo $student["Apellido"] . " " . $student["Nombre"]; ?></h4></td>
        <td width="700" align="center" class="noprint">
        </td>
        <td width="700" align="center" class="print-presencia-col">
          <table>
            <tr><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td></tr>
            <tr><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td></tr>
          </table>
        </td>
        <td width="200" align="center" class="print-parcial-col">
          <table><tr><td>&nbsp;</td><td>&nbsp;</td></tr></table>
        </td>
        <td width="200" align="center" class="print-parcial-col">
          <table><tr><td>&nbsp;</td><td>&nbsp;</td></tr></table>
        </td>
        <td width="100" align="center" class="actions noprint">
        <button class="quitarBtn btn btn-danger"  type="button" data-alumno-id=<?php echo $student["IdAlumno"]; ?>>Quitar</button>
        </td>
      </tr>
      <?php ++$counter;  ?>
    <?php endforeach;

		}
  } elseif ($action == "agregar") {
    $student_in_allowed_students = FALSE;
      // se supone que el alert ya salio antes en caso de ser duplicado

      if(isset($_REQUEST["agregarAlumno"]) && isset($_REQUEST["agregarAlumno"]["IdAlumno"])) {
        foreach($_SESSION["listado"] as $allowed_student) {
          if ($allowed_student["IdAlumno"] == $_REQUEST["agregarAlumno"]["IdAlumno"]) {
            $student_in_allowed_students = TRUE;
          }
        }

        if (!$student_in_allowed_students) {
          $_SESSION["listado"][] = $_REQUEST["agregarAlumno"];
        }
      }


    if ($student_in_allowed_students === FALSE) {
      usort($_SESSION["listado"], function ($item1, $item2) {
          return $item1['Apellido'] <=> $item2['Apellido'];
      });
      $counter = 1;
      foreach ($_SESSION["listado"] as $student): ?>
      <tr>
        <!-- <td width="150"  align="center"><h4><?php var_dump($_SESSION["listado"]); ?></h4></td> -->
        <td width="50"  align="center"><h4><?php echo $counter; ?></h4></td>
        <td width="150"  align="center"><h4><?php echo $student["DNI"]; ?></h4></td>
        <td width="400"  align="left" style="padding-left: 7px"><h4><?php echo $student["Apellido"] . " " . $student["Nombre"]; ?></h4></td>
        <td width="700" align="center" class="noprint">
        </td>
        <td width="700" align="center" class="print-presencia-col">
          <table>
            <tr><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td></tr>
            <tr><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td></tr>
          </table>
        </td>
        <td width="200" align="center" class="print-parcial-col">
          <table><tr><td>&nbsp;</td><td>&nbsp;</td></tr></table>
        </td>
        <td width="200" align="center" class="print-parcial-col">
          <table><tr><td>&nbsp;</td><td>&nbsp;</td></tr></table>
        </td>
        <td width="100" align="center" class="actions noprint">
        <button class="quitarBtn btn btn-danger"  type="button" data-alumno-id=<?php echo $student["IdAlumno"]; ?>>Quitar</button>
        </td>
      </tr>
      <?php ++$counter;  ?>

    <?php endforeach;

  } else {
    usort($_SESSION["listado"], function ($item1, $item2) {
        return $item1['Apellido'] <=> $item2['Apellido'];
    });
    $counter = 1;
    foreach ($_SESSION["listado"] as $student): ?>
    <tr>
      <td width="50"  align="center"><h4><?php echo $counter; ?></h4></td>
      <td width="150"  align="center"><h4><?php echo $student["DNI"]; ?></h4></td>
      <td width="400"  align="left" style="padding-left: 7px"><h4><?php echo $student["Apellido"] . " " . $student["Nombre"]; ?></h4></td>
      <td width="700" align="center" class="noprint">
      </td>
      <td width="700" align="center" class="print-presencia-col">
        <table>
          <tr><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td></tr>
          <tr><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td></tr>
        </table>
      </td>
      <td width="200" align="center" class="print-parcial-col">
        <table><tr><td>&nbsp;</td><td>&nbsp;</td></tr></table>
      </td>
      <td width="200" align="center" class="print-parcial-col">
        <table><tr><td>&nbsp;</td><td>&nbsp;</td></tr></table>
      </td>
      <td width="100" align="center" class="actions noprint">
        <BR>
      <button class="quitarBtn btn btn-danger"  type="button" data-alumno-id=<?php echo $student["IdAlumno"]; ?>>Quitar</button>
      </td>
    </tr>
    <?php ++$counter;  ?>

  <?php endforeach;
   }
}

?>
