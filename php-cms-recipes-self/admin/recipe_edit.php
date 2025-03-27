<?php

include( 'includes/database.php' );
include( 'includes/config.php' );
include( 'includes/functions.php' );

secure();

if( !isset( $_GET['id'] ) )
{
  
  header( 'Location: recipes.php' );
  die();
  
}

if( isset( $_POST['RecipeName'] ) )
{
  
  if( $_POST['RecipeName'] and $_POST['Instructions'] )
  {
    
    $query = 'UPDATE recipes SET
      RecipeName = "'.mysqli_real_escape_string( $connect, $_POST['RecipeName'] ).'",
      Insturctions = "'.mysqli_real_escape_string( $connect, $_POST['content'] ).'",
      date = "'.mysqli_real_escape_string( $connect, $_POST['date'] ).'",
      type = "'.mysqli_real_escape_string( $connect, $_POST['type'] ).'",
      url = "'.mysqli_real_escape_string( $connect, $_POST['url'] ).'"
      WHERE id = '.$_GET['id'].'
      LIMIT 1';
    mysqli_query( $connect, $query );
    
    set_message( 'Recipe has been updated' );
    
  }

  header( 'Location: recipes.php' );
  die();
  
}


if( isset( $_GET['RecipeID'] ) )
{
  
  $query = 'SELECT *
    FROM recipes
    WHERE RecipeID = '.$_GET['RecipeID'].'
    LIMIT 1';
  $result = mysqli_query( $connect, $query );
  
  if( !mysqli_num_rows( $result ) )
  {
    
    header( 'Location: recipes.php' );
    die();
    
  }
  
  $record = mysqli_fetch_assoc( $result );
  
}

include( 'includes/header.php' );

?>

<h2>Edit Recipe</h2>

<form method="post">
  
  <label for="name">Recipe Name:</label>
  <input type="text" name="name" id="name" value="<?php echo htmlentities( $record['RecipeName'] ); ?>">
    
  <br>
  
  <label for="instruction">Instruction:</label>
  <textarea type="text" name="instruction" id="instruction" rows="5"><?php echo htmlentities( $record['Instructions'] ); ?></textarea>
  
  <script>

  ClassicEditor
    .create( document.querySelector( '#instruction' ) )
    .then( editor => {
        console.log( editor );
    } )
    .catch( error => {
        console.error( error );
    } );
    
  </script>
  
  <br>
  
  <label for="time">Preparation Time:</label>
  <input type="text" name="time" id="time" value="<?php echo htmlentities( $record['PrepTime'] ); ?>">
    
  <br>
  
  <label for="serving">Servings:</label>
  <input type="text" name="serving" id="serving" value="<?php echo htmlentities( $record['serving'] ); ?>">
    
  <br>
  <br>
  
  <input type="submit" value="Edit Recipe">
  
</form>

<p><a href="recipes.php"><i class="fas fa-arrow-circle-left"></i> Return to Recipe List</a></p>


<?php

include( 'includes/footer.php' );

?>