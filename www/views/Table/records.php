<h2>Records</h2>
<?php if (!$db_exists) : ?>
  <h4>Database not found.</h4>
<?php elseif (!$table_exists) : ?>
  <h4>Table not found on database.</h4>
<?php elseif (empty($records)) : ?>
  <h4>No records found.</h4>
<?php else : ?>
  <table class="table">
    <thead>
      <tr>
        <?php foreach ($schema as $column) : ?>
          <th class="text-capitalize"><?= $column["Field"] ?></th>
        <?php endforeach; ?>
      </tr>
    </thead>
    <tbody>
      <?php foreach ($records as $row) : ?>
        <tr>
          <?php foreach ($row as $field) : ?>
            <td><?= $field ?></td>
          <?php endforeach; ?>
        </tr>
      <?php endforeach; ?>
    </tbody>
  </table>
<?php endif; ?>
