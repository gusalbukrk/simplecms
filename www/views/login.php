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
  $success = mail($_POST["email"], "Reset password", "New password: kfdsi@3i");

  echo $success ? 'sent' : 'not sent';
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