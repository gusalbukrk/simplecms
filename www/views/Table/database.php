<?php if (!$exists) : ?>
  <h4>Database does not exist</h4>
<?php else : ?>
  <h4 class="mb-4 fw-normal">Database: <b><?= ucfirst($db) ?></b></h4>
  <table class="table table-hover max-w-350">
    <?php foreach ($tables as $table) : ?>
      <tr>
        <td><?= $table ?></td>
      </tr>
    <?php endforeach; ?>
  </table>
<?php endif; ?>
