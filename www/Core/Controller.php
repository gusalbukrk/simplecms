<?php

namespace Core;

// every method in the controller has (roughly) the same name as its corresponding route
// "roughly" because slashes are converted to underlines (function names can't contain slashes)
abstract class Controller
{
  protected $model, $view;

  abstract public function __construct();

  static function not_found()
  {
    View::not_found();
  }
}
