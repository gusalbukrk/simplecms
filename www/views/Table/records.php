<h2>Records</h2>
<?php if (!$db_exists) : ?>
  <h4>Database not found.</h4>
<?php elseif (!$table_exists) : ?>
  <h4>Table not found on database.</h4>
<?php else : ?>
  <?php if (empty($records)) : ?>
    <h4>No records found.</h4>
  <?php endif; ?>
  <table id="records" class="table">
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
            <!-- form can be placed inside td but not inside table or tr; because a form per row
            is needed, form attribute will be used to associate inputs w/ form -->
            <form id="<?= "record-{$index}" ?>" method="post">
              <input type="hidden" name="db" value="<?= $db ?>">
              <input type="hidden" name="table" value="<?= $table ?>">
              <?php foreach ($record as $name => $value) : ?>
                <input type="hidden" name="record[<?= $name ?>]" value="<?= $value ?>">
              <?php endforeach; ?>
            </form>
            <button form="<?= "record-{$index}" ?>" name="action" value="delete">
              <i class="fa-solid fa-trash text-danger"></i>
            </button>
          </td>
          <?php foreach ($record as $field) : ?>
            <td><?= $field ?></td>
          <?php endforeach; ?>
        </tr>
      <?php endforeach; ?>
      <!-- last row of the table (which is hidden by default) contains the form to add new record !-->
      <tr id="newRow" class="d-none">
        <td>
          <form id="createForm" method="post">
            <input type="hidden" name="db" value="<?= $db ?>">
            <input type="hidden" name="table" value="<?= $table ?>">
            <input type="submit" hidden name="action" value="create">
          </form>
        </td>
        <?php $types = ["char" => "text", "varchar" => "text", "int" => "number",]; ?>
        <?php foreach ($schema as $column) : ?>
          <td>
            <input type="<?= $types[preg_replace("/\(\d+\)$/", "", $column["Type"])] ?>" name="record[<?= $column["Field"] ?>]" form="createForm">
          </td>
        <?php endforeach; ?>
      </tr>
    </tbody>
  </table>
  <button id="addRecord" class="btn btn-primary">Add</button>
  <script>
    const addRecordBtn = document.querySelector('button#addRecord');
    const newRow = document.querySelector('tr#newRow');

    addRecordBtn.addEventListener('click', e => {
      addRecordBtn.disabled = true; // only one row can be added at a time
      newRow.classList.remove('d-none');
    });
  </script>
<?php endif; ?>
