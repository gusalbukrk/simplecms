<?php

namespace Core;

// every method call on an instance of View is triggered by a
// Controller method of the same name and it's handled by the `__call` magic method
class View
{
  protected $controller;

  public function __call($method, $args)
  {
    $method = preg_replace("/_/", "-", $method); // replace slash with underline

    require_once __DIR__ . "/../views/$this->controller/$method.php";
  }

  static public function not_found()
  {
    require_once __DIR__ . "/../views/not-found.php";
  }

  static public function header()
  {
    require_once __DIR__ . "/../views/header.php";
  }

  static public function footer()
  {
    require_once __DIR__ . "/../views/footer.php";
  }
}
