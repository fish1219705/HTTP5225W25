<?php
include('includes/database.php');
include('includes/config.php');
include('includes/functions.php');

secure();

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header('Location: recipes.php');
    exit();
}

$recipe_id = (int)$_GET['id'];

// Handle Photo Upload
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['photo'])) {
    if ($_FILES['photo']['error'] === 0) {
        $allowed_types = ['image/png', 'image/jpeg', 'image/jpg', 'image/gif'];
        
        if (in_array($_FILES['photo']['type'], $allowed_types)) {
            // Get original filename
            $original_filename = $_FILES['photo']['name'];
            
            // Create a unique filename to avoid overwriting existing files
            $filename = pathinfo($original_filename, PATHINFO_FILENAME) . '_' . time() . '.' . pathinfo($original_filename, PATHINFO_EXTENSION);
            
            // Define upload path (adjust this to match your directory structure)
            $upload_dir = '../uploads/';
            $upload_path = $upload_dir . $filename;
            $db_path = 'uploads/' . $filename; // Path to store in database
            
            // Create directory if it doesn't exist
            if (!file_exists($upload_dir)) {
                mkdir($upload_dir, 0777, true);
            }
            
            // Move the uploaded file
            if (move_uploaded_file($_FILES['photo']['tmp_name'], $upload_path)) {
                // Update database with file path (not base64 data)
                $query = "UPDATE recipes SET Photo = ? WHERE RecipeID = ? LIMIT 1";
                $stmt = mysqli_prepare($connect, $query);
                mysqli_stmt_bind_param($stmt, 'si', $db_path, $recipe_id);
                
                if (mysqli_stmt_execute($stmt)) {
                    set_message('Recipe photo has been updated');
                } else {
                    set_message('Error updating photo: ' . mysqli_error($connect));
                }
                
                header('Location: recipes.php');
                exit();
            } else {
                set_message('Error moving uploaded file');
            }
        } else {
            set_message('Invalid file type. Please upload PNG, JPEG, or GIF.');
        }
    } else {
        set_message('Error uploading file: ' . $_FILES['photo']['error']);
    }
    
    header('Location: recipe_photo.php?id=' . $recipe_id);
    exit();
}

// Handle Photo Deletion
if (isset($_GET['delete'])) {
    // Get current photo path first
    $query = "SELECT Photo FROM recipes WHERE RecipeID = ? LIMIT 1";
    $stmt = mysqli_prepare($connect, $query);
    mysqli_stmt_bind_param($stmt, 'i', $recipe_id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    
    if ($row = mysqli_fetch_assoc($result)) {
        $current_photo = $row['Photo'];
        
        // Delete file if it exists and is within uploads folder (security check)
        if (!empty($current_photo) && strpos($current_photo, 'uploads/') === 0) {
            $file_path = '../' . $current_photo;
            if (file_exists($file_path)) {
                unlink($file_path);
            }
        }
    }
    
    // Update database to remove photo reference
    $query = "UPDATE recipes SET Photo = '' WHERE RecipeID = ? LIMIT 1";
    $stmt = mysqli_prepare($connect, $query);
    mysqli_stmt_bind_param($stmt, 'i', $recipe_id);
    mysqli_stmt_execute($stmt);
    
    set_message('Recipe photo has been deleted');
    header('Location: recipes.php');
    exit();
}

// Retrieve Recipe Details
$query = "SELECT * FROM recipes WHERE RecipeID = ? LIMIT 1";
$stmt = mysqli_prepare($connect, $query);
mysqli_stmt_bind_param($stmt, 'i', $recipe_id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

if (!$record = mysqli_fetch_assoc($result)) {
    header('Location: recipes.php');
    exit();
}

include('includes/header.php');
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Edit Recipe Photo</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="container py-4">

<h2 class="mb-4">Edit Recipe Photo</h2>

<?php if (!empty($record['Photo'])): ?>
    <div class="mb-3">
        <?php if (strpos($record['Photo'], 'data:image/') === false): ?>
            <img src="../<?php echo htmlspecialchars($record['Photo']); ?>" class="img-fluid rounded shadow" style="max-width: 400px;">
        <?php else: ?>
            <img src="<?php echo htmlspecialchars($record['Photo']); ?>" class="img-fluid rounded shadow" style="max-width: 400px;">
        <?php endif; ?>
    </div>
    <p>
        <a href="recipe_photo.php?id=<?php echo $recipe_id; ?>&delete" class="btn btn-danger">
            <i class="fas fa-trash-alt"></i> Delete this Photo
        </a>
    </p>
<?php endif; ?>

<form method="post" enctype="multipart/form-data" class="mb-4">
    <div class="mb-3">
        <label for="photo" class="form-label">Upload New Photo:</label>
        <input type="file" class="form-control" name="photo" id="photo">
    </div>
    <button type="submit" class="btn btn-primary">Save Photo</button>
</form>

<p>
    <a href="recipes.php" class="btn btn-secondary">
        <i class="fas fa-arrow-circle-left"></i> Return to Recipe List
    </a>
</p>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

<?php include('includes/footer.php'); ?>