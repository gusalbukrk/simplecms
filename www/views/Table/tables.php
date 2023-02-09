<?php if (!$exists) : ?>
  <h4>Database does not exist</h4>
<?php else : ?>
  <h4 class="mb-3 fw-normal">Database: <b><?= ucfirst($db) ?></b></h4>
  <h6 class="mb-4"><span class="fw-normal">Role</span>: <?= $role->name ?></h6>
  <?php if (!is_null($tables)) : ?>
    <table id="tables" class="table table-hover table-borderless container m-0 max-w-350 mb-3">
      <thead>
        <tr class="row mx-0">
          <th class="col-2"></th>
          <th class="col-6">Name</th>
          <th class="col-2"></th>
          <th class="col-2"></th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($tables as $table) : ?>
          <tr class="row mx-0">
            <td class="col-2">
              <a href="https://<?= $db ?>.simpletables.xyz/<?= $table ?>">
                <i class="fa-solid fa-up-right-from-square"></i>
              </a>
            </td>
            <td class="col-6">
              <form class="me-2" method="post">
                <input type="hidden" name="table" value="<?= $table ?>">
                <input type="hidden" name="role" value="<?= $role->name ?>">
                <input class="w-100 border-0 bg-transparent text-dark" type="text" name="new_name" value="<?= $table ?>" disabled>
                <input type="hidden" name="action" value="rename">
              </form>
            </td>
            <td class="col-2">
              <button class="btn m-0 p-0 border-0" name="action" value="rename" <?php if ($role->name !== "Admin") echo "hidden"; ?>>
                <i class="fa-solid fa-pen-to-square"></i>
              </button>
            </td>
            <td class="col-2 text-center">
              <form method="post">
                <input type="hidden" name="table" value="<?= $table ?>">
                <input type="hidden" name="role" value="<?= $role->name ?>">
                <button class="btn m-0 p-0 border-0" name="action" value="delete" <?php if ($role->name !== "Admin") echo "hidden"; ?>>
                  <i class="fa-solid fa-trash text-danger"></i>
                </button>
              </form>
            </td>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
    <script>
      // iterate through every row of the table used to list the databases
      // in order to add the following event: when one of the rename buttons is clicked,
      // focus and select the database name input of that respective row
      (function() {
        const tbody = document.querySelector('table#tables tbody');

        for (let i = 1; i <= tbody.children.length; i++) {
          const row = tbody.querySelector(`tr:nth-child(${i})`);

          const input = row.querySelector('td:nth-child(2) input[name="new_name"]');
          const button = row.querySelector('td:nth-child(3) button[value="rename"]');

          const inputValueBak = input.value;

          button.addEventListener('click', e => {
            console.log('clicked');
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
  <?php else : ?>
    <p class="fw-bold mb-3">No databases found.</p>
  <?php endif; ?>
  <form class="max-w-350 d-flex" method="post">
    <input class="form-control-sm flex-fill me-3 border-dark border-opacity-75 rounded" type="text" name="table" style="padding: 3px" pattern="\w+" required>
    <input class="btn btn-primary flex-fill btn-sm fw-bold" type="submit" name="action" value="create">
  </form>
<?php endif; ?>
