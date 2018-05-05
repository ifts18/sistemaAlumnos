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
			$errors[] = "No hay ninguna Apellido ingresado";
                } else if (empty($_POST['dni'])){
			$errors[] = "No hay ninguna DNI ingresado";
		} else if (
			!empty($_POST['nombre']) && 
			!empty($_POST['apellido']) && 
			!empty($_POST['dni']) 
			
		){

                    // escaping, additionally removing everything that could be (html/javascript-) code
                    $nombre=mysqli_real_escape_string(dbconnect(),(strip_tags($_POST["nombre"],ENT_QUOTES)));
                    $apellido=mysqli_real_escape_string(dbconnect(),(strip_tags($_POST["apellido"],ENT_QUOTES)));
                    $email=mysqli_real_escape_string(dbconnect(),(strip_tags($_POST["email"],ENT_QUOTES)));
                    $DNI=mysqli_real_escape_string(dbconnect(),(strip_tags($_POST["dni"],ENT_QUOTES)));


                    $sql4 = "SELECT DNI FROM alumnos WHERE DNI = '$DNI' ";
                    $Recordset4 = mysqli_query(dbconnect(),$sql4) or die(mysqli_error());
                    $resultarr1 = mysqli_fetch_assoc($Recordset4);
                    $attempts1 = $resultarr1["DNI"];

                    if ($attempts1 == $DNI)
                    {
                           $errors[] = "Alumno ya existente!";
                    }else{
		

                    $sql1 = "INSERT INTO alumnos (IdAlumno, IdPlan, Email, DNI, Apellido, Nombre, Password, FechaCreacion) 
                    VALUES (NULL, 1, '$email', '$DNI', '$apellido', '$nombre', '$DNI', CURRENT_TIMESTAMP);";
                    $Recordset1 = mysqli_query(dbconnect(),$sql1) or die(mysqli_error());

                    $sql2 = "SELECT IdAlumno FROM alumnos WHERE DNI = '$DNI' ";
                    $Recordset2 = mysqli_query(dbconnect(),$sql2) or die(mysqli_error());
                    $resultarr = mysqli_fetch_assoc($Recordset2);
                    $attempts = $resultarr["IdAlumno"];

                    $sql3="INSERT INTO alumno_materias (idAlumnoMateria, IdAlumno, IdMateriaPlan, FechaFirma, FechaCaduco, MotivoCaduco, Repeticion, FechaCreacion) 
                    VALUES (NULL, '$attempts', 1, NULL, NULL, NULL, 0, CURRENT_TIMESTAMP),
                                    (NULL, '$attempts', 2, NULL, NULL, NULL, 0, CURRENT_TIMESTAMP),
                               (NULL, '$attempts', 3, NULL, NULL, NULL, 0, CURRENT_TIMESTAMP),
                               (NULL, '$attempts', 4, NULL, NULL, NULL, 0, CURRENT_TIMESTAMP),
                           (NULL, '$attempts', 5, NULL, NULL, NULL, 0, CURRENT_TIMESTAMP),
                               (NULL, '$attempts', 6, NULL, NULL, NULL, 0, CURRENT_TIMESTAMP),
                               (NULL, '$attempts', 7, NULL, NULL, NULL, 0, CURRENT_TIMESTAMP),
                               (NULL, '$attempts', 8, NULL, NULL, NULL, 0, CURRENT_TIMESTAMP),
                               (NULL, '$attempts', 9, NULL, NULL, NULL, 0, CURRENT_TIMESTAMP),
                               (NULL, '$attempts', 10, NULL, NULL, NULL, 0, CURRENT_TIMESTAMP);";

                    $Recordset3 = mysqli_query(dbconnect(),$sql3) or die(mysqli_error());

                    $sql5="INSERT INTO alumno_materias (idAlumnoMateria, IdAlumno, IdMateriaPlan, FechaFirma, FechaCaduco, MotivoCaduco, Repeticion, FechaCreacion) 
                    VALUES (NULL, '$attempts', 11, NULL, NULL, NULL, 0, CURRENT_TIMESTAMP),
                                    (NULL, '$attempts', 12, NULL, NULL, NULL, 0, CURRENT_TIMESTAMP),
                               (NULL, '$attempts', 13, NULL, NULL, NULL, 0, CURRENT_TIMESTAMP),
                               (NULL, '$attempts', 14, NULL, NULL, NULL, 0, CURRENT_TIMESTAMP),
                           (NULL, '$attempts', 15, NULL, NULL, NULL, 0, CURRENT_TIMESTAMP),
                               (NULL, '$attempts', 16, NULL, NULL, NULL, 0, CURRENT_TIMESTAMP),
                               (NULL, '$attempts', 17, NULL, NULL, NULL, 0, CURRENT_TIMESTAMP),
                               (NULL, '$attempts', 18, NULL, NULL, NULL, 0, CURRENT_TIMESTAMP),
                               (NULL, '$attempts', 19, NULL, NULL, NULL, 0, CURRENT_TIMESTAMP),
                               (NULL, '$attempts', 20, NULL, NULL, NULL, 0, CURRENT_TIMESTAMP);";

                    $Recordset5 = mysqli_query(dbconnect(),$sql5) or die(mysqli_error());

                    $sql6="INSERT INTO alumno_materias (idAlumnoMateria, IdAlumno, IdMateriaPlan, FechaFirma, FechaCaduco, MotivoCaduco, Repeticion, FechaCreacion) 
                    VALUES (NULL, '$attempts', 21, NULL, NULL, NULL, 0, CURRENT_TIMESTAMP),
                                    (NULL, '$attempts', 22, NULL, NULL, NULL, 0, CURRENT_TIMESTAMP),
                               (NULL, '$attempts', 23, NULL, NULL, NULL, 0, CURRENT_TIMESTAMP),
                               (NULL, '$attempts', 24, NULL, NULL, NULL, 0, CURRENT_TIMESTAMP),
                           (NULL, '$attempts', 25, NULL, NULL, NULL, 0, CURRENT_TIMESTAMP),
                               (NULL, '$attempts', 26, NULL, NULL, NULL, 0, CURRENT_TIMESTAMP),
                               (NULL, '$attempts', 27, NULL, NULL, NULL, 0, CURRENT_TIMESTAMP),
                               (NULL, '$attempts', 28, NULL, NULL, NULL, 0, CURRENT_TIMESTAMP),
                               (NULL, '$attempts', 29, NULL, NULL, NULL, 0, CURRENT_TIMESTAMP),
                               (NULL, '$attempts', 30, NULL, NULL, NULL, 0, CURRENT_TIMESTAMP);";

                    $Recordset6 = mysqli_query(dbconnect(),$sql6) or die(mysqli_error());

                    $messages[] = "Los datos han sido guardados satisfactoriamente.";

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

