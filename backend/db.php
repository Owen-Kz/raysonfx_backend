<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
require_once dirname(__DIR__) . "/backend/exportENV.php";


$servername = $DB_HOST_RAYSON;
$username = $DB_USER_RAYSON;
$password = $DB_PASS_RAYSON;
$db = $DB_NAME_RAYSON;



// Create connection
$con = mysqli_connect($servername, $username, $password, $db);

// Check connection
if (!$con) {
    $response = array('status' => 'error', 'error' => mysqli_connect_error());
    echo json_encode($response);
    die("Connection failed: " . mysqli_connect_error());

}


?>