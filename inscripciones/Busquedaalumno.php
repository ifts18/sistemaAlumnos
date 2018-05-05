<?php require_once('Connections/MySQL.php'); ?>
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

<?php
// *** Validate request to login to this site.
if (!isset($_SESSION)) {
  session_start();
}

$loginFormAction = $_SERVER['PHP_SELF'];
if (isset($_GET['accesscheck'])) {
  $_SESSION['PrevUrl'] = $_GET['accesscheck'];
}

if (isset($_POST['dni'])) {
  $loginUsername=$_POST['dni'];
  $MM_fldUserAuthorization = "";
  //$MM_redirectLoginSuccess = "Carga1.php";
  $MM_redirectLoginSuccess = "AltaFechaFirma.php";
  $MM_redirectLoginFailed = "Failure.php";
  $MM_redirecttoReferrer = false;
  //mysql_select_db($database_MySQL, $MySQL);
  
  $LoginRS__query=sprintf("SELECT idAlumno, DNI FROM alumnos WHERE DNI=%s",
        GetSQLValueString($loginUsername, "int")); 
   
  $LoginRS = mysqli_query(dbconnect(),$LoginRS__query) or die(mysql_error());
  $row_Recordset1 = mysqli_fetch_assoc($LoginRS);
  $loginFoundUser = mysqli_num_rows($LoginRS);
  if ($loginFoundUser) {
     $loginStrGroup = "";
    
	if (PHP_VERSION >= 5.1) {session_regenerate_id(true);} else {session_regenerate_id();}
    //declare two session variables and assign them
    $_SESSION['MM_Username'] = $row_Recordset1['idAlumno'];
    $_SESSION['MM_UserGroup'] = $loginStrGroup;	      

    if (isset($_SESSION['PrevUrl']) && false) {
      $MM_redirectLoginSuccess = $_SESSION['PrevUrl'];	
    }
    header("Location: " . $MM_redirectLoginSuccess );
  }
  else {
    header("Location: ". $MM_redirectLoginFailed );
  }
}
?>

<table width="1000" border="1" align="center">
  <tbody>
    <tr>
      <td width="604" align="center" ><h1> IFTS18 - Seleccione alumno para ingresar fechas de firma</h1></td>
    </tr>
  </tbody>
</table>

<table width="500" border="1" align="center">
  <tbody>
    <tr>
      <td><form name="form1" method="POST" action="<?php echo $loginFormAction; ?>">
        <table width="335" border="1" align="center">
          <tbody>
            <tr>
              <td width="129" style="font-family: Gotham, 'Helvetica Neue', Helvetica, Arial, sans-serif; text-align: right;">Ingrese DNI : </td>
              <td width="190"><input name="dni" type="text" required="required" id="dni"></td>
            </tr>
            <tr>
              <td>&nbsp;</td>
              <td><input type="submit" name="Enviar" id="enviar" value="Enviar"></td>
            </tr>
          </tbody>
        </table>
      </form></td>
    </tr>
  </tbody>
</table>
