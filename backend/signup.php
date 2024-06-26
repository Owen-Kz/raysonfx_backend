<?php
include 'cors.php';
// enableCORS();
include "./db.php";
session_start();
// Get the JSON data from the POST request

$data = json_decode(file_get_contents('php://input'), true);

// // Access the values
$email_post = $data['email'];
$pass = $data['password'];
$username_post = $data['username'];
$first_name  = $data["first_name"];
$last_name = $data["last_name"];
$country = $data["country"];
$address = $data["address"];
$state = $data["state"];
$city = $data["city"];
$zipCode = $data["zipCode"];
$phonenumber = $data["password"];



// Encryoted Data 
$email = mysqli_real_escape_string($con, $email_post);
// $password = md5($pass);
$password = password_hash($pass, PASSWORD_DEFAULT);


if(isset($pass) && isset($email_post) && isset($first_name) && isset($last_name) && isset($username_post)){
// CHeck if the user already exists
    $stmt = $con->prepare("SELECT * FROM `user_data` WHERE `email` = ? OR `username` = ? ");
    $stmt->bind_param("ss", $email, $username_post);
    $stmt->execute();
    $result = $stmt->get_result();
    $run_query = $result;
    // $run_query = $result;    
    $count = mysqli_num_rows($run_query);


	//if user record is available in database then $count will be equal to 1
	if($count > 0){
		$row = mysqli_fetch_array($run_query);
        $response = array('status' => 'error', 'message' => 'Account Already exist please login');
        echo json_encode($response);
    }
    else {
        $notSet = "not-set";
        // Create a NEw account if the user does not exist i.e record is not >  0
        $stmt = $con->prepare("INSERT INTO `user_data` (`username`, `email`, `first_name`, `last_name`, `state`, `zip_code`, `city`, `phonenumber`, `country`, `address`, `password`, `resetToken`) VALUES (?, ?, ?, ?, ?, ?,?,?,?,?,?,?)");
        $stmt->bind_param("ssssssssssss", $username_post, $email, $first_name, $last_name, $state,  $zipCode, $city, $phonenumber,  $country, $address, $password, $notSet);


        if($stmt->execute()){

        $response = array('status' => 'success', 'message' => 'Account Created Successfully', 'statement' => $count, 'result' => $result);
        echo json_encode($response);
   
    }
    else{
        $response = array('status' => 'error', 'message' => 'Account Was not Created Successfully', "Error: " . $stmt->error, 'result' => "result");
        echo json_encode($response);
    }
    }
}else{
    $response = array('status' => 'error', 'message' => 'Incomplete data');
    echo json_encode($response);
    
}



?>