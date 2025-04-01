<?php

include('includes/database.php');
include('includes/config.php');
include('includes/functions.php');

if (isset($_POST['email'])) {

  $query = 'SELECT *
    FROM users
    WHERE email = "' . $_POST['email'] . '"
    AND password = "' . md5($_POST['password']) . '"
    AND active = "Yes"
    LIMIT 1';
  $result = mysqli_query($connect, $query);

  if (mysqli_num_rows($result)) {

    $record = mysqli_fetch_assoc($result);

    $_SESSION['id'] = $record['id'];
    $_SESSION['email'] = $record['email'];

    header('Location: dashboard.php');
    die();

  } else {

    set_message('Incorrect email and/or password');

    header('Location: index.php');
    die();

  }

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
  .card-header {
    font-weight: 600;
  }
  .form-label {
    font-weight: 400;
  }
</style>

<!-- Bootstrap Styling for Login Form -->
<div class="container mt-5">
  <div class="row justify-content-center">
    <div class="col-md-4">
      <div class="card shadow-lg border-0">
        <div class="card-header bg-primary text-white text-center">
          <h4>Login</h4>
        </div>
        <div class="card-body">
          
          <!-- Error Message Display -->
          <?php if (isset($_SESSION['message'])): ?>
            <div class="alert alert-danger text-center">
              <?php echo $_SESSION['message']; unset($_SESSION['message']); ?>
            </div>
          <?php endif; ?>
          
          <!-- Login Form -->
          <form method="post">
            <div class="mb-3">
              <label for="email" class="form-label">Email:</label>
              <input type="text" name="email" id="email" class="form-control" required>
            </div>
            <div class="mb-3">
              <label for="password" class="form-label">Password:</label>
              <input type="password" name="password" id="password" class="form-control" required>
            </div>
            <button type="submit" class="btn btn-primary w-100">Login</button>
          </form>
        </div
