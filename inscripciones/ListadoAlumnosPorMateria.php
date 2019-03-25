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
    <link rel="stylesheet" href="style.css?<?php echo time(); ?>" type="text/css" media="screen" />
    <link rel="stylesheet" href="print.css?<?php echo time(); ?>" type="text/css" media="print"  id="printCss"/>
    <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
    <script  type="text/javascript" src="ABM_Modal/js/jquery.min.js"></script>
    <script src="ABM_Modal/js/bootstrap.min.js"></script>
  </head>
  <body>
    <?php include("ABM_Modal/modal/modal_ListadoAgregarAlumno.php");?>
    <div id="printable-table">
      <table width="1000" border="1" align="center" style="margin-bottom: 80px;" >
        <thead>
          <tr>
            <td width="604" colspan="2" align="center" class="noprint"><h1> IFTS18 - Listado Alumnos por Materia </h1></td>
            <td width="480" colspan="2" align="center" class="noprint"><h2><?php print $subjectDetails['Descripcion'] ?>&nbsp;</h2></td>
            <td class="printable-text" colspan="4" align="center" ><h2 class="printable-title"><?php print $subjectDetails['Descripcion'] ?>&nbsp;</h2></td>
          </tr>
          <tr>
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
            <input type="hidden" name=IdMateria value="<?php $_POST['materia'];?>">
            <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#dataAgregar">
              <i class='glyphicon glyphicon-edit'></i> Agregar Alumno</button>
              <button  class="btn btn-primary" type="button" onclick="printPresencia()" >Imprimir Listado De Presencia</button>
            <button  class="btn btn-primary" type="button" onClick="window.print()">Imprimir Listado Para Parciales</button>
          </div>
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
      var alumnoAAgregar = {};
      $("#loader").fadeIn('slow');
      $('#dataAgregar').on('hide.bs.modal', function(e){
          //console.log('asasassas',  $(this).parent())
            $(this).parent().trigger('reset');
            $(this).find('#alumnos_busqueda').empty();
            $(this).find('#datos_error').empty();
        }) ;

      //obtengo listado inicial de alumnos que pueden cursar
      $.ajax({
  			url:'./ABM_Modal/ajax/alumnosListado_ajax.php',
  			data: {'action': 'ajax', 'materia': <?php echo $materia_id ?>},
        beforeSend: function(objeto){
          $("#loader").html("<img src='./ABM_Modal/loader.gif'>");
        },
  			success:function(data){
  				$("#listado").html(data);
          $("#loader").html("");
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
           beforeSend: function(objeto){
             $("#loader").html("<img src='./ABM_Modal/loader.gif'>");
           },
           success: function(data) {
             $("#listado").html(data);
             $("#loader").html("");
           }
         });
      });

      // buscar alumno por dni en el modal
      $('#dataAgregar').on('show.bs.modal', function (event) {
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
