
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

		$reload = 'MateriasPorAlumnos.php';
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
                            case "dni":
                                    $WhereClause = " WHERE a.DNI LIKE '%$palabra%' ";
                                    $WhereClauseCount = " WHERE DNI LIKE '%$palabra%' ";
                                    break;
                            case "apellido":
                                    $WhereClause = " WHERE a.Apellido LIKE '%$palabra%' ";
                                    $WhereClauseCount = " WHERE Apellido LIKE '%$palabra%' ";
                                    break;
                            case "materia":
                                    $WhereClause = " WHERE m.Descripcion LIKE '%$palabra%' ";
                                    $WhereClauseCount = " WHERE Materia LIKE '%$palabra%' ";
                                     break;
                            }
                }
                 $sql = "SELECT a.idAlumno, a.DNI , a.Apellido, a.Nombre, am.EsEquivalencia , m.Descripcion as Materia,
						if(am.FechaFirma is not null, 'Si ', 'No') as Firmada ,
						case when (select 1 from mesa_final_alumno mfa where am.idAlumnoMateria = mfa.IdAlumnoMateria and mfa.Aprobado = 1 LIMIT 1 ) = 1
						or ae.idAlumnoEquivalencia is not null then 'Si' else 'No' end as Aprobada,
						(select mfa.nota from mesa_final_alumno mfa where am.idAlumnoMateria = mfa.IdAlumnoMateria and mfa.Aprobado = 1 LIMIT 1 ) as Nota,
                                                (select mfa.IdMesaFinalAlumno from mesa_final_alumno mfa where am.idAlumnoMateria = mfa.IdAlumnoMateria and mfa.Aprobado = 1 LIMIT 1 ) as IdMesaFinalAlumno
                                                FROM alumnos a
						INNER JOIN alumno_materias am on am.IdAlumno = a.IdAlumno
						INNER JOIN materias_plan mp on mp.IdMateriaPlan = am.IdMateriaPlan
						INNER JOIN materias m on m.IdMateria = mp.IdMateria
						LEFT JOIN alumno_equivalencias ae on ae.idMateriaPlan = mp.IdMateriaPlan and a.IdAlumno = ae.idAlumno
						$WhereClause
						GROUP BY a.DNI , a.Apellido, a.Nombre , m.Descripcion
						ORDER BY a.Apellido, m.idMateria
                        LIMIT $offset,$per_page ";

                    $count_query = mysqli_query(dbconnect(),"SELECT count(*) AS numrows  FROM (
										SELECT a.DNI , a.Apellido, a.Nombre , am.EsEquivalencia, m.Descripcion as Materia,
										if(am.FechaFirma is not null, 'Si ', 'No') as Firmada ,
										case when (select 1 from mesa_final_alumno mfa where am.idAlumnoMateria = mfa.IdAlumnoMateria and mfa.Aprobado = 1 LIMIT 1 ) = 1
										or ae.idAlumnoEquivalencia is not null then 'Si' else 'No' end as Aprobada,
										(select mfa.nota from mesa_final_alumno mfa where am.idAlumnoMateria = mfa.IdAlumnoMateria and mfa.Aprobado = 1 LIMIT 1 ) as Nota
										FROM alumnos a
										INNER JOIN alumno_materias am on am.IdAlumno = a.IdAlumno
										INNER JOIN materias_plan mp on mp.IdMateriaPlan = am.IdMateriaPlan
										INNER JOIN materias m on m.IdMateria = mp.IdMateria
										LEFT JOIN alumno_equivalencias ae on ae.idMateriaPlan = mp.IdMateriaPlan and a.IdAlumno = ae.idAlumno
                                        GROUP BY a.DNI , a.Apellido, a.Nombre , m.Descripcion ) x
                                        $WhereClauseCount");

                //Cuenta el número total de filas de la tabla*/
		if ($row= mysqli_fetch_array($count_query)){$numrows = $row['numrows'];}
		$total_pages = ceil($numrows/$per_page);

                $query = mysqli_query(dbconnect(), $sql );
		if ($numrows>0){
			?>
                <div class="col-sm-12">
                    <table id="myTable" align="center" aria-describedby="table_info" role="grid"
                    <?php if ($_SESSION['MM_UserGroup']=='Admin'){ ?>  style="width: 100%;"
                    <?php } else { ?> style="width: 60%;" <?php } ?>
                    class="table table-striped table-bordered dataTable tablesorter" cellspacing="0" width="100%">
                              <thead>
                                    <tr style="font-weight: bold">
                                        <?php if ($_SESSION['MM_UserGroup']=='Admin'){ ?>
                                            <td width="100" align="center" >DNI</td>
                                            <td width="100" align="center">Apellido</td>
                                            <td width="100" align="center">Nombre</td>
                                        <?php } ?>
                                        <td width="100" align="center">Materia</td>
                                        <td width="100" align="center">Firmada</td>
                                        <td width="100" align="center">Aprobada</td>
                                        <td width="80" align="center">Nota</td>
                                        <td width="100" align="center">Equivalencia</td>
                                        <?php if ($_SESSION['MM_UserGroup']=='Admin'){ ?>
                                        <th width="90" align="center">Acciones</th>
                                        <?php } ?>
                                    </tr>
                            </thead>
                            <tbody>
                            <?php
                            while($row = mysqli_fetch_array($query)){
                                    ?>
                                    <tr >
                                            <?php if ($_SESSION['MM_UserGroup']=='Admin'){ ?>
                                                <td align="center" <h4> <?php echo $row['DNI']; ?></h4></td>
                                                <td align="left" <h4> <?php echo $row['Apellido']; ?></h4></td>
                                                <td align="left" <h4> <?php echo $row['Nombre']; ?></h4></td>
                                            <?php } ?>

                                            <td align="left" <h4> <?php echo $row['Materia']; ?></h4></td>
                                            <?php if($row['Firmada'] == "No") { ?>
                                                <td align="center" style="color:#DF0101"<h4><b> <?php echo $row['Firmada']; ?><b></h4></td>
                                            <?php } else { ?>
                                                <td align="center" style="color:#04B404"<h4><b> <?php echo $row['Firmada']; ?><b> </h4></td>
                                            <?php } ?>

                                            <?php if($row['Aprobada'] == "No") { ?>
                                                <td align="center" style="color:#DF0101"<h4><b> <?php echo $row['Aprobada']; ?><b></h4></td>
                                            <?php } else { ?>
                                                <td align="center" style="color:#04B404"<h4><b> <?php echo $row['Aprobada']; ?><b> </h4></td>
                                            <?php } ?>
                                            <td align="center" style="font-weight:bold" <h4> <?php echo $row['Nota']; ?></h4></td>
                                            <?php if($row['EsEquivalencia'] == 0) { ?>
                                                <td align="center" style="color:#DF0101"<h4><b>No<b></h4></td>
                                            <?php } else { ?>
                                                <td align="center" style="color:#04B404"<h4><b>Si<b> </h4></td>
                                            <?php } ?>

                                            <?php if ($_SESSION['MM_UserGroup']=='Admin'){ ?>
                                            <td >
                                                    <button type="button" class="btn btn-info" data-toggle="modal" data-target="#dataUpdate" data-idAlumno="<?php echo $row['idAlumno']?>" data-dni="<?php echo $row['DNI']?>" data-apellido="<?php echo $row['Apellido']?>" data-nombre="<?php echo $row['Nombre']?>" data-materia="<?php echo $row['Materia']?>" data-nota="<?php echo $row['Nota']?>" data-idmesafinalalumno="<?php echo $row['IdMesaFinalAlumno']?>" ><i class='glyphicon glyphicon-edit'></i> Modificar</button>
                                            </td>
                                            <?php } ?>
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
