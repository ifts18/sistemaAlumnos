<?php 
include_once($_SERVER['DOCUMENT_ROOT'].'/helpers/routes.php');

$RouteHelper = new AdminRoutes();

if (!$AuthManager->isAdmin()) {
  $RouteHelper = new StudentRoutes();
}

?>
<nav class="col-md-2 d-none d-md-block bg-light sidebar">
  <div class="sidebar-sticky">
    <ul class="nav flex-column">
      <li class="nav-item">
        <?php 
          foreach ($RouteHelper->filter($routes) as $key => $route) { 
            if($route['show-on-menu']) { ?>
              <a class="nav-link <?php echo $requestRoute === $key ? 'active' : ''; ?>" href="/<?php echo $key; ?>"><?php echo $route['name']?></a>
            <?php }
          } ?>
      </li>
    </ul>
  </div>
</nav>