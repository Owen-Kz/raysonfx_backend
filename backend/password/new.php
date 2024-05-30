<?php


include "../db.php";

$data = json_Decode(file_get_contents('php://input'), true);

$oldpassword = $data["old_pass"];
$newpassword = $data['new_pass'];
$user_id = $data['user_id'];

if(isset($oldpassword) && isset($newpassword)){


    $password = password_hash($newpassword, PASSWORD_DEFAULT);
    $passwordOld = password_hash($oldpassword, PASSWORD_DEFAULT);


    $userID = mysqli_real_escape_string($con, $user_id);
    // $password = md5($pass);
    
    // $password = $_POST["password"];

    $stmt = $con->prepare("SELECT * FROM `user_data` WHERE md5(`id`) = ?");
    $stmt->bind_param("s", $userID);
    
    if($stmt->execute()){

    $result = $stmt->get_result();
    
    // $run_query = mysqli_query($con,$sql);
    $run_query = $result;    
    $count = mysqli_num_rows($run_query);
	//if user record is available in database then $count will be equal to 1

	if($count > 0){
        // Get and verify the users password if the account exists  
        $row = mysqli_fetch_array($run_query);
        $storedHashedPassword = $row["password"];
        if((password_verify($passwordOld, $storedHashedPassword)) ){
            $stmt = $con->prepare("UPDATE `user_data` SET `password` = ? WHERE md5(id) =?");
            $stmt->bind_param("ss", $password, $userID);
        }else{
            $response = array("status" => "success", "message" => "Incorrect current Password");
            echo json_encode($response);
        }
     
    
        if($stmt->execute()){
            $response = array("status" => "success", "message" => "Password has been changed");
            echo json_encode($response);
        }else{
            $response = array("status" => "success", "message" => "Error: " . $stmt->error);
            echo json_encode($response);
        }
    }else{
        $response = array("status" => "success", "message" => "User Not Found");
        echo json_encode($response);
    }
}else{
    $response = array("status" => "success", "message" => "Error: " . $stmt->error);
    echo json_encode($response);
}
}else{
    $response = array("status" => "success", "message" =>"Please fill All fields");
    echo json_encode($response);
}