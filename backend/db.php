<?php

require_once "./exportENV.php";

$servername = $_ENV['DB_HOST_RAYSON'];
$username = $_ENV['DB_USER_RAYSON'];
$password = $_ENV['DB_PASS_RAYSON'];
$db = $_ENV["DB_NAME_RAYSON"];



// Create connection
$con = mysqli_connect($servername, $username, $password, $db);

// Check connection
if (!$con) {
    die("Connection failed: " . mysqli_connect_error());
}


?>