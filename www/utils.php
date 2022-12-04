<?php

// PHP `headers` function is used to redirect but won't work after HTML output
// https://stackoverflow.com/a/8028987
function redirect($url)
{
  echo "<script> location.replace(\"$url\"); </script>";
}

// password is used for both MySQL root user & SMTP2GO simpletables user
function get_password()
{
  $path = "/run/secrets/password"; // docker secrets location

  $f = fopen($path, "r");
  $pw = preg_replace("/\s+$/", "", fread($f, filesize($path))); // remove trailing space if any
  fclose($f);

  return $pw;
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
  require "vendor/autoload.php";

  // https://www.smtp2go.com/setupguide/php_mailer/
  $mail = new PHPMailer();

  $mail->isSMTP();
  $mail->Host = "mail.smtp2go.com";
  $mail->Port = "2525";
  $mail->SMTPAuth = true;
  $mail->SMTPSecure = "tls";
  $mail->Username = "simpletables";
  $mail->Password = get_password();

  $mail->setFrom("admin@simpletables.xyz", "simpletables.xyz");
  $mail->addAddress($to);

  $mail->Subject = $subject;
  $mail->Body = $body;
  $mail->AltBody = $alt; // $alt = message to show if email client doesn't support html

  return $mail->send();
}

function get_user($email)
{
  global $conn;

  $stmt = $conn->prepare("SELECT * FROM simpletables.user WHERE email = ?");
  $stmt->execute([$email]);
  $user = $stmt->fetch(PDO::FETCH_ASSOC);

  return $user;
}

function update_password($email, $password)
{
  global $conn;

  $stmt = $conn->prepare("UPDATE simpletables.user SET password = ? WHERE email = ?");
  $stmt->execute([password_hash($password, PASSWORD_DEFAULT), $email]);
  $stmt->rowCount();
}

// return array containing all databases except defaults & simpletables
function get_dbs()
{
  global $conn;

  $except = ["information_schema", "mysql", "performance_schema", "sys", "simpletables"];

  $dbs = [];

  try {
    $stmt = $conn->prepare("SHOW DATABASES");
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

function print_dbs_list()
{
  global $url;

  echo "<ul>";

  $tld = end($url["host"]);

  $dbs = get_dbs();
  foreach ($dbs as $db) {
    echo "<li><a href=\"http://$db.simpletables.$tld\">$db</a></li>";
  }

  echo "</ul>";
}

function print_dbs_table()
{
  global $url;

  echo "<table class=\"table table-bordered border-secondary\" style=\"width: 200px;\">";
  echo "<tr><th>Database</th></tr>";

  $tld = end($url["host"]);

  $dbs = get_dbs();
  foreach ($dbs as $db) {
    echo "<tr><td><a href=\"http://$db.simpletables.$tld/admin\">$db</a></td></tr>";
  }

  echo "</table>";
}

function change_page_title($page)
{
  echo "<script> document.title = 'simpletables.xyz - $page'; </script>";
}

function get_current_url()
{
  $url = parse_url(
    (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") .
      "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]"
  );

  $url["path"] = preg_replace("/^\//", "", $url["path"]); // remove leading slash from path
  $url["host"] = explode(".", $url["host"]);
  parse_str($url["query"], $url["parameters"]); // breakdown query string into array of parameters

  return $url;
}

function create_user($email, $password)
{
  global $conn;

  try {
    $stmt = $conn->prepare("INSERT INTO simpletables.user (email, password) VALUES (?, ?)");
    $stmt->execute([$email, password_hash($password, PASSWORD_DEFAULT)]);

    if ($stmt->rowCount() == 1)
      echo "<p class=\"alert alert-success alert-dismissible fade show max-w-350 mx-auto mb-4\"><b>User created</b><button type=\"button\" class=\"btn-close\" data-bs-dismiss=\"alert\"></button></p>";
  } catch (PDOException $e) {
    echo "<p class=\"alert alert-danger alert-dismissible fade show max-w-350 mx-auto mb-4\"><b>Couldn't create user</b>: " . $e->getMessage() . "<button type=\"button\" class=\"btn-close\" data-bs-dismiss=\"alert\"></button></p>";
  }
}
