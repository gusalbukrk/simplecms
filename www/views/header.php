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
  <!-- https://fontawesome.com/v5/docs/web/setup/host-font-awesome-yourself#using-web-fonts-with-css -->
  <link rel="stylesheet" href="../vendor/fortawesome/font-awesome/css/all.min.css">
  <title>simpletables.xyz</title>
</head>

<body class="container py-4">
  <header class="d-flex justify-content-between align-items-center mb-5">
    <h1 class="fs-3 mb-0 flex-grow-1">
      <a class="text-dark" href="https://simpletables.xyz">
        <img class="me-2" src="logo.svg" alt="icon" style="height: 50px;">
        simpletables.xyz
      </a>
    </h1>
    <div class="flex-grow-1 text-end">
      <!-- using absolute instead of relative links because of subdomains -->
      <? if (isset($_SESSION["user"])) : ?>
        <span id="user" class="me-4"><?= $_SESSION["user"] ?></span>
        <a class="fs-5 fw-bold" href="https://simpletables.xyz/logout">Log out</a>
      <? else : ?>
        <a class="fs-5 me-4 fw-bold" href="https://simpletables.xyz/login">Log in</a>
        <a class="fs-5 fw-bold" href="https://simpletables.xyz/signup">Sign up</a>
      <? endif; ?>
    </div>
  </header>
