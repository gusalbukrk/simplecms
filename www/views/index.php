<h1>index</h1>

<?php
// return array containing all non-default databases
function get_dbs()
{
  global $conn;

  // every MySQL has following databases by default
  define("DEFAULT_DBS", ["information_schema", "mysql", "performance_schema", "sys"]);

  $dbs = [];

  try {
    $stmt = $conn->prepare('SHOW DATABASES');
    $stmt->execute();


    while (($db = $stmt->fetchColumn()) !== false) {
      if (!in_array($db, DEFAULT_DBS)) {

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
  echo "<ul>";

  $dbs = get_dbs();

  // 'online' in dev environment, 'site' in production environment
  $tld = end(explode(".", $_SERVER["HTTP_HOST"]));

  foreach ($dbs as $db) {
    echo "<li><a href=\"http://$db.simplecms.$tld\">$db</a></li>";
  }

  echo "</ul>";
}

print_dbs_list();
