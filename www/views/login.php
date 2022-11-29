<?php

if (isset($_SESSION['user'])) {
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

  $user = get_user($email); // false if user not found

  if ($user) {
    $sent = send_email(
      $email,
      "Your new password",
      "<code>" . generate_password() . "</code>",
      generate_password(),
    );

    echo $sent ? "Email sent!\n" : "Mailer error: " . $mail->ErrorInfo;
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
  <label>E-mail: <input type="email" name="email" required></label>
  <label>Password: <input type="password" name="password" required></label>
  <input type="submit" name="action" value="Log in">
</form>

<a href="login?action=reset-password">Forgot your password?</a>