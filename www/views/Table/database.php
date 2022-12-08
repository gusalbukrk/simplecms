<?php if (!$exists) : ?>
  <h4>Database does not exist</h4>
<?php else : ?>
  <h4 class="mb-3 fw-normal"><?= "<b>Database</b>: " . ucfirst($db) ?></h4>

  <form class="container max-w-350" method="post">
    <div class="row mb-3">
      <select id="select" class="form-select col me-3" name="type">
        <option>text</option>
        <option>integer</option>
        <option>float</option>
      </select>
      <button id="add" class="btn btn-secondary col">+ Add field</button>
    </div>
    <div class="row mb-3">
      <input class="btn btn-primary" type="submit" value="Create table">
    </div>
  </form>
<?php endif; ?>

<script>
  const btn = document.getElementById("add");
  const select = document.getElementById("select");

  btn.addEventListener('click', (e) => {
    e.preventDefault();
    console.log(select.value);
  })
</script>
