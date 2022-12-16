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
    <table id="dbs" class="table table-hover container m-0 max-w-350">
      <thead>
        <tr class="row mx-0">
          <th class="col-2"></th>
          <th class="col-3">Name</th>
          <th class="col-3">Role</th>
          <th class="col-2"></th>
          <th class="col-2"></th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($dbs as $db => $role) : ?>
          <tr class="row mx-0">
            <td class="col-2">
              <a href="https://<?= $db ?>.simpletables.xyz">
                <i class="fa-solid fa-up-right-from-square"></i>
              </a>
            </td>
            <td class="col-3">
              <form class="me-2" method="post">
                <input type="hidden" name="db" value="<?= $db ?>">
                <input class="w-100 border-0 bg-transparent text-dark" type="text" name="name" value="<?= $db ?>" disabled>
                <input type="hidden" name="action" value="Rename">
              </form>
            </td>
            <td class="col-3"><?= strtolower($role->name) ?></td>
            <td class="col-2">
              <button class="btn m-0 p-0 border-0" name="action" value="Rename">
                <i class="fa-solid fa-pen-to-square"></i>
              </button>
            </td>
            <td class="col-2 text-center">
              <form method="post">
                <button class="btn m-0 p-0 border-0" name="action" value="Delete">
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
  <script>
    // iterate through every row of the table used to list the databases
    // in order to add the following event: when one of the rename buttons is clicked,
    // focus and select the database name input of that respective row
    (function() {
      const tbody = document.querySelector('table#dbs tbody');

      for (let i = 1; i <= tbody.children.length; i++) {
        const row = tbody.querySelector(`tr:nth-child(${i})`);

        const input = row.querySelector('td:nth-child(2) form input[name="name"]');
        const button = row.querySelector('td:nth-child(4) button[value="Rename"]');

        const inputValueBak = input.value;

        button.addEventListener('click', e => {
          input.removeAttribute('disabled');
          input.focus();
          input.select();
        });

        // additionally, handle input focusout
        input.addEventListener('focusout', e => {
          input.setAttribute('disabled', '');

          // if focusout, form wasn't submitted; therefore, name hasn't changed
          input.value = inputValueBak;
        });
      }
    })()
  </script>
<?php endif; ?>
