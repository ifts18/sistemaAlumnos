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

//mysql_select_db($database_MySQL, $MySQL) ;
$query_Recordset1 = "select
                    IdMateria, CodigoMateria , Descripcion
                    from terciario.materias";

$Recordset1 = mysqli_query(dbconnect(),$query_Recordset1) or die(mysqli_error());
$row_Recordset1 = mysqli_fetch_assoc($Recordset1);
$totalRows_Recordset1 = mysqli_num_rows($Recordset1);
?>

<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="ABM_Modal/css/bootstrap.min.css" rel="stylesheet">
    <link href="ABM_Modal/css/dataTables.bootstrap.css" rel="stylesheet">
    <link href="ABM_Modal/css/bootstrap-select.min.css" rel="stylesheet">
    <link href="ABM_Modal/css/dataTables.bootstrap.css" rel="stylesheet">
    <link href="ABM_Modal/css/bootstrap-select.min.css" rel="stylesheet">
    <link rel="stylesheet" href="style.css?<?php echo time(); ?>" type="text/css" media="screen" />
    <link rel="stylesheet" href="print.css?<?php echo time(); ?>" type="text/css" media="print"  id="printCss"/>
    <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
    <script  type="text/javascript" src="ABM_Modal/js/jquery.min.js"></script>
    <script src="ABM_Modal/js/bootstrap.min.js"></script>
    <script src="ABM_Modal/js/bootstrap-select.min.js"></script>
  </head>
</html>
<body>
<table width="1000" border="1" align="center">
  <tbody>
    <tr>
      <td width="604" align="center" ><h1> IFTS18 - Generar listado por Materia </h1></td>
    </tr>
  </tbody>
</table>
<form method="post" name="selectMateria" action="ListadoAlumnosPorMateria.php" style="padding-bottom: 60px;">
<table width="1103" border="1"align="center">
  <tbody>
    <tr>
      <td width="100" align="center">ID Materia</td>
      <td width="100" align="center">Codigo Materia</td>
      <td width="100" align="center">Nombre</td>
    </tr>
<?php do { ?>
    <tr>
      <td align="center" <h4> <?php echo $row_Recordset1['IdMateria']; ?> </h4></td>
      <td align="center" <h4> <?php echo $row_Recordset1['CodigoMateria']; ?> </h4></td>
      <td align="center" <h4> <?php echo $row_Recordset1['Descripcion']; ?> </h4></td>
      <td>
        <div class="radio" align="center">
          <label><input type="radio" name="materia" value="<?php echo $row_Recordset1['IdMateria']?>">Seleccionar</label>
        </div>
      </td>
    </tr>
  <?php } while ($row_Recordset1 = mysqli_fetch_assoc($Recordset1));
  echo $row_Recordset1;
  ?>
  </tbody>
</table>
<div style="text-align:center; position: fixed; bottom: 0; background-color: #fff; left: 0; right: 0; padding-bottom: 10px;">
  <button class="btn btn-primary" type="submit">Enviar</button>
  <input class="btn btn-info" type=button onClick="location.href='Direcciones.php'" value='Volver al menu principal'>
</div>
</form>
</body>
<?php
mysqli_free_result($Recordset1);
?>
