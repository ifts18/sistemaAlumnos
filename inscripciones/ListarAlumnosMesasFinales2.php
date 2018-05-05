<style type="text/css" media="print">   
@page   
{  
size: auto;   
margin: 7mm;  
}  
body  
{  
background-color:#FFFFFF;   
}                
</style>
<html>
<body>
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

//mysql_select_db($database_MySQL, $MySQL) ;
$par1 = $_SESSION['MM_Username'];
$query_Recordset1 = "select 
                            mf.IdMesaFinal,  m.Descripcion , mf.FechaMesa , count(*) as Inscriptos
                    from terciario.mesas_final mf
                                    inner join terciario.materias_plan mp on mp.IdMateriaPlan = mf.IdMateriaPlan
                                    inner join terciario.materias m on m.IdMateria = mp.IdMateria
                                    inner join terciario.mesa_final_alumno mfa on mfa.IdMesaFinal = mf.IdMesaFinal
                    where mf.Abierta = 1
                    group by mf.IdMesaFinal,  m.Descripcion , mf.FechaMesa;";

$Recordset1 = mysqli_query(dbconnect(),$query_Recordset1) or die(mysqli_error());
$row_Recordset1 = mysqli_fetch_assoc($Recordset1);
$totalRows_Recordset1 = mysqli_num_rows($Recordset1);
?>

<img src="head.PNG" align="center">
<br><br>
<h4 align="center"> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Año: _______ </h4>
<!---<table width="1000" border="1" align="center">
  <tbody>
    <tr>
      <td width="220" align="center" ><h2> ASIGNATURA </h2></td>
      <td width="220" align="center"><h2><?php print $row_Recordset1['Descripcion'] ?>&nbsp;</h2></td>
    </tr>
  </tbody>
</table>
--->
<br><br>
<!---<label align="left"><h3>ASIGNATURA:<u><?php print $row_Recordset1['Descripcion'] ?></u>&nbsp &nbsp &nbsp;Fecha: <u><?php print $row_Recordset1['FechaMesa'] ?></h3></label>--->

<table width="1103" border="1"align="center">
  <tbody>
    <tr>
      <td width="200" align="center">Asignatura</td>
      <td width="200" align="center">Fecha</td>
      <td width="200" align="center">N° orden</td>
      <td width="200" align="center">DNI, Apellido y Nombre</td>
	  <td width="200" align="center">Nota</td>
	  <td width="200" align="center">En letras</td>
    </tr>
    <?php 

    do { 
        if(($_POST['idmesa'.(string)$row_Recordset1['IdMesaFinal']])!='0')
        {
        ?>
            <td width="20%" align="center" <h4> <?php echo $row_Recordset1['Descripcion']; ?> </h4></td>
            <td width="20%" align="center" <h4> <?php echo substr($row_Recordset1['FechaMesa'], 0, 10); ?> </h4></td>
            <td width="5%">
            <?php
            $query_Recordset2 = sprintf("select 
                                        a.Email,
                                        a.DNI,
                                        a.Apellido,
                                        a.Nombre,
                                        m.Descripcion,
                                        mf.FechaMesa,
                                        mf.IdMesaFinal
                                from 
                                        terciario.mesa_final_alumno mfa
                                        inner join terciario.mesas_final mf on mf.idMesaFinal = mfa.IdMesaFinal
                                        inner join terciario.materias_plan mp on mp.IdMateriaPlan = mf.IdMateriaPlan
                                        inner join terciario.materias m on m.IdMateria = mp.IdMateria
                                        inner join terciario.alumno_materias am on mfa.IdAlumnoMateria = am.idAlumnoMateria
                                        inner join terciario.alumnos a on a.IdAlumno = am.IdAlumno
                                where mf.Abierta = 1 and mf.IdMesaFinal = %s" , GetSQLValueString($row_Recordset1['IdMesaFinal'], "int") ) ;


            $Recordset2 = mysqli_query(dbconnect(),$query_Recordset2) or die(mysqli_error());
            $row_Recordset2 = mysqli_fetch_assoc($Recordset2);
            ?>
			      <?php  $ae=0; do { $ae++; ?>
            <table> 
                    <td><u><?php echo $ae ?></u></td>
			      </table>
			      <?php 
              } while ($ae < mysqli_num_rows($Recordset2));?>
			      </td>
            <td width="40%">
            <?php
            if (mysqli_num_rows($Recordset2)>0){
                do {  ?>
                    <table> 
                            <td> <u><?php echo $row_Recordset2['DNI']; ?> <?php echo $row_Recordset2['Apellido']; ?> <?php echo ","; ?> <?php echo $row_Recordset2['Nombre']; ?></u></td>
                    </table>
                    <?php 
                   } while ($row_Recordset2 = mysqli_fetch_assoc($Recordset2)); 
            }?>
            </td>
			
			<td width="5%">
			<?php  $i=0; do { $i++; ?>
                    <table> 
                            <td><u>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</u></td>
					</table>
					<?php 
                   } while ($i < mysqli_num_rows($Recordset2));?>
			</td>
			
			<td width="5%">
			<?php  $ea=0; do { $ea++; ?>
            <table> 
                    <td><u>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</u></td>
			</table>
			<?php 
           } while ($ea < mysqli_num_rows($Recordset2));?>
			</td>
		  
            <?php 
        } ;?>
		
        <?php 
        
    } while ($row_Recordset1 = mysqli_fetch_assoc($Recordset1)); ?>

  </tbody>
</table>  
<br><br><br>
<h4 align="right">Total de alumnos: ______________</h4>
<h4>Presidente: ______________</h4> <h4 align="right">Aprobados: ______________</h4>
<h4>Vocal: ___________________</h4> <h4 align="right">Aplazados: ______________</h4>
<h4>Vocal: ___________________</h4>  <h4 align="right">Ausentes: ______________</h4>                           
    
<?php
mysqli_free_result($Recordset1);
?>
 <BR>
        <div style="text-align:center">
            <!--<input type='button' onclick='window.print();' value='Imprimir' />     -->
            <!--<input type=button onClick="location.href='Direcciones.php'" value='Volver al menu principal'> --->
            <!--<input type=button onClick="location.href='GenerarActaVolante.php'" value='Generar Acta Volante'>  -->
            <!--  <input type="submit" />    -->
        </div>              
</body>
</html>
