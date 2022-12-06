<?php
ini_set("session.cookie_domain", ".simpletables.xyz"); // allow session across subdomains
session_start();
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="../vendor/twbs/bootstrap/dist/css/bootstrap.min.css">
  <link rel="stylesheet" href="../style.css">
  <title>simpletables.xyz</title>
</head>

<body class="container pt-4">

  <header class="d-flex justify-content-between align-items-center mb-5">
    <h1 class="fs-3 mb-0 flex-grow-1"><a class="text-dark" href="https://simpletables.xyz">simpletables.xyz</a></h1>
    <div class="flex-grow-1 text-end">
      <?php
      if (isset($_SESSION["user"])) {
        echo "<span id=\"user\" class=\"me-4\">{$_SESSION["user"]}</span>";
        echo "<a class=\"fs-5 fw-bold\" href=\"/logout\">Log out</a>";
      } else {
        echo "<a class=\"fs-5 me-4 fw-bold\" href=\"/login\">Log in</a>";
        echo "<a class=\"fs-5 fw-bold\" href=\"/signup\">Sign up</a>";
      }
      ?>
    </div>
  </header>
