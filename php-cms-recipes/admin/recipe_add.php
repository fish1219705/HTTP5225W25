<?php
include('includes/database.php');
include('includes/config.php'); // Add this to have access to session functions
include('includes/functions.php'); // Add this if needed for secure() function

secure(); // Uncomment if you want to check authentication

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $RecipeName = $_POST["RecipeName"];
  $Instructions = $_POST["Instructions"];
  $PrepTime = $_POST["PrepTime"];
  $Servings = $_POST["Servings"];



  $query = "INSERT INTO recipes (RecipeName, Instructions, PrepTime, Servings) 
            VALUES ('$RecipeName','$Instructions','$PrepTime','$Servings')";
  $result = mysqli_query($connect, $query);

  if ($result) {
    // Get the ID of the newly inserted recipe
    $recipeID = mysqli_insert_id($connect);
    
    // Process the ingredients
    if (isset($_POST['ingredient']) && isset($_POST['quantity'])) {
      for ($i = 0; $i < count($_POST['ingredient']); $i++) {
        $ingredient = $_POST['ingredient'][$i];
        $quantity = $_POST['quantity'][$i];
        
        if (!empty($ingredient) && !empty($quantity)) {
          $ingredientQuery = "INSERT INTO ingredients (RecipeID, IngredientName, Quantity) 
                              VALUES ('$recipeID', '$ingredient', '$quantity')";
          mysqli_query($connect, $ingredientQuery);
        }
      }
    }
    
    // Set success message in session
    $_SESSION['message'] = "Recipe added successfully!";
    $_SESSION['message_type'] = "success";
    
    // Redirect to recipes.php
    header('Location: recipes.php');
    exit(); // Make sure to exit after redirect
  } else {
    // Set error message in session
    $_SESSION['message'] = "Error adding recipe: " . mysqli_error($connect);
    $_SESSION['message_type'] = "danger";
    
    // Still redirect, but with error message
    header('Location: recipes.php');
    die();
  }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Add Recipe</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"
    integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
</head>

<body>
  <?php include("includes/nav.php"); ?>

  <div class="container mt-5">
    <h2>Add Recipe</h2>
    <form action="recipe_add.php" method="post" enctype="multipart/form-data">
      <div class="mb-3">
        <label for="RecipeName" class="form-label">Recipe Name</label>
        <input type="text" name="RecipeName" id="RecipeName" class="form-control" required>
      </div>
      <div class="mb-3">
        <label for="Instructions" class="form-label">Recipe Instructions</label>
        <textarea name="Instructions" id="Instructions" class="form-control" rows="4" required></textarea>
      </div>
      <div class="mb-3">
        <label for="Servings" class="form-label">Servings</label>
        <input type="number" name="Servings" id="Servings" class="form-control" required>
      </div>
      <div class="mb-3">
        <label for="PrepTime" class="form-label">Prep Time (in minutes)</label>
        <input type="number" name="PrepTime" id="PrepTime" class="form-control" required>
      </div>

      <h4>Ingredients:</h4>
      <div id="ingredient-list">
        <div class="mb-3">
          <label for="ingredient[]" class="form-label">Ingredient</label>
          <input type="text" name="ingredient[]" class="form-control" required>

          <label for="quantity[]" class="form-label">Quantity</label>
          <input type="text" name="quantity[]" class="form-control" required>
        </div>
      </div>

      <button type="button" class="btn btn-primary mb-3" onclick="addIngredient()">Add Another Ingredient</button>

        <div class="mt-3">
            <button type="submit" class="btn btn-success">Add Recipe</button>
        </div>
    </form>

        <div class="mt-3">
            <a href="recipes.php" class="btn btn-secondary"><i class="fas fa-arrow-circle-left"></i> Back to Recipes</a>
        </div>
    </div>

  <script>
    function addIngredient() {
      const ingredientList = document.getElementById('ingredient-list');
      const newIngredient = `
        <div class="mb-3">
          <label for="ingredient[]" class="form-label">Ingredient</label>
          <input type="text" name="ingredient[]" class="form-control" required>

          <label for="quantity[]" class="form-label">Quantity</label>
          <input type="text" name="quantity[]" class="form-control" required>
        </div>
      `;
      ingredientList.insertAdjacentHTML('beforeend', newIngredient);
    }
  </script>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>