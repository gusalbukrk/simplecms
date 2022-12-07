<?php

namespace Core;

// every function inside view is called by a controller method of the same name
class View
{
  public function __call($method, $args)
  {
    $method = preg_replace("/_/", "-", $method); // replace slash with underline

    require_once __DIR__ . "/../views/header.php";
    require_once __DIR__ . "/../views/$method.php";
    require_once __DIR__ . "/../views/footer.php";
  }
}
