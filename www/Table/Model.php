<?php

namespace Table;

require_once __DIR__ . "/../Core/Model.php";

class Model extends \Core\Model
{
  public function create_db($db)
  {
    $this->conn->exec("CREATE DATABASE $db");

    // create 'user' table inside database created above
    $this->conn->exec(
      "CREATE TABLE $db.user (
        email VARCHAR(50) NOT NULL,
        role TINYINT UNSIGNED NOT NULL,
        FOREIGN KEY (email) REFERENCES simpletables.user(email)
      )"
    );

    // insert current user as admin in the newly created 'user' table
    $stmt = $this->conn->prepare("INSERT INTO $db.user (email, role) VALUES (?, " . Roles::Admin->value . ")");
    $stmt->execute([$_SESSION["user"]]);
  }

  public function get_user_dbs($email)
  {
    $except = ["information_schema", "mysql", "performance_schema", "sys", "simpletables"];

    $dbs = []; // associative array — key = database name and value = role

    $stmt = $this->conn->prepare("SHOW DATABASES");
    $stmt->execute();

    while (($db = $stmt->fetchColumn()) !== false) {
      if (!in_array($db, $except)) {
        $stmt2 = $this->conn->prepare("SELECT role FROM $db.user WHERE email = ?");
        $stmt2->execute([$email]);
        $role = $stmt2->fetchColumn();

        if ($role !== false) $dbs[$db] = Roles::from($role);
      }
    }

    return $dbs;
  }

  public function db_exists($db)
  {
    $stmt = $this->conn->prepare(
      "SELECT EXISTS(SELECT SCHEMA_NAME FROM INFORMATION_SCHEMA.SCHEMATA WHERE SCHEMA_NAME = ?)"
    );
    $stmt->execute([$db]);

    return $stmt->fetch(\PDO::FETCH_COLUMN) === "1";
  }

  public function delete_db($db)
  {
    return $this->conn->exec("DROP DATABASE $db");
  }
}
