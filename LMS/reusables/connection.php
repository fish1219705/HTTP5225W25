<?php
  $connect = mysqli_connect(
    'localhost', //URL
    'root', //Username
    'root', //Your password here, either root or empty
    'school2025' // your database name, check your PHP myAdmin
  );
  if(!$connect){
    echo 'Error Code: ' . mysqli_connect_errno();
    echo 'Error Message: ' . mysqli_connect_error();
    exit;
  }