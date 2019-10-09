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

<meta http-equiv="conten-type" content="text/html; charset=UTF-8" />
<form id="guardarDatos" name="guardarDatos">
<div class="form-control-static" id="dataRegister" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <!--<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>-->
        <h4 class="modal-title" id="exampleModalLabel">Dar de alta Alumno</h4>
      </div>
      <div class="modal-body">
			<div id="datos_ajax_register"></div>
          <div class="form-group">
            <label for="nombre0" class="control-label">Nombre:</label>
            <input type="text" class="form-control" id="nombre0" name="nombre" required maxlength="40">
		  </div>
		  <div class="form-group">
            <label for="apellido0" class="control-label">Apellido:</label>
            <input type="text" class="form-control" id="apellido0" name="apellido" required maxlength="45">
          </div>
		  <div class="form-group">
            <label for="email0" class="control-label">E-mail:</label>
            <input type="text" class="form-control" id="email0" name="email" required maxlength="40">
          </div>
		  <div class="form-group">
            <label for="dni0" class="control-label">DNI:</label>
            <input type="text" class="form-control" id="dni0" name="dni" required maxlength="10"> 
          </div>
      <div class="form-group">
            <!-- <label for="division0" class="control-label">Division 1° año:</label>
            <input type="text" class="form-control" id="division0" name="division" required maxlength="1">  -->
            <label for="division0" class="control-label">Division:</label>
              <select id="division0" name="division" class="form-control">
                  <option value="1">1° A</option>
                  <option value="2">1° B</option>
                  <!-- Las opciones siguiente no estan implementadas, en caso de abrir divisiones en segundo año implemetarlas -->
                  <option value="1">2° A</option>
                  <option value="2">2° B</option>
                  <option value="0">3°</option>
              </select>
            </label> 
          </div>
      </div>
      <div class="modal-footer">
        <!--<button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>-->
        <button type="submit" class="btn btn-primary" >Guardar datos</button>
      </div>
    </div>
  </div>
</div>
</form>
