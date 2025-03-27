<?php

$connect = mysqli_connect("localhost", "root", "root", "recipe");

mysqli_set_charset($connect, 'UTF8');

if (!$connect) {
    die("Connection failed: " . mysqli_connect_error());
}
