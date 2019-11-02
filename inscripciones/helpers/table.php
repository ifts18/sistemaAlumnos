<?php

$perPage = 10; // resultados por pagina
$pageNumber = (int) (isset($_GET['page']) ? $_GET['page'] : '1');
$offset = ($pageNumber - 1) * $perPage;

function print_table($headers, $rows, $actions) {
  if (empty($rows)) {
    return '<div class="card"><div class="card-body mx-auto">Ups... No existen resultados</div></div>';
  }

  $html = '<table class="table table-bordered">';
  
  if (!empty($headers)) {
    $html = $html . '<thead><tr>';

    foreach($headers as $header) {
      $html = $html . '<th scope="col">' . $header . '</th>';
    }

    $html = $html . '</tr></thead>';
  }

  if (!empty($rows)) {
    $html = $html . '<tbody>';

    foreach($rows as $row) {
      $html = $html . '<tr>';

      foreach($row as $tableData) {
        $html = $html . '<td>' . $tableData . '</td>';
      }

      if (!empty($actions)) {
        foreach($actions as $action) {
          $html = $html . '<td data-id="'.$row['idAlumno'].'">' . $action . '</td>';
        }
      }

      $html = $html . '</tr>';
    }

    $html = $html . '</tbody>';
  }

  $html = $html . '</table>';

  return $html;
}

function print_pagination($pageNumber, $totalPages) {
  $antClass = 'page-item disabled';
  $antLink = '#';
  $sigClass = 'page-item disabled';
  $sigLink = '#';

  if ($pageNumber > 1) {
    $antClass = 'page-item';
    $antLink = $pageNumber - 1;
  }

  if ($pageNumber < $totalPages) {
    $sigClass = 'page-item';
    $sigLink = $pageNumber + 1;
  }

  return '
    <nav>
      <ul class="pagination justify-content-center">
        <li class="page-item"><a class="page-link" href="?page=1">Primera página</a></li>
        <li class="'.$antClass.'"><a class="page-link" href="?page='.$antLink.'">Anterior</a></li>
        <li class="'.$sigClass.'"><a class="page-link" href="?page='.$sigLink.'">Siguiente</a></li>
        <li class="page-item"><a class="page-link" href="?page='. $totalPages .'">Última página</a></li>
      </ul>
    </nav>
  ';
}
