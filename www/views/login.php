<?php

if (isset($_SESSION['user'])) {
  redirect("admin");
}

if (isset($_POST["email"]) && isset($_POST["password"])) {
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
?>

<form method="post">
  <label>E-mail: <input type="email" name="email" required></label>
  <label>Password: <input type="password" name="password" required></label>
  <input type="submit" name="login" value="Log in">
</form>