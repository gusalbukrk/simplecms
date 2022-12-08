<?php

namespace User;

require_once __DIR__ . "/../Core/Controller.php";

require_once __DIR__ . "/Model.php";
require_once __DIR__ . "/View.php";

class Controller extends \Core\Controller
{
  public function __construct()
  {
    $this->model = new Model();
    $this->view = new View();
  }

  protected function signup()
  {
    $this->view->signup();
    \Utils::change_page_title("Sign up");

    // must be placed after view otherwise session wouldn't yet have been started
    if (isset($_SESSION["user"])) \Utils::redirect("/");

    if ($_POST["action"] == "Sign up") {
      $email = $_POST["email"];
      $password = $_POST["password"];

      try {
        if ($this->model->create($email, $password)) \Utils::redirect("/");
        throw new \Exception("user creation failed");
      } catch (\Exception $e) {
        exit("<b>Couldn't sign up</b>: " . $e->getMessage() . ".");
      }
    }
  }

  protected function login()
  {
    $this->view->login();
    \Utils::change_page_title("Log in");

    // must be placed after view otherwise session wouldn't yet have been started
    if (isset($_SESSION["user"])) \Utils::redirect("/");

    if ($_POST["action"] == "Log in") {
      $email = $_POST["email"];
      $password = $_POST["password"];

      try {
        $user = $this->model->get($email);

        if (isset($user)) {
          if (password_verify($password, $user["password"])) {
            $_SESSION["user"] = $email;
            \Utils::redirect("/");
          } else {
            throw new \Exception("wrong password");
          }
        } else {
          throw new \Exception("user not found");
        }
      } catch (\Exception $e) {
        exit("<b>Couldn't log in</b>: " . $e->getMessage());
      }
    }
  }

  protected function logout()
  {
    $this->view->logout();
    \Utils::change_page_title("Log out");

    if (isset($_SESSION["user"])) session_unset();

    \Utils::redirect("/");
  }

  protected function reset_password()
  {
    $this->view->reset_password();
    \Utils::change_page_title("Reset password");

    // must be placed after view otherwise session wouldn't yet have been started
    if (isset($_SESSION["user"])) \Utils::redirect("/");

    if ($_POST["action"] == "Reset password") {
      $email = $_POST["email"];

      try {
        $user = $this->model->get($email);

        if (is_null($user)) throw new \Exception("user not found");
      } catch (\Exception $e) {
        exit("<b>Couldn't fetch user</b>: " . $e->getMessage() . ".");
      }

      try {
        $password = \Utils::generate_password();

        if (
          !$this->model->update($email, $password)
        ) throw new \Exception("update failed");
      } catch (\Exception $e) {
        exit("<b>Couldn't update user's password</b>: " . $e->getMessage() . ".");
      }

      // send email containing new password
      $sent = \Utils::send_email(
        $email,
        "Your new password",
        "<code>$password</code>",
        $password,
      );

      if ($sent) \Utils::redirect("login");
      exit("Couldn't send email.");
    }
  }
}
