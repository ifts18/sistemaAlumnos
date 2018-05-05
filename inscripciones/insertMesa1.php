<?php require_once('Connections/MySQL.php'); ?>

<?php
if (!isset($_SESSION)) {
  session_start();

}

$loginFormAction = $_SERVER['PHP_SELF'];
if (isset($_GET['accesscheck'])) {
  $_SESSION['PrevUrl'] = $_GET['accesscheck'];
}

if (isset($_POST['turno'])=='')
{      
		header('Location: altamesa.php');
}
else {
	 /****
	 if($_POST['materia'] == ''){
			 header("Location: " . "altamesa.php" );
     }		 
     else
	     {
		 if($_POST['fechamesa'] == ''){
				 header("Location: " . "altamesa.php" );
		 }		 
		 else{
			 if($_POST['desde'] == ''){
					 header("Location: " . "altamesa.php" );
			 }		 
			 else{
				  if($_POST['hasta'] == ''){
					 header("Location: " . "altamesa.php" );
			     }	*****/
					    $turno=$_POST["turno"];
						$materia=$_POST["materia"];
						$count = count($materia);
						
						for ($i = 0; $i < $count; $i++) {
							echo $materia[$i];
						
    						$turnoint=$turno;
							$materiaint=$materia[$i];
							$fechamesa=$_POST['fechamesa'];
							$fechamesa= $fechamesa.' 00:00:00';
							$desde=$_POST['desde'];
							$desde=$desde.' 00:00:00';
							$hasta=$_POST['hasta'];
							$hasta=$hasta.' 00:00:00';
							
							$sql1 = "INSERT INTO `terciario`.`mesas_final`(`IdMesaFinal`, `IdTurnosFinales`, `IdMateriaPlan`, `Abierta`, `FechaMesa`, `Limite`, `FechaCreacion`, `DisponibleDesdeFecha`, `DisponibleHastaFecha`)
							VALUES(NULL, $turnoint, $materiaint, 1, '$fechamesa', 0, CURRENT_TIMESTAMP, '$desde', '$hasta');";
							$Recordset1 = mysqli_query(dbconnect(),$sql1) or die(mysqli_error());
						}  
					header("Location: " . "AltaMesa1.php" );
					
		    /***}***/
			
      /***   }****/
    }

?>
