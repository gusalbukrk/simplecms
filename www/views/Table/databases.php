<h2 class="fs-4 mb-5">
  <span class="p-1 me-1 bg-lighter-blue"><?= explode("'", $title)[0] ?></span>'<?= explode("'", $title)[1] ?>
</h2>
<?php if (empty($dbs)) : ?>
  <p class="fw-bold mb-5">No databases found.</p>
<?php else : ?>
  <table id="dbs" class="table table-hover container mb-5">
    <thead>
      <tr class="row g-0">
        <th class="col"></th>
        <th class="col">Name</th>
        <th class="col">Role</th>
      </tr>
    </thead>
    <tbody>
      <?php foreach ($dbs as $db => $role) : ?>
        <tr class="row g-0">
          <td class="col d-flex justify-content-center align-items-center">
            <a href="https://<?= $db ?>.simpletables.xyz" class="me-3">
              <i class="fa-solid fa-up-right-from-square fs-1dot125"></i>
            </a>
            <button class="btn p-0 me-3 border-0" name="action" value="rename" <?php if ($role->name !== "Admin") echo "hidden"; ?>>
              <i class="fa-solid fa-pen-to-square text-orange fs-1dot125"></i>
            </button>
            <form method="post">
              <input type="hidden" name="db" value="<?= $db ?>">
              <input type="hidden" name="role" value="<?= $role->name ?>">
              <button class="btn m-0 p-0 border-0" name="action" value="delete" <?php if ($role->name !== "Admin") echo "hidden"; ?>>
                <i class="fa-solid fa-trash text-danger fs-1dot125"></i>
              </button>
            </form>
          </td>
          <td class="col">
            <form class="me-2" method="post">
              <input type="hidden" name="db" value="<?= $db ?>">
              <input type="hidden" name="role" value="<?= $role->name ?>">
              <input class="w-100 border-0 bg-transparent text-dark" type="text" name="new_name" value="<?= $db ?>" disabled>
              <input type="hidden" name="action" value="rename">
            </form>
          </td>
          <td class="col"><?= strtolower($role->name) ?></td>
        </tr>
      <?php endforeach; ?>
    </tbody>
  </table>
  <script>
    // iterate through every row of the table used to list the databases
    // in order to add the following event: when one of the rename buttons is clicked,
    // focus on the database name input of that respective row
    document.querySelectorAll('table#dbs tbody tr').forEach(row => {
      const button = row.querySelector('td:nth-child(1) button[value="rename"]');
      const input = row.querySelector('td:nth-child(2) input[name="new_name"]');

      const inputValueBak = input.value;

      button.addEventListener('click', e => {
        input.removeAttribute('disabled');
        input.focus();
        input.select();
      });

      // handle input focusout
      input.addEventListener('focusout', e => {
        input.setAttribute('disabled', '');

        // if focusout, form wasn't submitted; therefore, name hasn't changed
        input.value = inputValueBak;
      });
    })
  </script>
<?php endif; ?>
<form class="max-w-350" method="post">
  <div class="mb-3">
    <label for="dbNameInput" class="form-label fs-dot9 fw-bold text-dark-gray">Database name</label>
    <input type="text" id="dbNameInput" name="db" class="form-control border-dark border-opacity-50 border-width-2" pattern="\w+" required>
  </div>
  <input class="btn btn-success fw-bold" type="submit" name="action" value="create">
</form>