<?php

include('includes/database.php');
include('includes/config.php');
include('includes/functions.php');

secure();

if (isset($_GET['RecipeID'])) {
    $RecipeID = mysqli_real_escape_string($connect, $_GET['RecipeID']);

    // Delete ingredients first (foreign key constraint)
    $deleteIngredientsQuery = "DELETE FROM ingredients WHERE RecipeID = '$RecipeID'";
    mysqli_query($connect, $deleteIngredientsQuery);
    
    // Then delete the recipe
    $deleteRecipeQuery = "DELETE FROM recipes WHERE RecipeID = '$RecipeID'";
    $result = mysqli_query($connect, $deleteRecipeQuery);
    
    if ($result) {
        // Set a session variable with success message
        $_SESSION['message'] = "Recipe deleted successfully!";
        $_SESSION['message_type'] = "success";
    } else {
        $_SESSION['message'] = "Error deleting recipe: " . mysqli_error($connect);
        $_SESSION['message_type'] = "danger";
    }
}


// Redirect to the recipes page
header('Location: recipes.php');
die();
?>