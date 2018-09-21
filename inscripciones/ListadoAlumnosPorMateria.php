<?php require_once('Connections/MySQL.php'); ?>
<?php

//
//
//***para generar listado de presentismo y la de finales***
//
//

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

<?php
if (!function_exists("GetSQLValueString")) {
function GetSQLValueString($theValue, $theType, $theDefinedValue = "", $theNotDefinedValue = "")
{
  if (PHP_VERSION < 6) {
    $theValue = get_magic_quotes_gpc() ? stripslashes($theValue) : $theValue;
  }

  $theValue = function_exists("mysqli_real_escape_string") ? mysqli_real_escape_string(dbconnect(), $theValue) : mysqli_escape_string(dbconnect(), $theValue);

  switch ($theType) {
    case "text":
      $theValue = ($theValue != "") ? "'" . $theValue . "'" : "NULL";
      break;
    case "long":
    case "int":
      $theValue = ($theValue != "") ? intval($theValue) : "NULL";
      break;
    case "double":
      $theValue = ($theValue != "") ? doubleval($theValue) : "NULL";
      break;
    case "date":
      $theValue = ($theValue != "") ? "'" . $theValue . "'" : "NULL";
      break;
    case "defined":
      $theValue = ($theValue != "") ? $theDefinedValue : $theNotDefinedValue;
      break;
  }
  return $theValue;
}
}

?>



<table width="1000" border="1" align="center">
  <tbody>
    <tr>
      <td width="604" align="center" ><h1> IFTS18 - Listado Alumnos por Materia </h1></td>
      <td width="480" align="center"><h2>Alumno:<?php print $row_Recordset1['Descripcion'] ?>&nbsp;</h2></td>
    </tr>
  </tbody>
</table>

<table width="1103" border="1" align="center">
  <tbody>
    <tr>
      <td width="100" align="center">Apellido</td>
      <td width="100" align="center">DNI</td>
      <td width="100" align="center">Materia</td>
    </tr>
    <?php do { ?>
  <tr>
    <td align="center" <h4><?php echo $row_Recordset1['Apellido']; ?></h4></td>
    <td align="center" <h4><?php echo $row_Recordset1['DNI']; ?></h4></td>
    <td align="center" <h4><?php echo $row_Recordset1['Descripcion']; ?></h4></td>
  </tr>
  <?php } while ($row_Recordset1 = mysqli_fetch_assoc($Recordset1));
  echo $row_Recordset1;
  ?>
</tbody>
</table>
</form>

<div style="text-align:center">
    <BR>
    <input type="submit" />
    <input type=button onClick="location.href='Direcciones.php'" value='Volver al menu principal'>
</div>

<?php
mysqli_free_result($Recordset1);
?>
