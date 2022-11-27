<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>simpleCMS</title>
</head>

<body>
  <?php
  $domain = explode(".", $_SERVER['HTTP_HOST']);

  // allow only one subdomain level
  if (count($domain) > 3) {
    require_once __DIR__ . '/views/404.php';
    exit();
  }

  echo var_dump($domain) . "</br>";
  $path = $_SERVER['REQUEST_URI'];
  $subdomain = (count($domain) == 2 || $domain[0] == "www") ? "" : $domain[0];
  echo "path: $path</br>";
  echo "subdomain: $subdomain</br>";

  switch ($path) {
    case "":
    case "/":
      require_once __DIR__ . '/views/index.php';
      break;
    case "/about":
      require_once __DIR__ . '/views/about.php';
      break;
    default:
      http_response_code(404);
      require_once __DIR__ . '/views/404.php';
  }

  ?>
</body>

</html>