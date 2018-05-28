
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
  
        
    <table width="900" border="2" align="center" >
      <tbody>
        <tr>
          <td width="604" align="center" ><h3> IFTS18 - Alumnos </h3></td>
          <td width="480" align="center"><h3>Usuario: <?php print $_SESSION['ApeNom'] ?>&nbsp;</h3></td>
        </tr>
      </tbody>
    </table>
      <?php include("modal/modal_agregarAlumnos.php");?>
      
      
    <div class="container">
        
                    <div style="text-align:center">    
                      
                        <input type="button" class="btn btn-danger" onClick="location.href='../Direcciones.php'" value='Volver al menu principal'>
                      <BR>
                      <BR>
                    
                    <!--    <div class="text-center">    
                            <button type="button" class="btn btn-success" data-toggle="modal" data-target="#dataRegister"><i class='glyphicon glyphicon-plus'></i> Dar de alta un alumno</button>
                        <BR>
                        <BR>
                        <BR>
                        <BR>
                        </div>    
                    </div>-->
                </div>
	<!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
        <script src="js/jquery.min.js"></script>
	<!-- Latest compiled and minified JavaScript -->
        <script src="js/bootstrap.min.js"></script>
        <script src="js/bootstrap-select.min.js"></script>
	<script src="js/appAlumnos.js"></script>
	<script>
		$(document).ready(function(){
			load(1);
		});
                $( "#buscar" ).click(function() {
                   load(1);
                });
	</script>
        
        
 </body>
</html>

