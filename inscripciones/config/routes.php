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
  
  'listar-alumnos' => [
    'show-on-menu' => true,
    'name' => 'Listar alumnos',
    'page' => $_SERVER['DOCUMENT_ROOT'].'/pages/listar-alumnos.php'
  ],
  'listado-alumnos-por-materias' => [
    'show-on-menu' => true,
    'name' => 'Listado Alumnos por Materias',
    'page' => $_SERVER['DOCUMENT_ROOT'].'/pages/listado-alumnos-por-materias.php'
  ],
    'modificar-alumnos' => [
    'show-on-menu' => true,
    'name' => 'Modificar alumnos',
    'page' => $_SERVER['DOCUMENT_ROOT'].'/pages/modificar-alumnos.php'
  ],
  'modificar-alumnos' => [
    'show-on-menu' => true,
    'name' => 'Modificar alumnos',
    'page' => $_SERVER['DOCUMENT_ROOT'].'/pages/modificar-alumnos.php'
  ],
  
  'regularidad-materia' => [
    'show-on-menu' => true,
    'name' => 'Regularidad de Materias',
    'page' => $_SERVER['DOCUMENT_ROOT'].'/pages/regularidad-materia.php'
  ],
  'equivalencias' => [
    'show-on-menu' => true,
    'name' => 'Equivalencias',
    'page' => $_SERVER['DOCUMENT_ROOT'].'/pages/equivalencias.php'
  ],
  'listado-materias-por-alumnos' => [
    'show-on-menu' => true,
    'name' => 'Listado Materias por Alumnos',
    'page' => $_SERVER['DOCUMENT_ROOT'].'/pages/listado-materias-por-alumnos.php'
  ],
  'alta-mesas-finales' => [
    'show-on-menu' => true,
    'name' => 'Alta Mesas de Finales',
    'page' => $_SERVER['DOCUMENT_ROOT'].'/pages/alta-mesas-finales.php'
  ],
  'listado-mesas-finales' => [
    'show-on-menu' => true,
    'name' => 'Listado Mesas de Finales',
    'page' => $_SERVER['DOCUMENT_ROOT'].'/pages/listado-mesas-finales.php'
  ],
  'actas-examenes' => [
    'show-on-menu' => true,
    'name' => 'Actas de Examenes',
    'page' => $_SERVER['DOCUMENT_ROOT'].'/pages/actas-examenes.php'
  ],
  'notas-finales' => [
    'show-on-menu' => true,
    'name' => 'Notas de Finales',
    'page' => $_SERVER['DOCUMENT_ROOT'].'/pages/notas-finales.php'
  ],

];