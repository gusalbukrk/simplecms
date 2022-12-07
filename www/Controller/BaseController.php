<?php

// every method in the controller has (roughly) the same name as its corresponding route
// "roughly" because slashes are converted to underlines (function names can't contain slashes)
abstract class BaseController
{
  protected $model, $view;

  abstract public function __construct();

  static function page_not_found()
  {
    // TODO: create view
    // $this->view->page_not_found();
    echo "404 - PAGE NOT FOUND";
  }
}
