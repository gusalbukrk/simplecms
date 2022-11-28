<?php

// 'online' in dev environment, 'site' in production environment
function get_tld()
{
  return end(explode(".", $_SERVER["HTTP_HOST"]));
}

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
