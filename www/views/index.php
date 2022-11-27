<h1>index</h1>

<?php
// every MySQL has following databases by default
define("DEFAULT_DBS", ["information_schema", "mysql", "performance_schema", "sys"]);

$servername = "db";
$username = "root";
$password = "rootpw";
$port = "3306";

try {
  $conn = new PDO("mysql:host=$servername;port=$port", $username, $password);
  $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
  echo "Connected successfully</br>";

  $dbs = [];

  // populate $dbs with all non-default databases
  $stmt = $conn->prepare('SHOW DATABASES');
  $stmt->execute();
  while (($db = $stmt->fetchColumn()) !== false) {
    if (!in_array($db, DEFAULT_DBS)) array_push($dbs, $db);
  }

  // dev environment = 'online', production environment is 'site'
  $tld = end(explode(".", $_SERVER["HTTP_HOST"]));

  echo "<ul>";
  foreach ($dbs as $index => $db) {
    $address = "$db.simplecms.$tld";

    echo "<li><a href=\"http://$address\">$db</a></li>";
  }
  echo "</ul>";
} catch (PDOException $e) {
  echo "Connection failed: " . $e->getMessage();
}
