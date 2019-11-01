<?php 
echo '<script>let showToast = false;</script>';
$toast_message = 'Alumno creado correctamente';
$toast_bg = 'bg-success';
$form_fields = [
  'name' => [
      'id' => 'name',
      'label' => 'Nombre',
      'type' => 'text',
      'value' => isset($_POST['name']) ? $_POST['name'] : '',
      'message' => '',
      'isValid' => true,
    ],
  'lastname' => [
    'id' => 'lastname',
    'label' => 'Apellido',
    'type' => 'text',
    'value' => isset($_POST['lastname']) ? $_POST['lastname'] : '',
    'message' => '',
    'isValid' => true,
  ],
  'mail' => [
    'id' => 'mail',
    'label' => 'Mail',
    'type' => 'text',
    'value' => isset($_POST['mail']) ? $_POST['mail'] : '',
    'message' => '',
    'isValid' => true,
  ],
  'dni' => [
    'id' => 'dni',
    'label' => 'DNI',
    'type' => 'text',
    'value' => isset($_POST['dni']) ? $_POST['dni'] : '',
    'message' => '',
    'isValid' => true
  ],
  'division' => [
    'id' => 'division',
    'label' => 'Division',
    'type' => 'select',
    'options' => [['1° A', 1], ['1° B', 2], ['2° A', 1], ['2° B', 2], ['3°', 0]],
    'value' => isset($_POST['division']) ? $_POST['division'] : '',
    'message' => '',
    'isValid' => true
  ]
];

if ($_POST) {
  $isFormValid = true;

  foreach($form_fields as $key => &$form_field) { // $form_field es pasado por referencia
    if ($form_field['value'] === '') {
      $isFormValid = false;
      $form_field['isValid'] = false;
      $form_field['message'] = $form_field['label'] . ' no puede estar vacío';
      continue;
    }

    // Validación por DNI. Podriamos sacarlo a una función helper si la usamos en muchos lados
    if ($form_field['id'] === 'dni') {
      $escapedDni = $DbManager->quote($form_field['value']);
      $alumno = $DbManager->select('SELECT * FROM alumnos WHERE DNI = '.$escapedDni);
      if (!empty($alumno)) {
        $isFormValid = false;
        $form_field['isValid'] = false;
        $form_field['message'] = 'El alumno ya existe';
      }
    }

    $form_field['escaped'] = $DbManager->quote($form_field['value']);
  }

  if ($isFormValid) {
    $insertionOk = $DbManager->query('INSERT INTO alumnos (IdAlumno, IdPlan, Email, DNI, Apellido, Nombre, Password, FechaCreacion) VALUES 
      (NULL, 1, '.$form_fields['mail']['escaped'].', '.$form_fields['dni']['escaped'].', '.$form_fields['lastname']['escaped'].', '
      .$form_fields['name']['escaped'].', '.$form_fields['dni']['escaped'].', CURRENT_TIMESTAMP)');

    echo '<script> showToast = true; </script>';

    if(!$insertionOk) {
      error_log($DbManager->error());
      $toast_bg = 'bg-danger';
      $toast_message = 'Ocurrió un error al crear el alumno';
    }

    $insertedAlumno = $DbManager->select('SELECT * FROM alumnos WHERE DNI = '.$form_fields['dni']['escaped']);
    $insertIntoAlumnoMaterias = 'INSERT INTO alumno_materias (idAlumnoMateria, IdAlumno, IdMateriaPlan, FechaFirma, FechaCaduco, MotivoCaduco, Repeticion, FechaCreacion, IdDivision) VALUES';

    // 30 materias
    for ($i = 1; $i <= 30; $i++) {
      $insertIntoAlumnoMaterias = $insertIntoAlumnoMaterias.' (NULL, '.$insertedAlumno[0]['IdAlumno'].', '.$i.',NULL, NULL, NULL, 0, CURRENT_TIMESTAMP, '.$form_fields['division']['escaped'].')';

      if ($i !== 30) {
        $insertIntoAlumnoMaterias = $insertIntoAlumnoMaterias.', ';
      }
    }

    $insertionOk = $DbManager->query($insertIntoAlumnoMaterias); // Insert de alumno_materias

    if(!$insertionOk) { // Si falla, tambien mostramos la toast
      error_log($DbManager->error());
      $toast_bg = 'bg-danger';
      $toast_message = 'Ocurrió un error al asignarle las materias al alumno';
    }
  }
}
?>

<div data-delay="3000" class="<?php echo 'toast '.$toast_bg; ?>" style="position: absolute; top: 60px; right: 0;">
  <div class="toast-body">
    <p id="toast-message"><?php echo $toast_message; ?></p>
  </div>
</div>


<div class="container-fluid">
  <div class="row">
    <div class="col-md-12">
      <?php generate($form_fields, ''); ?>
    </div>
  </div>
</div>