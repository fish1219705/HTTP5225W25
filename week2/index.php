<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Week 2</title>
</head>
<body>

    <!-- use . period could connect two together -->
  <?php
    echo '<h1>PHP and Creating Output</h1>';
    echo '<p>The PHP echo command can be used to create output.</p>'

  ?>

  <p>When creating output using echo and PHP, quotes can often cause problems. There are several solutions to using quotes within an echo statement:</p>
  
  <ul>
      <li>Use HTML special characters</li>
      <li>Alternate between single and double quotes</li>
      <li>Use a backslash to escape quotes</li>
  </ul>
  
  <h2>More HTML to Convert</h2>

  <p>PHP says "Hello World!"</p>

  <p>Can you display a sentence with ' and "?</p>

  <img src="php-logo.png">

  <?php
    echo '<img src="https://google.com/image" alt="">';
  ?>

  <img src="<?php echo 'https://google.com/image' ?>" alt="<?php echo 'ALT TAG'; ?>">

  <br><br><br>

  <?php   
    $name = "Peiyu Han";
    $lastName = 'Han';

    echo "Hello, " . $name;
    
    //$person = array('', '', ''); 
    //$person[0];

    $person['first'] = 'Peiyu';
    $person['last'] = 'Han';
    $person['email'] = 'info@pixelr.io';
    $person['web'] = 'https://www.google.com';

    echo 'Hello, ' . $person['first'];

  ?>
    
    <a href="<?php echo 'mailto: ' . $person['email'];?>">info@pixelr.io</a>
    <br>
    <a href="<?php echo $person['web'];?>">google.com</a>





    <!-- Trying out Javascript -->
    <script>
      document.write('Hello world in Javascript')
    </script>

    <!-- Trying out echo in php -->
    <!-- ; is mandatory -->
    <?php  
    echo 'Welcome to Peiyu\'s World!'; 
    
    ?>
</body>
</html>