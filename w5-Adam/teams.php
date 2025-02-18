<?php

// Connect to the MySQL database
$connect = mysqli_connect (
    'localhost',
    'root',
    'root',
    'demo'
);

// Create a query
$query = 'SELECT * 
        FROM teams
        ORDER BY name';
$result = mysqli_query($connect, $query);

// Output the number of rows
echo 'Rows: ' .mysqli_num_rows($result);


// Loop through each record
while($record = mysqli_fetch_assoc($result))
{
    // Output each record
    // print_t($record);

    echo '<h2>' .$record['name']. '</h2>';
    echo '<p>' .$record['league'] .'
    <br>
    Rank: '.$record['rank'] .'
    </p>';
    if($record['logo'])
    {
        echo '<img src="images/'.$record['logo'].'" width="100">';
    }
    echo '<hr>';
}

?>

how to use file zilla
what is difference between those loops