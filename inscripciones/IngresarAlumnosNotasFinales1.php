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


        //mysql_select_db($database_MySQL, $MySQL) ;
$par1 = $_SESSION['MM_Username'];
$query_Recordset1 = "select
                        mf.IdMesaFinal,
                        m.Descripcion ,
                        mf.FechaMesa ,
                        count(*) as Inscriptos
                    from terciario.mesas_final mf
                        inner join terciario.materias_plan mp on mp.IdMateriaPlan = mf.IdMateriaPlan
                        inner join terciario.materias m on m.IdMateria = mp.IdMateria
                        inner join terciario.mesa_final_alumno mfa on mfa.IdMesaFinal = mf.IdMesaFinal
                    group by
                        mf.IdMesaFinal,
                        m.Descripcion ,
                        mf.FechaMesa;";
$Recordset1 = mysqli_query(dbconnect(),$query_Recordset1) or die(mysqli_error());
$row_Recordset1 = mysqli_fetch_assoc($Recordset1);
$totalRows_Recordset1 = mysqli_num_rows($Recordset1);
?>

<form method="post" action="CargarNotasFinales1.php" style="padding-bottom: 60px;">

<table width="1000" border="1" align="center">
  <tbody>
    <tr>
      <td width="604" align="center" ><h1> IFTS18 - Notas obtenidas en mesas de Final </h1></td>
      <td width="480" align="center"><h2>Admin:<?php print $_SESSION['ApeNom'] ?>&nbsp;</h2></td>
    </tr>
  </tbody>
</table>

<table width="1103" border="1" align="center">
  <tbody>
    <tr>
      <td width="100" align="center">Materia</td>
      <td width="100" align="center">Nombre</td>
      <td width="100" align="center">Mesas</td>
      <td width="800" align="center">Alumnos</td>

    </tr>
    <?php
    $numero = 1;
    if (mysqli_num_rows($Recordset1)>0){
        do {//verifica que todos los argumentos que vienen en el post existen
            if(isset($_POST['idmesa'.(string)$row_Recordset1['IdMesaFinal']])&&
              ($_POST['idmesa'.(string)$row_Recordset1['IdMesaFinal']])!='0')
            {
            ?>
                <input type="hidden" name="IdMesaFinal<?php echo $numero?>" value="<?php echo $row_Recordset1['IdMesaFinal'] ?>" />
                <?php $numero++ ;  ?>
                <tr>
                <td style="font-size: 15px"><?php echo $row_Recordset1['IdMesaFinal']; ?></td>
                <td style="font-size: 15px"><?php echo $row_Recordset1['Descripcion']; ?></td>
                <td style="font-size: 15px"><?php echo $row_Recordset1['FechaMesa']; ?></td>
                <td>
                <?php
                $query_Recordset2 = sprintf("select
                                                a.Email,
                                                a.DNI,
                                                a.Apellido,
                                                a.Nombre,
                                                m.Descripcion,
                                                mf.FechaMesa,
                                                mf.IdMesaFinal,
                                                mfa.IdMesaFinalAlumno,
                                                mfa.Nota,
                                                mfa.Aprobado,
                                                mfa.Ausente
                                            from
                                                terciario.mesa_final_alumno mfa
                                                inner join terciario.mesas_final mf on mf.idMesaFinal = mfa.IdMesaFinal
                                                inner join terciario.materias_plan mp on mp.IdMateriaPlan = mf.IdMateriaPlan
                                                inner join terciario.materias m on m.IdMateria = mp.IdMateria
                                                inner join terciario.alumno_materias am on mfa.IdAlumnoMateria = am.idAlumnoMateria
                                                inner join terciario.alumnos a on a.IdAlumno = am.IdAlumno
                                            where
                                                 mf.IdMesaFinal = %s" , GetSQLValueString($row_Recordset1['IdMesaFinal'], "int") ) ;


                $Recordset2 = mysqli_query(dbconnect(),$query_Recordset2) or die(mysqli_error());
                $row_Recordset2 = mysqli_fetch_assoc($Recordset2);
                ?>
                <?php
                if (mysqli_num_rows($Recordset2)>0){
                    do {  ?>
                        <table  border="2" >
                            <td width="350" align="center">Alumno</td>
                            <td width="250" align="center">Presente</td>
                            <!--<td width="300" align="center">Aprobado</td>-->
                            <td width="10" align="center">Nota</td>
                            <tr>
                                <td> <?php echo $row_Recordset2['DNI']; ?>
                                <?php echo $row_Recordset2['Apellido']; ?>
								<?php echo ", "; ?>
								<?php echo $row_Recordset2['Nombre']; ?>								</td>

                                <td><input type="radio" name="Presente<?php echo $row_Recordset2['IdMesaFinalAlumno']?>"
                                  <?php if ($row_Recordset2['Ausente'] === '1') {?>checked="checked"<?php } ?> value="1" >Ausente
                                <input type="radio" name="Presente<?php echo $row_Recordset2['IdMesaFinalAlumno']?>" value="0"
                                <?php if ($row_Recordset2['Ausente'] === '0') {?>checked="checked"<?php } ?> > Presente</td>
                                <td><input style="text-align:center;" type="number" maxlength="2"  onKeyUp="if(this.value>10){this.value='10';}" name="Nota<?php echo $row_Recordset2['IdMesaFinalAlumno']?>" value="<?php echo $row_Recordset2['Nota']?>"></td>

                            <tr>
                        </table>
                        <?php
                       } while ($row_Recordset2 = mysqli_fetch_assoc($Recordset2));
                    } ?>
                </td>
              </tr>
                <?php
            } ;?>
            <input type="hidden" name="UltimoNumero" value="<?php echo $numero ?>" />
            <?php
        } while ($row_Recordset1 = mysqli_fetch_assoc($Recordset1));
        //var_dump($_POST);
    }
    ?>

  </tbody>
</table>

<div style="text-align:center; position: fixed; bottom: 0; background-color: #fff; left: 0; right: 0; padding-bottom: 10px;">
    <BR>
    <input type="submit" value="Guardar Nota/s" />
    <input type=button onClick="location.href='Direcciones.php'" value='Volver al menu principal'>
</div>

</form>
<?php
mysqli_free_result($Recordset1);
?>
