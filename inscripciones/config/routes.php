<?php 
$requestRoute = str_replace('/', '', $_SERVER['REQUEST_URI']);

$routes = [
  '' => [
    'show-on-menu' => true,
    'name' => 'Inicio',
    'page' => $_SERVER['DOCUMENT_ROOT'].'/pages/inicio.php',
    'admin' => true,
    'student' => true
  ],
  'not-allowed' => [
    'show-on-menu' => false,
    'name' => 'No permitido',
    'page' => $_SERVER['DOCUMENT_ROOT'].'/pages/not-allowed.php',
    'admin' => true,
    'student' => true
  ],
  'login' => [
    'show-on-menu' => false,
    'name' => 'Login',
    'page' => $_SERVER['DOCUMENT_ROOT'].'/pages/login.php',
    'admin' => true,
    'student' => true
  ],
  'logout' => [
    'show-on-menu' => false,
    'name' => 'Logout',
    'page' => $_SERVER['DOCUMENT_ROOT'].'/pages/logout.php',
    'admin' => true,
    'student' => true
  ],
  'alta-alumnos' => [
    'show-on-menu' => true,
    'name' => 'Alta de alumnos',
    'page' => $_SERVER['DOCUMENT_ROOT'].'/pages/alta-alumnos.php',
    'admin' => true,
    'student' => false
  ],
  'listar-alumnos' => [
    'show-on-menu' => true,
    'name' => 'Listar alumnos',
    'page' => $_SERVER['DOCUMENT_ROOT'].'/pages/listar-alumnos.php',
    'admin' => true,
    'student' => false
  ],
  'listado-alumnos-por-materias' => [
    'show-on-menu' => true,
    'name' => 'Listado Alumnos por Materias',
    'page' => $_SERVER['DOCUMENT_ROOT'].'/pages/listado-alumnos-por-materias.php',
    'admin' => true,
    'student' => false
  ],
    'modificar-alumnos' => [
    'show-on-menu' => true,
    'name' => 'Modificar alumnos',
    'page' => $_SERVER['DOCUMENT_ROOT'].'/pages/modificar-alumnos.php',
    'admin' => true,
    'student' => false
  ],
  'regularidad-materia' => [
    'show-on-menu' => true,
    'name' => 'Regularidad de Materias',
    'page' => $_SERVER['DOCUMENT_ROOT'].'/pages/regularidad-materia.php',
    'admin' => true,
    'student' => false

  ],
  'equivalencias' => [
    'show-on-menu' => true,
    'name' => 'Equivalencias',
    'page' => $_SERVER['DOCUMENT_ROOT'].'/pages/equivalencias.php',
    'admin' => true,
    'student' => false
  ],
  'listado-materias-por-alumnos' => [
    'show-on-menu' => true,
    'name' => 'Listado Materias por Alumnos',
    'page' => $_SERVER['DOCUMENT_ROOT'].'/pages/listado-materias-por-alumnos.php',
    'admin' => true,
    'student' => false
  ],
  'alta-mesas-finales' => [
    'show-on-menu' => true,
    'name' => 'Alta Mesas de Finales',
    'page' => $_SERVER['DOCUMENT_ROOT'].'/pages/alta-mesas-finales.php',
    'admin' => true,
    'student' => false
  ],
  'listado-mesas-finales' => [
    'show-on-menu' => true,
    'name' => 'Listado Mesas de Finales',
    'page' => $_SERVER['DOCUMENT_ROOT'].'/pages/listado-mesas-finales.php',
    'admin' => true,
    'student' => false
  ],
  'actas-examenes' => [
    'show-on-menu' => true,
    'name' => 'Actas de Examenes',
    'page' => $_SERVER['DOCUMENT_ROOT'].'/pages/actas-examenes.php',
    'admin' => true,
    'student' => false
  ],
  'notas-finales' => [
    'show-on-menu' => true,
    'name' => 'Notas de Finales',
    'page' => $_SERVER['DOCUMENT_ROOT'].'/pages/notas-finales.php',
    'admin' => true,
    'student' => false
  ],
  'anotarse-mesas-finales' => [
    'show-on-menu' => true,
    'name' => 'Anotarse en las mesas de final',
    'page' => $_SERVER['DOCUMENT_ROOT'].'/pages/anotarse-mesas-finales.php',
    'admin' => false,
    'student' => true
  ],
  'modificar-mesa-anoto' => [
    'show-on-menu' => true,
    'name' => 'Modifcar las mesas en las que se anotó',
    'page' => $_SERVER['DOCUMENT_ROOT'].'/pages/modificar-mesas-finales.php',
    'admin' => false,
    'student' => true
  ],
  'ver-estado-materias' => [
    'show-on-menu' => true,
    'name' => 'Ver estado de las materías',
    'page' => $_SERVER['DOCUMENT_ROOT'].'/pages/ver-estado-materias.php',
    'admin' => false,
    'student' => true
  ],
  'cambiar-contrasena' => [
    'show-on-menu' => true,
    'name' => 'Cambiar contraseña',
    'page' => $_SERVER['DOCUMENT_ROOT'].'/pages/cambiar-contrasena.php',
    'admin' => true,
    'student' => true
  ],
];