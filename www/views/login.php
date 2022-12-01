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
  <form class="container max-w-350" method="post">
    <div class="row mb-3">
      <label class="px-0 mb-2 fw-bold" for="email">Email</label>
      <input type="email" name="email" id="email" required>
    </div>
    <div class="row mb-3">
      <input class="btn btn-primary" type="submit" name="action" value="Reset password">
    </div>
    <p class="row text-end">
      <a class="px-0" href="login">Log in</a>
    </p>
  </form>

  <?php exit(); ?>

<?php endif; ?>

<form class="container max-w-350" method="post">
  <div class="row mb-3">
    <label class="px-0 mb-2 fw-bold" for="email">Email</label>
    <input type="email" name="email" id="email" required>
  </div>
  <div class="row mb-3">
    <label class="px-0 mb-2 fw-bold" for="password">Password</label>
    <input type="password" name="password" id="password" required>
  </div>
  <div class="row mb-3">
    <input class="btn btn-primary" type="submit" name="action" value="Log in">
  </div>
  <p class="row text-end">
    <a class="px-0" href="login?action=reset-password">Forgot your password?</a>
  </p>
</form>
