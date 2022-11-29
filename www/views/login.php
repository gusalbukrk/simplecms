<?php

if (isset($_SESSION['user'])) {
  redirect("admin");
}

if ($_POST["action"] == "Log in") {
  $email = $_POST["email"];
  $password = $_POST["password"];

  try {
    $stmt = $conn->prepare("SELECT * FROM simplecms.users WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    $verify = password_verify($password, $user["password"]);

    if ($verify) {
      $_SESSION["user"] = $email;
      redirect("admin");
    } else {
      echo "Wrong password";
    }
  } catch (PDOException $e) {
    echo "<b>Couldn't fetch databases</b>: " . $e->getMessage();
  }
}

if ($_POST["action"] == "Reset password") {
  $success = send_email(
    $_POST["email"],
    "Your new password",
    "<code>" . generate_password() . "</code>",
    generate_password(),
  );

  if ($success) {
    echo "Message sent!\n";
  } else {
    echo "Mailer Error: " . $mail->ErrorInfo;
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