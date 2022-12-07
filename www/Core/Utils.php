<?php

// used in send_email method
use PHPMailer\PHPMailer\PHPMailer;

class Utils
{
  // get password stored in docker secret
  public static function get_password()
  {
    $path = "/run/secrets/password"; // docker secrets location

    $f = fopen($path, "r");
    $pw = preg_replace("/\s+$/", "", fread($f, filesize($path))); // remove trailing space if any
    fclose($f);

    return $pw;
  }

  // both functions are written in JavaScript to circumvent PHP's "header already sent" error
  public static function redirect($url)
  {
    echo "<script> location.replace(\"$url\"); </script>";
  }
  //
  public static function change_page_title($page)
  {
    echo "<script> document.title = '$page · simpletables.xyz'; </script>";
    // echo "<script> document.title = 'simpletables.xyz · $page'; </script>";
  }

  // generate random password
  public static function generate_password($length = 16)
  {
    $chars = str_split(
      "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789!@#$%^&*()",
    );
    shuffle($chars);

    $password = "";
    foreach (array_rand($chars, $length) as $k) $password .= $chars[$k];

    return $password;
  }

  // https://www.smtp2go.com/setupguide/php_mailer/
  public static function send_email($to, $subject, $body, $alt)
  {
    require "vendor/autoload.php";

    $mail = new PHPMailer();

    $mail->isSMTP();
    $mail->Host = "mail.smtp2go.com";
    $mail->Port = "2525";
    $mail->SMTPAuth = true;
    $mail->SMTPSecure = "tls";
    $mail->Username = "simpletables";
    $mail->Password = self::get_password();

    $mail->setFrom("admin@simpletables.xyz", "simpletables.xyz");
    $mail->addAddress($to);

    $mail->Subject = $subject;
    $mail->Body = $body;
    $mail->AltBody = $alt; // $alt = message to show if email client doesn't support html

    return $mail->send();
  }
}
