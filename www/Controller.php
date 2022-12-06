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

  public function signup()
  {
    $this->view->signup();

    // must be placed after view otherwise session wouldn't yet have been started
    if (isset($_SESSION["user"])) Utils::redirect("/");

    if ($_POST["action"] == "Sign up") {
      $email = $_POST["email"];
      $password = $_POST["password"];

      try {
        if ($this->model->create_user($email, $password)) Utils::redirect("/");
        throw new Exception("user creation failed");
      } catch (Exception $e) {
        exit("<b>Couldn't sign up</b>: " . $e->getMessage() . ".");
      }
    }
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
            throw new Exception("wrong password");
          }
        } else {
          throw new Exception("user not found");
        }
      } catch (Exception $e) {
        exit("<b>Couldn't log in</b>: " . $e->getMessage());
      }
    }
  }

  public function logout()
  {
    $this->view->logout();

    if (isset($_SESSION["user"])) session_unset();

    Utils::redirect("/");
  }

  public function reset_password()
  {
    $this->view->reset_password();

    // must be placed after view otherwise session wouldn't yet have been started
    if (isset($_SESSION["user"])) Utils::redirect("/");

    if ($_POST["action"] == "Reset password") {
      $email = $_POST["email"];

      try {
        $user = $this->model->get_user_by_email($email);

        if (is_null($user)) throw new Exception("user not found");
      } catch (Exception $e) {
        exit("<b>Couldn't fetch user</b>: " . $e->getMessage() . ".");
      }

      try {
        $password = Utils::generate_password();

        if (
          !$this->model->update_user_password($email, $password)
        ) throw new Exception("update failed");
      } catch (Exception $e) {
        exit("<b>Couldn't update user's password</b>: " . $e->getMessage() . ".");
      }

      // send email containing new password
      $sent = Utils::send_email(
        $email,
        "Your new password",
        "<code>$password</code>",
        $password,
      );

      if ($sent) Utils::redirect("login");
      exit("Couldn't send email.");
    }
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
