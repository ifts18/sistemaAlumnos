<?php require_once('Connections/MySQL.php'); ?>
<?php
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

<?php
// *** Validate request to login to this site.
if (!isset($_SESSION)) {
  session_start();
}

$loginFormAction = $_SERVER['PHP_SELF'];
if (isset($_GET['accesscheck'])) {
  $_SESSION['PrevUrl'] = $_GET['accesscheck'];
}

if (isset($_POST['dni'])) {

  $loginUsername=$_POST['dni'];
  $password=$_POST['password'];
  $MM_fldUserAuthorization = "";

  //$MM_redirectLoginSuccess = "Carga1.php";
  $MM_redirectLoginSuccess = "Direcciones.php";
  $MM_redirectLoginFailed = "Failure.php";
  $MM_redirecttoReferrer = false;

  $LoginRS__query=sprintf("SELECT idAlumno, DNI, concat_ws(',', Apellido, Nombre) ApeNom FROM alumnos WHERE DNI=%s AND Password=%s",
    GetSQLValueString($loginUsername, "int"), GetSQLValueString($password, "int"));

  $LoginRS = mysqli_query(dbconnect(),$LoginRS__query) or die(mysql_error());
  $row_Recordset1 = mysqli_fetch_assoc($LoginRS);
  $loginFoundUser = mysqli_num_rows($LoginRS);

  if ($loginFoundUser) {
     $loginStrGroup = "";

    if (PHP_VERSION >= 5.1) {session_regenerate_id(true);} else {session_regenerate_id();}

    //declare two session variables and assign them
    $_SESSION['MM_Username'] = $row_Recordset1['idAlumno'];
    $_SESSION['MM_UserGroup'] = $loginStrGroup;
    $_SESSION['ApeNom'] = $row_Recordset1['ApeNom'];

    if (isset($_SESSION['PrevUrl']) && false) {
      $MM_redirectLoginSuccess = $_SESSION['PrevUrl'];
    }
    header("Location: " . $MM_redirectLoginSuccess );
  }
  else {

    $LoginRS__query=sprintf("SELECT idAdmin, DNI, concat_ws(',', Apellido, Nombre) ApeNom FROM terciario.admin WHERE DNI=%s AND Password=%s",
      GetSQLValueString($loginUsername, "int"), GetSQLValueString($password, "int"));

    $LoginRS = mysqli_query(dbconnect(),$LoginRS__query) or die(mysql_error());
    $row_Recordset1 = mysqli_fetch_assoc($LoginRS);
    $loginFoundUser = mysqli_num_rows($LoginRS);

    if ($loginFoundUser) {
       $loginStrGroup = "Admin";

      if (PHP_VERSION >= 5.1) {session_regenerate_id(true);} else {session_regenerate_id();}

      //declare two session variables and assign them
      $_SESSION['MM_Username'] = $row_Recordset1['idAdmin'];
      $_SESSION['MM_UserGroup'] = $loginStrGroup;
      $_SESSION['ApeNom'] = $row_Recordset1['ApeNom'];

      if (isset($_SESSION['PrevUrl']) && false) {
        $MM_redirectLoginSuccess = $_SESSION['PrevUrl'];
      }
      header("Location: " . $MM_redirectLoginSuccess );
    } else {
        header("Location: ". $MM_redirectLoginFailed );
    }
  }
}
?>
<!doctype html>
<html lang="en">
<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="icon" href="favicon.png">
    <link rel="stylesheet" href="styles/bootstrap-4.3.1.min.css">
    <title>IFTS18</title>
</head>
<body>
  <nav class="navbar navbar-expand-lg navbar-light navbar-laravel">
    <div class="container">
        <a class="navbar-brand" href="#">Instituto de Formación Técnica Superior N°18</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
    </div>
  </nav>
  <form name="form1"  method="POST" action="<?php echo $loginFormAction; ?>">
  <main class="login-form">
      <div class="cotainer">
          <div class="row justify-content-center">
              <div class="col-md-8">
                  <div class="card">
                      <div class="card-header">Iniciar Sesion</div>
                      <div class="card-body">
                          <form class="container">
                              <div class="form-group row">
                                  <label for="email_address" class="col-md-4 col-form-label text-md-right">DNI</label>
                                  <div class="col-md-6">
                                      <input type="text" id="email_address" class="form-control" id="dni" name="dni" required="required">
                                  </div>
                              </div>

                              <div class="form-group row">
                                  <label for="password" class="col-md-4 col-form-label text-md-right">Contraseña</label>
                                  <div class="col-md-6">
                                      <input type="password" id="password" class="form-control" name="password" required="required">
                                  </div>
                              </div>

                              
                              <div class="col-md-6 offset-md-4">
                                  <button type="submit" name="login" id="login" value="Login" class="btn btn-primary">
                                      Login
                                  </button>
                              </div>
                      </div>
                      </form>
                  </div>
              </div>
          </div>
      </div>
      </div>
  </main>
</form>
</body>
<script src="scripts/jquery-3.3.1.slim.min.js"></script>
<script src="scripts/popper-1.14.7.min.js"></script>
<script src="scripts/bootstrap-4.3.1.min.js"></script>
</html>