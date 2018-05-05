
<?php require_once('../../Connections/MySQL.php'); ?>
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

	$action = (isset($_REQUEST['action'])&& $_REQUEST['action'] !=NULL)?$_REQUEST['action']:'';
	if($action == 'ajax'){
		include 'pagination.php'; //incluir el archivo de paginación
		//las variables de paginación
		$page = (isset($_REQUEST['page']) && !empty($_REQUEST['page']))?$_REQUEST['page']:1;
		$per_page = 10; //la cantidad de registros que desea mostrar
		$adjacents  = 4; //brecha entre páginas después de varios adyacentes
		$offset = ($page - 1) * $per_page;
		
		$reload = 'Equivalencias.php';
		//consulta principal para recuperar los datos
                $method = (isset($_REQUEST['method'])&& $_REQUEST['method'] !=NULL)?$_REQUEST['method']:'';
                $palabra = (isset($_REQUEST['palabra'])&& $_REQUEST['palabra'] !=NULL)?$_REQUEST['palabra']:'';
                $filtro = (isset($_REQUEST['filtro'])&& $_REQUEST['filtro'] !=NULL)?$_REQUEST['filtro']:'';
                
                if($method == 'buscar'){
                    switch($filtro){
                            case "todos":
                                    $sql = "SELECT ae.idAlumnoEquivalencia as id , a.DNI as DNI, a.Apellido as Apellido, a.Nombre as Nombre, 
                                                   m.Descripcion as Materia, ae.FechaCreacion as Fecha, mp.idMateriaPlan, a.IdAlumno 
                                            FROM terciario.alumno_equivalencias ae
                                            INNER JOIN terciario.alumnos a on ae.idAlumno = a.idAlumno
                                            INNER JOIN terciario.materias_plan mp on mp.idMateriaPlan = ae.idMateriaPlan
                                            INNER JOIN terciario.materias m on m.idMateria = mp.idMateria
                                            LIMIT $offset,$per_page  ";
                                    $count_query  = mysqli_query(dbconnect(),"SELECT count(*) AS numrows  FROM terciario.alumno_equivalencias ae
                                            INNER JOIN terciario.alumnos a on ae.idAlumno = a.idAlumno
                                            INNER JOIN terciario.materias_plan mp on mp.idMateriaPlan = ae.idMateriaPlan
                                            INNER JOIN terciario.materias m on m.idMateria = mp.idMateria ");
                                    break;
                            case "dni":
                                    $sql = "SELECT ae.idAlumnoEquivalencia as id , a.DNI as DNI, a.Apellido as Apellido, a.Nombre as Nombre, 
                                                   m.Descripcion as Materia, ae.FechaCreacion as Fecha , mp.idMateriaPlan, a.IdAlumno 
                                            FROM terciario.alumno_equivalencias ae
                                            INNER JOIN terciario.alumnos a on ae.idAlumno = a.idAlumno
                                            INNER JOIN terciario.materias_plan mp on mp.idMateriaPlan = ae.idMateriaPlan
                                            INNER JOIN terciario.materias m on m.idMateria = mp.idMateria
                                            WHERE DNI LIKE '%$palabra%'
                                            ORDER BY a.DNI ASC LIMIT $offset,$per_page ";
                                    $count_query  = mysqli_query(dbconnect(),"SELECT count(*) AS numrows  FROM terciario.alumno_equivalencias ae
                                            INNER JOIN terciario.alumnos a on ae.idAlumno = a.idAlumno
                                            INNER JOIN terciario.materias_plan mp on mp.idMateriaPlan = ae.idMateriaPlan
                                            INNER JOIN terciario.materias m on m.idMateria = mp.idMateria 
                                            WHERE DNI LIKE '%$palabra%'");
                                    break;
                            case "apellido":
                                    $sql = "SELECT ae.idAlumnoEquivalencia as id , a.DNI as DNI, a.Apellido as Apellido, a.Nombre as Nombre, 
                                                   m.Descripcion as Materia, ae.FechaCreacion as Fecha , mp.idMateriaPlan, a.IdAlumno 
                                            FROM terciario.alumno_equivalencias ae
                                            INNER JOIN terciario.alumnos a on ae.idAlumno = a.idAlumno
                                            INNER JOIN terciario.materias_plan mp on mp.idMateriaPlan = ae.idMateriaPlan
                                            INNER JOIN terciario.materias m on m.idMateria = mp.idMateria
                                            WHERE Apellido LIKE '%$palabra%' 
                                            ORDER BY a.Apellido ASC LIMIT $offset,$per_page ";
                                $count_query  = mysqli_query(dbconnect(),"SELECT count(*) AS numrows  FROM terciario.alumno_equivalencias ae
                                            INNER JOIN terciario.alumnos a on ae.idAlumno = a.idAlumno
                                            INNER JOIN terciario.materias_plan mp on mp.idMateriaPlan = ae.idMateriaPlan
                                            INNER JOIN terciario.materias m on m.idMateria = mp.idMateria 
                                              WHERE Apellido LIKE '%$palabra%' ");
                                    break;
                            case "materia":
                                    $sql = "SELECT ae.idAlumnoEquivalencia as id , a.DNI as DNI, a.Apellido as Apellido, a.Nombre as Nombre, 
                                                   m.Descripcion as Materia, ae.FechaCreacion as Fecha , mp.idMateriaPlan, a.IdAlumno 
                                            FROM terciario.alumno_equivalencias ae
                                            INNER JOIN terciario.alumnos a on ae.idAlumno = a.idAlumno
                                            INNER JOIN terciario.materias_plan mp on mp.idMateriaPlan = ae.idMateriaPlan
                                            INNER JOIN terciario.materias m on m.idMateria = mp.idMateria
                                            WHERE m.Descripcion LIKE '%$palabra%' 
                                            ORDER BY m.Descripcion ASC LIMIT $offset,$per_page ";
                                    $count_query  = mysqli_query(dbconnect(),"SELECT count(*) AS numrows  FROM terciario.alumno_equivalencias ae
                                            INNER JOIN terciario.alumnos a on ae.idAlumno = a.idAlumno
                                            INNER JOIN terciario.materias_plan mp on mp.idMateriaPlan = ae.idMateriaPlan
                                            INNER JOIN terciario.materias m on m.idMateria = mp.idMateria 
                                            WHERE m.Descripcion LIKE '%$palabra%' 
                                            ORDER BY m.Descripcion ASC ");
                                     break;
                            }
                }else{
                        $sql =  "select ae.idAlumnoEquivalencia as id, a.DNI as DNI, a.Apellido as Apellido, a.Nombre as Nombre, 
                                    m.Descripcion as Materia, ae.FechaCreacion as Fecha , mp.idMateriaPlan, a.IdAlumno 
                             from terciario.alumno_equivalencias ae
                             inner join terciario.alumnos a on ae.idAlumno = a.idAlumno
                             inner join terciario.materias_plan mp on mp.idMateriaPlan = ae.idMateriaPlan
                             inner join terciario.materias m on m.idMateria = mp.idMateria 
                             ORDER BY a.Apellido ASC 
                             LIMIT $offset,$per_page ";
                        $count_query  = mysqli_query(dbconnect(),"SELECT count(*) AS numrows  FROM terciario.alumno_equivalencias ae
                                            INNER JOIN terciario.alumnos a on ae.idAlumno = a.idAlumno
                                            INNER JOIN terciario.materias_plan mp on mp.idMateriaPlan = ae.idMateriaPlan
                                            INNER JOIN terciario.materias m on m.idMateria = mp.idMateria 
                                            ORDER BY a.Apellido ASC  ");
                }
                
                //Cuenta el número total de filas de la tabla*/
		if ($row= mysqli_fetch_array($count_query)){$numrows = $row['numrows'];}
		$total_pages = ceil($numrows/$per_page);
                
                $query = mysqli_query(dbconnect(), $sql );
              
		if ($numrows>0){
			?>
                <div class="col-sm-12">            
                    <table style="width: 100%;" aria-describedby="table_info" role="grid" 
                           class="table table-striped table-bordered dataTable" cellspacing="0" width="100%">
                              <thead>
                                    <tr style="font-weight: bold">
                                        <td width="100" align="center">DNI</td>
                                        <td width="100" align="center">Apellido</td>
                                        <td width="100" align="center">Nombre</td>
                                        <td width="120" align="center">Materia Aprobada</td>
                                        <td width="100" align="center">Fecha de alta</td>
                                        <th width="110" align="center">Acciones</th>
                                    </tr>
                            </thead>
                            <tbody>
                            <?php
                            while($row = mysqli_fetch_array($query)){
                                    ?>
                                    <tr >
                                            <td align="center" <h4> <?php echo $row['DNI']; ?></h4></td>
                                            <td align="left" <h4> <?php echo $row['Apellido']; ?></h4></td>
                                            <td align="left" <h4> <?php echo $row['Nombre']; ?></h4></td>
                                            <td align="left" <h4> <?php echo $row['Materia']; ?></h4></td>
                                            <td align="center" <h4> <?php echo $row['Fecha']; ?></h4></td>
                                            <td >
                                                    <button type="button" class="btn btn-info" data-toggle="modal" data-target="#dataUpdate" data-id="<?php echo $row['id']?>" data-alumno="<?php echo $row['IdAlumno']?>" data-materia="<?php echo $row['idMateriaPlan']?>" ><i class='glyphicon glyphicon-edit'></i> Modificar</button>
                                                    <button type="button" class="btn btn-danger" data-toggle="modal" data-target="#dataDelete" data-id="<?php echo $row['id']?>" data-alumno="<?php echo $row['IdAlumno']?>" data-materia="<?php echo $row['idMateriaPlan']?>"   ><i class='glyphicon glyphicon-trash'></i> Eliminar</button>
                                            </td>
                                    </tr>
                                    <?php
                            }
                            ?>
                            </tbody>
                    </table>
                </div>    
		<div class="table-pagination" style="text-align: center;">
			<?php echo paginate($reload, $page, $total_pages, $adjacents);?>
		</div>
		
			<?php
			
		} else {
			?>
			<div class="alert alert-warning alert-dismissable">
              <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
              <h4>Aviso!!!</h4> No hay datos para mostrar
            </div>
			<?php
		}
	}
?>


