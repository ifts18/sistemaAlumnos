<?php 
$fields = ['idAlumno', 'DNI', 'Apellido', 'Nombre', 'FechaCreacion', 'Email', 'Password']; // aca estan los campos que voy a pedirle al SQL

$query = 'SELECT &query& FROM alumnos WHERE 1 = 1'; // Estructura basica de la query

$criterio = $_GET['criterio']; // Seteo variables del form de busqueda
$text = $_GET['text']; // Seteo variables del form de busqueda

if (isset($criterio) && isset($text)) {
  $query = $query . ' AND ' . $criterio . ' LIKE ' . $DbManager->quote('%'.$text.'%'); // Si tengo variables de busqueda, las agrego a la clausula del WHERE
}

$count = (int) $DbManager->select(str_replace('&query&', 'COUNT(*) AS Total', $query))[0]['Total']; // Ejecuto un COUNT para el total y el paginado
$totalPages = ceil($count / $perPage); // Estas son las paginas que voy a tener

$query = $query . ' LIMIT ' . $offset . ', ' . $perPage; // Ahora le agrego el LIMIT

$alumnos = $DbManager->select(str_replace('&query&', implode(', ', $fields), $query)); // Hago la query de resultados!

$headers = ['ID', 'DNI', 'Apellido', 'Nombre', 'Fecha de creación', 'Email', 'Contraseña', 'Acciones']; // Seteo los headers para la tabla
$actions = ['<button type="button" class="btn btn-editar-alumno btn-outline-info btn-sm">Modificar</button>'];
$cancelLink = '/alumnos';
?>

<div class="container">
  <div class="row">
    <div class="col-md-12 mb-4">
      <?php include_once($_SERVER['DOCUMENT_ROOT'].'/blocks/buscarAlumno.php'); ?>
    </div>
    <div class="col-md-12">
      <?php echo print_table($headers, $alumnos, $actions); ?>
    </div>
    <div class="col-md-12">
      <?php echo print_pagination($pageNumber, $totalPages); ?>
    </div>
  </div>
</div>