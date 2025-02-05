<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>

    <?php
    //connection string
    $connect = mysqli_connect('localhost', 'root', 'root', 'colors');

    if(!$connect){
        die("Connection failed: " . mysqli_connect_error());
    }

    $query = "SELECT * FROM colors";
    $colors = mysqli_query($connect, $query);

    //echo '<pre>' . print_r($colors) . '</pre>';

    $result = mysqli_fetch_all($colors, MYSQLI_ASSOC);

    foreach($result as $color){
        echo '<div style="height: 50px; background-color:' . $color['Hex'] . '">' . $color['Name'] . '</div>';
    }
    
    //echo '<div style= height: 20px; background-color:black>COLOR NAME</div>';


    ?>
    
</body>
</html>