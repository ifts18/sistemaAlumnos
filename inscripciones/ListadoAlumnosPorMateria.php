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


  $materia_id = $_POST['materia'];
  $queryDivisiones = mysqli_query(dbconnect(), "
  SELECT D.NombreDivision, D.IdDivision
  FROM alumno_materias AM
  INNER JOIN division D ON D.IdDivision = AM.IdDivision
  WHERE AM.IdMateriaPlan = $materia_id AND AM.IdDivision <> 0
  GROUP BY AM.IdDivision;
");

$divisiones = mysqli_fetch_all($queryDivisiones, MYSQLI_ASSOC);

$divisionPorDefecto = 0;

if (count($divisiones) > 0) {
  $divisionPorDefecto = $divisiones[0]['IdDivision'];
}

// obtengo los datos de la materia solamente
 $subjectDetails = getSubjectDetails($materia_id);
function getSubjectDetails($id) {
  $query = "select * from terciario.materias where IdMateria={$id}";
  $recordset = mysqli_query(dbconnect(), $query) or die(mysqli_error(dbconnect()));
  $subjectDetails = mysqli_fetch_assoc($recordset);
  return $subjectDetails;
}

function getListDetails($id) {
 $query = "select * from terciario.lista_materia where IdListaMateria={$id} AND YEAR(fechaCicloLectivo) = YEAR(NOW())";
 $recordset = mysqli_query(dbconnect(), $query) or die(mysqli_error(dbconnect()));
 $listDetails = mysqli_fetch_assoc($recordset);
 return $listDetails;
}

$listDetails = getListDetails($materia_id);

?>

<html lang="es">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="ABM_Modal/css/bootstrap.min.css" rel="stylesheet">
    <link href="ABM_Modal/css/dataTables.bootstrap.css" rel="stylesheet">
    <link href="ABM_Modal/css/bootstrap-select.min.css" rel="stylesheet">
    <link href="ABM_Modal/css/dataTables.bootstrap.css" rel="stylesheet">
    <link href="ABM_Modal/css/bootstrap-select.min.css" rel="stylesheet">
    <link rel="stylesheet" href="style.css?<?php echo time(); ?>" type="text/css" media="screen" />
    <link rel="stylesheet" href="print.css?<?php echo time(); ?>" type="text/css" media="print"  id="printCss"/>
    <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
    <script  type="text/javascript" src="ABM_Modal/js/jquery.min.js"></script>
    <script src="ABM_Modal/js/bootstrap.min.js"></script>
    <script src="ABM_Modal/js/bootstrap-select.min.js"></script>
  </head>
  <body>
    <?php include("ABM_Modal/modal/modal_ListadoAgregarAlumno.php");?>
    <div id="printable-table">
      <form action="ListadoAlumnosPorMateriaGuardar.php" method="post" onsubmit="return confirm('¿Seguro quiere guardar los datos?');">
        <table width="1000" border="1" align="center" style="margin-bottom: 150px;" >
        <thead>
          <tr>
            <td width="604" colspan="3" align="center" class="noprint"><h3> IFTS18 - Listado Alumnos por Materia </h3></td>
            <td width="480" colspan="2" align="center" class="noprint"><h2><?php print $subjectDetails['Descripcion'] ?>&nbsp;</h2><small>Última modificación: <?php print($listDetails['fechaCicloLectivo']); ?></small></td>
            <td class="printable-text" colspan="5" align="center" ><h2 class="printable-title"><?php print($subjectDetails['Descripcion']); ?>&nbsp;</h2></td>
          </tr>
          <tr>
            <td width="50" align="center"><h4>Nro</h4></td>
            <td width="150" align="center"><h4>DNI</h4></td>
            <td width="300" align="left" style="padding-left: 7px"><h4>Apellido y Nombre</h4></td>
            <td class="noprint" width="700" align="center"></td>
            <td class="noprint" width="100" align="center"></td>
            <td class="print-parcial" align="center">
              <table>
                <tr><td align="center"  colspan="2" class="titulo">Primer Parcial</td></tr>
                <tr>
                  <td align="center">Calificación</td>
                  <td align="center">Recuperatorio</td>
                </tr>
              </table>
            </td>
            <td align="center" class="print-parcial">
              <table>
                <tr><td align="center" colspan="2" class="titulo">Segundo Parcial</td></tr>
                <tr>
                  <td align="center">Calificación</td>
                  <td align="center">Recuperatorio</td>
                </tr>
              </table>
            </td>
            <td class="print-presencia" align="center"><h4>Asistencias</h4></td>
          </tr>
        </thead>
        <tbody id="listado"></tbody>
      </table>
    	<div id="loader" class="text-center noprint"> <img src="./ABM_Modal/loader.gif"></div>
      <div class="noprint" style="text-align:center; position: fixed; bottom: 0; background-color: #fff; left: 0; right: 0; padding-bottom: 10px;">
          <div style="padding: 10px;">
          <?php if (count($divisiones) > 0) { ?>
            <label for="">Cambiar division
              <select id="divisiones" class="form-control">
                <?php foreach($divisiones as $division) { ?>
                  <option value="<?php echo $division['IdDivision'] ?>"><?php echo $division['NombreDivision']; ?></option>
                <?php } ?> 
              </select>
            </label> 
          <?php } ?>
            <input type="submit" value="Guardar" class="btn btn-warning"/>
            <input type="hidden" name="IdMateria" value="<?php echo  $materia_id; ?>">
            <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#dataAgregar">
              <i class='glyphicon glyphicon-edit'></i> Agregar Alumno</button>
              <button  class="btn btn-primary" type="button" onclick="printPresencia()" >Imprimir Listado De Presencia</button>
            <button  class="btn btn-primary" type="button" onClick="window.print()">Imprimir Listado Para Parciales</button>
          </div>
          <button class="btn btn-info" type="button" onClick="location.href='GenerarListadoAlumnosPorMateria.php'">Volver atras</button>
          <button class="btn btn-info" type="button" onClick="location.href='Direcciones.php'" >Volver al menu principal</button>
      </div>
    </div>

    <script>
    function printPresencia(){
      var elemento = document.getElementById("printable-table")
        elemento.classList.add("presencia")
        window.print()
        elemento.classList.remove("presencia")
    }

    $(document).ready(function(){
      let idDivision = <?php echo $divisionPorDefecto; ?>;
      var alumnoAAgregar = {};
      $("#loader").fadeIn('slow');
      
      obtenerListado(<?php echo $materia_id; ?>, <?php echo $divisionPorDefecto; ?>);

      $('#divisiones').on('change', function() {
        idDivision = this.value;
        obtenerListado(<?php echo $materia_id ?>, this.value);
      });

      function obtenerListado(idMateria, idDivision) {
        $.ajax({
          url: './ABM_Modal/ajax/alumnosListado_ajax.php',
          data: {
            action: 'ajax',
            materia: idMateria,
            division: idDivision
          },
          beforeSend: () => $('#loader').html(`<img src="./ABM_Modal/loader.gif" />`),
          success: data => {
            $('#listado').html(data);
            $('#loader').html('');
          }
        });
      }

      $('#dataAgregar').on('hide.bs.modal', function(e){
          //console.log('asasassas',  $(this).parent())
            $(this).parent().trigger('reset');
            $(this).find('#alumnos_busqueda').empty();
            $(this).find('#datos_error').empty();
        }) ;



      //eliminar alumno de la lista x id
      $(document).on("click",".quitarBtn",function(){
        const $this = $(this);
        const studentId = $this.attr('data-alumno-id');
        //console.log($this, studentId)
        $.ajax({
           url: './ABM_Modal/ajax/alumnosListado_ajax.php',
           type: 'POST',
           data: {'action': 'borrar', 'materia': <?php echo $materia_id ?>, 'id': studentId, 'division': idDivision},
           beforeSend: function(objeto){
             $this[0].setAttribute('disabled', 'disabled');
             $("#loader").html("<img src='./ABM_Modal/loader.gif'>");
           },
           success: function(data) {
            $this[0].removeAttribute('disabled');
             $("#listado").html(data);
             $("#loader").html("");
           }
         });
      });

      // Reinicio valores del modal
      $('#dataAgregar').on('hide.bs.modal', function (event) {
        alumnoAAgregar = {};
        $('#palabra').val('');
      });

      // buscar alumno por dni en el modal
      $('#dataAgregar').on('show.bs.modal', function (event) {
        var modal = $(this);
        modal.find('.modal-title').text('Buscar alumno por:');
        const submitButton = modal.find('.btn-primary');
        modal.find('#materia_id').val(<?php echo $materia_id ?>);
        //const listadoAlumnosJS = <?php //echo json_encode($allowed_student) ?>;

        const DNI = modal.find('#palabra');
        const tipoFiltro = modal.find('#filtro');

        $('#btnBuscarEnAgregar').on('click', function() {
          var valorABuscar = DNI.val();
          const filtroUsado = tipoFiltro.val();
          if(valorABuscar) {
            $.ajax({
               url: 'ABM_Modal/ajax/agregarAlumnosListado_ajax.php',
               type: 'GET',
               data: {'palabra': valorABuscar, 'filtro': filtroUsado},
               success: function(data) {
                 $('#alumnos_busqueda').html(data);
                 // tempDatos['IdAlumno'] = modal.find('input').val();
                 // console.log('aaa',tempDatos['IdAlumno'] )
                 // tempDatos['DNI'] = modal.find('input:checked').parent().find('.agregarAlumno_dni').text().trim();
                 // tempDatos['Apellido'] = modal.find('input:checked').parent().find('.agregarAlumno_apellido').text().trim();
                 // tempDatos['Nombre'] = modal.find('input:checked').parent().find('.agregarAlumno_nombre').text().trim();
                 // alumnoAAgregar = tempDatos;
               }
             });
           }
        });

        $(document).on("change",".agregarAlumno_idAlumno",function(){
          var tempDatos = {};
          tempDatos['IdAlumno'] = this.value;
          tempDatos['DNI'] =  $(this).parent().parent().find('.agregarAlumno_dni').text().trim();
          tempDatos['Apellido'] = $(this).parent().parent().find('.agregarAlumno_apellido').text().trim();
          tempDatos['Nombre'] =  $(this).parent().parent().find('.agregarAlumno_nombre').text().trim();
          alumnoAAgregar = tempDatos;
        });
      });

      //agregar el alumno a la lista
      $('#btnGuardarBuscarAlumnoListado').click(function( event ) {
        event.preventDefault();
        var modal = $(this);
        $.ajax({
          type: "POST",
          url: "./ABM_Modal/ajax/alumnosListado_ajax.php",
          data: {
            action: 'agregar', 
            materia: <?php echo $materia_id ?>,
            division: idDivision,
            'agregarAlumno': alumnoAAgregar
          },
          beforeSend: function(objeto){
            $("#loader").html("<img src='./ABM_Modal/loader.gif'>");
          },
          success: function(data){
            //console.log(data)
            $("#listado").html(data);
            $("#loader").html("");

          }
        });
        modal.find('#datos_error').html('<div class="alert alert-success" role="alert"><button type="button" class="close" data-dismiss="alert">&times;</button><strong>Alumno Agregado</strong></div>')
      });

    });

    </script>

  </body>
</html>
