<?php
  include_once($_SERVER['DOCUMENT_ROOT'].'/helpers/db.php');
  include_once($_SERVER['DOCUMENT_ROOT'].'/config/routes.php');
  include_once($_SERVER['DOCUMENT_ROOT'].'/helpers/auth.php');
  include_once($_SERVER['DOCUMENT_ROOT'].'/helpers/sessions.php');
  include_once($_SERVER['DOCUMENT_ROOT'].'/helpers/page.php');
  include_once($_SERVER['DOCUMENT_ROOT'].'/helpers/form.php');

  
  if ($requestRoute !== 'login' && !$AuthManager->isLoggedIn()) {
    header('Location: /login');
  }

  if (($routes[$requestRoute]['admin'] && !$routes[$requestRoute]['student']) && !$AuthManager->isAdmin()) {
    header('Location: /not-allowed');
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
    <?php exclude_blocks($requestRoute) ? '' : include_once($_SERVER['DOCUMENT_ROOT'].'/blocks/navbar.php'); ?>
    <div class="container-fluid">
      <div class="row">
        <?php exclude_blocks($requestRoute) ? '' : include_once($_SERVER['DOCUMENT_ROOT'].'/blocks/sidebar.php'); ?>
        <main role="main" class="<?php echo exclude_blocks($requestRoute) ? 'col-md-12 ml-sm-auto col-lg-12 px-4' : 'col-md-9 ml-sm-auto col-lg-10 px-4'; ?>">
          <div class="d-flex justify-content-between flex-wrap flex-md-nowrap flex-column align-items-center pt-3 pb-2 mb-3 border-bottom">
            <h1 class="h2"><?php echo $routes[$requestRoute]['name']; ?></h1>
          </div>
          <div class="d-flex justify-content-between flex-wrap flex-md-nowrap flex-column align-items-center pt-3 pb-2 mb-3 border-bottom">
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
    <script src="/scripts/<?php echo $requestRoute; ?>.js"></script>
    <script>
      console.log('%c Creado por los alumnos del IFTS! ', 'background: #ff0000; color: #1500ff; font-size: 20px;');
      console.log('%c Última modificación: 2019 - Por Cris Montes de Oca, Sebas Poliak, Mati Rojas y Agus Tashdjian! ', 'background: #ff0000; color: #1500ff; font-size: 15px;');
    </script>
  </body>
</html>