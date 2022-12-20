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
    if (isset($_POST["action"])) {
      switch ($_POST["action"]) {
        case "Create":
          $this->model->create_db($_POST["db"], true);
          break;
        case "Rename":
          $this->model->rename_database($_POST["db"], $_POST["name"]);
          break;
        case "Delete":
          $this->model->delete_db($_POST["db"]);
          break;
      }
    }

    $dbs = isset($_SESSION["user"]) ? $this->model->get_user_dbs($_SESSION["user"]) : [];

    $this->view->home(["dbs" => $dbs]);
  }

  protected function database()
  {
    $db = explode(".", $_SERVER["HTTP_HOST"])[0];
    $exists = $this->model->db_exists($db);

    $tables = $exists ? $this->model->get_all_tables_from_db($db) : null;

    $role = $this->model->get_db_user($db, $_SESSION["user"]);

    \Utils::change_page_title("$db database");
    $this->view->database(["db" => $db, "exists" => $exists, "role" => $role, "tables" => $tables]);
  }

  protected function table()
  {
    \Utils::change_page_title("Table");
    $this->view->table();
  }
}
