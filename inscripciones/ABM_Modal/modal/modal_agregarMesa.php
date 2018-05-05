
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

<meta http-equiv="conten-type" content="text/html; charset=UTF-8" />
<form id="guardarDatos" name="guardarDatos">
<div class="modal fade" id="dataRegister" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="exampleModalLabel">Agregar Materia Aprobada por Equivalencia</h4>
      </div>
      <div class="modal-body">
			<div id="datos_ajax_register"></div>
          <div class="form-group">
            <label for="alumno0" class="control-label">Alumno:</label>                  
            <?php        
             $sqlAlumnos='select * from alumnos';
             $resultado_consulta_sqlAlumnos=mysqli_query(dbconnect(),$sqlAlumnos);
             echo "<select class='selectpicker form-control' name='alumno' id='alumno' data-live-search='true'>";
             echo "<option selected disabled hidden style='display: none' value=''></option>";
             while($fila=mysqli_fetch_array($resultado_consulta_sqlAlumnos)){
                 echo "<option value='".$fila['IdAlumno']."'>".$fila['Apellido'].", ".$fila['Nombre']."</option>";
             }
             echo "</select>";
            ?>
          </div>
                        
          <div class="form-group">
            <label for="materia0" class="control-label">Materias aprobadas por equivalencia:</label>                  
            <?php        
             $sqlMaterias='SELECT mp.IdMateriaPlan, m.Descripcion 
                           FROM materias m 
                           INNER JOIN materias_plan mp on mp.IdMateria = m.IdMateria 
                           WHERE mp.IdPlan = 1';
             $resultado_consulta_sqlMaterias=mysqli_query(dbconnect(),$sqlMaterias);
             echo "<select class='selectpicker form-control' name='materia' id='materia' data-live-search='true'>";
             echo "<option selected disabled hidden style='display: none' value=''></option>";
             while($fila=mysqli_fetch_array($resultado_consulta_sqlMaterias)){
                 echo "<option value='".$fila['IdMateriaPlan']."'>".$fila['Descripcion']."</option>";
             }
             echo "</select>";
            ?>
          </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
        <button type="submit" class="btn btn-primary" >Guardar datos</button>
      </div>
    </div>
  </div>
</div>
</form>
