<?php

include_once($_SERVER['DOCUMENT_ROOT'].'/helpers/db.php');

class AuthManager {
  public function __construct($DbManager) {
    $this->sessionKey = 'user_id';
    $this->dbManager = $DbManager;
  }

  public function isLoggedIn() {
    start_session();
    return null !== get($this->sessionKey);
  }
  
  public function doLogin($dni, $password) {
    start_session();
    $escapedDni = $this->dbManager->quote($dni);
    $escapedPassword = $this->dbManager->quote($password);

    $alumnos = $this->dbManager->select('SELECT * FROM alumnos WHERE DNI = '.$escapedDni.' AND Password = '.$escapedPassword);
    $admin = $this->dbManager->select('SELECT * FROM admin WHERE DNI = '.$escapedDni.' AND Password = '.$escapedPassword);

    if (empty($alumnos) && empty($admin)) {
      return false;
    }

    // Negrada, porque la base tiene DOS tablas (una admin y otra de alumnos) -> No queremos tocar la base en esta version
    if (!empty($alumnos)) {
      $final = $alumnos[0];
    }

    if (!empty($admin)) {
      $final = $admin[0];
    }

    set($this->sessionKey, $final);

    return true;
  }
  
  public function logout() {
    start_session();
    if (has($this->sessionKey)) {
      remove($this->sessionKey);
    }
  }
  
  public function getUserSession() {
    return get($this->sessionKey);
  }
}

$AuthManager = new AuthManager($DbManager);