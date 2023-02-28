<h2 class="fs-4 mb-5">
  <a href="https://simpletables.xyz" class="me-3"><i class="fa-solid fa-circle-arrow-left fs-3"></i></a>
  <span class="p-1 me-1 bg-lighter-blue"><?= explode("'", $title)[0] ?></span>'<?= explode("'", $title)[1] ?>
</h2>
<?php if (!$db_exists) : ?>
  <p class="fw-bold">Database doesn't exist.</p>
<?php else : ?>
  <?php if (empty($tables)) : ?>
    <p class="fw-bold mb-5">No tables found.</p>
  <?php else : ?>
    <table id="tables" class="table table-hover container mb-5">
      <thead>
        <tr class="row g-0">
          <th class="col"></th>
          <th class="col">Name</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($tables as $table) : ?>
          <tr class="row g-0">
            <td class="col d-flex justify-content-center align-items-center">
              <a href="https://<?= $db ?>.simpletables.xyz/<?= $table ?>" class="me-3">
                <i class="fa-solid fa-up-right-from-square fs-1dot125"></i>
              </a>
              <button class="btn m-0 p-0 border-0 me-3" name="action" value="rename" <?php if ($role->name !== "Admin") echo "hidden"; ?>>
                <i class="fa-solid fa-pen-to-square text-orange fs-1dot125"></i>
              </button>
              <form method="post">
                <input type="hidden" name="table" value="<?= $table ?>">
                <input type="hidden" name="role" value="<?= $role->name ?>">
                <button class="btn m-0 p-0 border-0" name="action" value="delete" <?php if ($role->name !== "Admin") echo "hidden"; ?>>
                  <i class="fa-solid fa-trash text-danger fs-1dot125"></i>
                </button>
              </form>
            </td>
            <td class="col">
              <form class="me-2" method="post">
                <input type="hidden" name="table" value="<?= $table ?>">
                <input type="hidden" name="role" value="<?= $role->name ?>">
                <input class="w-100 border-0 bg-transparent text-dark" type="text" name="new_name" value="<?= $table ?>" disabled>
                <input type="hidden" name="action" value="rename">
              </form>
            </td>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
    <script>
      // iterate through every row of the table used to list the tables
      // in order to add the following event: when one of the rename buttons is clicked,
      // focus on the database name input of that respective row
      document.querySelectorAll('table#tables tbody tr').forEach(row => {
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
  <form id="createForm" class="max-w-350" method="post">
    <div class="mb-4">
      <label for="tableNameInput" class="form-label fs-dot9 fw-bold text-dark-gray">Table name</label>
      <input type="text" id="tableNameInput" name="table" class="form-control border-dark border-opacity-50 border-width-2" pattern="\w+" required>
    </div>
    <div class="row mb-3 mx-0 fs-dot9 text-center fw-bold text-dark-gray">
      <span class="col-2">PK</span>
      <span class="col-6">Name</span>
      <span class="col-4">Type</span>
    </div>
    <div id="fields" class="mb-4"></div> <!-- fields will be inserted inside here -->
    <!-- not using button element because pressing enter would trigger this instead of submit -->
    <div id="addButton" class="btn btn-light text-dark-gray me-1">
      <i class="fa-solid fa-plus"></i>
    </div>
    <input class="btn btn-success fw-bold" type="submit" name="action" value="create">
  </form>
  <template id="field"> <!-- field input template -->
    <div class="row mb-3 mx-0">
      <div class="col-2 d-flex justify-content-center align-items-center">
        <input type="radio" name="pkIndex" value="" class="form-check-input border-dark border-opacity-50 border-width-2" required>
      </div>
      <div class="col-6">
        <input type="text" name="" class="w-100 form-control form-control-sm border-dark border-opacity-50 border-width-2" pattern="\w+" required>
      </div>
      <div class="col-4">
        <select name="" class="form-select form-select-sm w-100 border-dark border-opacity-50 border-width-2">
          <option value="text">Text</option>
          <option value="number">Number</option>
        </select>
      </div>
    </div>
  </template>
  <script>
    function insertNewField() {
      const fields = document.querySelector('form#createForm #fields');
      const index = fields.children.length; // count the current number of fields

      const element = document.querySelector('template#field').content.cloneNode(true);
      element.querySelector('input[type="radio"]').setAttribute('value', index);
      element.querySelector('input[type="text"]').setAttribute('name', `fields[${index}][name]`);
      element.querySelector('select').setAttribute('name', `fields[${index}][type]`);

      // insert field inside div#fields wrapper
      fields.appendChild(element);
    }

    insertNewField(); // table to be created must've at least one field

    document.getElementById('addButton').addEventListener('click', e => {
      e.preventDefault();
      insertNewField();
    });
  </script>
<?php endif; ?>