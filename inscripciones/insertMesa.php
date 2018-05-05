<?php require_once('Connections/MySQL.php'); ?>
<?php
if (!isset($_SESSION)) {
  session_start();
}

$loginFormAction = $_SERVER['PHP_SELF'];
if (isset($_GET['accesscheck'])) {
  $_SESSION['PrevUrl'] = $_GET['accesscheck'];
}

$turno=$_POST['turno'];
$turnoint=intval($turno);
$materia=$_POST['materia'];
$materiaint=intval($materia);
$fechamesa=$_POST['fechamesa'];
$fechamesa= $fechamesa.' 00:00:00';
$desde=$_POST['desde'];
$desde=$desde.' 00:00:00';
$hasta=$_POST['hasta'];
$hasta=$hasta.' 00:00:00';
echo $hasta;
$sql1 = "INSERT INTO `terciario`.`mesas_final`(`IdMesaFinal`, `IdTurnosFinales`, `IdMateriaPlan`, `Abierta`, `FechaMesa`, `Limite`, `FechaCreacion`, `DisponibleDesdeFecha`, `DisponibleHastaFecha`)
VALUES(NULL, $turnoint, $materiaint, 1, '$fechamesa', 0, CURRENT_TIMESTAMP, '$desde', '$hasta');";
$Recordset1 = mysqli_query(dbconnect(),$sql1) or die(mysqli_error());
mysqli_free_result($Recordset1);
 header("Location: " . "Direcciones.php" );

?>