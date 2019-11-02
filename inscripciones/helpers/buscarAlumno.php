<?php
function setWhere() {
  if (isset($_GET['criterio']) && isset($_GET['text'])) {
    return ' AND ' . $_GET['criterio'] . ' LIKE \'%' . $_GET['text'] . '%\'';
  }
}