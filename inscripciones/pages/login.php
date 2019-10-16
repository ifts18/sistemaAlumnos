<?php
if($AuthManager->isLoggedIn()) {
  header('Location: /');
}

if (isset($_POST) && !empty($_POST)) {
  if ($AuthManager->doLogin($_POST['dni'], $_POST['password'])) {
    return header('Location: /');
  }
}
?>

<form action="/login" method="POST">
  <div class="form-group">
    <label for="dni">DNI</label>
    <input name="dni" type="text" class="form-control" id="dni" placeholder="DNI">
  </div>
  <div class="form-group">
    <label for="password">Password</label>
    <input name="password" type="password" class="form-control" id="password" placeholder="Password">
  </div>
  <button type="submit" class="btn btn-primary">Ingresar</button>
</form>