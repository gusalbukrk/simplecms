<?php

class Model
{
  protected $conn;

  function __construct()
  {
    try {
      $servername = "db";
      $username = "root";
      $password = Utils::get_password();
      $port = "3306";

      $options = [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
      ];

      $this->conn = new PDO("mysql:host=$servername;port=$port", $username, $password, $options);

      // echo "Connected successfully</br>";
    } catch (PDOException $e) {
      echo "<b>Couldn't connect to MySQL</b>: " . $e->getMessage();
    }
  }

  // return user found or null if no user found
  function get($email)
  {
    $stmt = $this->conn->prepare("SELECT * FROM simpletables.user WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    return $user === false ? null : $user;
  }

  // return true if user was updated, false otherwise
  function update($email, $password)
  {
    $stmt = $this->conn->prepare("UPDATE simpletables.user SET password = ? WHERE email = ?");
    return $stmt->execute([password_hash($password, PASSWORD_DEFAULT), $email]);
  }

  function create($email, $password)
  {
    $stmt = $this->conn->prepare("INSERT INTO simpletables.user (email, password) VALUES (?, ?)");
    return $stmt->execute([$email, password_hash($password, PASSWORD_DEFAULT)]);
  }
}
