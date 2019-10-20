<?php 
$form_fields = [
  [
    'id' => 'name',
    'label' => 'Nombre',
    'type' => 'text',
    'value' => isset($_POST['name']) ? $_POST['name'] : ''
  ],
  [
    'id' => 'lastname',
    'label' => 'Apellido',
    'type' => 'text',
    'value' => isset($_POST['lastname']) ? $_POST['lastname'] : ''
  ],
  [
    'id' => 'mail',
    'label' => 'Mail',
    'type' => 'text',
    'value' => isset($_POST['mail']) ? $_POST['mail'] : ''
  ],
  [
    'id' => 'dni',
    'label' => 'DNI',
    'type' => 'text',
    'value' => isset($_POST['dni']) ? $_POST['dni'] : ''
  ]
];
?>

<div class="container-fluid">
  <div class="row">
    <div class="col-md-12">
      <?php generate($form_fields, '', 'alta-alumno'); ?>
    </div>
  </div>
</div>