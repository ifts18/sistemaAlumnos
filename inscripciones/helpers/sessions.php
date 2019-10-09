<?php 

function start_session() {
  if (PHP_SESSION_ACTIVE === session_status()) {
    return true;
  }

  session_start();

  return true;
}

function set($key, $value) {
  $_SESSION[$key] = $value;
}

function get($key, $default = null) {
  return has($key) ? $_SESSION[$key] : $default;
}

function has($key) {
  return array_key_exists($key, $_SESSION);
}

function all() {
  return $_SESSION;
}

function remove($key) {
  $retval = null;
  
  if (array_key_exists($key, $_SESSION)) {
    $retval = get($key);
    unset($_SESSION[$key]);
  }

  return $retval;
}