<h2>Records</h2>
<?php if (!$db_exists) : ?>
  <h4>Database not found.</h4>
<?php elseif (!$table_exists) : ?>
  <h4>Table not found on database.</h4>
<?php else : ?>
  <?php if (empty($records)) : ?>
    <h4>No records found.</h4>
  <?php endif; ?>
  <table id="records" class="table" data-schema="<?= htmlspecialchars(json_encode($schema)) ?>">
    <thead>
      <tr>
        <th></th>
        <?php foreach ($schema as $column) : ?>
          <th class="text-capitalize"><?= $column["Field"] ?></th>
        <?php endforeach; ?>
      </tr>
    </thead>
    <tbody>
      <?php foreach ($records as $index => $record) : ?>
        <tr>
          <td class="text-center">
            <!--
              form can be placed inside td but not inside table or tr
              because a form per row is needed, form attribute will be used to associate inputs w/ form
            -->
            <form id="<?= "form-record-$index" ?>" method="post">
              <input type="hidden" name="db" value="<?= $db ?>">
              <input type="hidden" name="table" value="<?= $table ?>">
              <?php foreach ($record as $name => $value) : ?>
                <input type="hidden" name="record[<?= $name ?>]" value="<?= $value ?>">
              <?php endforeach; ?>
            </form>
            <button form="<?= "form-record-$index" ?>" name="action" value="delete">
              <i class="fa-solid fa-trash text-danger"></i>
            </button>
          </td>
          <?php foreach ($record as $field) : ?>
            <td><?= $field ?></td>
          <?php endforeach; ?>
        </tr>
      <?php endforeach; ?>
      <form id="createForm" method="post">
        <input type="hidden" name="db" value="<?= $db ?>">
        <input type="hidden" name="table" value="<?= $table ?>">
        <input type="submit" hidden name="action" value="create">
      </form>
    </tbody>
  </table>
  <button id="addRecord" class="btn btn-primary">Add</button>
  <script>
    const types = {
      'char': 'text',
      'varchar': 'text',
      'int': 'number',
    };

    const button = document.querySelector('button#addRecord');

    const schema = JSON.parse(document.querySelector('table#records').dataset.schema);

    button.addEventListener('click', e => {
      button.disabled = true; // only allow one row to be added at a time

      const row = document.createElement('tr');

      // first column is where action buttons are placed
      const emptyCell = document.createElement('td');
      row.appendChild(emptyCell);

      schema.forEach(column => {
        const cell = document.createElement('td');

        const input = document.createElement('input');
        input.type = types[column.Type.replace(/\(\d+\)$/, '')];
        input.name = `record[${column.Field}]`;
        input.setAttribute('form', 'createForm');

        cell.appendChild(input);
        row.appendChild(cell);
      });

      document.querySelector('table#records tbody').appendChild(row);
    });
  </script>
<?php endif; ?>
