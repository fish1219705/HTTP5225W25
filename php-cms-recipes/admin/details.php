<?php
include('includes/database.php');
include('includes/config.php');
include('includes/functions.php');

secure();

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recipe Details</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
</head>

<body>
    <main class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="mb-4">
                    <a href="recipes.php" class="btn btn-secondary">
                        &larr; Return to Recipes
                    </a>
                </div>
                <?php
                if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET['RecipeID'])) {
                    $RecipeID = mysqli_real_escape_string($connect, $_GET['RecipeID']);

                    $query = "SELECT 
                        recipes.RecipeName, 
                        recipes.RecipeID, 
                        recipes.PrepTime, 
                        recipes.Instructions, 
                        recipes.Servings,
                        recipes.Photo, 
                        ingredients.IngredientName, 
                        ingredients.Quantity
                        FROM recipes
                        LEFT JOIN ingredients ON recipes.RecipeID = ingredients.RecipeID
                        WHERE recipes.RecipeID = '$RecipeID'";

                    $result = mysqli_query($connect, $query);

                    if ($result) {
                        $recipes = mysqli_fetch_all($result, MYSQLI_ASSOC);

                        if (!empty($recipes)) {
                            $recipe = $recipes[0];

                            echo '<h1 class="display-4 mb-4">' . htmlspecialchars($recipe['RecipeName']) . '</h1>';

                            if (!empty($recipe['Photo'])) {
                                echo '<img src="../' . htmlspecialchars($recipe['Photo']) . '" alt="Recipe Image" class="img-fluid mb-4 d-block mx-auto rounded shadow-sm" style="width: 650px; height: auto; object-fit: cover;">';
                            }

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
                            // if (!empty($recipe['Photo'])) {
                            //     echo '<img src="../' . htmlspecialchars($recipe['Photo']) . '" alt="Recipe Image" class="img-fluid mb-4 rounded shadow-sm">';
                            // }
                            echo '</div>';

                            echo '</div>';
                        } else {
                            echo '<div class="alert alert-warning" role="alert">No recipe found.</div>';
                        }
                    } else {
                        echo '<div class="alert alert-danger" role="alert">Database query error: ' . mysqli_error($connect) . '</div>';
                    }
                } else {
                    echo '<div class="alert alert-warning" role="alert">No RecipeID provided.</div>';
                }
                ?>
            </div>
        </div>
    </main>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>