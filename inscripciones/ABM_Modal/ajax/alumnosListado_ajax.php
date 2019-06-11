<?php require_once('../../Connections/MySQL.php');

// Constantes de esta pagina
const HOW_MANY_YEARS_OLD = 3, HOW_MANY_SUBJECTS = 3, MAX_ID_SUBJECT_FROM_FIRST_YEAR = 11;

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
  $idMateria = GetSQLValueString($_REQUEST['materia'], "int");
  $idDivision = GetSQLValueString($_REQUEST['division'], 'int');

	if($action == "ajax") {
    $allowed_student = [];

    $query = mysqli_query(dbconnect(), "
      SELECT COUNT(*)
      FROM lista_materia LM
      WHERE LM.IdListaMateria = $idMateria
    ;");

    $tieneListadoGenerado = mysqli_fetch_row($query);

    // Si tiene listado generado, voy a buscar los alumnos por IdMateria
    if ($tieneListadoGenerado[0] == 1) {
      $query = mysqli_query(dbconnect(), "
        SELECT * FROM
        alumnos A
        INNER JOIN alumno_materias AM ON A.IdAlumno = AM.IdAlumno
        WHERE AM.IdListaMateria = $idMateria
        AND AM.IdDivision = $idDivision
        ORDER BY A.Apellido ASC
      ");

      $allowed_student = mysqli_fetch_all($query, MYSQLI_ASSOC);
    } else { // Sino, tengo que generar un nuevo listado
      $query = mysqli_query(dbconnect(),
      "SELECT DISTINCT 
        A.IdAlumno, 
        A.Apellido, 
        A.Nombre, 
        A.DNI, 
        AM.FechaFirma, 
        AM.EsEquivalencia, 
        M.Descripcion, 
        M.IdMateria, 
        A.FechaCreacion
        , COUNT(C.IdCorrelativa) AS CorrelativasMateria
        , COALESCE(CA.CorrelativasAprobadas, 0) AS CorrelativasAprobadas
        , COALESCE(AMF.TotalFirmadas, 0) AS TotalFirmadas
        , IF(YEAR(CURDATE()) = YEAR(A.FechaCreacion), 1, 0) AS DeEste
        , YEAR(CURDATE()) - YEAR(A.FechaCreacion) AS Antiguedad
        FROM alumnos A
        INNER JOIN alumno_materias AM ON A.IdAlumno = AM.IdAlumno
        INNER JOIN materias_plan MP ON AM.IdMateriaPlan = MP.IdMateriaPlan
        INNER JOIN materias M ON MP.IdMateria = M.IdMateria
        LEFT JOIN correlativas C ON C.IdMateriaPlan = AM.IdMateriaPlan
        LEFT JOIN (
          SELECT AM.IdAlumno, COUNT(C.IdMateriaPlan) AS CorrelativasAprobadas
          FROM correlativas C
          INNER JOIN alumno_materias AM ON 
            AM.IdMateriaPlan = C.IdCorrelativa AND 
            AM.FechaFirma IS NOT NULL 
            AND C.IdMateriaPlan = $idMateria
          GROUP BY AM.IdAlumno
        ) AS CA ON CA.IdAlumno = A.IdAlumno
        LEFT JOIN (
          SELECT AM.IdAlumno, COUNT(AM.IdAlumno) As TotalFirmadas
          FROM alumno_materias AM
          WHERE AM.FechaFirma IS NOT NULL OR AM.EsEquivalencia = 1
          GROUP BY AM.IdAlumno
        ) AS AMF ON AMF.IdAlumno = A.IdAlumno
        WHERE 
        M.IdMateria = $idMateria
        AND AM.EsEquivalencia = 0 /* No la tiene que haber aprobado por equivalencia */
        AND AM.FechaFirma IS NULL /* Obvio que tiene que tener la fechaFirma en NULL */
        AND AM.IdDivision = $idDivision /* chequeo la division */
        GROUP BY A.IdAlumno, AM.FechaFirma, AM.EsEquivalencia, M.Descripcion
        HAVING CorrelativasAprobadas = CorrelativasMateria /* Chequeamos que la cantidad de correlativas aprobadas sea igual a la requerida por la materia */
        AND 
          (M.IdMateria > ".MAX_ID_SUBJECT_FROM_FIRST_YEAR." AND (
            TotalFirmadas > ".HOW_MANY_SUBJECTS." AND
            Antiguedad <= ".HOW_MANY_YEARS_OLD." AND
            DeEste = 0
          )) OR (
            M.IdMateria <= ".MAX_ID_SUBJECT_FROM_FIRST_YEAR." AND
            (DeEste = 1 OR Antiguedad <= 1) /* Trae los de este año y el anterior, los demas los sacamos */
          )
        ORDER BY A.Apellido ASC;");
      
      $allowed_student = mysqli_fetch_all($query, MYSQLI_ASSOC);
      
      if(isset($_SESSION["listado"])) {
        $_SESSION["listado"] = $allowed_student;
      }
    }

		if ($allowed_student) {
      $_SESSION["listado"] = $allowed_student;
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
    //borra de la lista temporal
    function DeleteAlumnoFromResult($listado, $id) {
      foreach ($listado as $key => $val) {
        if ($val["IdAlumno"] === $id) {
          unset($listado[$key]);
        }
      }
      return $listado;
    }

    // Borra de la BD
    function DeleteAlumnoFromDb($idAlumno, $idMateria) {
      mysqli_query(dbconnect(),"UPDATE alumno_materias SET IdListaMateria = NULL, IdDivision = 0 WHERE IdAlumno = $idAlumno AND IdMateriaPlan = $idMateria") or printf('error', mysqli_error(dbconnect()));
    }

    // //agrego a array para borrarlo en la base si es que ya tenia listado
    // function DeleteAlumnoFromDBList($listado, $id) {
    //   foreach ($listado as $key => $val) {
    //     if ($val["IdAlumno"] === $id) {
    //       return $listado[$key];
    //     }
    //   }
    //   return $listadoTrash;
    // }


    if(isset($_REQUEST['id']) && isset($_REQUEST['materia'])) {
      DeleteAlumnoFromDb($_REQUEST['id'], $_REQUEST['materia']);
      // $_SESSION["trash"][] = DeleteAlumnoFromDBList($_SESSION["listado"], $_REQUEST['id']);
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

          $idMateria = $_REQUEST['materia'];
          $idDivision = $_REQUEST['division'];
          $idAlumno = $_REQUEST["agregarAlumno"]["IdAlumno"];

          // actualizamos en BD un alumno que agro por modal
          mysqli_query(dbconnect(),
            "UPDATE alumno_materias SET IdDivision = $idDivision, IdListaMateria = $idMateria WHERE IdAlumno = $idAlumno AND IdMateriaPlan = $idMateria") or printf('error', mysqli_error(dbconnect()));
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
