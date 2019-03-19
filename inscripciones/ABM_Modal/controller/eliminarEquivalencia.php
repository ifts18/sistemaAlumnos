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
	 if (empty($_POST['idAlumnoEquivalencia'])){
			$errors[] = "ID vacío";
		}   else if (
			!empty($_POST['idAlumnoEquivalencia'])

		){

		// escaping, additionally removing everything that could be (html/javascript-) code
		$id_equivalencia=intval($_POST['idAlumnoEquivalencia']);
                $alumno=mysqli_real_escape_string(dbconnect(),(strip_tags($_POST["alumno"],ENT_QUOTES)));
		$materia=mysqli_real_escape_string(dbconnect(),(strip_tags($_POST["materia"],ENT_QUOTES)));

		$sqlDelete="DELETE FROM alumno_equivalencias WHERE idAlumnoEquivalencia='".$id_equivalencia."' ";
		$query_delete = mysqli_query(dbconnect(),$sqlDelete);
			if ($query_delete){
				$messages[] = "Los datos han sido eliminados satisfactoriamente.";
			} else{
				$errors []= "Lo siento algo ha salido mal intenta nuevamente.".mysqli_error($con);
			}
                $sqlUpdate= "UPDATE alumno_materias SET EsEquivalencia = 0, FechaFirma = NULL WHERE IdAlumno ='".$alumno."' AND IdMateriaPlan = '".$materia."'";
                $query_delete = mysqli_query(dbconnect(),$sqlUpdate);
			if ($query_delete){
				$messages[] = "Los datos han sido eliminados satisfactoriamente.";
			} else{
				$errors []= "Lo siento algo ha salido mal intenta nuevamente.".mysqli_error($con);
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
