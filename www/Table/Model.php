<?php

namespace Table;

require_once __DIR__ . "/../Core/Model.php";

class Model extends \Core\Model
{
  public function create_db($database)
  {
    $this->conn->exec("CREATE DATABASE $database");

    // create role
    $stmt = $this->conn->prepare("INSERT INTO simpletables.role (db, email, role) VALUES (?, ?, 'admin')");
    $stmt->execute([$database, $_SESSION["user"]]);
  }

  public function get_user_dbs($email)
  {
    if (empty($email)) return [];

    $stmt = $this->conn->prepare("SELECT * FROM simpletables.role WHERE email = ?");
    $stmt->execute([$email]);

    return $stmt->fetchAll(\PDO::FETCH_ASSOC);
  }

  // returns boolean
  public function db_exists($db)
  {
    $stmt = $this->conn->prepare(
      "SELECT EXISTS(SELECT SCHEMA_NAME FROM INFORMATION_SCHEMA.SCHEMATA WHERE SCHEMA_NAME = ?)"
    );
    $stmt->execute([$db]);

    // fetch returns string "0" or "1"
    return $stmt->fetch(\PDO::FETCH_COLUMN) === "1";
  }
}
