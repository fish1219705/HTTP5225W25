<?php
include('includes/database.php');
include('includes/config.php');
include('includes/functions.php');

secure();

// Initialize $RecipeID
$RecipeID = null;

// Handle initial page load (GET request)
if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET['RecipeID'])) {
    $RecipeID = $_GET['RecipeID'];
}

// Handle form submission (POST request)
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $RecipeID = $_POST["RecipeID"];
    $RecipeName = mysqli_real_escape_string($connect, $_POST["RecipeName"]);
    $Instructions = mysqli_real_escape_string($connect, $_POST["Instructions"]);
    $PrepTime = mysqli_real_escape_string($connect, $_POST["PrepTime"]);
    $Servings = mysqli_real_escape_string($connect, $_POST["Servings"]);

    // Update recipe details
    $query = "UPDATE recipes 
              SET RecipeName = '$RecipeName', 
                  Instructions = '$Instructions', 
                  PrepTime = '$PrepTime', 
                  Servings = '$Servings'
              WHERE RecipeID = '$RecipeID'";
    $result = mysqli_query($connect, $query);

    if ($result) {
        // Update or delete existing ingredients
        if (!empty($_POST['Ingredients'])) {
            foreach ($_POST['Ingredients'] as $IngredientID => $Ingredient) {
                $IngredientName = mysqli_real_escape_string($connect, $Ingredient['name']);
                $Quantity = mysqli_real_escape_string($connect, $Ingredient['quantity']);
                $delete = isset($Ingredient['delete']) && $Ingredient['delete'] == '1';

                if (!empty($IngredientID)) {
                    if ($delete) {
                        // Delete the ingredient
                        $query2 = "DELETE FROM ingredients WHERE IngredientID = '$IngredientID' AND RecipeID = '$RecipeID'";
                        if (!mysqli_query($connect, $query2)) {
                            $_SESSION['message'] = "Failed to delete ingredient: " . mysqli_error($connect);
                            $_SESSION['message_type'] = "danger";
                            break;
                        }
                    } else {
                        // Update the ingredient
                        $query2 = "UPDATE ingredients 
                                   SET IngredientName = '$IngredientName', 
                                       Quantity = '$Quantity' 
                                   WHERE IngredientID = '$IngredientID' AND RecipeID = '$RecipeID'";
                        if (!mysqli_query($connect, $query2)) {
                            $_SESSION['message'] = "Failed to update ingredient: " . mysqli_error($connect);
                            $_SESSION['message_type'] = "danger";
                            break;
                        }
                    }
                }
            }
        }

        // Insert new ingredients if added
        if (!empty($_POST['new_ingredients'])) {
            foreach ($_POST['new_ingredients'] as $new_ingredient) {
                if (!empty($new_ingredient['name']) && !empty($new_ingredient['quantity'])) {
                    $NewIngredientName = mysqli_real_escape_string($connect, $new_ingredient['name']);
                    $NewQuantity = mysqli_real_escape_string($connect, $new_ingredient['quantity']);

                    $query3 = "INSERT INTO ingredients (RecipeID, IngredientName, Quantity) 
                               VALUES ('$RecipeID', '$NewIngredientName', '$NewQuantity')";
                    if (!mysqli_query($connect, $query3)) {
                        $_SESSION['message'] = "Failed to insert new ingredient: " . mysqli_error($connect);
                        $_SESSION['message_type'] = "danger";
                        break;
                    }
                }
            }
        }

        $_SESSION['message'] = "Updated successfully!";
        $_SESSION['message_type'] = "success";
    } else {
        $_SESSION['message'] = "Failed to update recipe: " . mysqli_error($connect);
        $_SESSION['message_type'] = "danger";
    }
}

// Fetch recipe and ingredients if $RecipeID is set
if ($RecipeID !== null) {
    $query = "SELECT RecipeName, RecipeID, PrepTime, Instructions, Servings
              FROM recipes
              WHERE RecipeID = '$RecipeID'";
    $result = mysqli_query($connect, $query);

    if (mysqli_num_rows($result) > 0) {
        $recipe = $result->fetch_assoc();

        // Get ingredients for the recipe
        $query2 = "SELECT IngredientID, IngredientName, Quantity
                   FROM ingredients
                   WHERE RecipeID = '$RecipeID'";
        $ingredients = mysqli_query($connect, $query2);
    } else {
        die("No recipe found for RecipeID: $RecipeID");
    }
} else {
    die("No RecipeID provided in the request.");
}

include('includes/header.php');
?>

<main class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <h1 class="mb-4">Edit Recipe</h1>

            <!-- Display flash message -->
            <?php if (isset($_SESSION['message'])): ?>
                <div class="alert alert-<?php echo $_SESSION['message_type']; ?> alert-dismissible fade show" role="alert">
                    <?php echo $_SESSION['message']; ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
                <?php unset($_SESSION['message']); unset($_SESSION['message_type']); ?>
            <?php endif; ?>

            <!-- Form to edit recipe -->
            <form method="post" action="recipe_edit.php" enctype="multipart/form-data">
                <input type="hidden" name="RecipeID" value="<?php echo $recipe['RecipeID']; ?>">

                <!-- Recipe Name -->
                <div class="mb-3">
                    <label for="RecipeName" class="form-label">Recipe Name</label>
                    <input type="text" name="RecipeName" id="RecipeName" class="form-control"
                           value="<?php echo htmlspecialchars($recipe['RecipeName']); ?>" required>
                </div>

                <!-- Prep Time -->
                <div class="mb-3">
                    <label for="PrepTime" class="form-label">Preparation Time (in minutes)</label>
                    <input type="number" name="PrepTime" id="PrepTime" class="form-control"
                           value="<?php echo htmlspecialchars($recipe['PrepTime']); ?>" required>
                </div>

                <!-- Servings -->
                <div class="mb-3">
                    <label for="Servings" class="form-label">Servings</label>
                    <input type="number" name="Servings" id="Servings" class="form-control"
                           value="<?php echo htmlspecialchars($recipe['Servings']); ?>" required>
                </div>

                <!-- Instructions -->
                <div class="mb-3">
                    <label for="Instructions" class="form-label">Instructions</label>
                    <textarea name="Instructions" id="Instructions" rows="5" class="form-control"
                              required><?php echo htmlspecialchars($recipe['Instructions']); ?></textarea>
                </div>

                <h4 class="mt-4">Ingredients</h4>

                <!-- Existing Ingredients -->
                <?php while ($ingredient = mysqli_fetch_assoc($ingredients)): ?>
                    <div class="mb-3 border p-3 rounded ingredient-item">
                        <input type="hidden" name="Ingredients[<?php echo $ingredient['IngredientID']; ?>][id]"
                               value="<?php echo $ingredient['IngredientID']; ?>">
                        <div class="mb-2">
                            <label class="form-label">Ingredient Name</label>
                            <input type="text" name="Ingredients[<?php echo $ingredient['IngredientID']; ?>][name]"
                                   class="form-control" value="<?php echo htmlspecialchars($ingredient['IngredientName']); ?>" required>
                        </div>
                        <div class="mb-2">
                            <label class="form-label">Quantity</label>
                            <input type="text" name="Ingredients[<?php echo $ingredient['IngredientID']; ?>][quantity]"
                                   class="form-control" value="<?php echo htmlspecialchars($ingredient['Quantity']); ?>" required>
                        </div>
                        <div>
                            <button type="button" class="btn btn-danger btn-sm delete-ingredient" 
                                    data-id="<?php echo $ingredient['IngredientID']; ?>">Delete</button>
                            <input type="hidden" name="Ingredients[<?php echo $ingredient['IngredientID']; ?>][delete]" 
                                   value="0" class="delete-flag">
                        </div>
                    </div>
                <?php endwhile; ?>

                <!-- New Ingredients Section -->
                <div id="new-ingredients-container" class="mt-3"></div>

                <div class="mb-3">
                    <button type="button" id="add-ingredient" class="btn btn-primary">Add Another Ingredient</button>
                </div>

                <!-- Submit Button -->
                <div class="mb-3">
                    <button type="submit" class="btn btn-success">Update Recipe</button>
                </div>
            </form>

            <!-- Cancel Button -->
            <div class="mb-3">
                <a href="recipes.php" class="btn btn-secondary"><i class="fas fa-times"></i> Back to list</a>
            </div>
        </div>
    </div>
</main>

<?php include('includes/footer.php'); ?>

<script>
    let ingredientCount = 0;

    // Add new ingredient
    document.getElementById("add-ingredient").addEventListener("click", function () {
        const container = document.getElementById("new-ingredients-container");
        const ingredientHTML = `
            <div class="mb-3 border p-3 rounded new-ingredient-item">
                <div class="mb-2">
                    <label class="form-label">Ingredient Name</label>
                    <input type="text" name="new_ingredients[${ingredientCount}][name]" class="form-control" required>
                </div>
                <div class="mb-2">
                    <label class="form-label">Quantity</label>
                    <input type="text" name="new_ingredients[${ingredientCount}][quantity]" class="form-control" required>
                </div>
                <div>
                    <button type="button" class="btn btn-danger btn-sm remove-new-ingredient">Remove</button>
                </div>
            </div>`;
        container.insertAdjacentHTML("beforeend", ingredientHTML);
        ingredientCount++;
    });

    // Remove new ingredient (client-side only)
    document.addEventListener("click", function (e) {
        if (e.target.classList.contains("remove-new-ingredient")) {
            e.target.closest(".new-ingredient-item").remove();
        }
    });

    // Mark existing ingredient for deletion with confirmation
    document.addEventListener("click", function (e) {
        if (e.target.classList.contains("delete-ingredient")) {
            if (confirm("Are you sure you want to delete this ingredient?")) {
                const ingredientItem = e.target.closest(".ingredient-item");
                const deleteFlag = ingredientItem.querySelector(".delete-flag");
                deleteFlag.value = "1"; // Mark for deletion
                ingredientItem.style.display = "none"; // Hide visually
            }
        }
    });
</script>