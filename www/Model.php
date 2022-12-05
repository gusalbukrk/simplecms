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

  function get_user_by_email($email)
  {
    $stmt = $this->conn->prepare("SELECT * FROM simpletables.user WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    return $user === false ? null : $user;
  }
}
