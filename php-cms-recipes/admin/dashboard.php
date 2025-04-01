<?php include("admin/includes/nav.php"); ?>
</header>

<?php
include('includes/database.php');
include('includes/config.php');
include('includes/functions.php');

secure();

include('includes/header.php');
?>

<!-- Google Font -->
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">

<!-- Custom Font Style -->
<style>
  body {
    font-family: 'Poppins', sans-serif;
  }
  .card-header {
    font-weight: 600;
  }
  .list-group-item {
    font-weight: 400;
  }
</style>

<!-- Dashboard container with Bootstrap styling -->
<div class="container mt-5">
  <div class="row justify-content-center">
    <div class="col-md-6">
      <div class="card shadow-lg border-0">
        <div class="card-header bg-primary text-white text-center">
          <h3 class="mb-0">Admin Dashboard</h3>
        </div>
        <div class="card-body">
          <div class="list-group">
            <a href="recipes.php" class="list-group-item list-group-item-action d-flex align-items-center">
              <i class="fas fa-utensils me-2"></i> Manage Recipes
            </a>
            <a href="users.php" class="list-group-item list-group-item-action d-flex align-items-center">
              <i class="fas fa-users me-2"></i> Manage Users
            </a>
            <a href="logout.php" class="list-group-item list-group-item-action text-danger d-flex align-items-center">
              <i class="fas fa-sign-out-alt me-2"></i> Logout
            </a>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<?php include('includes/footer.php'); ?>
