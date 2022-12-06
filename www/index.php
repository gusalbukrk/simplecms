<?php

require_once __DIR__ . "/Router.php";

$router = new Router();

$router->add("core", "home", NULL, NULL);
$router->add("core", "database", "*", NULL);
$router->add("core", "table", "*", "[\w-]+(?:\?(?<parameters>(?:[\w-]+=[\w-]+&?)+))?");
//
$router->add("user", "signup", NULL, "signup");
$router->add("user", "login", NULL, "login");
$router->add("user", "logout", NULL, "logout");
$router->add("user", "reset-password", NULL, "reset-password");

$router->dispatch();
