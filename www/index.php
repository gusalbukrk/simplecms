<?php
require_once __DIR__ . "/utils.php";

ini_set("session.cookie_domain", ".simplecms." . get_tld()); // allow session across subdomains

session_start();
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>simpleCMS</title>
</head>

<body>
  <?php

  $domain = explode(".", $_SERVER["HTTP_HOST"]);

  // allow only one subdomain level
  if (count($domain) > 3) {
    require_once __DIR__ . "/views/404.php";
    exit();
  }

  $subdomain = (count($domain) == 2 || $domain[0] == "www") ? "" : $domain[0];
  [$path, $query] = explode("?", preg_replace("/^\//", "", $_SERVER["REQUEST_URI"]));

  // echo var_dump($domain) . "</br>";
  // echo "subdomain: $subdomain</br>";
  // echo "path: $path</br>";
  // echo "query: $query</br>";

  try {
    $servername = "db";
    $username = "root";
    $password = "rootpw";
    $port = "3306";

    $options = [
      PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    ];

    $conn = new PDO("mysql:host=$servername;port=$port", $username, $password, $options);

    // echo "Connected successfully</br>";
  } catch (PDOException $e) {
    echo "<b>Couldn't connect to MySQL</b>: " . $e->getMessage();
  }

  switch ($path) {
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
