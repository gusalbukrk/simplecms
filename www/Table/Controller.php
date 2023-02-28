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
    $title = \Utils::pluralize(explode("@", $_SESSION["user"])[0]) . " databases";

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

    \Utils::change_page_title($title);
    $this->view->databases(["title" => $title, "dbs" => $dbs]);
  }

  protected function tables()
  {
    $db = explode(".", $_SERVER["HTTP_HOST"])[0];
    $db_exists = $this->model->db_exists($db);

    $title = \Utils::pluralize($db) . " tables";

    if ($db_exists) {
      if (isset($_POST["action"])) {
        $action = $_POST["action"];

        if ($action === "create") {
          $this->model->create_table($db, $_POST["table"], $_POST["fields"], $_POST["pkIndex"]);
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

    \Utils::change_page_title($title);
    $this->view->tables(array_merge(
      [
        "title" => $title,
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

    $title = \Utils::pluralize("$db / $table") . " records";

    if ($db_exists && $table_exists) {
      if (isset($_POST["action"])) {
        $action = $_POST["action"];

        if ($action === "create") {
          $this->model->create_record($_POST["db"], $_POST["table"], $_POST["record"]);
        } else if ($action === "update") {
          $changed_fields = array_reduce(
            array_keys($_POST["record"]), // $_POST["record"] and $_POST["inputs"] have the same keys
            function ($acc, $cur) {
              if ($_POST["record"][$cur] !== $_POST["inputs"][$cur]) {
                $acc[$cur] = $_POST["inputs"][$cur];
              }

              return $acc;
            },
            []
          );

          if (!empty($changed_fields)) {
            $this->model->update_record($_POST["db"], $_POST["table"], $_POST["pkName"], $_POST["record"][$_POST["pkName"]], $changed_fields);
          }
        } else if ($action === "delete") {
          $this->model->delete_record($_POST["db"], $_POST["table"], $_POST["pkName"], $_POST["record"][$_POST["pkName"]]);
        }
      }

      $schema = $this->model->get_columns_schema($db, $table);
      $records = $this->model->get_records($db, $table);
    }

    \Utils::change_page_title($title);
    $this->view->records([
      "title" => $title,
      "db" => $db,
      "db_exists" => $db_exists,
      "table" => $table,
      "table_exists" => $table_exists,
      "schema" => $schema ?? null,
      "records" => $records ?? null
    ]);
  }
}
