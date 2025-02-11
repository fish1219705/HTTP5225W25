<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Colors</title>
</head>
<body>

    <h2>Colors</h2>
    
    <?php
    //connection string
    $connect = mysqli_connect('localhost', 'root', 'root', 'colors');

    if(!$connect){
        die("Connection failed: " . mysqli_connect_error());
    }

    $query = "SELECT * FROM colors";
    $result = mysqli_query($connect, $query);

    // echo '<pre>' . print_r($result) . '</pre>';

    while ($record = mysqli_fetch_assoc($result))
    {
        echo '<div style="height: 50px; background-color:' . $record['Hex'] . '">' . $record['Name'] . '</div>';
    }

   
    
    //echo '<div style= height: 20px; background-color:black>COLOR NAME</div>';


    ?>
    
</body>
</html>