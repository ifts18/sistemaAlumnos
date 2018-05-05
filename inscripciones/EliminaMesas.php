<?php require_once('Connections/MySQL.php'); ?>
<?php
//initialize the session
if (!isset($_SESSION)) {
  session_start();
}
?>


<h1>Elimina Mesas <?php print $_SESSION['MM_Username'] ?>  </h1>

<?php
$par1 = $_SESSION['MM_Username'];
$query_Recordset1 = "CALL terciario.ListarFinalesAlumno( $par1)";
$Recordset1 = mysqli_query(dbconnect(),$query_Recordset1) or die(mysqli_error());
$row_Recordset1 = mysqli_fetch_assoc($Recordset1);
$totalRows_Recordset1 = mysqli_num_rows($Recordset1);
?>

<?php 
do { 
    //var_dump($_POST);
    if(($_POST['idmesa'.(string)$row_Recordset1['IdMesaFinalAlumno']])!='0')
    {
        //print $_POST['idmesa'.(string)$row_Recordset1['IdMesaFinalAlumno']];
        $par2 = $row_Recordset1['IdMesaFinalAlumno'];      
        mysqli_query(dbconnect(),"DELETE FROM terciario.mesa_final_alumno WHERE IdMesaFinalAlumno=  $par2 ");
        
    }
} while ($row_Recordset1 = mysqli_fetch_assoc($Recordset1));
 header("Location: " . "Direcciones.php" );
?>

