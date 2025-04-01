<?php

include('includes/database.php');
include('includes/config.php');
include('includes/functions.php');

secure();

if (isset($_GET['delete'])) {

  $query = 'DELETE FROM users
    WHERE id = ' . $_GET['delete'] . '
    LIMIT 1';
  mysqli_query($connect, $query);

  set_message('User has been deleted');

  header('Location: users.php');
  die();

}

include('includes/header.php');

$query = 'SELECT *
  FROM users 
  ' . (($_SESSION['id'] != 1) ? 'WHERE id = ' . $_SESSION['id'] . ' ' : '') . '
  ORDER BY id ASC';
$result = mysqli_query($connect, $query);

?>

<!-- Google Font for Poppins -->
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">

<!-- Custom Style for Poppins Font -->
<style>
  body {
    font-family: 'Poppins', sans-serif;
  }
  .table th, .table td {
    vertical-align: middle;
  }
</style>

<!-- Bootstrap Styling for Manage Users -->
<div class="container mt-5">
  <h2 class="text-center mb-4">Manage Users</h2>

  <table class="table table-striped table-bordered">
  <thead>
    <tr>
      <th>ID</th>
      <th>Name</th>
      <th>Email</th>
      <th>Status</th>
      <th>Operation</th> <!-- Combined column for Edit and Delete buttons -->
    </tr>
  </thead>
  <tbody>
    <?php while ($record = mysqli_fetch_assoc($result)): ?>
      <tr>
        <td align="center"><?php echo $record['id']; ?></td>
        <td><?php echo htmlentities($record['first']); ?> <?php echo htmlentities($record['last']); ?></td>
        <td><a href="mailto:<?php echo htmlentities($record['email']); ?>"><?php echo htmlentities($record['email']); ?></a></td>
        <td align="center"><?php echo $record['active']; ?></td>
        <td align="center">
            <!-- Operation Column for Edit and Delete buttons -->
            <a href="users_edit.php?id=<?php echo $record['id']; ?>" class="btn btn-warning btn-sm">Edit</a>
            <a href="#" class="btn btn-danger btn-sm" data-bs-toggle="modal" data-bs-target="#deleteModal" onclick="setDeleteLink(<?php echo $record['id']; ?>)">
              Delete
            </a>
          </td>
        </tr>
      <?php endwhile; ?>
    </tbody>
  </table>

  <p class="text-center">
    <a href="users_add.php" class="btn btn-primary">
      <i class="fas fa-plus-square"></i> Add User
    </a>
  </p>
</div>

<!-- Bootstrap Modal for Delete Confirmation -->
<div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="deleteModalLabel">Delete User</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        Are you sure you want to delete this user? This action cannot be undone.
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
        <a href="#" id="confirmDelete" class="btn btn-danger">Delete</a>
      </div>
    </div>
  </div>
</div>

<!-- JavaScript to Set the Dynamic Delete Link -->
<script>
  function setDeleteLink(userId) {
    document.getElementById('confirmDelete').href = 'users.php?delete=' + userId;
  }
</script>

<!-- Add Bootstrap JS (Bundle includes Popper.js) -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.0/dist/js/bootstrap.bundle.min.js"></script>

<?php
include('includes/footer.php');
?>
