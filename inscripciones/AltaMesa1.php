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
$loginFormAction = $_SERVER['PHP_SELF'];
if (isset($_GET['accesscheck'])) {
  $_SESSION['PrevUrl'] = $_GET['accesscheck'];
}

?>

<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Alta de mesas de Examen</title>

<script language="javascript">
//VALIDACION CHECKBOX
function validacion(formu, obj) {
  limite=1; //limite de checks a seleccionar
  num=0;
  if (obj.checked) {
    for (i=0; ele=document.getElementById(formu).children[i]; i++)
      if (ele.checked) num++;
  if (num>limite)
    obj.checked=false;
  }
}  
</script>
</head>
<body>
</left>
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
<form action="insertMesa1.php" method="Post" id="form1" onsubmit="return confirm('¿Seguro quiere guardar los datos?');"><br><br>
Seleccione Turno: <!-- <input type="text" name="turno" /><br><br>-->
<br>
<div>
<input type="checkbox" name="turno" value="1"  onchange="validacion('form1', this)"> 1<br>
  <input type="checkbox" name="turno" value="2"  onchange="validacion('form1', this)" > 2<br>
  <input type="checkbox" name="turno" value="3"   onchange="validacion('form1', this)"> 3<br><br>
  </div>
Materia: <!-- <input type="text" name="materia" /><br><br> -->
  <br><input type="checkbox" name="materia[]" value="1" > Ingles<br>
  <input type="checkbox" name="materia[]" value="2" > Arquitectura de computadoras<br>
  <input type="checkbox" name="materia[]" value="3" > Contabilidad<br>
  <input type="checkbox" name="materia[]" value="4" > Diagramacion Logica<br>
  <input type="checkbox" name="materia[]" value="5" > Introduccion al estudio de las TICS<br>
  <input type="checkbox" name="materia[]" value="6" > Logica Computacional<br>
  <input type="checkbox" name="materia[]" value="7" > Algebra Lineal<br>
  <input type="checkbox" name="materia[]" value="8" > Estructura de Datos<br>
  <input type="checkbox" name="materia[]" value="9" > Estructura de la Organizacion<br>
  <input type="checkbox" name="materia[]" value="10" > Paradigmas de Programación<br>
  <input type="checkbox" name="materia[]" value="11" > Practica profesional I<br>
  <input type="checkbox" name="materia[]" value="12" > Analisis de Sistemas<br>
  <input type="checkbox" name="materia[]" value="13" > Base de datos<br>
  <input type="checkbox" name="materia[]" value="14" > Gestion de Proyectos Informaticos<br>
  <input type="checkbox" name="materia[]" value="15" > Ingenieria de Software<br>
  <input type="checkbox" name="materia[]" value="16" > Sistemas Operativos<br>
  <input type="checkbox" name="materia[]" value="17" > Calculo Numerico<br>
  <input type="checkbox" name="materia[]" value="18" > Diseno de Sistemas<br>
  <input type="checkbox" name="materia[]" value="19" > Planeamiento y Control de Gestion<br>
  <input type="checkbox" name="materia[]" value="20" > Practica profesional II<br>
  <input type="checkbox" name="materia[]" value="21" > Programacion Aplicada<br>
  <input type="checkbox" name="materia[]" value="22" > Seminario de Profundizacion<br>
  <input type="checkbox" name="materia[]" value="23" > Practica Profesional III<br>
  <input type="checkbox" name="materia[]" value="24" > Desarrollo Web<br>
  <input type="checkbox" name="materia[]" value="25" > Estadistica Aplicada<br>
  <input type="checkbox" name="materia[]" value="26" > Laboratorio de redes<br>
  <input type="checkbox" name="materia[]" value="27" > Seminario de Profundizacion III<br>
  <input type="checkbox" name="materia[]" value="28" > Herramientas de Gestion Empresarial<br>
  <input type="checkbox" name="materia[]" value="29" > Legislacion Aplicable a la Tecnologia<br>
  <input type="checkbox" name="materia[]" value="30" > Seguridad informatica<br>
  <br><br>
Fecha de la Mesa: <input type="date" name="fechamesa" required/><br><br>
Disponible desde: <input type="date" name="desde" required/><br><br>
Disponible hasta: <input type="date" name="hasta" required/><br><br> 
<input type="submit" id="button-find" value="Ingresar" />
</form>
<!---<script>
var el = document.getElementById('form1');

el.addEventListener('submit', function(){
    return confirm('¿Está seguro que desea guardar los datos?');
}, false);
</script>--->
</body>
</html>