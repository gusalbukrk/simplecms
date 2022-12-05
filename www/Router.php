<?php

require_once __DIR__ . "/Controller.php";

class Router
{
  protected $routes = [];

  // get all routes
  public function get()
  {
    return $this->routes;
  }

  // add route to routes array
  public function add($name, $subdomain, $path)
  {
    if ($subdomain == "*") $subdomain = "[\w-]+\."; // * => wildcard subdomain

    array_push($this->routes, ["name" => $name, "regex" => "/^{$subdomain}simpletables.xyz\/{$path}$/"]);
  }

  // find route that matches current url
  public function match()
  {
    $url = "$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]"; // current URL

    foreach ($this->routes as $route) {
      if (preg_match($route["regex"], $url, $matches)) {
        return $route;
      }
    }

    return NULL;
  }

  // instantiate controller and run desired method by using route name
  public function dispatch()
  {
    $controller = new Controller();

    $route = $this->match();
    $fn = preg_replace("/-/", "_", $route["name"]); // replace slash with underline

    if (is_null($route) || !is_callable([$controller, $fn])) {
      $controller->page_not_found();
    } else {
      $controller->{$fn}();
    }
  }
}
