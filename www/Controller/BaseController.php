<?php

require_once __DIR__ . "/../Model/Model.php";
require_once __DIR__ . "/../View/View.php";

// every method in the controller has (roughly) the same name as its corresponding route
// "roughly" because slashes are converted to underlines (function names can't contain slashes)
class BaseController
{
  protected $model, $view;

  public function __construct()
  {
    $this->model = new Model();
    $this->view = new View();
  }

  static function page_not_found()
  {
    // TODO: create view
    // $this->view->page_not_found();
    echo "404 - PAGE NOT FOUND";
  }
}
