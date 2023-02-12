<?php

namespace Table;

require_once __DIR__ . "/../Core/Model.php";

// privilege levels
enum Roles: int
{
  case Reader = 0;
  case Editor = 1;
  case Admin = 2;
}

class Model extends \Core\Model
{
  public function create_db($db, $create_user_table = false)
  {
    $this->conn->exec("CREATE DATABASE $db");

    if ($create_user_table) { // create table inside database created above
      $this->conn->exec(
        "CREATE TABLE $db.user (
        email VARCHAR(50) NOT NULL,
        role TINYINT UNSIGNED NOT NULL,
        FOREIGN KEY (email) REFERENCES simpletables.user(email)
        )"
      );

      // insert current user as admin in the newly created table
      $stmt = $this->conn->prepare("INSERT INTO $db.user (email, role) VALUES (?, " . Roles::Admin->value . ")");
      $stmt->execute([$_SESSION["user"]]);
    }
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

    return $stmt->fetch(\PDO::FETCH_COLUMN) === 1;
  }

  public function delete_db($db)
  {
    return $this->conn->exec("DROP DATABASE $db");
  }

  public function get_all_tables_from_db($db)
  {
    $stmt = $this->conn->prepare(
      // exclude user table because it's not an user-created table
      "SHOW TABLES FROM $db WHERE tables_in_$db NOT LIKE 'user'"
    );
    $stmt->execute();
    $tables = $stmt->fetchAll(\PDO::FETCH_COLUMN);

    return $tables;
  }

  public function rename_db($db, $new_name)
  {
    $this->create_db($new_name);

    // move all tables in $db to $new_name
    $tables = $this->get_all_tables_from_db($db);
    foreach ($tables as $table) {
      $this->conn->exec("RENAME TABLE $db.$table TO $new_name.$table");
    }

    $this->delete_db($db);
  }

  // return role integer (check Roles enum)
  public function get_db_user($db, $email)
  {
    $stmt = $this->conn->prepare("SELECT role FROM $db.user WHERE email = ?");
    $stmt->execute([$email]);

    return Roles::from($stmt->fetchColumn());
  }

  public function get_records($db, $table)
  {
    $stmt = $this->conn->prepare("SELECT * FROM $db.$table");
    $stmt->execute();

    return $stmt->fetchAll(\PDO::FETCH_ASSOC);
  }

  public function get_columns_schema($db, $table)
  {
    $stmt = $this->conn->prepare("SHOW COLUMNS FROM $db.$table");
    $stmt->execute();

    return $stmt->fetchAll(\PDO::FETCH_ASSOC);
  }

  public function create_table($db, $table, $fields)
  {
    $types = [
      "text" => "VARCHAR(255)",
      "number" => "INT",
    ];

    $fields_str = preg_replace("/,\\s$/", "", array_reduce($fields, function ($acc, $field) use ($types) {
      // each $field has 2 indexed columns representing, respectively: name and type
      return $acc . $field[0] . " " . $types[$field[1]] . " NOT NULL, ";
    }, ""));

    $statement = "CREATE TABLE $db.$table ( $fields_str, PRIMARY KEY (" . $fields[0][0] . ") )";

    $this->conn->exec($statement);
  }

  public function rename_table($db, $table, $new_name)
  {
    $this->conn->exec("RENAME TABLE $db.$table TO $db.$new_name");
  }

  public function delete_table($db, $table)
  {
    $this->conn->exec("DROP TABLE $db.$table");
  }

  public function table_exists($db, $table)
  {
    $stmt = $this->conn->prepare("SHOW TABLES FROM $db LIKE ?");
    $stmt->execute([$table]);

    // $stmt->fetch returns false if no record was found
    return $stmt->fetch(\PDO::FETCH_COLUMN) !== false;
  }
}
