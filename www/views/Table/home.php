<?php if (!isset($_SESSION["user"])) : ?>
  <h4>Login or sign up to create tables</h4>
<?php else : ?>
  <h4 class="mb-3">Create database</h4>
  <form class="mb-5" method="post">
    <input class="border-dark border-opacity-75 rounded me-2" type="text" name="db" style="padding: 3px" pattern="\w+" required>
    <input class="btn btn-primary" type="submit" name="action" value="Create">
  </form>
  <h4 class="mb-3">Databases</h4>
  <?php if ($dbs) : ?>
    <table class="table table-hover container m-0 max-w-350">
      <thead>
        <tr class="row">
          <th class="col-4">Name</th>
          <th class="col-4">Role</th>
          <th class="col-2"></th>
          <th class="col-2"></th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($dbs as $db => $role) : ?>
          <tr class="row">
            <td class="col-4 d-flex">
              <form class="me-2" method="post" style="width: 66.6%;">
                <input type="hidden" name="db" value="<?= $db ?>">
                <input id="nameInput" class="w-100 border-0 bg-transparent" type="text" name="name" value="<?= $db ?>" readonly>
                <input type="hidden" name="action" value="Rename">
              </form>
              <a class="text-center" href="https://<?= $db ?>.simpletables.xyz" style="width: 33.3%;">
                <i class="fa-solid fa-arrow-up-right-from-square text-primary"></i>
              </a>
            </td>
            <td class="col-4"><?= strtolower($role->name) ?></td>
            <td class="col-2">
              <button id="renameButton" class="btn m-0 p-0 border-0">
                <i class="fa-solid fa-pen-to-square"></i>
              </button>
            </td>
            <td class="col-2 text-center">
              <form method="post">
                <button class="btn m-0 p-0 border-0" name="action" value="Remove">
                  <input type="hidden" name="db" value="<?= $db ?>">
                  <i class="fa-solid fa-trash text-danger"></i>
                </button>
              </form>
            </td>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  <?php else : ?>
    <p class="fw-bold">No databases found.</p>
  <? endif; ?>
<?php endif; ?>
<script>
  const nameInput = document.getElementById("nameInput");

  nameInput.addEventListener('focusout', e => {
    nameInput.setAttribute('readonly', '');
  });

  document.getElementById("renameButton").addEventListener('click', e => {
    nameInput.removeAttribute('readonly');
    nameInput.focus();
    nameInput.select();
  });
</script>
