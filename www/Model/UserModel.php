<?php

require_once __DIR__ . "/BaseModel.php";

class UserModel extends BaseModel
{
  // return true on success, false otherwise
  function create($email, $password)
  {
    $stmt = $this->conn->prepare("INSERT INTO simpletables.user (email, password) VALUES (?, ?)");
    return $stmt->execute([$email, password_hash($password, PASSWORD_DEFAULT)]);
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
}
