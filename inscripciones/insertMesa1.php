<?php 

require_once('Connections/MySQL.php');
if (!isset($_SESSION)) {
  session_start();
}

$loginFormAction = $_SERVER['PHP_SELF'];

if (isset($_GET['accesscheck'])) {
  $_SESSION['PrevUrl'] = $_GET['accesscheck'];
}

$format = '%d/%m/%Y : %H:%i';

for ($i = 0; $i < count($_POST['materias']); $i++) {
	$turno = $_POST['turno'];
	$fechamesa = $_POST['fechamesa'];
	$desde = $_POST['desde'];
	$hasta = $_POST['hasta'];
	list($idMateria, $idDivision) = explode('-', $_POST['materias'][$i]);
	mysqli_query(dbconnect(), "INSERT INTO mesas_final (IdMesaFinal, IdTurnosFinales, IdDivision, IdMateriaPlan, Abierta, FechaMesa, Limite, FechaCreacion, DisponibleDesdeFecha, DisponibleHastaFecha) VALUES (NULL, $turno, $idDivision, $idMateria, 1, STR_TO_DATE('$fechamesa', '$format'), 0, CURRENT_TIMESTAMP, STR_TO_DATE('$desde', '$format'), STR_TO_DATE('$hasta', '$format'));");
}

header('Location: ABM_Modal/MesasDeFinales.php');