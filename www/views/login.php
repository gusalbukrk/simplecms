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
  // https://www.smtp2go.com/setupguide/php_mailer/
  $mail = new PHPMailer();

  $mail->isSMTP();
  $mail->Host = "mail.smtp2go.com";
  $mail->Port = "2525";
  $mail->SMTPAuth = true;
  $mail->SMTPSecure = "tls";

  $mail->Username = "simplecms";
  $mail->Password = "kp%@NcBWZRm547CR";

  $mail->setFrom("admin@simplecms.site", "simpleCMS");
  $mail->addAddress($_POST["email"]);
  $mail->Subject = "Your new password";
  $mail->Body = "This is the HTML message body <b>in bold!</b>";
  $mail->AltBody = "This is the body in plain text for non-HTML mail clients";

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