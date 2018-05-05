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
<html>
<body>
<table width="1000" border="1" align="center">
  <tbody>
    <tr>
      <td width="604" align="center" ><h1> IFTS18 - Dar de alta una nueva mesa </h1></td>
      <td width="480" align="center"><h2>Admin:<?php print $_SESSION['ApeNom'] ?>&nbsp;</h2></td>
    </tr>
  </tbody>
</table>
<br><br><br><br>
<form action="insertMesa.php" method="post">
Turno: <!-- <input type="text" name="turno" /><br><br>-->
<br><input type="checkbox" name="turno" value="1" > 1<br>
  <input type="checkbox" name="turno" value="2" > 2<br>
  <input type="checkbox" name="turno" value="3" > 3<br><br>
Materia: <!-- <input type="text" name="materia" /><br><br> -->
  <br><input type="checkbox" name="materia" value="1" > Ingles<br>
  <input type="checkbox" name="materia" value="2" > Arquitectura de computadoras<br>
  <input type="checkbox" name="materia" value="3" > Contabilidad<br>
  <input type="checkbox" name="materia" value="4" > Diagramacion Logica<br>
  <input type="checkbox" name="materia" value="5" > Introduccion al estudio de las TICS<br>
  <input type="checkbox" name="materia" value="6" > Logica Computacional<br>
  <input type="checkbox" name="materia" value="7" > Algebra Lineal<br>
  <input type="checkbox" name="materia" value="8" > Estructura de Datos<br>
  <input type="checkbox" name="materia" value="9" > Estructura de la Organizacion<br>
  <input type="checkbox" name="materia" value="10" > Paradigmas de Programación<br>
  <input type="checkbox" name="materia" value="11" > Practica profesional I<br>
  <input type="checkbox" name="materia" value="12" > Analisis de Sistemas<br>
  <input type="checkbox" name="materia" value="13" > Base de datos<br>
  <input type="checkbox" name="materia" value="14" > Gestion de Proyectos Informaticos<br>
  <input type="checkbox" name="materia" value="15" > Ingenieria de Software<br>
  <input type="checkbox" name="materia" value="16" > Sistemas Operativos<br>
  <input type="checkbox" name="materia" value="17" > Calculo Numerico<br>
  <input type="checkbox" name="materia" value="18" > Diseno de Sistemas<br>
  <input type="checkbox" name="materia" value="19" > Planeamiento y Control de Gestion<br>
  <input type="checkbox" name="materia" value="20" > Practica profesional II<br>
  <input type="checkbox" name="materia" value="21" > Programacion Aplicada<br>
  <input type="checkbox" name="materia" value="22" > Seminario de Profundizacion<br>
  <input type="checkbox" name="materia" value="23" > Practica Profesional III<br>
  <input type="checkbox" name="materia" value="24" > Desarrollo Web<br>
  <input type="checkbox" name="materia" value="25" > Estadistica Aplicada<br>
  <input type="checkbox" name="materia" value="26" > Laboratorio de redes<br>
  <input type="checkbox" name="materia" value="27" > Seminario de Profundizacion III<br>
  <input type="checkbox" name="materia" value="28" > Herramientas de Gestion Empresarial<br>
  <input type="checkbox" name="materia" value="29" > Legislacion Aplicable a la Tecnologia<br>
  <input type="checkbox" name="materia" value="30" > Seguridad informatica<br>
  <br><br>
Fecha de la Mesa: <input type="date" name="fechamesa" /><br><br>
Disponible desde: <input type="date" name="desde" /><br><br>
Disponible hasta: <input type="date" name="hasta" /><br><br> 
    <input type="submit" />
    <input type=button onClick="location.href='Direcciones.php'" value='Volver al menu principal'>
</form>

</body>
</html>