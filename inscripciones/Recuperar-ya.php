<?php
  require_once('Connections/MySQL.php');

  if (!isset($_SESSION)) {
  session_start();
}
?>

<?php
$passwd=$_POST['pass'];
$passwd1=$_POST['pass1'];
$par1 = $_SESSION['MM_Username'];

if ($passwd==$passwd1)
{
$sql5 = "UPDATE `terciario`.`alumnos` SET `Password`='$passwd' WHERE `IdAlumno`='$par1'";
$Recordset5 = mysqli_query(dbconnect(),$sql5) or die(mysqli_error());
mysqli_free_result($Recordset5);
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
              <td width="129" align="center" style="font-family: Gotham, 'Helvetica Neue', Helvetica, Arial, sans-serif; text-align: center;">Se ha cambiado la contrase&ntildea exitosamente.</td>
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
} else {
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
              <td width="129" align="center" style="font-family: Gotham, 'Helvetica Neue', Helvetica, Arial, sans-serif; text-align: center;">Las contrase&ntildeas ingresadas deben ser iguales.</td>
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