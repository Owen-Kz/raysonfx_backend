<?php
include 'cors.php';
// enableCORS();
include "db.php";
session_start();
// Get the JSON data from the POST request

$data = json_decode(file_get_contents('php://input'), true);

// Access the values
$email_post = $data['username'];
$pass = $data['password'];

if(isset($pass) && isset($email_post)){

    $userID = mysqli_real_escape_string($con, $email_post);
    // $password = md5($pass);
    
    // $password = $_POST["password"];

    $stmt = $con->prepare("SELECT * FROM `user_data` WHERE `username` = ? OR `email` = ? AND verification_email != 'unverified' LIMIT 1");
    $stmt->bind_param("ss", $userID, $userID);
    
    if($stmt->execute()){

    $result = $stmt->get_result();
    
    // $run_query = mysqli_query($con,$sql);
    $run_query = $result;    
    $count = mysqli_num_rows($run_query);
    
   
	//if user record is available in database then $count will be equal to 1

	if($count == 1){
        // Get and verify the users password if the account exists  
        $row = mysqli_fetch_array($run_query);
        $storedHashedPassword = $row["password"];
        if((password_verify($pass, $storedHashedPassword)) ){

        $_SESSION["user_id"] = $row["id"];
    
		$_SESSION["user_name"] = $row["username"];

        $_SESSION["user_email"] = $row["email"];

        $ip_add = getenv("REMOTE_ADDR"); 

        // $HOTEL =  $_SESSION["Hotel_name"];
        $userLogin = array("user" => md5($row["email"]), "id" => md5($row["id"]));

        $response = array('status' => 'success', 'message' => 'Logged in successfully', 'userData' => $userLogin);
        echo json_encode($response);
    }else{
        $response = array('status' => 'error', 'message' => 'Invalid Credentials', 'userData' => "[]");
        echo json_encode($response);
    }
    
    }
    else {
        $response = array('status' => 'error', 'message' => 'Invalid Login details provided / Email not verified ', 'userData' => '[]');
        echo json_encode($response);
    }
    
}else{
    echo "Error: " . $stmt->error;
}
}else{
    $response = array('status' => 'error', 'message' => 'Fill all fields', 'userData' => '[]');
    echo json_encode($response);
}


