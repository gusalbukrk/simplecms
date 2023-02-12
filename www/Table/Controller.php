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
          $this->model->rename_db($_POST["db"], $_POST["new_name"]);
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
    $db_exists = $this->model->db_exists($db);

    if ($db_exists) {
      if (isset($_POST["action"])) {
        $action = $_POST["action"];

        if ($action === "create") {
          $this->model->create_table($db, $_POST["table"], $_POST["fields"]);
        } else if (isset($_POST["role"]) && $_POST["role"] === "Admin") { // only admins can rename or delete tables
          if ($action === "rename") {
            $this->model->rename_table($db, $_POST["table"], $_POST["new_name"]);
          } else if ($action === "delete") {
            $this->model->delete_table($db, $_POST["table"]);
          }
        }
      }

      $role = $this->model->get_db_user($db, $_SESSION["user"]);
      $tables = $this->model->get_all_tables_from_db($db);
    }

    \Utils::change_page_title("$db database");
    $this->view->tables(array_merge(
      [
        "db" => $db,
        "db_exists" => $db_exists,
      ],
      // $role and $tables are only defined if $db_exists is true
      ($db_exists ? ["role" => $role, "tables" => $tables] : []),
    ));
  }

  protected function records()
  {
    $db = explode(".", $_SERVER["HTTP_HOST"])[0];
    $db_exists = $this->model->db_exists($db);

    $table = preg_replace( // remove leading slash and query string
      ["/^\//", "/(\?(\w+=\w+&?)+)?$/"],
      "",
      $_SERVER["REQUEST_URI"]
    );
    $table_exists = $db_exists ? $this->model->table_exists($db, $table) : false;

    if ($db_exists && $table_exists) {
      $schema = $this->model->get_columns_schema($db, $table);
      $records = $this->model->get_records($db, $table);
    }

    \Utils::change_page_title("$table table");
    $this->view->records([
      "db" => $db,
      "db_exists" => $db_exists,
      "table" => $table,
      "table_exists" => $table_exists,
      "schema" => $schema ?? null,
      "records" => $records ?? null
    ]);
  }
}
