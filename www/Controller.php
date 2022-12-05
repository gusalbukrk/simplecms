<?php

require_once __DIR__ . "/Utils.php";

require_once __DIR__ . "/Model.php";
require_once __DIR__ . "/View.php";

// every method in the controller has (roughly) the same name as its corresponding route
// "roughly" because slashes are converted to underlines (function names can't contain slashes)
class Controller
{
  protected $model, $view;

  function __construct()
  {
    $this->model = new Model();
    $this->view = new View();
  }

  public function home()
  {
    $this->view->home();
  }

  public function login()
  {
    $this->view->login();

    // must be placed after view otherwise session wouldn't yet have been started
    if (isset($_SESSION["user"])) Utils::redirect("/");

    if ($_POST["action"] == "Log in") {
      $email = $_POST["email"];
      $password = $_POST["password"];

      try {
        $user = $this->model->get_user_by_email($email);

        if (isset($user)) {
          if (password_verify($password, $user["password"])) {
            $_SESSION["user"] = $email;
            Utils::redirect("/");
          } else {
            throw new Exception("Wrong password");
          }
        } else {
          throw new Exception("User doesn't exist");
        }
      } catch (Exception $e) {
        echo "<b>Couldn't fetch user</b>: " . $e->getMessage();
      }
    }
  }

  public function signup()
  {
    $this->view->signup();
  }

  public function reset_password()
  {
    $this->view->reset_password();
  }

  public function database()
  {
    $this->view->database();
  }

  public function table()
  {
    $this->view->table();
  }

  public function page_not_found()
  {
    $this->view->page_not_found();
  }
}
