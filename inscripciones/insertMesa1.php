<?php 

require_once('Connections/MySQL.php');
if (!isset($_SESSION)) {
  session_start();
}

$loginFormAction = $_SERVER['PHP_SELF'];

if (isset($_GET['accesscheck'])) {
  $_SESSION['PrevUrl'] = $_GET['accesscheck'];
}

if (isset($_POST['turno']) == '') {
	return header('Location altamesa.php');
}

for ($i = 0; $i < count($_POST["materias"]); $i++) {
	$turno = $_POST["turno"];
	$division = isset($_POST["division"]) ? $_POST["division"] : 0;
	$materiaId = $_POST["materias"][$i];
	$fechamesa= $_POST['fechamesa'];
	$desde = $_POST['desde'];
	$hasta = $_POST['hasta'];

	mysqli_query(dbconnect(), "
		INSERT INTO mesas_final
		(IdMesaFinal, IdTurnosFinales, IdDivision, IdMateriaPlan, Abierta, FechaMesa, Limite, 
		FechaCreacion, DisponibleDesdeFecha, DisponibleHastaFecha)
		VALUES (NULL, $turno, $division, $materiaId, 1, 
		STR_TO_DATE('$fechamesa', '%d/%m/%Y %H:%i'), 0, CURRENT_TIMESTAMP, 
		STR_TO_DATE('$desde', '%d/%m/%Y %H:%i'), 
		STR_TO_DATE('$hasta', '%d/%m/%Y %H:%i')
		);
	");
}

header("Location: " . "ABM_Modal/MesasDeFinales.php" );
?>