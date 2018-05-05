
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
        <h4 class="modal-title" id="exampleModalLabel">Modificar Mesa de Final:</h4>
      </div>
      <div class="modal-body">
          <div id="datos_ajax"></div>
          <input type="hidden" class="form-control" id="id" name="id">
            
          <div class="form-group">
                <label for="materia" class="control-label">Materia:</label>
                <input type="text" class="form-control" id="materia" name="materia" readonly>
          </div>
          
          <div class="form-group">
                <label for="fechaMesa" class="control-label">Fecha de Mesa:</label>              
                <input type="date"  class="form-control"  id="fechaMesa" name="fechaMesa" /><br><br>
          </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
        <button type="submit" class="btn btn-primary">Actualizar Mesa</button>
      </div>
    </div>
  </div>
</div>
</form>