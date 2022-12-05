<?php

require_once __DIR__ . "/Model.php";
require_once __DIR__ . "/View.php";

// every method in the controller has (roughly) the same name as its corresponding route
// "roughly" because slashes are converted to underlines (function names can't contain slashes)
class Controller
{
  public function home()
  {
    echo "home";
  }

  public function login()
  {
    echo "login";
  }

  public function signup()
  {
    echo "signup";
  }

  public function reset_password()
  {
    echo "reset-password";
  }

  public function database()
  {
    echo "database";
  }

  public function table()
  {
    echo "table";
  }

  public function page_not_found()
  {
    echo "page_not_found";
  }
}
