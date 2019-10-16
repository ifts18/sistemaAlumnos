<?php

interface Routable {
  public function filterRoute($route);
}

abstract class Routes {
  public function filter($routes) {
    return array_filter($routes, array($this, "filterRoute"));
  }

  abstract function filterRoute($route);
}

class AdminRoutes extends Routes implements Routable {
  public function filterRoute($route) {
    return $route['admin'];
  }
}

class StudentRoutes extends Routes implements Routable {
  public function filterRoute($route) {
    return $route['student'];
  }
}