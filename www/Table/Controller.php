<?php

namespace Table;

require_once __DIR__ . "/../Core/Controller.php";

require_once __DIR__ . "/Model.php";
require_once __DIR__ . "/View.php";

class Controller extends \Core\Controller
{
  public function __construct()
  {
    $this->model = new Model();
    $this->view = new View();
  }

  protected function home()
  {
    $this->view->home();
  }

  protected function database()
  {
    $this->view->database();
    // \Utils::change_page_title("Database");
  }

  protected function table()
  {
    $this->view->table();
    // \Utils::change_page_title("Table");
  }
}
