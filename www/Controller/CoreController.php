<?php

require_once __DIR__ . "/BaseController.php";

require_once __DIR__ . "/../Model/CoreModel.php";
require_once __DIR__ . "/../View/CoreView.php";

class CoreController extends BaseController
{
  public function __construct()
  {
    $this->model = new CoreModel();
    $this->view = new CoreView();
  }

  public function home()
  {
    $this->view->home();
  }

  public function database()
  {
    $this->view->database();
  }

  public function table()
  {
    $this->view->table();
  }
}
