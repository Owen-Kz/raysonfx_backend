<?php
include "../db.php";
session_start();

unset($_SESSION["administrator"] );

unset($_SESSION["admin_id"]);

unset($_SESSION["admin_email"]);


mysqli_close($con);
$response = array('status' => 'kill', 'message' => 'logout');
echo json_encode($response);
?>