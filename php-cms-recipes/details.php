<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recipe Details</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

</head>

<body>
    <header>
        <?php include("admin/includes/nav.php"); ?>
    </header>

    <div class="container my-4">
    <div class="d-flex justify-content-start">
        <a href="index.php" class="btn btn-success">← Back to Recipe List</a>
    </div>
    </div>

    <main class="container mt-4">
        <?php
        $RecipeID = $_GET['RecipeID'];
        include('admin/includes/database.php');

        // SQL query to fetch recipe details and its ingredients
        $query = "SELECT recipes.RecipeName, recipes.RecipeID, recipes.PrepTime, recipes.Instructions, recipes.Servings,
                  ingredients.IngredientName, ingredients.Quantity, recipes.Photo
                  FROM recipes
                  INNER JOIN ingredients ON recipes.recipeID = ingredients.recipeID
                  WHERE recipes.recipeID = '$RecipeID'";

        $result = mysqli_query($connect, $query);

        if ($result) {
            $recipes = mysqli_fetch_all($result, MYSQLI_ASSOC);

            if (!empty($recipes)) {
                $recipe = $recipes[0];

                echo '<h1 class="display-4 mb-4">' . htmlspecialchars($recipe['RecipeName']) . '</h1>';


                echo '<div class="card mb-4 shadow-sm">';
                echo '<div class="card-header bg-light"><h5 class="mb-0">Ingredients</h5></div>';
                echo '<ul class="list-group list-group-flush">';
                foreach ($recipes as $ingredient) {
                    if (!empty($ingredient['IngredientName'])) {
                        echo '<li class="list-group-item">' .
                            htmlspecialchars($ingredient['Quantity'] . ' ' . $ingredient['IngredientName']) .
                            '</li>';
                    }
                }
                echo '</ul>';
                echo '</div>';

                echo '<div class="row mb-4">';
                echo '<div class="col-md-6">';
                echo '<div class="card shadow-sm">';
                echo '<div class="card-body text-center">';
                echo '<p class="text-muted mb-1">Servings</p>';
                echo '<h3 class="text-success">' . htmlspecialchars($recipe['Servings']) . '</h3>';
                echo '</div>';
                echo '</div>';
                echo '</div>';

                echo '<div class="col-md-6">';
                echo '<div class="card shadow-sm">';
                echo '<div class="card-body text-center">';
                echo '<p class="text-muted mb-1">Prep Time</p>';
                echo '<h3 class="text-success">' . htmlspecialchars($recipe['PrepTime']) . ' Minutes</h3>';
                echo '</div>';
                echo '</div>';
                echo '</div>';
                echo '</div>';

                echo '<div class="card mt-4 shadow-sm">';
                echo '<div class="card-header bg-light"><h5 class="mb-0">Instructions</h5></div>';
                echo '<div class="card-body">';
                $instructions = htmlspecialchars($recipe['Instructions']);
                $formattedInstructions = preg_replace('/\.\s*/', '.<br>', $instructions);

                // Output the formatted instructions
                echo nl2br($formattedInstructions);
                echo '</div>';
                echo '</div>';

                if (!empty($recipe['Photo'])) {
                    echo '<img src="' . htmlspecialchars($recipe['Photo']) . '" alt="Recipe Image" class="img-fluid mb-4 d-block mx-auto rounded shadow-sm" style="width: 500px; height: auto; object-fit: cover;">';
                }



            } else {
                echo '<div class="alert alert-warning" role="alert">No recipe found.</div>';
            }
        } else {
            echo '<div class="alert alert-danger" role="alert">Database query error: ' . mysqli_error($connect) . '</div>';
        }
        ?>
    </main>
</body>

</html>