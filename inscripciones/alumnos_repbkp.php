<?php require("Connections/connection_reportes.php"); ?>
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


<html xmlns="http://www.w3.org/1999/xhtml">
<body>
<table width="1000" border="1" align="center">
  <tbody>
    <tr>
      <td width="604" align="center" ><h1> IFTS18 - Listar alumnos </h1></td>
      <td width="480" align="center"><h2>Admin:<?php print $_SESSION['ApeNom'] ?>&nbsp;</h2></td>
    </tr>
  </tbody>
</table>
<br><br><br><br>
<center>

<div id="filtros" align="left">
<form action="alumnos_rep.php" method="post">
 <label align="right"><h2>Administrador:<?php print $_SESSION['ApeNom'] ?>&nbsp;</h2></label>
 <label>Buscar</label>  
         <select name="filtro">
			    <option value="fechaing">A&ntilde;o</option>
			    <option value="todos">Todos</option>
			    <option value="dni">DNI</option>
			    <option value="apellido">Apellido</option>
</select>
<input type="text" name="palabra">
<input type="submit" value="Buscar" name="buscar" id="button-find">
</div>
</form>
</center>

<?php 
	if(isset($_POST['buscar'])){
		switch($_POST['filtro']){
			case "asd":
				break;
			case "todos":
				$sql = "SELECT * from alumnos;";
				break;
			case "dni":
	     	    $sql = "SELECT  DNI, Apellido, Nombre,FechaCreacion 
									FROM alumnos al
									WHERE DNI LIKE '{$_POST['palabra']}%'
									ORDER BY al.idAlumno ASC, FechaCreacion ASC ";
				break;
			case "apellido":
			     $sql = "SELECT * from alumnos WHERE Apellido LIKE '%{$_POST['palabra']}%'";
				break;
			case "fechaing":
			     $sql = "SELECT * from alumnos WHERE fechacreacion LIKE '%{$_POST['palabra']}%'";	
				 break;
		}
	}
	
	
	else{
		
		
		
		echo "<br/><br/>Seleccione el tipo de reporte que desea obtener"; 
		$sql = "select 1 ";
	}
?>

<div id="productos">
	<?php
	    error_reporting(E_ALL ^ E_NOTICE);
        $result = mysqli_query($link, $sql);
	
		if(!$result )
		{
		 	die('Ocurrio un error al obtener los valores de la base de datos: ' . mysql_error());
		}
		echo "<left witdh :1000px><table><th>Dni</th><th>Apellido</th><th>Nombre</th><th>Fecha Ingreso</th><th>E-mail</th><th>Password</th><br/>";

		while ($row = mysqli_fetch_array($result)){
        echo "<tr><td width=\"15%\">{$row['DNI']}</td></td>".
		     "<td width=\"15%\">{$row['Apellido']}</td>".
		     "<td width=\"15%\">{$row['Nombre']}</td>".
		     "<td width=\"15%\">{$row['FechaCreacion']}</td>".
			 "<td width=\"15%\">{$row['Email']} </td> ". 
		     "<td width=\"15%\">{$row['Password']} </td></tr>";
    
		}
		echo "</table></center>";
		
		mysqli_free_result($result);
        mysqli_close($link);

		
	?>
</div>
