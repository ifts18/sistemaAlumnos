<?php require_once('Connections/MySQL.php'); ?>
<?php
//initialize the session
if (!isset($_SESSION)) {
  session_start();
}
$MessageError = '';
if(isset($_GET['FechaRepetida']))
{
    $MessageError = '<br> <div align="center" style = "color: Red; font-size: large; "><b>No se puede inscribir en varias mesas con una misma fecha!<b></div>';
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
$query_Recordset1 = "CALL terciario.FinalesAlumno( $par1)";

$Recordset1 = mysqli_query(dbconnect(),$query_Recordset1) or die(mysqli_error());
$row_Recordset1 = mysqli_fetch_assoc($Recordset1);
$totalRows_Recordset1 = mysqli_num_rows($Recordset1);
?>


<table width="1000" border="1" align="center">
  <tbody>
    <tr>
      <td width="604" align="center" ><h1> IFTS18 - Inscripcion en mesas de Final </h1></td>
      <td width="480" align="center"><h2>Alumno:<?php print $_SESSION['ApeNom'] ?>&nbsp;</h2></td>
    </tr>
  </tbody>
</table>

<?php echo $MessageError ?>

<form method="post" action="Loader.php" >
<br><br>

<table width="1103" align="center" border="1">
    <tbody>
    <tr>
      <td width="100" align="center"><h3>Materia</h3></td>
      <td width="100" align="center"><h3>Nombre</h3></td>
      <td width="100" align="center"><h3>Mesas</h3></td>
    </tr>
    <?php 
    if (mysqli_num_rows($Recordset1)>0){

        do { ?>
            <tr>
                <td <h4><?php echo $row_Recordset1['IdMateriaPlan']; ?></h4></td>
                <td <h4><?php echo $row_Recordset1['Descripcion']; ?></h4></td>
                <!--td style="font-size: 9px"><?php echo $row_Recordset1['IdMesaFinal']; ?></td-->
            <td
            <br><br>
            <?php
            $par1 = $row_Recordset1['IdMateriaPlan'];
            $par2 = $_SESSION['MM_Username'];
            $query_Recordset2 = "CALL terciario.MesasFinalMateria( $par1, $par2)";
            $Recordset2 = mysqli_query(dbconnect(),$query_Recordset2) or die(mysqli_error(dbconnect()));
            $row_Recordset2 = mysqli_fetch_assoc($Recordset2);
            if (mysqli_num_rows($Recordset2)>0){    
            ?>
            <input type="radio" name="idmesa<?php echo $row_Recordset1['IdMateriaPlan']?>" value="0" checked="checked">No presentarse
            <?php
                do {  ?>
                    <input type="radio" name="idmesa<?php echo $row_Recordset1['IdMateriaPlan']?>" value="<?php echo $row_Recordset2['IdMesaFinal']?>"><?php echo $row_Recordset2['FechaMesa']?>
                    <input type="hidden" name="fechamesa<?php echo $row_Recordset1['IdMateriaPlan']?>" value="<?php echo $row_Recordset2['FechaMesa']?>">
            <?php } while ($row_Recordset2 = mysqli_fetch_assoc($Recordset2));}; ?>
            </td>
            </tr>
            <?php 
        } while ($row_Recordset1 = mysqli_fetch_assoc($Recordset1)); ?>
    </tbody>
    
</table>
        <BR>
        <div style="text-align:center">  
            <input type="submit" />
        </div>          
        
        <?php 
    } else 
    {    ; ?> 

</form>



<table width="1000" border="1" align="center">
  <tbody>
    <tr>
        <td>
        <center>No hay informacion Disponible</center>
        </td>
    </tr>
  </tbody>
</table>
    <?php }; ?>
    
    
<?php
mysqli_free_result($Recordset1);
if (isset($Recordset2)) mysqli_free_result($Recordset2);
?>


