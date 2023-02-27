<form class="max-w-350 mb-5" method="post">
  <div class="mb-3">
    <label for="email" class="form-label fs-dot9 fw-bold text-dark-gray">Email</label>
    <input type="email" name="email" id="email" class="form-control border-dark border-opacity-50 border-width-2" required>
  </div>
  <div class="mb-3">
    <label for="password" class="form-label fs-dot9 fw-bold text-dark-gray">Password</label>
    <div class="input-group">
      <input type="password" name="password" id="password" class="form-control border-dark border-opacity-50 border-width-2" required>
      <span id="toggler" class="input-group-text border-dark border-opacity-50 border-width-2 bg-body border-left-0 cursor-pointer">
        <i class="fa-solid fa-fw fa-eye"></i>
      </span>
    </div>
  </div>
  <input type="submit" name="action" value="sign up" class="btn btn-primary fw-bold">
</form>
<script>
  const input = document.getElementById('password');
  const toggler = document.getElementById('toggler');
  const icon = toggler.querySelector('i');

  toggler.addEventListener('click', e => {
    icon.classList.toggle('fa-eye');
    icon.classList.toggle('fa-eye-slash');

    input.type = input.type === 'password' ? 'text' : 'password';
  })
</script>
