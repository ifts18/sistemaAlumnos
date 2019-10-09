<?php 
$requestRoute = str_replace('/', '', $_SERVER['REQUEST_URI']);

$routes = [
  'login' => [
    'show-on-menu' => false,
    'name' => 'Login',
    'page' => $_SERVER['DOCUMENT_ROOT'].'/pages/login.php'
  ],
  'alta-alumnos' => [
    'show-on-menu' => true,
    'name' => 'Alta de alumnos',
    'page' => $_SERVER['DOCUMENT_ROOT'].'/pages/alta-alumnos.php'
  ],
  'modificar-alumnos' => [
    'show-on-menu' => true,
    'name' => 'Modificar alumnos',
    'page' => $_SERVER['DOCUMENT_ROOT'].'/pages/modificar-alumnos.php'
  ],
];