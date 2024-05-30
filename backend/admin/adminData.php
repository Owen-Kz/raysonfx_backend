<?php 

include '../db.php';
include "../getInitials.php";
session_start();

$userId = $_GET["uid"];


if(isset($userId)){

    $stmt = $con->prepare("SELECT * FROM `administrators` WHERE md5(`id`) = ? LIMIT 1");
    $stmt->bind_param("s", $userId);
    
    if($stmt->execute()){

    $result = $stmt->get_result();
    
    // $run_query = mysqli_query($con,$sql);
    $run_query = $result;    
    $count = mysqli_num_rows($run_query);
    
   
	//if user record is available in database then $count will be equal to 1

	if($count == 1){
        // Get and verify the users password if the account exists  
        $row = mysqli_fetch_array($run_query);
        $email = $_SESSION["admin_email"];
        $username = $row["username"];


   
        // $HOTEL =  $_SESSION["Hotel_name"];
        $user = array("username" => $username, "email" => $email);

        $response = array('status' => 'success', 'message' => 'User Loggedin', 'user' => $user);
        echo json_encode($response);

    

    
}else{
   
    $response = array('status' => 'error', 'message' => "Error: " . $stmt->error, 'user' => '[]');
    echo json_encode($response);
}
}
}