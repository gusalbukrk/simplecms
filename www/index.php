<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Document</title>
</head>

<body>
  <?php
  $req = $_SERVER['REQUEST_URI'];

  echo $req . "</br>";
  echo $_SERVER['HTTP_HOST'] . "</br>";

  switch ($req) {
    case "":
    case "/":
      require __DIR__ . '/views/index.php';
      break;
    case "/about":
      require __DIR__ . '/views/about.php';
      break;
    default:
      http_response_code(404);
      require __DIR__ . '/views/404.php';
  }

  ?>
</body>

</html>