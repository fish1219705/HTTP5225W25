<?php

$connect = mysqli_connect (
    'sql213.infinityfree.com',
    'if0_37424643',
    'Passw0rd7755',
    'if0_37424643_fishes'
);

if (!$connect)
{
    die('Connection failed: ' . mysqli_connect_error());
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
    <title>Oishii Sashimi </title>
</head>

<body class="w3-sand w3-sans-serif">
    <header class="w3-container w3-center w3-padding-32">
        <h1 class="w3-center w3-text-black">Explore 20 Delicious Sashimi Varietie</h1>
    </header>

        <?php

            $query = 'SELECT s.sashimi_id, s.name, s.fish_type, s.origin, s.price_per_kg, s.market_id, s.description, s.fish_page_link AS link, s.pic, fm.name AS market, fm.location, fm.founded_year AS `year`, fm.specialty, fm.market_website AS web
            FROM sashimis s
            JOIN fish_markets fm
            ON s.market_id = fm.market_id';

            $result = mysqli_query($connect, $query);

            if (!$result) 
            {
                die('Query failed: ' . mysqli_error($connect));
            }
        ?>


        <!-- echo 'Rows: ' .mysqli_num_rows($result); -->
        <div class="w3-container w3-padding-24">

        <?php while($s = mysqli_fetch_assoc($result)): ?>
    
            <div class="w3-card w3-white w3-round w3-margin w3-padding-small  w3-center w3-text-dark-grey w3-hover-shadow">
                <div class="w3-container w3-padding-large">
                
                    <h2> <?=$s['name']?> </h2>

                    <p><strong>Fish Type: </strong> <?=$s['fish_type'] ?>
                    <br>
                    <strong>Origin: </strong> <?=$s['origin'] ?>
                    <br>
                    <strong>Price: </strong> $<?=$s['price_per_kg']?>/kg
                    <br>
                    <strong>Description: </strong> <?=$s['description']?>
                    <br>
                    <a href="<?=$s['link']?>" target="_blank" class="w3-hover-opacity"><em>More Details</em></a>
                    </p>

                    <?php if($s['pic']): ?>
                
                    <img src="images/<?=$s['pic']?>" alt="<?=$s['name']?> picture" class="w3-image w3-padding-16 w3-round w3-border w3-responsive" style="max-width: 80%; height: auto; display: block; margin: auto;">
                
                    <?php endif; ?>

                    <br><br>
                </div>

                <div class="w3-center w3-panel w3-round w3-light-grey">
                <h3 class="w3-text-grey">Market Information</h3>
                    <div class="w3-col s12 m12 l12">
                        <p><strong>Market: </strong><a href="<?=$s['web']?>" target="_blank"><?=$s['market']?></a> 
                        <br>
                        <strong>Location: </strong><em><?=$s['location']?></em>
                        <br>
                        <strong>Specialty: </strong> <?=$s['specialty']?>
                        <br>
                        <strong>Founded Year: </strong> <?=$s['year']?>
                        <br>
                        </p>
                    </div>
                </div>
            </div>
    

            <?php endwhile; ?>
    
        </div>
        
        <footer class="w3-container w3-center w3-padding-small">
            <p class="w3-text-black"> &copy; 2025 Oishii Sashimi. All Rights Reserved. </p>
        </footer>


        <?php mysqli_close($connect); ?>


</body>
</html>