<?php require_once('Connections/MySQL.php'); ?>
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
if (!isset($_SESSION['MM_Username']))
    {
        header("Location: " .$MM_Redirigir);

    }
    else
        {
//mysql_select_db($database_MySQL, $MySQL);
$par1 = $_SESSION['MM_Username'];
$query_Recordset1 = "
  SELECT MF.IdMesaFinal, M.Descripcion, DATE_FORMAT(MF.FechaMesa, '%d/%m/%Y') AS FechaMesa, D.NombreDivision, C.Inscriptos
  FROM mesas_final MF
  INNER JOIN materias_plan MP ON MP.IdMateriaPlan = MF.IdMateriaPlan
  INNER JOIN materias M ON M.IdMateria = MP.IdMateria
  LEFT JOIN division D ON MF.IdDivision = D.IdDivision
  INNER JOIN (
    SELECT MF.IdMesaFinal, COUNT(MFA.IdMesaFinal) AS Inscriptos
    FROM mesas_final MF
    LEFT JOIN mesa_final_alumno MFA ON MFA.IdMesaFinal = MF.IdMesaFinal
    GROUP BY MF.IdMesaFinal
  ) AS C ON C.IdMesaFinal = MF.IdMesaFinal
  ORDER BY MF.FechaMesa DESC, M.Descripcion ASC";
$Recordset1 = mysqli_query(dbconnect(),$query_Recordset1) or die(mysqli_error());
$row_Recordset1 = mysqli_fetch_assoc($Recordset1);
$totalRows_Recordset1 = mysqli_num_rows($Recordset1);
?>

<table width="1000" border="1" align="center">
  <tbody>
    <tr>
      <td width="604" align="center" ><h1> IFTS18 - Inscriptos en mesas de Final </h1></td>
      <td width="480" align="center"><h2>Admin:<?php print $_SESSION['ApeNom'] ?>&nbsp;</h2></td>
    </tr>
  </tbody>
</table>


<form method="post" action="ListarAlumnosMesasFinales2.php" style="padding-bottom: 60px;">

<table width="1103" border="1" align="center">
  <tbody>
    <tr>
      <td width="100" align="center">Mesa</td>
      <td width="250" align="center">Materia</td>
      <td width="100" align="center">División</td>
      <td width="100" align="center">Fecha</td>
      <td width="100" align="center">Alumnos Inscriptos</td>

    </tr>
    <?php do { ?>
  <tr>
    <td align="center" <h4> <?php echo $row_Recordset1['IdMesaFinal']; ?></h4></td>
    <td align="center" <h4> <?php echo $row_Recordset1['Descripcion']; ?></h4></td>
    <td align="center" <h4> <?php echo $row_Recordset1['NombreDivision']; ?></h4></td>
    <td align="center" <h4> <?php echo $row_Recordset1['FechaMesa']; ?></h4></td>
    <td align="center" <h4> <?php echo $row_Recordset1['Inscriptos']; ?></h4></td>

    <td
    <br><br>
        <input type="radio" name="idmesa<?php echo $row_Recordset1['IdMesaFinal']?>" value="0" checked="checked">No listar
        <input type="radio" name="idmesa<?php echo $row_Recordset1['IdMesaFinal']?>" value="1">Listar
    </td>
  </tr>
  <?php } while ($row_Recordset1 = mysqli_fetch_assoc($Recordset1));
  //var_dump($_POST);
  ?>
  </tbody>
</table>


<div style="text-align:center; position: fixed; bottom: 0; background-color: #fff; left: 0; right: 0; padding-bottom: 10px;">

    <BR>
    <input type="submit" />
    <input type=button onClick="location.href='Direcciones.php'" value='Volver al menu principal'>
</div>

</form>


<?php
mysqli_free_result($Recordset1);
?>
<?php
        }
        ?>
