<?php


include "../db.php";

$data = json_Decode(file_get_contents('php://input'), true);



$newpassword = $data['new_pass'];
$user_id = $data['user_id'];
if(isset($user_id) && isset($newpassword)){

  
    
    $password = password_hash($newpassword, PASSWORD_DEFAULT);


    $userID = mysqli_real_escape_string($con, $user_id);


    $stmt = $con->prepare("SELECT * FROM `user_data` WHERE md5(`email`) = ?");
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
     
        $stmt = $con->prepare("UPDATE `user_data` SET `password` = ? WHERE md5(`email`) =?");
        $stmt->bind_param("ss", $password, $userID);
    
        if($stmt->execute()){
            $response = array("status" => "success", "message" => "Password has been changed");
            echo json_encode($response);
        }else{
            $response = array("status" => "error", "message" => "Error: " . $stmt->error);
            echo json_encode($response);
        }
    }else{
        $response = array("status" => "error", "message" => "User Not Found");
        echo json_encode($response);
    }
}else{
    $response = array("status" => "error", "message" => "Error: " . $stmt->error);
    echo json_encode($response);
}
}else{
    $response = array("status" => "errpr", "message" =>"Please fill All fields");
    echo json_encode($response);
}