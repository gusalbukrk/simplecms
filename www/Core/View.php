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

    self::render("/../views/$this->controller/$method.php");
  }

  static public function not_found()
  {
    self::render("/../views/not-found.php");
  }

  // load the file $body and wrap it with header and footer
  static private function render($body)
  {
    require_once __DIR__ . "/../views/header.php";
    require_once __DIR__ . $body;
    require_once __DIR__ . "/../views/footer.php";
  }
}
