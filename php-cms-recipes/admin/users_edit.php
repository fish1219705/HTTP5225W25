<?php

include('includes/database.php');
include('includes/config.php');
include('includes/functions.php');

secure();

if( !isset( $_GET['id'] ) ) {
  header('Location: users.php');
  die();
}

if( isset( $_POST['first'] ) ) {

  if( $_POST['first'] && $_POST['last'] && $_POST['email'] ) {

    $query = 'UPDATE users SET
      first = "' . mysqli_real_escape_string($connect, $_POST['first']) . '",
      last = "' . mysqli_real_escape_string($connect, $_POST['last']) . '",
      email = "' . mysqli_real_escape_string($connect, $_POST['email']) . '",
      active = "' . $_POST['active'] . '"
      WHERE id = ' . $_GET['id'] . '
      LIMIT 1';
    mysqli_query($connect, $query);

    if( $_POST['password'] ) {

      $query = 'UPDATE users SET
        password = "' . md5($_POST['password']) . '"
        WHERE id = ' . $_GET['id'] . '
        LIMIT 1';
      mysqli_query($connect, $query);

    }

    set_message('User has been updated');
  }

  header('Location: users.php');
  die();
}

if( isset( $_GET['id'] ) ) {

  $query = 'SELECT *
    FROM users
    WHERE id = ' . $_GET['id'] . '
    LIMIT 1';
  $result = mysqli_query($connect, $query);

  if( !mysqli_num_rows($result) ) {
    header('Location: users.php');
    die();
  }

  $record = mysqli_fetch_assoc($result);

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
  h2 {
    font-weight: 600;
  }
  label {
    font-weight: 400;
  }
</style>

<!-- Bootstrap Styling for Edit User Form -->
<div class="container mt-5">
  <h2 class="text-center mb-4">Edit User</h2>

  <form method="post">
    <div class="mb-3">
      <label for="first" class="form-label">First Name:</label>
      <input type="text" name="first" id="first" class="form-control" value="<?php echo htmlentities($record['first']); ?>" required>
    </div>
    <div class="mb-3">
      <label for="last" class="form-label">Last Name:</label>
      <input type="text" name="last" id="last" class="form-control" value="<?php echo htmlentities($record['last']); ?>" required>
    </div>
    <div class="mb-3">
      <label for="email" class="form-label">Email:</label>
      <input type="email" name="email" id="email" class="form-control" value="<?php echo htmlentities($record['email']); ?>" required>
    </div>
    <div class="mb-3">
      <label for="password" class="form-label">Password:</label>
      <input type="password" name="password" id="password" class="form-control">
    </div>
    <div class="mb-3">
      <label for="active" class="form-label">Active:</label>
      <select name="active" id="active" class="form-select">
        <?php
          $values = array('Yes', 'No');
          foreach( $values as $value ) {
            echo '<option value="' . $value . '"';
            if( $value == $record['active'] ) echo ' selected="selected"';
            echo '>' . $value . '</option>';
          }
        ?>
      </select>
    </div>
    <button type="submit" class="btn btn-success w-100">Edit User</button>
  </form>

  <p class="mt-3 text-center"><a href="users.php" class="btn btn-link"><i class="fas fa-arrow-circle-left"></i> Return to User List</a></p>
</div>

<?php

include('includes/footer.php');

?>
