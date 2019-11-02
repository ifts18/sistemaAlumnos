<form class="form-inline">
  <div class="form-row">
    <div class="col">
      <select class="form-control" name="criterio">
        <option <?php echo $criterio && $criterio === 'DNI' ? 'selected' : ''; ?> value="DNI">DNI</option>
        <option <?php echo $criterio && $criterio === 'Apellido' ? 'selected' : ''; ?> value="Apellido">Apellido</option>
      </select>
    </div>
    <div class="col">
      <input type="text" name="text" class="form-control" value="<?php echo $text; ?>">
    </div>
    <div class="col">
      <button type="submit" class="btn btn-primary">Buscar</button>
    </div>
    <div class="col">
      <a href="<?php echo $cancelLink; ?>" class="btn btn-outline-secondary">Cancelar</a>
    </div>
  </div>
</form>