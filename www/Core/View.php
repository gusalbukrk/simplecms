<?php

namespace Core;

// every function inside view is called by a controller method of the same name
class View
{
  protected $controller;

  public function __call($method, $args)
  {
    $method = preg_replace("/_/", "-", $method); // replace slash with underline

    self::render("/../views/$this->controller/$method.php");
  }

  static public function not_found()
  {
    self::render("/../views/not-found.php");
  }

  static private function render($body)
  {
    require_once __DIR__ . "/../views/header.php";
    require_once __DIR__ . $body;
    require_once __DIR__ . "/../views/footer.php";
  }
}
