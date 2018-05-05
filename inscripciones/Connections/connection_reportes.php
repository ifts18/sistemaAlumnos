<?php
$servername = "localhost";
$username = "terciario18";
$password = "nji90okm";
error_reporting(E_COMPILE_ERROR|E_ERROR|E_CORE_ERROR);
$link = mysqli_connect($servername,$username,$password);
mysqli_select_db($link, "terciario");
$tildes = $link->query("SET NAMES 'utf8'"); //Para que se muestren las tildes correctamente
?>