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
    \Utils::change_page_title("Database");
    $this->view->database();
  }

  protected function table()
  {
    \Utils::change_page_title("Table");
    $this->view->table();
  }
}
