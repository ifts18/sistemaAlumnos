
<?php require_once('../Connections/MySQL.php'); ?>
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

<html lang="es">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
	<!-- Latest compiled and minified CSS -->
	  <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="css/dataTables.bootstrap.css" rel="stylesheet">
    <link href="css/bootstrap-select.min.css" rel="stylesheet">
  </head>
  <body>
  
  <?php include("modal/modal_modificarAlumnos.php");?> 
  <?php include("modal/modal_eliminarAlumnos.php");?>
      
    <table width="1000" border="1" align="center" >
      <tbody>
        <tr>
          <td width="604" align="center" ><h1> IFTS18 - Modificar Alumnos </h1></td>
          <td width="480" align="center"><h2>Usuario: <?php print $_SESSION['ApeNom'] ?>&nbsp;</h2></td>
        </tr>
      </tbody>
    </table>
      
      <br>
      
    <div class="container">
        
      <div class="row" >    
          <div class="col-sm-6">    
              <!--<button type="button" class="btn btn-success" data-toggle="modal" data-target="#dataRegister"><i class='glyphicon glyphicon-plus'></i> Dar de alta un alumno</button>-->
          </div>    
          <div class="col-xs-12 col-sm-6 col-md-8">   
            <div class="form-inline"  style="text-align: center">
                <select class="selectpicker" aria-controls="table" name="filtro" id="filtro">
                  <option value="todos">Todos</option>
                  <option value="fechaing">Fecha de Ingreso</option>
                  <option value="dni">DNI</option>
                  <option value="apellido">Apellido</option>
                </select>
                  <input class="form-control" type="text" name="palabra" id="palabra">
                  <input type="hidden" class="form-control" id="origen" name="origen" value="3">
                <button id="buscar"  class="btn btn-primary">Buscar</button>
                <br>
                <br>
                <br>
                <br>
            </div>
          </div>   
      </div>        
		
      <div class="row">
        <div class="col-xs-12">
        <!--<div id="loader" class="text-center"> <img src="loader.gif"></div>-->
          <div class="datos_ajax_delete"></div><!-- Datos ajax Final -->
          <div class="outer_div"></div><!-- Datos ajax Final -->
        </div>
      </div>
	  </div>
	<!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
  <script src="js/jquery.min.js"></script>
	<!-- Latest compiled and minified JavaScript -->
  <script src="js/bootstrap.min.js"></script>
  <script src="js/bootstrap-select.min.js"></script>
	<script src="js/appAlumnos.js"></script>
	<script>
            //comento esta linea que es la que llebna la grilla de manera automatica
//                $(document).ready(function(){
//			load(1);
//		});
                $( "#buscar" ).click(function() {
                   load(1);
                });
	</script>
      <div style="text-align:center">
          <input type="button" class="btn btn-danger" onClick="location.href='../Direcciones.php'" value='Volver al menu principal'>
          <BR>
          <BR>
      </div>   
 </body>
</html>

               
