<?php require_once('../../Connections/MySQL.php'); ?>
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

<?php
	/*Inicia validacion del lado del servidor*/
	 if (empty($_POST['alumno'])){
			$errors[] = "No hay ningun Alumno seleccionado";
		} else if (empty($_POST['materia'])){
			$errors[] = "No hay ninguna Materia seleccionada";
		} else if (
			!empty($_POST['alumno']) &&
			!empty($_POST['materia'])

		){

		// escaping, additionally removing everything that could be (html/javascript-) code
		$alumno=mysqli_real_escape_string(dbconnect(),(strip_tags($_POST["alumno"],ENT_QUOTES)));
		$materia=mysqli_real_escape_string(dbconnect(),(strip_tags($_POST["materia"],ENT_QUOTES)));
		$id=intval($_POST['id']);
    $date = new DateTime("NOW");
		$sql="UPDATE alumno_equivalencias SET  idAlumno='".$alumno."', idMateriaPlan='".$materia."', fechaCreacion = '{$date->format('Y-m-d H:i:s')}'
                        WHERE idAlumnoEquivalencia='".$id."'";
		$query_update = mysqli_query(dbconnect(),$sql);
			if ($query_update){
				$messages[] = "Los datos han sido actualizados satisfactoriamente.";
			} else{
				$errors []= "Lo siento algo ha salido mal intenta nuevamente.".mysqli_error($con);
			}
      $date = new DateTime("NOW");
      $sqlUpdate = "UPDATE alumno_materias SET EsEquivalencia = 1, FechaFirma = '{$date->format('Y-m-d')}' WHERE IdAlumno ='".$alumno."' AND IdMateriaPlan = '".$materia."'";
      $query_AM_update = mysqli_query(dbconnect(),$sqlUpdate);
      if ($query_AM_update){
        $messages[] = "Los datos han sido guardados satisfactoriamente.";
      } else{
        $errors []= "Lo siento algo ha salido mal intenta nuevamente.".mysqli_error(dbconnect());
      }
		} else {
			$errors []= "Error desconocido.";
		}

		if (isset($errors)){

			?>
			<div class="alert alert-danger" role="alert">
				<button type="button" class="close" data-dismiss="alert">&times;</button>
					<strong>Error!</strong>
					<?php
						foreach ($errors as $error) {
								echo $error;
							}
						?>
			</div>
			<?php
			}
			if (isset($messages)){

				?>
				<div class="alert alert-success" role="alert">
						<button type="button" class="close" data-dismiss="alert">&times;</button>
						<strong>¡Bien hecho!</strong>
						<?php
							foreach ($messages as $message) {
									echo $message;
								}
							?>
				</div>
				<?php
			}

?>
