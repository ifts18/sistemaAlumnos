<?php

function exclude_blocks($requestRoute) {
  $excluded = array('login', 'logout');
  return in_array($requestRoute, $excluded);
}