<?php

class View
{

  public function __call($method, $args)
  {
    require_once __DIR__ . "/views/header.php";
    call_user_func_array(array($this, $method), $args);
    require_once __DIR__ . "/views/footer.php";
  }

  protected function home()
  {
    require_once __DIR__ . "/views/home.php";
  }

  protected function signup()
  {
    require_once __DIR__ . "/views/signup.php";
  }

  protected function login()
  {
    require_once __DIR__ . "/views/login.php";
  }

  protected function reset_password()
  {
    require_once __DIR__ . "/views/reset-password.php";
  }

  protected function database()
  {
    require_once __DIR__ . "/views/database.php";
  }

  protected function table()
  {
    require_once __DIR__ . "/views/table.php";
  }

  protected function page_not_found()
  {
    require_once __DIR__ . "/views/not-found.php";
  }
}
