<?php

namespace Core;

// every method in the controller has (roughly) the same name as its corresponding route
// "roughly" because slashes are converted to underlines (function names can't contain slashes)
abstract class Controller
{
  protected $must_be_logged_in = false, $model, $view;

  abstract public function __construct();

  // every non-static method call on an instance of Controller is handled by `__call`
  // as long as the method is inaccessible (for instance, access is set to `protected`)
  public function __call($method, $args)
  {
    self::wrap(function () use ($method, $args) {
      if ($this->must_be_logged_in && !isset($_SESSION["user"])) {
        \Utils::redirect("https://simpletables.xyz/login");
      }

      call_user_func_array(array($this, $method), $args); // call the method
    });
  }

  static public function not_found()
  {
    self::wrap(function () {
      \Utils::change_page_title("Not found");
      View::not_found();
    });
  }

  static private function wrap($fn)
  {
    require_once __DIR__ . "/../views/header.php";
    $fn();
    require_once __DIR__ . "/../views/footer.php";
  }
}
