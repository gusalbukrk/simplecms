<?php

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

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;
//
function send_email($to, $subject, $body, $alt)
{
  require 'vendor/autoload.php';

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

function get_user($email)
{
  global $conn;

  $stmt = $conn->prepare("SELECT * FROM simplecms.users WHERE email = ?");
  $stmt->execute([$email]);
  $user = $stmt->fetch(PDO::FETCH_ASSOC);

  return $user;
}

function update_password($email, $password)
{
  global $conn;

  $stmt = $conn->prepare("UPDATE simplecms.users SET password = ? WHERE email = ?");
  $stmt->execute([password_hash($password, PASSWORD_DEFAULT), $email]);
  $stmt->rowCount();
}

// return array containing all databases except defaults & simplecms
function get_dbs()
{
  global $conn;

  $except = ["information_schema", "mysql", "performance_schema", "sys", "simplecms"];

  $dbs = [];

  try {
    $stmt = $conn->prepare('SHOW DATABASES');
    $stmt->execute();


    while (($db = $stmt->fetchColumn()) !== false) {
      if (!in_array($db, $except)) {

        array_push($dbs, $db);
      }
    }
  } catch (PDOException $e) {
    echo "<b>Couldn't fetch databases</b>: " . $e->getMessage();
  }

  return $dbs;
}

function get_tld()
{
  // 'online' on dev environment and 'site' on production environment
  return end(explode(".", $_SERVER["HTTP_HOST"]));
}

function print_dbs_list()
{
  echo "<ul>";

  $tld = get_tld();

  $dbs = get_dbs();
  foreach ($dbs as $db) {
    echo "<li><a href=\"http://$db.simplecms.$tld\">$db</a></li>";
  }

  echo "</ul>";
}

function print_dbs_table()
{
  echo "<table>";
  echo "<tr><th>Database</th></tr>";

  $tld = get_tld();

  $dbs = get_dbs();
  foreach ($dbs as $db) {
    echo "<tr><td><a href=\"http://$db.simplecms.$tld/admin\">$db</a></td></tr>";
  }

  echo "</table>";
}
