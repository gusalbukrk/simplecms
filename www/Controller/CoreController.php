<?php

require_once __DIR__ . "/BaseController.php";

class CoreController extends BaseController
{
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
