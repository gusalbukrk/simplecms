<?php
if (isset($_POST["logout"])) {
  unset($_SESSION["user"]);
}
?>

<?php if (isset($_SESSION["user"])) : ?>
  <p><b>user</b>: <?php echo $_SESSION["user"]; ?></p>

  <form method="post">
    <input type="submit" name="logout" value="Log out">
  </form>
<?php endif; ?>

<h1>dashboard</h1>

<?php

require_once __DIR__ . "/functions/dbs.php";

print_dbs_table();
