<?php

namespace Table;

require_once __DIR__ . "/../Core/Controller.php";

require_once __DIR__ . "/Model.php";
require_once __DIR__ . "/View.php";

// privilege levels
enum Roles: int
{
  case Reader = 0;
  case Editor = 1;
  case Admin = 2;
}

class Controller extends \Core\Controller
{
  public function __construct()
  {
    $this->model = new Model();
    $this->view = new View();
  }

  protected function home()
  {
    // handle create database form submit
    if (isset($_POST["db"])) {
      $this->model->create_db($_POST["db"]);
    }

    $dbs = isset($_SESSION["user"]) ? $this->model->get_user_dbs($_SESSION["user"]) : [];

    $this->view->home(["dbs" => $dbs]);
  }

  protected function database()
  {
    $db = explode(".", $_SERVER["HTTP_HOST"])[0];
    $exists = $this->model->db_exists($db) == true;

    \Utils::change_page_title("Database");
    $this->view->database(["db" => $db, "exists" => $exists]);
  }

  protected function table()
  {
    \Utils::change_page_title("Table");
    $this->view->table();
  }
}
