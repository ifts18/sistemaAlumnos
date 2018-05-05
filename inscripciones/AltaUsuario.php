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
<html>
<body>
<table width="1000" border="1" align="center">
  <tbody>
    <tr>
      <td width="604" align="center" ><h1> IFTS18 - Dar de alta un alumno </h1></td>
      <td width="480" align="center"><h2>Admin:<?php print $_SESSION['ApeNom'] ?>&nbsp;</h2></td>
    </tr>
  </tbody>
</table>
<br><br><br><br>
<form action="insertUsuario.php" method="post" onsubmit="return confirm('¿Seguro quiere guardar los datos?');" >
Nombre: <input type="text" name="nombre"/><br><br>
Apellido: <input type="text" name="apellido"/><br><br>
E-mail: <input type="text" name="email"/><br><br>
DNI: <input type="text" name="dni"/><br><br>
    <input type="submit" />
    <input type=button onClick="location.href='Direcciones.php'" value='Volver al menu principal'>
</form>

</body>
</html>