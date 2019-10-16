<?php 
$form_fields = [
  [
    'id' => 'name',
    'label' => 'Nombre',
    'type' => 'text',
    'value' => isset($_POST['name']) ? $_POST['name'] : ''
  ]
];
?>
<div class="container-fluid">
  <div class="row">
    <div class="col-md-12">
      <?php generate($form_fields, ''); ?>
    </div>
  </div>
</div>