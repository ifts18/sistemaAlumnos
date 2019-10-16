<?php

class DbManager {
  protected static $connection;

  /**
   * Nos devuelve la conexion a la base de datos
   */
  public function connect() {
    if (!isset(self::$connection)) {
      $config = parse_ini_file($_SERVER['DOCUMENT_ROOT'].'/config/config.ini');
      $host = $config['host'];
      
      /**
       * Hack para cuando usamos docker-compose (setea una variable de entorno con el name del servicio de mysql)
       */
      if (array_key_exists("DB_HOST", $_SERVER)) {
        $host = $_SERVER["DB_HOST"];
      }

      self::$connection = new mysqli($host, $config['user'], $config['password'], $config['dbname']);
    }
    
    if (!self::$connection) {
      return false;
    }

    return self::$connection;
  }

  /**
   * Depende la sentencia devuelve:
   * - SELECT => un objeto mysqli_result
   * - INSERT|DELETE|UPDATE => true | false
   */
  public function query($query) {
    $connection = $this->connect();
    $result = $connection->query($query);

    return $result;
  }

  /**
   * Retorna un array de resultados
   */
  public function select($query) {
    $rows = array();
    $result = $this->query($query);

    if (!$result) {
      return $rows;
    }

    while($row = $result->fetch_assoc()) {
      $rows[] = $row;
    }

    return $rows;
  }

  /**
   * Retorna el ultimo error en forma de string
   */
  public function error() {
    $connection = $this->connect();

    return $connection->error;
  }

  public function info() {
    $connection = $this->connect();

    return $connection->info;
  }

  /**
   * Escapa los caracteres especiales de una cadena para usarla en sentencias SQL y evitar una inyeccion SQL
   */
  public function quote($value) {
    $connection = $this->connect();

    return "'".$connection->real_escape_string($value)."'";
  }
}

$DbManager = new DbManager();