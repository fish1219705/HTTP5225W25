<!doctype html>
<html>

<head>

  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Recipe Hub</title>

  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</head>

<body>
  <header>
    <?php include("admin/includes/nav.php"); ?>
  </header>

  <!-- <?php
  // Enable error reporting
  ini_set('display_errors', 1);
  error_reporting(E_ALL);
  ?> -->

  <?php
  include('admin/includes/database.php');
  if (!$connect) {
    die("Database connection failed: " . mysqli_connect_error());
  }

  // Get the current page number
  $page = isset($_GET['page']) ? (int) $_GET['page'] : 1; // Default to page 1 if not set
  $recipes_per_page = 15; // Number of recipes to display per page
  $offset = ($page - 1) * $recipes_per_page; // Calculate the OFFSET for SQL query
  
  // Get the total number of recipes
  $total_query = "SELECT COUNT(*) as total FROM recipes";
  $total_result = mysqli_query($connect, $total_query);
  $total_row = mysqli_fetch_assoc($total_result);
  $total_recipes = $total_row['total'];

  // Get the recipes for the current page
  $query = "SELECT * FROM recipes LIMIT $recipes_per_page OFFSET $offset";
  $result = mysqli_query($connect, $query);

  // Display recipes in 3-column grid
  echo '<div class="container">';
  echo '<h1 class="my-4 text-center">Recipe Hub</h1>'; 
  echo '<div class="row">';
  while ($recipe = mysqli_fetch_assoc($result)) {
    echo '<div class="col-12 col-md-4 mb-4">  <!-- col-md-4 to show 3 items in a row on medium screens and up -->
            <div class="card h-100 shadow-sm">
                <img src="' . $recipe['Photo'] . '" class="card-img-top" alt="Recipe Image" style="height: 200px; object-fit: cover;"> 
                <div class="card-body">
                    <h5 class="card-title">' . $recipe['RecipeName'] . '</h5>
                    <p class="card-text"><strong>Prep Time:</strong> ' . $recipe['PrepTime'] . ' mins</p>
                    <p class="card-text"><strong>Servings:</strong> ' . $recipe['Servings'] . '</p>
                    <form action="details.php" method="get">
                        <input type="hidden" name="RecipeID" value="' . $recipe['RecipeID'] . '">
                        <button type="submit" class="btn btn-success w-100">View Details</button>
                    </form>
                </div>
            </div>
        </div>';
  }
  echo '</div>'; // Close the row
  echo '</div>'; // Close the container
  
  // Pagination links
  $total_pages = ceil($total_recipes / $recipes_per_page); // Calculate the total number of pages
  echo '<div class="pagination mt-4 d-flex justify-content-center">';

  if ($page > 1) {
      echo '<a href="?page=' . ($page - 1) . '" class="btn btn-secondary mx-1">Previous</a>';
  }

  for ($i = 1; $i <= $total_pages; $i++) {
      if ($i == $page) {
          echo '<span class="btn btn-success mx-1">' . $i . '</span>';
      } else {
          echo '<a href="?page=' . $i . '" class="btn btn-secondary mx-1">' . $i . '</a>';
      }
  }

  if ($page < $total_pages) {
      echo '<a href="?page=' . ($page + 1) . '" class="btn btn-secondary mx-1">Next</a>';
  }

  echo '</div>';
  ?>



  </div>
</body>

</html>