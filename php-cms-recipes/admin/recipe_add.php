<?php
include('includes/database.php');
include('includes/config.php');
include('includes/functions.php');

secure();


if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $RecipeName = mysqli_real_escape_string($connect, $_POST["RecipeName"]);
  $Instructions = mysqli_real_escape_string($connect, $_POST["RecipeInstructions"]);
  $PrepTime = mysqli_real_escape_string($connect, $_POST["PrepTime"]);
  $Servings = mysqli_real_escape_string($connect, $_POST["Servings"]);
  $imagePath = "";


  if (!empty($_FILES["image"]["name"])) {
    $imagePath = 'uploads/' . basename($_FILES["image"]["name"]);
    move_uploaded_file($_FILES["image"]["tmp_name"], $imagePath);
  }


  $query = "INSERT INTO Recipes (RecipeName, Instructions, PrepTime, Servings, Photo) VALUES ('$RecipeName','$Instructions','$PrepTime','$Servings','$imagePath')";
  $result = mysqli_query($connect, $query);


  if ($result && isset($_POST['ingredient']) && isset($_POST['quantity'])) {
    $recipeId = mysqli_insert_id($connect);
    for ($i = 0; $i < count($_POST['ingredient']); $i++) {
      $ingredient = mysqli_real_escape_string($connect, $_POST['ingredient'][$i]);
      $quantity = mysqli_real_escape_string($connect, $_POST['quantity'][$i]);
      $ingredientQuery = "INSERT INTO Ingredients (RecipeID, IngredientName, Quantity) VALUES ('$recipeId', '$ingredient', '$quantity')";
      mysqli_query($connect, $ingredientQuery);
    }
  }

  if ($result) {
    set_message("Recipe Added Successfully");
    header('Location: recipes.php');
    die();
  } else {
    set_message("Error: " . $connect->error);
  }
}

include('includes/header.php');
?>

<div class="container-fluid">
  <div class="row justify-content-center">
    <div class="col-md-8">
      <h2 class="mb-4">Add New Recipe</h2>
      <form action="recipe_add.php" method="post" enctype="multipart/form-data">
        <div class="mb-3">
          <label for="RecipeName" class="form-label">Recipe Name</label>
          <input type="text" class="form-control" name="RecipeName" id="RecipeName" required>
        </div>
        
        <div class="mb-3">
          <label for="RecipeInstructions" class="form-label">Recipe Instructions</label>
          <textarea class="form-control" name="RecipeInstructions" id="RecipeInstructions" rows="5" required></textarea>
        </div>
        
        <div class="row">
          <div class="col-md-6 mb-3">
            <label for="Servings" class="form-label">Servings</label>
            <input type="number" class="form-control" name="Servings" id="Servings" min="1" required>
          </div>
          
          <div class="col-md-6 mb-3">
            <label for="PrepTime" class="form-label">Prep Time (minutes)</label>
            <input type="number" class="form-control" name="PrepTime" id="PrepTime" min="0" required>
          </div>
        </div>

        <div class="mb-3">
          <label class="form-label">Ingredients</label>
          <div id="ingredient-list">
            <div class="row mb-2">
              <div class="col-6">
                <input type="text" class="form-control" name="ingredient[]" placeholder="Ingredient" required>
              </div>
              <div class="col-6">
                <input type="text" class="form-control" name="quantity[]" placeholder="Quantity" required>
              </div>
            </div>
          </div>
          <button type="button" class="btn btn-secondary mt-2" onclick="addIngredient()">Add Another Ingredient</button>
        </div>

        <div class="mb-3">
          <label for="image" class="form-label">Recipe Image</label>
          <input type="file" class="form-control" id="image" name="image">
        </div>

        <button type="submit" class="btn btn-primary">Add Recipe</button>
      </form>
    </div>
  </div>
</div>

<script>
function addIngredient() {
  const ingredientList = document.getElementById('ingredient-list');
  const newIngredient = `
    <div class="row mb-2">
      <div class="col-6">
        <input type="text" class="form-control" name="ingredient[]" placeholder="Ingredient" required>
      </div>
      <div class="col-6">
        <input type="text" class="form-control" name="quantity[]" placeholder="Quantity" required>
      </div>
    </div>
  `;
  ingredientList.insertAdjacentHTML('beforeend', newIngredient);
}
</script>

<?php
include('includes/footer.php');
?>