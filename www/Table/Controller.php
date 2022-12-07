<?php

require_once __DIR__ . "/../Core/Controller.php";

require_once __DIR__ . "/Model.php";
require_once __DIR__ . "/View.php";

class TableController extends Controller
{
  public function __construct()
  {
    $this->model = new TableModel();
    $this->view = new TableView();
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
