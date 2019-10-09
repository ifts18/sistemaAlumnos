<?php
include_once($_SERVER['DOCUMENT_ROOT'].'/helpers/sessions.php');

function isLoggedIn() {
  start_session();
  return null !== get('user_id');
}

function login($user, $password) {
  // TODO:
  // 1. Ir a la base y buscar el usuario
  // 1.1 Sino existe, devolver error y salir
  // 1.2 Si existe, ir a la base y comparar con la password del usuario
  // 2. Si esta todo bien, guardamos la info del usuario en sesion y nos vamos
  // 2.1 Sino, volvemos al login
  // QUITAR ESTE MOCK!!!
  start_session();
  set('user_id', $user);
}

function getUsername() {
  return get('user_id');
}