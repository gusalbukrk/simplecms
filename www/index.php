<?php

require_once __DIR__ . "/Router.php";

$router = new Router();

$router->add("home", NULL, NULL);
$router->add("login", NULL, "login");
$router->add("signup", NULL, "signup");
$router->add("reset-password", NULL, "reset-password");
$router->add("database", "*", NULL);
$router->add("table", "*", "[\w-]+(?:\?(?<parameters>(?:[\w-]+=[\w-]+&?)+))?");

// echo "<pre>";
// print_r($router->get_routes());
// echo "</pre>";

// $router->match();
$router->dispatch();
