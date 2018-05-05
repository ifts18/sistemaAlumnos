<?php require_once('Connections/MySQL.php'); ?>
<?php
//initialize the session
if (!isset($_SESSION)) {
  session_start();
}
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
?>


<h1>Actualiza fecha de firmas <?php print $_SESSION['MM_Username'] ?>  </h1>
<?php
//var_dump($_POST);


for ( $numero = 1; $numero<$_POST['UltimoNumero']; $numero++) { 
    //if(isset(  $_POST['IdMesaFinal'.(string)$numero]))
        $par1= $_POST['IdMesaFinal'.(string)$numero];
        $query_Recordset2 = sprintf("select 
                                    a.Email,
                                    a.DNI,
                                    a.Apellido,
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
                            where  mf.Abierta = 1 and mfa.Procesada = 0 and mf.IdMesaFinal = %s" , GetSQLValueString($par1, "int") ) ;
        $Recordset2 = mysqli_query(dbconnect(),$query_Recordset2) or die(mysqli_error());
        $row_Recordset2 = mysqli_fetch_assoc($Recordset2);
  
        /*
        print "Presente";
        print $_POST['Presente'.(string)$row_Recordset2['IdMesaFinalAlumno']];
        echo "<BR>";
        print "Aprobado";
        print $_POST['Aprobado'.(string)$row_Recordset2['IdMesaFinalAlumno']]; 
        echo "<BR>";
        print "Nota";
        print $_POST['Nota'.(string)$row_Recordset2['IdMesaFinalAlumno']]; 
        */
        do {  
            if(($_POST['Presente'.(string)$row_Recordset2['IdMesaFinalAlumno']])!='0'){
                $par1 = $_POST['Aprobado'.(string)$row_Recordset2['IdMesaFinalAlumno']];
                $par2 = $_POST['Nota'.(string)$row_Recordset2['IdMesaFinalAlumno']];
                $par3 = $row_Recordset2['IdMesaFinalAlumno'];
                mysqli_query(dbconnect(),"UPDATE terciario.mesa_final_alumno SET Ausente = 0, Procesada = 1, Aprobado = $par1, Nota =  $par2  WHERE IdMesaFinalAlumno = $par3");
            }
           } while ($row_Recordset2 = mysqli_fetch_assoc($Recordset2));          
} 
 header("Location: " . "Direcciones.php" ); ?>