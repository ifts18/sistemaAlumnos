
<?php require_once('../../Connections/MySQL.php'); ?>
<?php
//initialize the session
if (!isset($_SESSION)) {
  session_start();
}

// verify that the user is admin 
if ($_SESSION['MM_UserGroup'] != 'Admin') {
    die("No cuenta con permisos suficientes");
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
                $origen = (isset($_REQUEST['origen'])&& $_REQUEST['origen'] !=NULL)?$_REQUEST['origen']:'';
                            
                  
                $WhereClause = "  ";
                $WhereClauseCount = " ";
                $OrderBy = " ";
                
                if($method == 'buscar'){
                    switch($filtro){
                            case "todos":
                                    $WhereClause = " ";
                                    $WhereClauseCount = " ";
                                    break;
                            case "dni":
                                    $WhereClause = " WHERE DNI LIKE '%$palabra%' ";   
                                    $WhereClauseCount = " WHERE DNI LIKE '%$palabra%' ";   
                                    $OrderBy = " ORDER BY DNI desc";
                                    break;
                            case "apellido":
                                    $WhereClause = " WHERE Apellido LIKE '%$palabra%' ";   
                                    $WhereClauseCount = " WHERE Apellido LIKE '%$palabra%' ";  
                                    $OrderBy = " ORDER BY Apellido desc";
                                    break;
                            case "fechaing":
                                    $WhereClause = " WHERE FechaCreacion LIKE '%$palabra%' ";   
                                    $WhereClauseCount = " WHERE FechaCreacion LIKE '%$palabra%' ";   
                                    $OrderBy = " ORDER BY FechaCreacion desc";
                                    break;
                            }
                }

                if ($origen != 3) {
                        $sql = "SELECT idAlumno, DNI, Apellido, Nombre, FechaCreacion, Email, Password  from alumnos
                                $WhereClause
                                $OrderBy
                                LIMIT $offset,$per_page ";
                } else {
                        $sql = "SELECT al.DNI, al.Apellido, al.Nombre, m.Descripcion, am.IdDivision, m.IdMateria
                                FROM alumno_materias am
                                INNER JOIN alumnos al ON am.IdAlumno = al.IdAlumno
                                INNER JOIN materias_plan mp on mp.IdMateriaPlan = am.IdMateriaPlan
                                INNER JOIN materias m on m.IdMateria = mp.IdMateria
                                WHERE al.DNI = '$palabra'
                                ORDER BY m.IdMateria";
                        $WhereClauseCount = " WHERE DNI = '$palabra' ";
                }

                   
                $count_query = mysqli_query(dbconnect(),"SELECT count(*) AS numrows  FROM (
                                    SELECT * from alumnos ) x
                                    $WhereClauseCount");
                    
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
                                    <?php
                                        if ($origen == 3){ ?>
                                        <th width="160" align="center">Materia</th>
                                        <th width="10" align="center">Division</th>
                                        <th width="80" align="center"></th>
                                    <?php } ?>
                                    <?php
                                        if ($origen != 3){ ?>    
                                        <td width="150" align="center">Fecha de ingreso</td>
                                        <td width="80" align="center">E-mail</td>
                                        <td width="100" align="center">Password</td>
                                    <?php } ?>
                                    <?php
                                        if ($origen != 1){ ?>
                                        <th width="90" align="center">Acciones</th>
                                    <?php } ?>
                                    </tr>
                            </thead>
                            <tbody>
                                <?php
                                while($row = mysqli_fetch_array($query)){
                                ?>
                                <tr >
                                <td align="center" <h4> <?php echo $row['DNI']; ?></h4></td>
                                <td align="left" <h4> <?php echo $row['Apellido'];?></h4></td>
                                <td align="left" <h4> <?php echo $row['Nombre']; ?></h4></td>
                                <?php
                                if ($origen == 3){ ?>
                                <td align="left" <h4> <?php echo $row['Descripcion']; ?></h4></td>
                                <td align="left" <h4>
                                                        <?php switch ($row['IdDivision']) {
                                                                case 1:
                                                                        echo 'A';
                                                                break;
                                                                case 2:
                                                                        echo 'B';
                                                                break;
                                                                default:
                                                                echo 'Ninguna';
                                                        break;
                                                }; ?></h4></td>
                                <td align="left" <h4> 
                                        <select  aria-controls="table" name="division" id="division">
                                                <option value="" selected disabled>Seleccionar</option>
                                                <option value="1">A</option>
                                                <option value="2">B</option>
                                                <option value="0">Ninguna</option>
                                        </select></h4></td>
                                <?php } ?>
                                <?php
                                if ($origen != 3){ ?>
                                <td align="left" <h4> <?php echo $row['FechaCreacion']; ?></h4></td>
                                <td align="left" <h4> <?php echo $row['Email']; ?></h4></td>
                                <td align="center" <h4> <?php echo $row['Password']; ?></h4></td>
                                <?php } ?>
                                <?php
                                if ($origen != 1){ ?>
                                <td>
                                <button type="button" class="btn btn-info" data-toggle="modal" data-target="#dataUpdate" data-origen="<?php echo $origen?>" data-idAlumno="<?php echo $row['idAlumno']?>" data-dni="<?php echo $row['DNI']?>" data-apellido="<?php echo $row['Apellido']?>" data-nombre="<?php echo $row['Nombre']?>" data-email="<?php echo $row['Email']?>" data-dni="<?php echo $row['DNI']?>" data-password="<?php echo $row['Password']?>" data-materia="<?php echo $row['Descripcion']?>" data-division="<?php echo $row['IdDivision']?>"><i class='glyphicon glyphicon-edit'></i> Modificar</button>    
                                </td>   
                                <?php } ?>
                                </tr>
                                <?php } ?>
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


