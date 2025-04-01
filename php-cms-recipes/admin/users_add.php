<?php

include('includes/database.php');
include('includes/config.php');
include('includes/functions.php');

secure();

if (isset($_POST['first'])) {

  if ($_POST['first'] and $_POST['last'] and $_POST['email'] and $_POST['password']) {

    $query = 'INSERT INTO users (
        first,
        last,
        email,
        password,
        active
      ) VALUES (
        "' . mysqli_real_escape_string($connect, $_POST['first']) . '",
        "' . mysqli_real_escape_string($connect, $_POST['last']) . '",
        "' . mysqli_real_escape_string($connect, $_POST['email']) . '",
        "' . md5($_POST['password']) . '",
        "' . $_POST['active'] . '"
      )';
    mysqli_query($connect, $query);

    set_message('User has been added');

  }

  header('Location: users.php');
  die();

}

include('includes/header.php');

?>

<!-- Google Font for Poppins -->
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">

<!-- Custom Style for Poppins Font -->
<style>
  body {
    font-family: 'Poppins', sans-serif;
  }
  .form-label {
    font-weight: 500;
  }
  .form-group {
    margin-bottom: 1.5rem;
  }
</style>

<!-- Bootstrap Styling for Add User Form -->
<div class="container mt-5">
  <div class="row justify-content-center">
    <div class="col-md-6">
      <div class="card shadow-lg border-0">
        <div class="card-header bg-primary text-white text-center">
          <h4>Add New User</h4>
        </div>
        <div class="card-body">
          
          <!-- Add User Form -->
          <form method="post">
            <div class="mb-3">
              <label for="first" class="form-label">First Name:</label>
              <input type="text" name="first" id="first" class="form-control" required>
            </div>
            <div class="mb-3">
              <label for="last" class="form-label">Last Name:</label>
              <input type="text" name="last" id="last" class="form-control" required>
            </div>
            <div class="mb-3">
              <label for="email" class="form-label">Email:</label>
              <input type="email" name="email" id="email" class="form-control" required>
            </div>
            <div class="mb-3">
              <label for="password" class="form-label">Password:</label>
              <input type="password" name="password" id="password" class="form-control" required>
            </div>
            <div class="mb-3">
              <label for="active" class="form-label">Active:</label>
              <select name="active" id="active" class="form-select">
                <?php
                $values = array('Yes', 'No');
                foreach ($values as $value) {
                  echo '<option value="' . $value . '">' . $value . '</option>';
                }
                ?>
              </select>
            </div>
            <button type="submit" class="btn btn-primary w-100">Add User</button>
          </form>

          <!-- Return to User List Link -->
          <p class="mt-3 text-center">
            <a href="users.php" class="btn btn-secondary">
              <i class="fas fa-arrow-circle-left"></i> Return to User List
            </a>
          </p>

        </div>
      </div>
    </div>
  </div>
</div>

<?php

include('includes/footer.php');

?>
