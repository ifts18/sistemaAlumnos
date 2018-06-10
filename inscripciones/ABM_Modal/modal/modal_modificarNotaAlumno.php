
<?php require_once('../Connections/MySQL.php'); ?>
<?php
//initialize the session
if (!isset($_SESSION)) {
  session_start();
}


// ** Logout the current user. **
$logoutAction = $_SERVER['PHP_SELF']."?doLogout=true";
if ((isset($_SERVER['QUERY_STRING'])) && ($_SERVER['QUERY_STRING'] != "")){
  $logoutAction .="&". htmlentities($_SERVER['QUERY_STRING']);
}
?>


<form id="actualidarDatos">
<div class="modal fade" id="dataUpdate" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="exampleModalLabel">Modificar Nota del Alumno:</h4>
      </div>
      <div class="modal-body">
	<div id="datos_ajax"></div>
           <input type="hidden" class="form-control" id="idAlumno" name="idAlumno">
           <input type="hidden" class="form-control" id="idmesafinalalumno" name="idmesafinalalumno">
<!--            <div class="form-group">
                <label for="idmesafinalalumno" class="control-label">ID:</label>
                <input type="text" class="form-control" id="idmesafinalalumno" name="idmesafinalalumno" required maxlength="40">
            </div> -->
           <div class="form-group">
                <label for="nombre" class="control-label">Nombre:</label>
                <input type="text" class="form-control" id="nombre" name="nombre" required maxlength="40">
            </div>
            <div class="form-group">
                <label for="apellido" class="control-label">Apellido:</label>
                <input type="text" class="form-control" id="apellido" name="apellido" required maxlength="45">
            </div>
            
          <div class="form-group">
            <label for="dni" class="control-label">DNI:</label>
            <input type="text" class="form-control" id="dni" name="dni" required maxlength="30"> 
          </div>
           
           <div class="form-group">
            <label for="materia" class="control-label">Materia:</label>
            <input type="text" class="form-control" id="materia" name="materia" required maxlength="40">
          </div>

           <div class="form-group">
            <label for="nota" class="control-label">Nota:</label>
            <input type="text" class="form-control" id="nota" name="nota" required maxlength="30"> 
          </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
        <button type="submit" class="btn btn-primary">Actualizar datos</button>
      </div>
    </div>
  </div>
</div>
</form>