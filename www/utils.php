<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php';

// PHP `headers` function is used to redirect but won't work after HTML output
// https://stackoverflow.com/a/8028987
function redirect($url)
{
  echo "<script> location.replace(\"$url\"); </script>";
}

function generate_password()
{
  $chars = str_split(
    "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789!@#$%^&*()",
  );
  shuffle($chars);

  $password = "";
  foreach (array_rand($chars, 16) as $k) $password .= $chars[$k];

  return $password;
}

function send_email($to, $subject, $body, $alt)
{
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
  $mail->addAddress($to);

  $mail->Subject = $subject;
  $mail->Body = $body;
  $mail->AltBody = $alt; // $alt = message to show if email client doesn't support html

  return $mail->send();
}
