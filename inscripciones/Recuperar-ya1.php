<?php
  require_once('Connections/MySQL.php');
?>

<?php
$email=$_POST['email'];


$sql4 = "SELECT DNI, Email FROM alumnos WHERE DNI ='$email'";
$Recordset4 = mysqli_query(dbconnect(),$sql4) or die(mysqli_error());
$resultarr1 = mysqli_fetch_assoc($Recordset4);
$attempts1 = $resultarr1["Email"];
$attempts2 = $resultarr1["DNI"];

if ($attempts2 == $email)
{
$pass = mt_rand(10000000, 99999999);
$sql5 = "UPDATE alumnos SET `Password`='$pass'  WHERE DNI = '$attempts2'";
$Recordset5 = mysqli_query(dbconnect(),$sql5) or die(mysqli_error());
mysqli_free_result($Recordset5);
mysqli_free_result($Recordset4);
?>

<table width="1000" border="1" align="center">
  <tbody>
    <tr>
      <td width="604" align="center" ><h1> IFTS18 - Recupero de contrase&ntildea</h1></td>
    </tr>
  </tbody>
</table>

<table width="500" border="1" align="center">
  <tbody>
    <tr>
      <td><form name="form1" method="POST" action="Recuperar-ya.php">
        <table width="335" border="1" align="center">
          <tbody>
            <tr>
              <td width="129" align="center" style="font-family: Gotham, 'Helvetica Neue', Helvetica, Arial, sans-serif; text-align: center;">Se ha enviado por e-mail la nueva contrase&ntildea a <?php echo "<b>".$attempts1."</b>"?><br><br>Verifique su bandeja de correo no deseado!</td>
            </tr>
            <tr>
              <td><center><input type=button onClick="location.href='index.php'" value='Volver a login'></td></center>
            </tr>
          </tbody>
        </table>
      </form></td>
    </tr>
  </tbody>
</table>

<?php
$cabeceras = 'From: no-reply@ifts18.edu.ar' . "\r\n" .
    'X-Mailer: PHP/' . phpversion();
$mensaje = "Su nueva clave es: ".$pass."\n Ingrese con su DNI en http://inscripciones.ifts18.edu.ar/";
//echo $mensaje;
mail($attempts1, 'IFTS 18 - Nueva contraseña', $mensaje, $cabeceras);
}
else
{
mysqli_free_result($Recordset4);
?>
<table width="1000" border="1" align="center">
  <tbody>
    <tr>
      <td width="604" align="center" ><h1> IFTS18 - Recupero de contrase&ntildea</h1></td>
    </tr>
  </tbody>
</table>

<table width="500" border="1" align="center">
  <tbody>
    <tr>
      <td><form name="form1" method="POST" action="Recuperar-ya.php">
        <table width="335" border="1" align="center">
          <tbody>
            <tr>
              <td width="129" align="center" style="font-family: Gotham, 'Helvetica Neue', Helvetica, Arial, sans-serif; text-align: center;">No encontramos su DNI en el sistema. Verifique los datos o comuniquese con secretaria.</td>
            </tr>
            <tr>
              <td><center><input type=button onClick="location.href='index.php'" value='Volver a login'></td></center>
            </tr>
          </tbody>
        </table>
      </form></td>
    </tr>
  </tbody>
</table>

<?php 
}
?>