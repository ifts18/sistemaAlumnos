
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
		$per_page = 12; //la cantidad de registros que desea mostrar
		$adjacents  = 4; //brecha entre páginas después de varios adyacentes
		$offset = ($page - 1) * $per_page;
		
		$reload = 'MesasDeFinales.php';
		//consulta principal para recuperar los datos
                $method = (isset($_REQUEST['method'])&& $_REQUEST['method'] !=NULL)?$_REQUEST['method']:'';
                $palabra = (isset($_REQUEST['palabra'])&& $_REQUEST['palabra'] !=NULL)?$_REQUEST['palabra']:'';
                $filtro = (isset($_REQUEST['filtro'])&& $_REQUEST['filtro'] !=NULL)?$_REQUEST['filtro']:'';
                
                $WhereClause = " ";
                $WhereClauseCount = " ";
                
                if($method == 'buscar'){
                    switch($filtro){
                            case "todos":
                                    $WhereClause = " ";
                                    $WhereClauseCount = " ";
                                    break;
                            case "materia":
                                    $WhereClause = " Where m.Descripcion LIKE '%$palabra%' ";
                                    $WhereClauseCount = " Where Materia LIKE '%$palabra%' ";   
                                     break;
                            }
                }
                $sql = "SELECT mf.IdMesaFinal as id, m.Descripcion as Materia, tf.IdTurnosFinales as Turno , 
                        DATE_FORMAT(mf.FechaMesa,'%d/%m/%Y') as FechaMesa 
                        FROM mesas_final mf
                        INNER JOIN materias_plan mp on mp.IdMateriaPlan = mf.IdMateriaPlan
                        INNER JOIN materias m on m.IdMateria = mp.IdMateria
                        INNER JOIN turnos_finales tf on tf.IdTurnosFinales = mf.IdTurnosFinales
                        /*WHERE mf.DisponibleDesdeFecha <= NOW() and mf.DisponibleHastaFecha >= NOW()*/
                        $WhereClause
                        ORDER BY mf.FechaMesa desc
                        LIMIT $offset,$per_page ";
                   
                $count_query = mysqli_query(dbconnect(),"
                        SELECT count(*) AS numrows  FROM (
                        SELECT mf.IdMesaFinal as id, m.Descripcion as Materia, tf.IdTurnosFinales as Turno , 
                        DATE_FORMAT(mf.FechaMesa,'%d/%m/%Y') as FechaMesa 
                        FROM mesas_final mf
                        INNER JOIN materias_plan mp on mp.IdMateriaPlan = mf.IdMateriaPlan
                        INNER JOIN materias m on m.IdMateria = mp.IdMateria
                        INNER JOIN turnos_finales tf on tf.IdTurnosFinales = mf.IdTurnosFinales
                        /*WHERE mf.DisponibleDesdeFecha <= NOW() and mf.DisponibleHastaFecha >= NOW()*/ ) x
                        $WhereClauseCount");
                
                //Cuenta el número total de filas de la tabla*/
		if ($row= mysqli_fetch_array($count_query)){$numrows = $row['numrows'];}
		$total_pages = ceil($numrows/$per_page);
                
                $query = mysqli_query(dbconnect(), $sql );
		if ($numrows>0){
			?>
                <div class="col-sm-12">            
                    <table id="myTable" align="center" aria-describedby="table_info" role="grid" style="width: 70%;"  
                    class="table table-striped table-bordered dataTable tablesorter" cellspacing="0" width="100%">
                              <thead>
                                    <tr style="font-weight: bold">
                                        <td style="width: 325px" align="center">Materia</td>
                                        <td align="center">Turno</td>
                                        <td align="center">Fecha de Mesa</td>
                                        <th style="text-align: center">Acciones</th>
                                    </tr>
                            </thead>
                            <tbody>
                            <?php
                            while($row = mysqli_fetch_array($query)){
                                    ?>
                                    <tr >
                                        <td align="left" <h4> <?php echo $row['Materia']; ?></h4></td>
                                        <td align="center" style="font-weight:bold" <h4> <?php echo $row['Turno']; ?></h4></td>                    
                                        <td align="center" <h4> <?php echo $row['FechaMesa']; ?></h4></td>
                                        <td align="center">
                                            <button type="button" class="btn btn-info" data-toggle="modal" 
                                                    data-target="#dataUpdate" data-id="<?php echo $row['id']?>" 
                                                    data-materia="<?php echo $row['Materia']?>" 
                                                    data-turno="<?php echo $row['Turno']?>" 
                                                    data-fecha="<?php echo $row['FechaMesa']?>" >
                                                    <i class='glyphicon glyphicon-edit'></i> Modificar
                                            </button>
                                            
                                            <button type="button" class="btn btn-danger" data-toggle="modal" 
                                                    data-target="#dataDelete" data-id="<?php echo $row['id']?>"  >
                                                    <i class='glyphicon glyphicon-trash'></i> Eliminar
                                            </button>
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


