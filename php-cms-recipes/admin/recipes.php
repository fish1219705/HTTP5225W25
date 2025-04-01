<?php

include('includes/database.php');
include('includes/config.php');
include('includes/functions.php');

secure();



// Pagination setup
$recipes_per_page = 15;
$page = isset($_GET['page']) ? (int) $_GET['page'] : 1;
$offset = ($page - 1) * $recipes_per_page;

// Get total number of recipes
$total_query = 'SELECT COUNT(*) FROM recipes';
$total_result = mysqli_query($connect, $total_query);
$total_row = mysqli_fetch_row($total_result);
$total_recipes = $total_row[0];

// Get recipes for current page
$query = 'SELECT * FROM recipes LIMIT ' . $recipes_per_page . ' OFFSET ' . $offset;
$result = mysqli_query($connect, $query);

include('includes/header.php');
?>

<?php
// Check for flash messages
$message = '';
$messageType = '';
if (isset($_SESSION['message'])) {
    $message = $_SESSION['message'];
    $messageType = $_SESSION['message_type'];
    
    // Clear the message after displaying
    unset($_SESSION['message']);
    unset($_SESSION['message_type']);
}
?>

<div class="container">
<?php if (!empty($message)): ?>
  <div class="alert alert-<?php echo $messageType; ?> alert-dismissible fade show" role="alert">
    <?php if ($messageType === 'success'): ?>
      <span class="text-success fw-bold"><?php echo $message; ?></span>
    <?php else: ?>
      <span class="text-danger fw-bold"><?php echo $message; ?></span>
    <?php endif; ?>
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
  </div>
<?php endif; ?>

  <!-- Header Section -->
  <div class="mb-3">
    <h2 class="fw-bold text-dark">Manage Recipes</h2>
  </div>

  <!-- Button Section (on next line) -->
  <div class="mb-3">
    <a href="recipe_add.php" class="btn btn-primary btn-sm">
      <i class="fas fa-plus-square"></i> Add New Recipe
    </a>
  </div>

  <!-- Recipes Cards -->
  <div class="row">
    <?php while ($record = mysqli_fetch_assoc($result)): ?>
      <div class="col-12 col-md-4 mb-4">
        <div class="card h-100 shadow-sm">
          <img src="../<?php echo $record['Photo']; ?>" class="card-img-top" alt="Recipe Image"
            style="height: 200px; object-fit: cover;">
          <div class="card-body d-flex flex-column">
            <h5 class="card-title"><?php echo htmlentities($record['RecipeName']); ?></h5>
            <p class="card-text"><strong>Instructions:</strong> <small><?php echo $record['Instructions']; ?></small>
            </p>
            <p class="card-text"><strong>Prep Time:</strong> <?php echo $record['PrepTime']; ?> mins</p>
            <p class="card-text"><strong>Servings:</strong> <?php echo $record['Servings']; ?></p>
            <div class="d-flex flex-column mt-auto">
                    <!-- Edit Button -->
                    <a href="recipe_edit.php?RecipeID=<?php echo $record['RecipeID']; ?>" class="btn btn-primary w-100 mb-2">Edit</a>

                    <!-- Delete Button triggers modal -->
                    <button type="button" class="btn btn-danger w-100 mb-2" data-bs-toggle="modal" data-bs-target="#deleteModal"
                        data-id="<?php echo $record['RecipeID']; ?>">Delete</button>

                    <!-- Details Button -->
                    <a href="details.php?RecipeID=<?php echo $record['RecipeID']; ?>" class="btn btn-info w-100 mb-2">Details</a>
                    <!-- Photo Button -->
                    <a href="recipe_photo.php?id=<?php echo $record['RecipeID']; ?>" class="btn btn-secondary w-100 mb-2">Photo</a>
                </div>
          </div>
        </div>
      </div>
    <?php endwhile; ?>
  </div> <!-- End of row -->

  <!-- Pagination -->
  <div class="pagination mt-4 d-flex justify-content-center">
    <?php
    $total_pages = ceil($total_recipes / $recipes_per_page);
    if ($page > 1) {
      echo '<a href="?page=' . ($page - 1) . '" class="btn btn-secondary">Previous</a>';
    }
    for ($i = 1; $i <= $total_pages; $i++) {
      if ($i == $page) {
        echo '<span class="btn btn-primary mx-1">' . $i . '</span>';
      } else {
        echo '<a href="?page=' . $i . '" class="btn btn-secondary mx-1">' . $i . '</a>';
      }
    }
    if ($page < $total_pages) {
      echo '<a href="?page=' . ($page + 1) . '" class="btn btn-secondary">Next</a>';
    }
    ?>
  </div> <!-- End of pagination -->
</div> <!-- End of container -->


<!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header bg-danger text-white">
        <h5 class="modal-title" id="deleteModalLabel">Confirm Delete</h5>
        <button type="button" class="btn-close text-white" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <p>Are you sure you want to delete this recipe? This action cannot be undone.</p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
        <!-- Actual Delete Link -->
        <a id="confirmDeleteBtn" href="#" class="btn btn-danger">Delete</a>
      </div>
    </div>
  </div>
</div>

<!-- JavaScript to handle modal logic -->
<script>
  // Pass RecipeID to the modal dynamically
  var deleteModal = document.getElementById('deleteModal');
  deleteModal.addEventListener('show.bs.modal', function (event) {
    var button = event.relatedTarget;
    var recipeId = button.getAttribute('data-id');
    var deleteUrl = 'recipe_delete.php?RecipeID=' + recipeId;
    document.getElementById('confirmDeleteBtn').setAttribute('href', deleteUrl);
  });
</script>

<?php
include('includes/footer.php');
?>