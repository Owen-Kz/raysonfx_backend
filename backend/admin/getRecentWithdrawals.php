<?php

include "../db.php";
session_start();
// Get the JSON data from the POST request

// Access the values
$email_post = $_SESSION["user_email"];
$user_name = $_SESSION["user_name"];

if(isset($email_post) || isset($user_name)){

    $user_email = mysqli_real_escape_string($con, $email_post);
    $session_username = mysqli_real_escape_string($con, $user_name);

    // $password = md5($pass);
    
    // $password = $_POST["password"];

    $stmt = $con->prepare("SELECT * FROM `user_data` WHERE `username` != ? AND `email` != ? LIMIT 1");
    $stmt->bind_param("ss", $user_email, $session_username);
    
    if($stmt->execute()){

    $result = $stmt->get_result();
    
    // $run_query = mysqli_query($con,$sql);
    $run_query = $result;    
    $count = mysqli_num_rows($run_query);

    if($count > 0){
        $row = mysqli_fetch_array($run_query);
        $response = array('status' => 'success', 'message' => 'User Data Found', 'users'=> $row);
        echo json_encode($response);
    }else{
        $response = array('status' => 'error', 'message' => 'NO USER DATA', 'users'=> "[]");
        echo json_encode($response);
    }

    }else{
        $response = array('status' => 'error', 'message' => 'Unable To Execute query', 'users'=> "[]");
        echo json_encode($response);
    }

}else{
    $response = array('status' => 'error', 'message' => 'Query not Complete: Invalid Session Data', 'users'=> "[]");
    echo json_encode($response);
}