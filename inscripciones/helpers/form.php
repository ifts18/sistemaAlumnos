<?php

function generate($fields, $action, $method = 'POST') {
  return print_top($action, $method).print_fields($fields).print_submit().print_bottom();
}

function print_fields($fields) {
  foreach($fields as $field) {
    $id = $field['id'];
    $name = $id;
    $label = $field['label'];
    $type = $field['type'];
    $isValid = $field['isValid'];
    $message = $field['message'];
    $placeholder = isset($field['placeholder']) ? $field['placeholder'] : '';
    $help = isset($field['help']) ? $field['help'] : '';
    $value = isset($field['value']) ? $field['value'] : '';

    $inputClass = $isValid ? 'form-control' : 'form-control is-invalid';

    $formGroup = '
      <div class="form-group">
        <label for="'.$id.'">'.$label.'</label>
        <input value="'.$value.'" name="'.$name.'" type="'.$type.'" class="'.$inputClass.'" id="'.$id.'" aria-describedby="'.$id.'Help" placeholder="'.$placeholder.'">
    ';

    if ($message !== '') {
      $formGroup = $formGroup.'<div class="invalid-feedback">'.$message.'</div>';
    }

    if ($help !== '') {
      $formGroup = $formGroup.'<small id="'.$id.'Help" class="form-text text-muted">'.$help.'</small>';
    }

    echo $formGroup.'</div>';
  }
}

function print_top($action, $method) {
  echo '<form';
  if (isset($action) && $action !== '') {
    echo ' action="'.$action.'"';
  }
  echo ' method="'.$method.'">';
}

function print_submit() {
  echo '<button type="submit" class="btn btn-primary">Guardar</button>';
}

function print_bottom() {
  echo '<form />';
}