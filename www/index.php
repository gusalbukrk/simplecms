<?php
require_once __DIR__ . "/utils.php";

$url = get_current_url();

// allow only one subdomain level is allowed
if (count($url["host"]) > 3) {
  require_once __DIR__ . "/views/404.php";
  exit();
}

ini_set("session.cookie_domain", ".simplecms." . end($url["host"])); // allow session across subdomains
session_start();
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="vendor/twbs/bootstrap/dist/css/bootstrap.min.css">
  <link rel="stylesheet" href="style.css">
  <title>simpleCMS</title>
</head>

<body class="p-3">
  <?php

  try {
    $servername = "db";
    $username = "root";
    $password = get_db_password();
    $port = "3306";

    $options = [
      PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    ];

    $conn = new PDO("mysql:host=$servername;port=$port", $username, $password, $options);

    // echo "Connected successfully</br>";
  } catch (PDOException $e) {
    echo "<b>Couldn't connect to MySQL</b>: " . $e->getMessage();
  }

  switch ($url["path"]) {
    case "":
      require_once __DIR__ . "/views/index.php";
      break;
    case "admin":
      require_once __DIR__ . "/views/admin.php";
      break;
    case "login":
      require_once __DIR__ . "/views/login.php";
      break;
    case "about":
      require_once __DIR__ . "/views/about.php";
      break;
    default:
      http_response_code(404);
      require_once __DIR__ . "/views/404.php";
  }

  $conn = null;
  ?>
</body>

</html>
