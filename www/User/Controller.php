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
    if (isset($_SESSION["user"])) \Utils::redirect("/");

    \Utils::change_page_title("Sign up");
    $this->view->signup();

    if (isset($_POST["action"]) && $_POST["action"] == "Sign up") {
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
    if (isset($_SESSION["user"])) \Utils::redirect("/");

    $this->view->login();
    \Utils::change_page_title("Log in");

    if (isset($_POST["action"]) && $_POST["action"] == "Log in") {
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
    \Utils::change_page_title("Log out");
    $this->view->logout();

    if (isset($_SESSION["user"])) session_unset();

    \Utils::redirect("/");
  }

  protected function reset_password()
  {
    if (isset($_SESSION["user"])) \Utils::redirect("/");

    \Utils::change_page_title("Reset password");
    $this->view->reset_password();

    if (isset($_POST["action"]) && $_POST["action"] == "Reset password") {
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
