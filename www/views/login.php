<?php

change_page_title("Login");

if (isset($_SESSION["user"])) {
  redirect("admin");
}

if ($_POST["action"] == "Log in") {
  $email = $_POST["email"];
  $password = $_POST["password"];

  try {
    $user = get_user($email);

    if (password_verify($password, $user["password"])) {
      $_SESSION["user"] = $email;
      redirect("admin");
    } else {
      echo "Wrong password";
    }
  } catch (PDOException $e) {
    echo "<b>Couldn't fetch user</b>: " . $e->getMessage();
  }
}

if ($_POST["action"] == "Reset password") {
  $email = $_POST["email"];

  if (get_user($email)) { // false if user not found
    $password = generate_password();

    update_password($email, $password);

    $sent = send_email(
      $email,
      "Your new password",
      "<code>$password</code>",
      $password,
    );

    if ($sent) redirect("login");
    echo "Mailer error.\n";
  } else {
    echo "User not found.\n";
  }
}
?>

<?php if ($_GET["action"] == "reset-password") : ?>
  <form method="post">
    <label>E-mail: <input type="email" name="email" required></label>
    <input type="submit" name="action" value="Reset password">
  </form>

  <a href="login">Log in</a>

  <?php exit(); ?>
<?php endif; ?>

<form method="post">
  <div class="mb-3"><label>E-mail: <input type="email" name="email" required></label></div>
  <div class="mb-3"><label>Password: <input type="password" name="password" required></label></div>
  <input type="submit" class="btn btn-primary mb-3" name="action" value="Log in">
</form>

<a href="login?action=reset-password">Forgot your password?</a>
