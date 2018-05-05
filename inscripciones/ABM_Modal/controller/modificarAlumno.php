<?php require_once('../../Connections/MySQL.php'); ?>
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

<?php
	/*Inicia validacion del lado del servidor*/
	  if (empty($_POST['nombre'])){
			$errors[] = "No hay ningun Nombre ingresado";
		} else if (empty($_POST['apellido'])){
			$errors[] = "No hay ningun Apellido ingresado";
                } else if (empty($_POST['dni'])){
			$errors[] = "No hay ningun DNI ingresado";
		} else if (
			!empty($_POST['nombre']) && 
			!empty($_POST['apellido']) && 
			!empty($_POST['dni']) 
			
		){

		// escaping, additionally removing everything that could be (html/javascript-) code
                $id=intval($_POST['idAlumno']);
		$nombre=mysqli_real_escape_string(dbconnect(),(strip_tags($_POST["nombre"],ENT_QUOTES)));
		$apellido=mysqli_real_escape_string(dbconnect(),(strip_tags($_POST["apellido"],ENT_QUOTES)));
                $email=mysqli_real_escape_string(dbconnect(),(strip_tags($_POST["email"],ENT_QUOTES)));
		$dni=mysqli_real_escape_string(dbconnect(),(strip_tags($_POST["dni"],ENT_QUOTES)));
                $password=mysqli_real_escape_string(dbconnect(),(strip_tags($_POST["password"],ENT_QUOTES)));
                
                
                $sqlNuevo = "SELECT DNI FROM alumnos WHERE DNI = '$dni' ";
                $RecordsetNuevo = mysqli_query(dbconnect(),$sqlNuevo) or die(mysqli_error());
                $resultadoNuevo = mysqli_fetch_assoc($RecordsetNuevo);
                $sqlViejo = "SELECT DNI FROM alumnos where idAlumno = '$id'";
                $RecordsetViejo = mysqli_query(dbconnect(),$sqlViejo) or die(mysqli_error());
                $resultadoViejo = mysqli_fetch_assoc($RecordsetViejo);
                $dniNuevoExistente = $resultadoNuevo["DNI"];
                $dniViejo= $resultadoViejo["DNI"];
                
                if ($dniNuevoExistente == $dni && $dniNuevoExistente != $dniViejo )
                {
                       $errors[] = "Alumno ya existente!";
                }else{

                    $sql="UPDATE alumnos SET  Apellido='".$apellido."', Nombre='".$nombre."' , Email='".$email."' , DNI='".$dni."' , Password = '".$password."'
                            WHERE idAlumno='".$id."'";
                    $query_update = mysqli_query(dbconnect(),$sql);
                            if ($query_update){
                                    $messages[] = "Los datos han sido actualizados satisfactoriamente.";
                            } else{
                                    $errors []= "Lo siento algo ha salido mal intenta nuevamente.".mysqli_error($con);
                            }
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