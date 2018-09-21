<?php require_once('Connections/MySQL.php'); ?>

<?php
// *** Validate request to login to this site.
if (!isset($_SESSION)) {
  session_start();
}
?>

<table width="600" border="1" align="center">
  <tbody>
    <?php if ($_SESSION['MM_UserGroup']=='Admin'){ ?>

    <tr>
        <td width="100" align="center"> <A HREF ="ABM_Modal/Alta_Alumnos.php">Dar de alta un alumno</A>
        <BR>
        <BR>
	</td>
    </tr>

    <tr>
      <td width="100" align="center"> <A HREF = "ABM_Modal/Listar_Alumnos.php?origen=1">Listar alumnos</A>
      <BR>
      <BR>
      </td>
    </tr>

    <tr>
    <td
        width="100" align="center"> <a href="GenerarListadoAlumnosPorMateria.php">General Listado Alumnos por Materias</A>
    <BR>
    <BR>
    </td>
    </tr>

    <tr>
        <td width="100" align="center"> <A HREF = "ABM_Modal/Modificar_Alumnos.php?origen=2">Modificar Alumnos</A>
        <BR>
        <BR>
        </td>
    </tr>

    <tr>
        <td width="100" align="center"> <A HREF = "Busquedaalumno.php">Ingresar fecha de regularidad de materia</A>
        <BR>
        <BR>
	</td>
    </tr>

    <tr>
        <td width="100" align="center"> <A HREF = "ABM_Modal/Equivalencias.php">Equivalencias Aprobadas por Alumnos</A>
        <BR>
        <BR>
        </td>
    </tr>

    <tr>
        <td width="100" align="center"> <A HREF = "ABM_Modal/MateriasPorAlumnos.php">Materias por Alumnos</A>
        <BR>
        <BR>
        </td>
    </tr>

    <tr>
        <td width="100" align="center"> <A HREF = "AltaMesa1.php">Dar de alta una mesa</A>
        <BR>
        <BR>
        </td>
    </tr>

    <tr>
        <td width="100" align="center"> <A HREF = "ABM_Modal/MesasDeFinales.php">Mesas de Finales</A>
        <BR>
        <BR>
        </td>
    </tr>

    <tr>
        <td width="100" align="center"> <A HREF = "ListarMesasFinales.php">Actas de ex&aacute;menes</A>
        <BR>
        <BR>
        </td>
    </tr>

    <tr>
        <td width="100" align="center"> <A HREF = "ListarMesasFinales2.php">Cargar Notas de Final</A>
        <BR>
        <BR>
        </td>
    </tr>
      <tr>
      <td width="100" align="center"> <A HREF = "CerrarSesion.php" >Cerrar Sesión</A>
    <BR>
    <BR>
    </td>
    </tr>

    <?php } else {
	$par1 = $_SESSION['MM_Username'];
	$sql2 = "SELECT DNI, Password FROM alumnos WHERE IdAlumno = '$par1' ";
	$Recordset2 = mysqli_query(dbconnect(),$sql2) or die(mysqli_error());
	$resultarr = mysqli_fetch_assoc($Recordset2);
	$attemptsdni = $resultarr["DNI"];
	$attemptspassword = $resultarr["Password"];
	/*Javascript de DNI/contraseña iguales
	if ($attemptsdni==$attemptspassword)
	{
		$message = "Su contraseña no puede ser igual a tu usuario. Por favor cambie su contraseña.";
		echo "<script type='text/javascript'>alert('$message');</script>";
	}*/
	?>
    <tr>
    <td width="100" align="center"> <A HREF = "Carga1.php">Anotarse en las Mesas de Final</A>
    <BR>
    <BR>
     </td>
    </tr>
    <tr>
      <td width="100" align="center"> <A HREF = "EditFinales.php" >Modificar mesas en las que se anoto</A>
    <BR>
    <BR>
    </td>
    </tr>

    <tr>
    <td
        width="100" align="center"> <A HREF = "ABM_Modal/MateriasPorAlumnos.php">Ver Estado de las Materias</A>
    <BR>
    <BR>
    </td>
    </tr>
    <tr>
      <td width="100" align="center"> <A HREF = "Recuperar.php" >Cambiar Contrase&ntildea</A>
    <BR>
    <BR>
    </td>
    </tr>

     <tr>
      <td width="100" align="center"> <A HREF = "CerrarSesion.php" >Cerrar Sesión</A>
    <BR>
    <BR>
    </td>
    </tr>
    <?php }
	?>



</table>
