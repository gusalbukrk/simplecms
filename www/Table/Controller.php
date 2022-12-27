<?php

namespace Table;

require_once __DIR__ . "/../Core/Controller.php";

require_once __DIR__ . "/Model.php";
require_once __DIR__ . "/View.php";

class Controller extends \Core\Controller
{
  public function __construct()
  {
    $this->must_be_logged_in = true;
    $this->model = new Model();
    $this->view = new View();
  }

  protected function databases()
  {
    if (isset($_POST["action"])) {
      $action = $_POST["action"];

      if ($action === "create") {
        $this->model->create_db($_POST["db"], true);
      } else if (isset($_POST["role"]) && $_POST["role"] === "Admin") { // only admins can rename or delete databases
        if ($action === "rename") {
          $this->model->rename_database($_POST["db"], $_POST["name"]);
        } else if ($action === "delete") {
          $this->model->delete_db($_POST["db"]);
        }
      }
    }

    $dbs = isset($_SESSION["user"]) ? $this->model->get_user_dbs($_SESSION["user"]) : [];

    \Utils::change_page_title("Databases");
    $this->view->databases(["dbs" => $dbs]);
  }

  protected function tables()
  {
    $db = explode(".", $_SERVER["HTTP_HOST"])[0];
    $exists = $this->model->db_exists($db);

    if ($exists) {
      $tables = $this->model->get_all_tables_from_db($db);
      $role = $this->model->get_db_user($db, $_SESSION["user"]);
    }

    \Utils::change_page_title("$db database");
    $this->view->tables([
      "db" => $db,
      "exists" => $exists,
      "role" => $role ?? null,
      "tables" => $tables ?? null,
    ]);
  }

  protected function records()
  {
    $db = explode(".", $_SERVER["HTTP_HOST"])[0];
    //
    // remove leading slash and query string
    $table = preg_replace(["/^\//", "/(\?(\w+=\w+&?)+)?$/"], "", $_SERVER["REQUEST_URI"]);

    $records = $this->model->get_records($db, $table);

    \Utils::change_page_title("Table");
    $this->view->records(["records" => $records]);
  }
}
