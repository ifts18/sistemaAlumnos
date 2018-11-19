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

AgregarAlumno($_POST['Materia'], $_POST['DNI'], $_POST['Apellido'], $_POST['Nombre'] );

function Modificarlista($IdMateria, $dni, $apellido, $nombre)

$setencia = "update";
?>
