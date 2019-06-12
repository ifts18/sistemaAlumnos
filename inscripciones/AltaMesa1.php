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
$recordsetMaterias = mysqli_query(dbconnect(), "SELECT IdMateria, Descripcion FROM materias");
$rowRecordset = mysqli_fetch_assoc($recordsetMaterias);
?>

<html>
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
	  <link href="ABM_Modal/css/bootstrap.min.css" rel="stylesheet">
    <link href="ABM_Modal/css/dataTables.bootstrap.css" rel="stylesheet">
    <link href="ABM_Modal/css/bootstrap-select.min.css" rel="stylesheet">
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
    <div class="container">
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
          <form action="insertMesa1.php" method="Post" id="add-mesa" onsubmit="return confirm('¿Seguro quiere guardar los datos?');">
            <div class="container">
              <div class="row">
                <div class="col-xs-12">
                  <div class="form-inline">
                    <div class="form-group">
                      <label for="fechamesa" style="margin-right: 5px;">Fecha de la mesa</label>
                      <input type="text" class="form-control" id="fechamesa">
                    </div>
                    <div class="form-group">
                      <label for="desde" style="margin-right: 5px;">Disponible desde</label>
                      <input type="text" class="form-control" id="desde">
                    </div>
                    <div class="form-group">
                      <label for="hasta" style="margin-right: 5px;">Disponible hasta</label>
                      <input type="text" class="form-control" id="hasta">
                    </div>
                  </div>
                </div>
              </div>
              <div class="row" style="margin-top: 30px;">
                <div class="col-xs-12">
                  <div class="form-inline">
                    <label style="margin-right: 5px;">División</label>
                    <div class="form-group">
                      <label class="radio-inline">
                        <input type="radio" name="division" value="1"> A
                      </label>
                      <label class="radio-inline">
                        <input type="radio" name="division" value="2"> B
                      </label>
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
                        <input type="radio" name="turno" value="1"> 1
                      </label>
                      <label class="radio-inline">
                        <input type="radio" name="turno" value="2"> 2
                      </label>
                      <label class="radio-inline">
                        <input type="radio" name="turno" value="3"> 3
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
                      <?php while ($rowRecordset = mysqli_fetch_assoc($recordsetMaterias)) { ?>
                        <tr>
                          <td><?php echo $rowRecordset['Descripcion']; ?></td>
                          <td>
                            <label class="checkbox-inline">
                              <input type="checkbox" name="materias[]" value="<?php echo $rowRecordset['IdMateria']; ?>">
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
    <script>
      $('#button-find').click(e => { e.preventDefault; $('#add-mesa').submit(); });
    </script>
  </body>
</html>