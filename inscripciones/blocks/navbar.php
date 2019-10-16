<nav class="navbar navbar-dark fixed-top bg-dark flex-md-nowrap p-0 shadow">
  <a class="navbar-brand col-sm-3 col-md-2 mr-0" href="#">IFTS 18</a>
  <ul class="navbar-nav px-3">
    <li class="nav-item text-nowrap">
      <span class="text-white"><?php echo $AuthManager->getUserSession()['Nombre'].' '.$AuthManager->getUserSession()['Apellido']?></span>
    </li>
  </ul>
  <ul class="navbar-nav px-3">
    <li class="nav-item text-nowrap">
      <a class="nav-link" href="/logout">Salir</a>
    </li>
  </ul>
</nav>