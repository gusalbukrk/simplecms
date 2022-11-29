<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php';

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
  $mail = new PHPMailer();

  $mail->isSMTP();
  $mail->Host = "smtp.office365.com";
  $mail->Port = 587;
  $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
  $mail->SMTPAuth = true;

  $mail->Username = "simplecms@outlook.com";
  $mail->Password = "Tq8oT%ER";

  $mail->setFrom("simplecms@outlook.com", "simpleCMS");
  $mail->addAddress("gusalbukrk@gmail.com");
  $mail->Subject = "simpleCMS - Your new password";
  $mail->Body = "This is the HTML message body <b>in bold!</b>";
  $mail->AltBody = "This is the body in plain text for non-HTML mail clients";

  //send the message, check for errors
  if (!$mail->send()) {
    echo "Mailer Error: " . $mail->ErrorInfo;
  } else {
    echo "Message sent!\n";
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