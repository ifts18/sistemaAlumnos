<?php require_once('Connections/MySQL.php'); ?>
<?php
//initialize the session
if (!isset($_SESSION)) {
  session_start();
}
?>


<h1>Loader<?php print $_SESSION['MM_Username'] ?>  </h1>

<?php
$par1 = $_SESSION['MM_Username'];
$par2 = '';
$query_Recordset1 = "CALL terciario.FinalesAlumno( $par1)";
if ($_SERVER["REQUEST_METHOD"] == "POST") {


    $Recordset1 = mysqli_query(dbconnect(),$query_Recordset1) or die(mysqli_error());
    $row_Recordset1 = mysqli_fetch_assoc($Recordset1);
    $totalRows_Recordset1 = mysqli_num_rows($Recordset1);

    $fechas = array();
    do {
       if(isset($_POST['idmesa'.(string)$row_Recordset1['IdMateriaPlan']]))
           $par2 = $_POST['idmesa'.(string)$row_Recordset1['IdMateriaPlan']]; 
       else
           $par2 = '0';
       
       if($par2 !='0')
       {
           $fechaMesa = $_POST['fechamesa'.(string)$row_Recordset1['IdMateriaPlan']];
           if(in_array($fechaMesa, $fechas) )  {
                header("Location: " . "Carga1.php?FechaRepetida" );
                //echo "Fechas repetidas";
                exit();
           }
           array_push($fechas, $fechaMesa );
           
           $query_FechaMesaRepetida = "
            SELECT  1
            FROM terciario.mesa_final_alumno mfa
            inner join terciario.alumno_materias am on mfa.IdAlumnoMateria = am.idAlumnoMateria
            inner join terciario.alumnos a on a.IdAlumno = am.IdAlumno
            inner join terciario.mesas_final mf on mf.idMesaFinal = mfa.IdMesaFinal
            WHERE a.IdAlumno = $par1 and mfa.Procesada = 0 and mf.Abierta = 1 
            and mf.FechaMesa = (select FechaMesa from mesas_final where IdMesaFinal = $par2)
            ";
            $FechaMesaRepetida = mysqli_query(dbconnect(), $query_FechaMesaRepetida) or die(mysqli_error());
            if (mysqli_num_rows($FechaMesaRepetida) > 0){
                header("Location: " . "Carga1.php?FechaRepetida" );
                exit();
            }else{
                mysqli_query(dbconnect(),"CALL terciario.AnotarseMesasFinalMateria( $par2,$par1)");
            }
       }
           
    } while ($row_Recordset1 = mysqli_fetch_assoc($Recordset1)); 
}

 header("Location: " . "Direcciones.php" );


?>

