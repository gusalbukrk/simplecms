<?php

change_page_title("Admin");

if (isset($_POST["logout"])) {
  unset($_SESSION["user"]);
  redirect("/");
}

if (!isset($_SESSION["user"])) {
  redirect("login");
}
?>

<?php if (isset($_SESSION["user"])) : ?>
  <p><b>user</b>: <?php echo $_SESSION["user"]; ?></p>

  <form method="post" class="mb-3">
    <input type="submit" name="logout" value="Log out">
  </form>
<?php endif; ?>

<h1>dashboard</h1>

<?php

print_dbs_table();
