<div class="d-flex flex-wrap mb-5">
  <a href="https://<?= $db ?>.simpletables.xyz" class="me-3 mb-3">
    <i class="fa-solid fa-circle-arrow-left fs-3"></i>
  </a>
  <h2 class="fs-4">
    <span class="p-1 me-1 bg-lighter-blue"><?= explode("'", $title)[0] ?></span>'<?= explode("'", $title)[1] ?>
  </h2>
</div>
<?php if (!$db_exists) : ?>
  <p class="fw-bold">Database doesn't exist.</p>
<?php elseif (!$table_exists) : ?>
  <p class="fw-bold">Table doesn't exist.</p>
<?php else : ?>
  <?php
  $types = ["char" => "text", "varchar" => "text", "int" => "number", "tinyint unsigned" => "number"];

  $pk_name = current(array_filter($schema, function ($column) {
    return $column["Key"] === "PRI";
  }))["Field"];
  ?>
  <?php if (empty($records)) : ?>
    <p class="fw-bold mb-5">No records found.</p>
  <?php else : ?>
    <table id="records" class="table table-hover mb-5">
      <thead>
        <tr class="row g-0">
          <th class="col"></th>
          <?php foreach ($schema as $column) : ?>
            <th class="col text-capitalize"><?= $column["Field"] ?></th>
          <?php endforeach; ?>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($records as $index => $record) : ?>
          <tr class="row g-0">
            <td class="col d-flex justify-content-center align-items-center">
              <!-- form can be placed inside td but not inside table or tr; because a form per row
            is needed, form attribute will be used to associate inputs w/ form -->
              <form id="<?= "record-{$index}" ?>" method="post">
                <input type="hidden" name="db" value="<?= $db ?>">
                <input type="hidden" name="table" value="<?= $table ?>">
                <input type="hidden" name="pkName" value="<?= $pk_name ?>">
                <?php foreach ($record as $name => $value) : ?>
                  <input type="hidden" name="record[<?= $name ?>]" value="<?= $value ?>">
                <?php endforeach; ?>
                <!-- if form has multiple buttons/submit inputs, the first button
              is the one triggered when the enter key is pressed -->
                <input type="submit" form="<?= "record-{$index}" ?>" name="action" value="update" hidden>
              </form>
              <button class="updateButton btn p-0 me-3"><i class="fa-solid fa-pen-to-square text-orange fs-1dot125"></i></button>
              <button form="<?= "record-{$index}" ?>" class="btn p-0" name="action" value="delete">
                <i class="fa-solid fa-trash text-danger fs-1dot125"></i>
              </button>
            </td>
            <?php foreach ($record as $name => $value) : ?>
              <td class="col">
                <input type="<?= $types[preg_replace("/\(\d+\)$/", "", current(array_filter($schema, function ($column) use ($name) {
                                return $column["Field"] === $name;
                              }))["Type"])] ?>" form="<?= "record-{$index}" ?>" name="inputs[<?= $name ?>]" value="<?= $value ?>" class="border-0 w-100 bg-transparent text-dark" disabled>
              </td>
            <?php endforeach; ?>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  <?php endif; ?>
  <form class="max-w-350" method="post">
    <input type="hidden" name="db" value="<?= $db ?>">
    <input type="hidden" name="table" value="<?= $table ?>">
    <?php foreach ($schema as $column) : ?>
      <div class="mb-3">
        <label for="<?= $column["Field"] ?>Input" class="form-label fs-dot9 fw-bold text-dark-gray"><?= ucfirst($column["Field"]) ?></label>
        <input id="<?= $column["Field"] ?>Input" type="<?= $types[preg_replace("/\(\d+\)$/", "", $column["Type"])] ?>" name="record[<?= $column["Field"] ?>]" class="form-control border-dark border-opacity-50 border-width-2">
      </div>
    <?php endforeach; ?>
    <input class="btn btn-success fw-bold" type="submit" name="action" value="create">
  </form>
  <script>
    // iterate through every row of the table used to list the records
    // in order to add the following event: when one of the rename buttons is clicked,
    // focus on the first input of that respective row
    document.querySelectorAll('table#records tbody tr').forEach(row => {
      // get all inputs inside row, ignoring first column because it only contains action buttons
      const inputs = row.querySelectorAll('td:not(:first-child) input');

      row.querySelector('td:nth-child(1) button.updateButton').addEventListener('click', e => {
        inputs.forEach(input => {
          input.disabled = false;
        });

        inputs[0].focus();
        inputs[0].select();
      });
    })
  </script>
<?php endif; ?>