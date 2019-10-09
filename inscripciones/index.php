<?php
  include_once($_SERVER['DOCUMENT_ROOT'].'/config/routes.php');
  include_once($_SERVER['DOCUMENT_ROOT'].'/helpers/auth.php');

  
  if ($requestRoute !== 'login' && !isLoggedIn()) {
    header('Location: /login');
  }
?>

<!doctype html>
<html lang="en">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="shortcut icon" href="/images/favicon.png" type="image/png" />

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="/styles/bootstrap.min.css">
    <link rel="stylesheet" href="/styles/main.css">
    <title>Sistema de alumnos</title>
  </head>
  <body>
    <?php $requestRoute !== 'login' ? include_once($_SERVER['DOCUMENT_ROOT'].'/blocks/navbar.php') : ''; ?>
    <div class="container-fluid">
      <div class="row">
        <?php $requestRoute !== 'login' ? include_once($_SERVER['DOCUMENT_ROOT'].'/blocks/sidebar.php') : ''; ?>
        <main role="main" class="col-md-9 ml-sm-auto col-lg-10 px-4">
          <div class="d-flex justify-content-between flex-wrap flex-md-nowrap flex-column align-items-center pt-3 pb-2 mb-3 border-bottom">
            <h1 class="h2"><?php echo $routes[$requestRoute]['name']; ?></h1>
            <?php
              include_once($routes[$requestRoute]['page']);
            ?>
          </div>
        </main>
      </div>
    </div>
    <!-- Optional JavaScript -->
    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
    <script src="/scripts/jquery.slim.min.js"></script>
    <script src="/scripts/popper-1.14.7.min.js"></script>
    <script src="/scripts/bootstrap.min.js"></script>
  </body>
</html>