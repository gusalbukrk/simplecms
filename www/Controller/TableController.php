<?php

require_once __DIR__ . "/Controller.php";

require_once __DIR__ . "/../Model/TableModel.php";
require_once __DIR__ . "/../View/TableView.php";

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
