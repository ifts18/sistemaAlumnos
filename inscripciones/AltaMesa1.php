<?php require_once('Connections/MySQL.php'); ?>

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
$recordsetMaterias = mysqli_query(dbconnect(), "
  SELECT M.IdMateria, M.Descripcion, D.IdDivision, D.NombreDivision
  FROM materias_plan MP
  INNER JOIN materias M ON M.IdMateria = MP.IdMateria
  INNER JOIN materias_division MD ON MD.IdMateriaPlan = M.IdMateria
  INNER JOIN division D ON D.IdDivision = MD.IdDivision
  UNION
  SELECT M.IdMateria, M.Descripcion, '0', 'Ninguna'
  FROM materias M
  WHERE M.IdMateria > 11;
");
// El 11 es porque es la ultima materia de 1r año y en el 2019 solo 1r año tenia A y B, suerte para el 2020
?>

<html>
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
	  <link href="ABM_Modal/css/bootstrap.min.css" rel="stylesheet">
    <link href="ABM_Modal/css/dataTables.bootstrap.css" rel="stylesheet">
    <link href="ABM_Modal/css/bootstrap-select.min.css" rel="stylesheet">
    <link href="ABM_Modal/css/datepicker.min.css" rel="stylesheet">
    <title>Nueva mesa de final</title>
    <style>
      .footer {
        width: 100%;
        /* Set the fixed height of the footer here */
        height: 60px;
        background-color: #f5f5f5;

        border-top: 1px solid #eee;
        text-align: center;
      }
    </style>
  </head>
  <body>
    <div class="container" style="margin-bottom: 60px;">
      <div class="row">
        <div class="col-xs-12">
          <table width="1000" border="1" align="center">
            <tbody>
              <tr>
                <td width="604" align="center" ><h1> IFTS18 - Nueva mesa de final </h1></td>
                <td width="480" align="center"><h2>Admin:<?php print $_SESSION['ApeNom'] ?>&nbsp;</h2></td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
      <div class="row" style="margin-top: 30px;">
        <div class="col-xs-12">
          <form action="insertMesa1.php" method="Post" id="add-mesa">
            <div class="container">
              <div class="row">
                <div class="col-xs-12">
                  <div class="form-inline">
                    <div class="form-group">
                      <label for="fechamesa" style="margin-right: 5px;">Fecha de la mesa</label>
                      <input type="text" class="form-control pone-calendar" name="fechamesa" id="fechamesa">
                    </div>
                    <div class="form-group">
                      <label for="desde" style="margin-right: 5px;">Disponible desde</label>
                      <input type="text" class="form-control pone-calendar" name="desde" id="desde">
                    </div>
                    <div class="form-group">
                      <label for="hasta" style="margin-right: 5px;">Disponible hasta</label>
                      <input type="text" class="form-control pone-calendar" name="hasta" id="hasta">
                    </div>
                  </div>
                </div>
              </div>
              <div class="row" style="margin-top: 30px;">
                <div class="col-xs-12">
                  <div class="form-inline">
                    <label style="margin-right: 5px;">Turno</label>
                    <div class="form-group">
                      <label class="radio-inline">
                        <input type="radio" name="turno" value="1"> 1 - Principio de año
                      </label>
                      <label class="radio-inline">
                        <input type="radio" name="turno" value="2"> 2 - Mitad de año
                      </label>
                      <label class="radio-inline">
                        <input type="radio" name="turno" value="3"> 3 - Fin de año
                      </label>
                    </div>
                  </div>
                </div>
              </div>
              <div class="row" style="margin-top: 30px;">
                <div class="col-xs-6">
                  <div class="form-group">
                    <label for="materia">Materias</label>
                    <table class="table">
                    <?php 
                        while ($rowRecordset = mysqli_fetch_assoc($recordsetMaterias)) {
                          $descripcion = $rowRecordset['Descripcion'];
                          if ($rowRecordset['IdDivision'] !== '0') {
                            $descripcion = $descripcion . ' - División: '.$rowRecordset['NombreDivision'];
                          }
                        ?>
                        <tr>
                          <td><?php echo $descripcion; ?></td>
                          <td>
                            <label class="checkbox-inline">
                              <?php 
                                if ($rowRecordset['IdDivision'] !== '0') { ?>
                                  <input type="checkbox" data-materia="<?php echo $rowRecordset['IdMateria']; ?>" data-division="<?php echo $rowRecordset['IdDivision']; ?>" class="with-division" name="materias[]" value="<?php echo $rowRecordset['IdMateria'].'-'.$rowRecordset['IdDivision']; ?>">
                                <?php } else { ?>
                                  <input type="checkbox" name="materias[]" value="<?php echo $rowRecordset['IdMateria'].'-0'; ?>">
                              <?php } ?>
                            </label>
                          </td>
                        </tr>
                      <?php } ?>
                    </table>
                  </div>
                </div>
              </div>
            </div>
          </form>
        </div>
      </div>
    </div>
    <div class="navbar-fixed-bottom footer">
      <div class="container">
        <div class="row" style="margin-top: 3px;">
          <div class="col-xs-12">
            <button type="submit" id="button-find" class="btn btn-primary">Crear</button>
            <button onclick="location.href='/Direcciones.php'" class="btn btn-danger">Volver</button>
          </div>
        </div>
      </div>
    </div>
    <script src="ABM_Modal/js/jquery.min.js"></script>
    <script src="ABM_Modal/js/datepicker.min.js"></script>
    <script src="ABM_Modal/js/datepicker.es.min.js"></script>
    <script>
      function oneYearAfter() {
        const da = new Date();
        return new Date(da.getFullYear() + 1, da.getMonth(), da.getDate());
      }

      $('.pone-calendar').datepicker({
        format: "dd/mm/yyyy",
        language: "es",
        daysOfWeekDisabled: "0,6",
        autoclose: true,
        startDate: new Date(),
        endDate: oneYearAfter()
      });

      $('#button-find').click(e => { e.preventDefault; $('#add-mesa').submit(); });

      $('#add-mesa').submit(e => {
        e.preventDefault();
        const form = e.target;
        const serialized = $(form).serializeArray();

        if (serialized[0].name === 'fechamesa' && serialized[0].value === '') {
          return alert('Debe ingresar una fecha valida para la mesa');
        }

        if (serialized[1].name === 'desde' && serialized[1].value === '') {
          return alert('Debe ingresar una fecha valida para el inicio del periodo de inscripcion');
        }

        if (serialized[2].name === 'hasta' && serialized[2].value === '') {
          return alert('Debe ingresar una fecha valida para el fin del periodo de inscripcion');
        }

        if (serialized.findIndex(e => e.name === 'turno') === -1) {
          return alert('Debe seleccionar un turno valido');
        }

        if (serialized.findIndex(e => e.name === 'materias[]') === -1) {
          return alert('Debe seleccionar al menos una materia');
        }

        form.submit();
      });
    </script>
  </body>
</html>