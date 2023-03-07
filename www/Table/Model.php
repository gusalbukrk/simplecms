<?php

namespace Table;

require_once __DIR__ . "/../Core/Model.php";

class Model extends \Core\Model
{
  public function create_db($db, $create_user_table = false)
  {
    $this->conn->exec("CREATE DATABASE $db");

    if ($create_user_table) { // create table inside database created above
      $this->conn->exec(
        "CREATE TABLE $db.users (
        email VARCHAR(50) NOT NULL,
        role ENUM('reader', 'editor', 'admin') NOT NULL,
        PRIMARY KEY (email),
        FOREIGN KEY (email) REFERENCES simpletables.users(email)
        )"
      );

      // insert current user as admin in the newly created table
      $stmt = $this->conn->prepare("INSERT INTO $db.users (email, role) VALUES (?, 'admin')");
      $stmt->execute([$_SESSION["user"]]);
    }
  }

  public function get_user_dbs($email)
  {
    $except = ["information_schema", "mysql", "performance_schema", "sys", "simpletables"];

    $dbs = []; // associative array — key = database name, value = role

    $stmt = $this->conn->prepare("SHOW DATABASES");
    $stmt->execute();

    while (($db = $stmt->fetchColumn()) !== false) {
      if (!in_array($db, $except)) {
        $stmt2 = $this->conn->prepare("SELECT role FROM $db.users WHERE email = ?");
        $stmt2->execute([$email]);
        $role = $stmt2->fetchColumn();

        if ($role !== false) $dbs[$db] = $role;
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

  // exclude users table it's default because it's not an user-created table
  public function get_all_tables_from_db($db, $exclude_users = true)
  {
    $stmt = $this->conn->prepare(
      "SHOW TABLES FROM $db" . ($exclude_users ? " WHERE tables_in_$db NOT LIKE 'users'" : "")
    );
    $stmt->execute();
    $tables = $stmt->fetchAll(\PDO::FETCH_COLUMN);

    return $tables;
  }

  public function rename_db($db, $new_name)
  {
    $this->create_db($new_name);

    // move all tables in $db to $new_name
    $tables = $this->get_all_tables_from_db($db, false);
    foreach ($tables as $table) {
      $this->conn->exec("RENAME TABLE $db.$table TO $new_name.$table");
    }

    $this->delete_db($db);
  }

  public function get_db_user($db, $email)
  {
    $stmt = $this->conn->prepare("SELECT role FROM $db.users WHERE email = ?");
    $stmt->execute([$email]);

    return $stmt->fetchColumn();
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

  public function create_table($db, $table, $fields, $pk_index)
  {
    $types = [
      "text" => "VARCHAR(255)",
      "number" => "INT",
    ];

    $fields_str = preg_replace("/, $/", "", array_reduce($fields, function ($acc, $field) use ($types) {
      return $acc . $field["name"] . " " . $types[$field["type"]] . " NOT NULL, ";
    }, ""));

    $statement = "CREATE TABLE $db.$table ( $fields_str, PRIMARY KEY (" . $fields[$pk_index]["name"] . ") )";

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

  // function relies on the fact that the fields in $record are preserved on the correct order
  // they've been fetched using `SHOW COLUMNS`, looped in JS and then dynamically layered in the form
  public function create_record($db, $table, $record)
  {
    $stmt = $this->conn->prepare(
      "INSERT INTO $db.$table VALUES (" .
        preg_replace("/, $/", "", str_repeat("?, ", count($record))) .
        ")"
    );
    $stmt->execute(array_values($record));
  }

  public function delete_record($db, $table, $pk_name, $pk_value)
  {
    $stmt = $this->conn->prepare("DELETE FROM $db.$table WHERE $pk_name = ?");
    $stmt->execute([$pk_value]);
  }

  public function update_record($db, $table, $pk_name, $pk_value, $changed_fields)
  {
    $stmt = $this->conn->prepare(
      "UPDATE $db.$table SET " .
        preg_replace("/, $/", "", array_reduce(array_keys($changed_fields), function ($str, $key) {
          return $str . "$key = ?, ";
        }, "")) .
        " WHERE $pk_name = ?"
    );
    $stmt->execute([...array_values($changed_fields), $pk_value]);
  }
}
