<?php
include_once('../partials/CheckIsAdmin.php');
include_once('../partials/GetMySQLValue.php');
include_once('../Connections/MySQL.php');

if (isset($_POST['action']) && $_POST['action'] === 'eliminar-mesa') {
  if (isset($_POST['id-mesa-final'])) {
    $IdMesaFinal = $_POST['id-mesa-final'];
    mysqli_query(dbconnect(), "
      DELETE
      FROM mesas_final
      WHERE IdMesaFinal = $IdMesaFinal
    ");
  }
}

if (isset($_POST['action'])) {
  if ($_POST['action'] === 'eliminar-mesa') {
    if (isset($_POST['id-mesa-final'])) {
      $IdMesaFinal = $_POST['id-mesa-final'];
      mysqli_query(dbconnect(), "
        DELETE
        FROM mesas_final
        WHERE IdMesaFinal = $IdMesaFinal
      ");
    }
  }

  if ($_POST['action'] === 'editar-mesa') {
    if (isset($_POST['id-mesa-final']) && isset($_POST['materia']) && isset($_POST['fechaMesa']) && isset($_POST['turno'])) {
      $IdMesaFinal = $_POST['id-mesa-final'];
      $fechaMesa = $_POST['fechaMesa'];
      $IdDivision = GetSQLValueString(0, 'int');
      $IdTurno = $_POST['turno'];

      if ($_POST['IdMateria'] < 11 && $_POST['division']) {
        $IdDivision = $_POST['division'];
      }

      mysqli_query(dbconnect(), "
        UPDATE
        mesas_final
        SET IdTurnosFinales = $IdTurno,
        FechaMesa = STR_TO_DATE('$fechaMesa', '%d/%m/%Y %H:%i'),
        IdDivision = $IdDivision
        WHERE IdMesaFinal = $IdMesaFinal
      ");
    }
  }
}

$turnos = mysqli_query(dbconnect(), "
  SELECT * FROM turnos_finales
");

$divisiones = mysqli_query(dbconnect(), "
  SELECT * FROM division
");

$limit = 12;
$page = isset($_GET['page']) ? (float) $_GET['page'] : 1;
$startFrom = ($page - 1) * $limit;

$resultado = mysqli_query(dbconnect(), "
  SELECT MF.IdMesaFinal AS id, 
  M.Descripcion AS Materia,
  M.IdMateria AS IdMateria,
  TF.IdTurnosFinales AS Turno,
  COALESCE(D.NombreDivision, '-') AS Division,
  COALESCE(D.IdDivision, 0) AS IdDivision,
  MF.FechaMesa FechaMesa
  FROM mesas_final MF
  INNER JOIN materias_plan MP ON MP.IdMateriaPlan = MF.IdMateriaPlan
  INNER JOIN materias M ON M.IdMateria = MP.IdMateria
  INNER JOIN turnos_finales TF ON TF.IdTurnosFinales = MF.IdTurnosFinales
  LEFT JOIN division D ON D.IdDivision = MF.IdDivision
  ORDER BY MF.FechaMesa DESC, TF.IdTurnosFinales ASC, MP.IdMateria ASC
  LIMIT $startFrom, $limit
  ;
");

$cantidadTotal = (int) mysqli_fetch_row(mysqli_query(dbconnect(), "
  SELECT COUNT(MF.IdMesaFinal)
  FROM mesas_final MF
  INNER JOIN materias_plan MP ON MP.IdMateriaPlan = MF.IdMateriaPlan
  INNER JOIN materias M ON M.IdMateria = MP.IdMateria
  INNER JOIN turnos_finales TF ON TF.IdTurnosFinales = MF.IdTurnosFinales
  LEFT JOIN division D ON D.IdDivision = MF.IdDivision
  ORDER BY MF.FechaMesa DESC, TF.IdTurnosFinales ASC, MP.IdMateria ASC
"))[0];

$totalDePaginas = ceil($cantidadTotal / $limit);

function Color($fechaMesa) {
  $today = new DateTime('');
  $mesaFecha = new DateTime($fechaMesa);
  $dayDiff = date_diff($today, $mesaFecha)->days;
  $dayInvert = date_diff($today, $mesaFecha)->invert;

  // Si es hoy va en verde
  if ($dayDiff === 0) {
    return 'success';
  }

  // Es diferencia negativa, ya paso
  if ($dayInvert === 1) {
    return 'danger';
  }

  // si todavia falta va en amarillo
  if ($dayInvert === 0) {
    return 'warning';
  }
  
  // sino va en active
  return 'active';
}
?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="css/dataTables.bootstrap.css" rel="stylesheet">
    <link href="css/bootstrap-select.min.css" rel="stylesheet">
    <title>Mesas de final</title>
    <style>
      .table td, th {
        text-align: center;
      }

      .disabled {
        pointer-events: none;
        cursor: not-allowed;
      }
    </style>
  </head>
  <body>
    <div class="modal fade" id="modalModificar">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <h4 class="modal-title" id="exampleModalLabel">Modificar Mesa de final</h4>
          </div>
          <div class="modal-body">
            <form method="POST">
              <input type="hidden" name="action" value="editar-mesa">
              <input type="hidden" id="modalModificarIdMesaFinal" name="id-mesa-final" value="">
              <input type="hidden" id="modalModificarIdMateria" name="id-materia" value="">
              <div class="form-group">
                <label for="materia" class="control-label">Materia</label>
                <input type="text" class="form-control" id="modalModificarMateria" name="materia" readonly>
              </div>
              <div class="form-group">
                <label for="fechaMesa" class="control-label">Fecha de la mesa</label>              
                <input type="text" class="form-control" id="modalModificarFechaMesa" name="fechaMesa" />
              </div>
              <div class="form-group">
                <label for="division" class="control-label">Division</label>
                <select name="division" id="modalModificarDivision" class="form-control">
                  <option value="0">NINGUNA</option>
                  <?php while($row = mysqli_fetch_assoc($divisiones)) { ?>
                    <option value="<?php echo $row['IdDivision'] ?>"><?php echo $row['NombreDivision']; ?></option>
                  <?php } ?>
                </select>
              </div>
              <div class="form-group">
                <label for="turno" class="control-label">Turno</label>
                <select name="turno" id="modalModificarTurno" class="form-control">
                  <?php while($row = mysqli_fetch_assoc($turnos)) { ?>
                    <option value="<?php echo $row['IdTurnosFinales'] ?>"><?php echo $row['IdTurnosFinales']; ?></option>
                  <?php } ?>
                </select>
              </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
            <button type="submit" class="btn btn-primary">Actualizar Mesa</button>
            </form>
          </div>
        </div>
      </div>
    </div>
    <div class="container">
      <div class="row">
        <div class="col-xs-12">
          <?php include('../partials/Header.php'); ?>
        </div>
      </div>
      <div class="row" style="margin-top: 10px; margin-bottom:10px;">
        <div class="col-xs-12 text-center">
          <a class="btn btn-warning" href="/Direcciones.php" role="button">Volver al menú principal</a>
        </div>
      </div>
      <div class="row">
        <div class="col-xs-12">
          <div class="table-responsive">
            <table class="table table-bordered">
              <thead>
                <tr>
                  <th>Materia</th>
                  <th>Turno</th>
                  <th>Fecha de mesa</th>
                  <th>División</th>
                  <th>Acciones</th>
                </tr>
              </thead>
              <tbody>
                <?php 
                  while($row = mysqli_fetch_assoc($resultado)) { ?>
                    <tr class="<?php echo Color($row['FechaMesa']); ?>">
                      <td><?php echo $row['Materia'] ?></td>
                      <td><?php echo $row['Turno'] ?></td>
                      <td><?php echo date("d/m/Y", strtotime($row['FechaMesa'])); ?></td>
                      <td><?php echo $row['Division'] ?></td>
                      <td>
                        <button class="btn btn-info botonModificarMesa"
                          data-mesa-id="<?php echo $row['id']; ?>"
                          data-mesa-materia="<?php echo $row['Materia']; ?>"
                          data-mesa-turno="<?php echo $row['Turno']; ?>"
                          data-mesa-fecha="<?php echo date("d/m/Y", strtotime($row['FechaMesa'])); ?>"
                          data-mesa-division="<?php echo $row['IdDivision']; ?>"
                          data-id-materia="<?php echo $row['IdMateria']?>"
                        >
                          Modificar
                        </button>
                        <form id="formEliminarMesa" style="display: inline; " method="POST">
                          <input type="hidden" name="id-mesa-final" value="<?php echo $row['id']; ?>">
                          <input type="hidden" name="action" value="eliminar-mesa">
                          <button class="btn btn-danger btn-eliminar" id="btnEliminarMesa" type="submit">
                            Eliminar
                          </button>
                        </form>
                      </td>
                    </tr>
                <?php } ?>
              </tbody>
            </table>
          </div>
        </div>
      </div>
      <div class="row">
        <div class="col-xs-12">
          <nav aria-label="Page navigation example">
            <ul class="pagination text-center">
              <li class="page-item <?php echo $page > 1 ? '' : 'disabled' ?>">
                <a class="page-link" href="?page=<?php echo $page - 1; ?>">Anterior</a>
              </li>
                <?php if(!empty($totalDePaginas)):for($i = 1; $i <= $totalDePaginas; $i ++): ?>
                  <li class="<?php echo $i === (int) $page ? 'active' : 'page-item'; ?>">
                    <a href="?page=<?php echo $i; ?>"><?php echo $i; ?></a>
                  </li>
                <?php endfor; endif; ?>
              <li class="page-item <?php echo $page !== $totalDePaginas ? '' : 'disabled' ?>">
                <a class="page-link" href="?page=<?php echo $page + 1; ?>">Siguiente</a>
              </li>
            </ul>
          </nav>
        </div>
      </div>
    </div>
    <script src="js/jquery.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <script src="js/bootstrap-select.min.js"></script>
    <script>
      $('.botonModificarMesa').click(e => {
        const { mesaDivision, mesaFecha, mesaId, mesaMateria, mesaTurno, idMateria } = e.target.dataset;
        $('#modalModificarDivision').prop('disabled', false);

        $('#modalModificarIdMateria').val(idMateria);
        $('#modalModificarIdMesaFinal').val(mesaId);
        $('#modalModificarMateria').val(mesaMateria);
        $('#modalModificarFechaMesa').val(mesaFecha);
        $('#modalModificarDivision').val(mesaDivision);
        $('#modalModificarTurno').val(mesaTurno);

        if (idMateria > 11) {
          $('#modalModificarDivision').val(0);
          $('#modalModificarDivision').prop('disabled', true);
        }

        $('#modalModificar').modal('show');
      });

      $('#btnEliminarMesa').click(e => {
        e.preventDefault();
        if(confirm("Seguro que desea eliminar esta mesa?")) {
          $('#formEliminarMesa').submit();
        }
      })
    </script>
  </body>
</html>