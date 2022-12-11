<?php

namespace Table;

require_once __DIR__ . "/../Core/Model.php";

class Model extends \Core\Model
{
  public function create_db($database)
  {
    $this->conn->exec("CREATE DATABASE $database");

    // create 'role' table inside database created above
    $this->conn->exec(
      "CREATE TABLE $database.user (
        email VARCHAR(50) NOT NULL,
        role TINYINT UNSIGNED NOT NULL,
        FOREIGN KEY (email) REFERENCES simpletables.user(email)
      )"
    );

    // insert current user in it as admin
    $stmt = $this->conn->prepare("INSERT INTO $database.user (email, role) VALUES (?, 1)");
    $stmt->execute([$_SESSION["user"]]);
  }

  public function get_user_dbs($email)
  {
    if (empty($email)) return [];

    $except = ["information_schema", "mysql", "performance_schema", "sys", "simpletables"];

    $dbs = []; // associative array — key = database name and value = privilege level

    $stmt = $this->conn->prepare("SHOW DATABASES");
    $stmt->execute();

    while (($db = $stmt->fetchColumn()) !== false) {
      if (!in_array($db, $except)) {
        $stmt2 = $this->conn->prepare("SELECT role FROM $db.user WHERE email = ?");
        $stmt2->execute([$email]);
        $role = $stmt2->fetchColumn();

        if ($role) $dbs[$db] = $role;
      }
    }

    return $dbs;
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
