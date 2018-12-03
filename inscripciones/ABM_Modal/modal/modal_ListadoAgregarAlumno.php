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
<div class="modal fade" id="dataAgregar" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="exampleModalLabel">Buscar Alumnos por DNI:</h4>
      </div>
      <div class="modal-body">
          <div id="datos_ajax"></div>
          <input type="hidden" class="form-control" id="materia_id" name="materia_id">

          <div class="form-group">
            <label for="dni" class="control-label">DNI:</label>
            <input type="text" class="form-control" id="dniBuscar" name="dni">
          </div>

          <div id="alumnos_busqueda"></div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
        <button type="submit" class="btn btn-primary">Agregar al listado</button>
      </div>
    </div>
  </div>
</div>
</form>
