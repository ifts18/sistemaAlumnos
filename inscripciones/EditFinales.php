<?php require_once('Connections/MySQL.php'); ?>
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

//mysql_select_db($database_MySQL, $MySQL);
$par1 = $_SESSION['MM_Username'];
$query_Recordset1 = "CALL terciario.ListarFinalesAlumno( $par1)";

$Recordset1 = mysqli_query(dbconnect(),$query_Recordset1) or die(mysqli_error());
$row_Recordset1 = mysqli_fetch_assoc($Recordset1);
$totalRows_Recordset1 = mysqli_num_rows($Recordset1);
?>

<table width="1000" border="1" align="center">
  <tbody>
    <tr>
      <td width="604" align="center" ><h1> IFTS18 - Editar Inscripcion en mesas de Final </h1></td>
      <td width="480" align="center"><h2>Alumno:<?php print $_SESSION['ApeNom'] ?>&nbsp;</h2></td>
    </tr>
  </tbody>
</table>

<form method="post" action="EliminaMesas.php">

<table width="1103" align="center" border="1">
	<tbody>
    <tr>
      <td width="80" align="center">Materia</td>
      <td width="80" align="center">Nombre</td>
      <td width="80" align="center">Mesa</td>
    </tr>
    <?php do { ?>
  <tr>
    <td align="center" <h4> <?php echo $row_Recordset1['IdMesaFinalAlumno']; ?></h4></td>
    <td align="center" <h4> <?php echo $row_Recordset1['Descripcion']; ?></h4></td>
    <td align="center" <h4> <?php echo $row_Recordset1['FechaMesa']; ?></h4></td>
    <td
    <br><br>
        <input type="radio" name="idmesa<?php echo $row_Recordset1['IdMesaFinalAlumno']?>" value="0" checked="checked">Presentarse
        <input type="radio" name="idmesa<?php echo $row_Recordset1['IdMesaFinalAlumno']?>" value="1">Eliminar Mesa
    </td>  
  </tr>
  <?php } while ($row_Recordset1 = mysqli_fetch_assoc($Recordset1)); 
  //var_dump($_POST); 
  ?>
  </tbody>
</table>
    
<div style="text-align:center">  
    <BR>
    <input type="submit" />
</div>          

</form>    
    
    
<?php
mysqli_free_result($Recordset1);
?>
