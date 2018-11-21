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
<html>
<body>
<table width="1000" border="1" align="center">
  <tbody>
    <tr>
      <td width="604" align="center" ><h1> IFTS18 - Agregar Alumno Al Listado de Materias </h1></td>
      <td width="480" align="center"><h2>Admin:<?php print $_SESSION['ApeNom'] ?>&nbsp;</h2></td>
    </tr>
  </tbody>
</table>
<br><br><br><br>
<form action="ListadoAlumnosPorMateriaGuardarNuevo.php"
      method="post"
      onsubmit="return confirm('¿Seguro quiere guardar los datos?');" >

      DNI: <input type="text" name="dni"/><br><br>
      Apellido: <input type="text" name="apellido"/><br><br>
      Nombre: <input type="text" name="nombre"/><br><br>

    <input type="submit">
    <input type=button onClick="location.href='Direcciones.php'" value='Volver al menu principal'>
</form>
</body>
</html>
