<?php
if(isset($_POST['addSchool'])){
  //print_r($_POST);
  //Array ( [boardname] => Gary [language] => English [schooltype] => Private [addSchool] => )
  $boardname = $_POST['boardname'];
  $language = $_POST['language'];
  $schooltype = $_POST['schooltype'];

  include('reusables/connection.php');
  $query = "INSERT INTO schools (`Board`, `Language`, `School Type`) VALUES ('$boardname', '$language', '$schooltype')";
  $school = mysqli_query($connect, $query);
  if($school){
    echo 'School added successfully.';
  }
  else {
    echo 'Unable to add the school, Error code:' .mysqli_error();
  }

  
}


?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>LMS</title>

  <!-- Bootstrap CDN CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
</head>
<body>
  <?php include('reusables/nav.php') ?>
  <div class="container">
    <div class="row">
      <div class="col">
        <h1 class="display-5">Add a School</h1>
      </div>
    </div>
    <div class="row">
      <div class="col-md-6">
      <form method="POST" action="addschool.php">
        <div class="mb-3">
          <label for="boardname" class="form-label">Board Name</label>
          <input type="text" class="form-control" name="boardname" id="boardname" aria-describedby="boardname">
        </div>

        <div class="mb-3">
          <label for="language" class="form-label">Language</label>
          <input type="text" class="form-control" name="language" id="language" aria-describedby="language">
        </div>

        <div class="mb-3">
          <label for="schooltype" class="form-label">School Type</label>
          <input type="text" class="form-control" name="schooltype" id="schooltype" aria-describedby="schooltype">
        </div>
        
        <button type="submit" class="btn btn-primary" name="addSchool">Submit</button>
      </form>
      </div>
    </div>
  </div>
</body>
</html>