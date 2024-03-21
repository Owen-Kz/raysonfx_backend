<?php
include "db.php";
session_start();

unset($_SESSION["id"]);

unset($_SESSION["user_name"]);

unset($_SESSION["user_email"]);


mysqli_close($con);
$response = array('status' => 'kill', 'message' => 'logout');
echo json_encode($response);
?>