<?php


include "../db.php";

$data = json_Decode(file_get_contents('php://input'), true);

$email = $data["email"];

if(isset($email)){
    $email_esc = mysqli_real_escape_string($con, $email);
    $stmt = $con->prepare("SELECT * FROM `user_data` WHERE `email` = ? OR `username` = ?");
    $stmt->bind_param("ss", $email_esc, $email_esc);
    
    if($stmt->execute()){

    $result = $stmt->get_result();
    
    // $run_query = mysqli_query($con,$sql);
    $run_query = $result;    
    $count = mysqli_num_rows($run_query);
	//if user record is available in database then $count will be equal to 1

	if($count > 0){
        // Get and verify the users password if the account exists  
        $row = mysqli_fetch_array($run_query);
        $userEmail = $row["email"];
        $userFullname = $row["first_name"]." ".$row["last_name"];
         // Generate a random 6-digit number
         $resetToken = rand(100000, 999999);

         $stmt = $con->prepare("UPDATE `user_data` SET `resetToken` = ? WHERE `email` = ?");
         $stmt->bind_param("ss", $resetToken, $userEmail);
         if($stmt->execute()){
       

        $response = array("status" => "success", "user"=> $userEmail, "fullname" => $userFullname, "resetToken" => $resetToken, "subject" => "Your Password Reset Code is $resetToken", 'cookie' => md5($userEmail));
        echo json_encode($response);

         }else{
            $response = array("status" => "error", "user"=> "NO DATA", "fullname" => $userFullname, "resetToken" => $resetToken, "subject" => "Your Password Reset Code is $resetToken", "message" => "Error updating Token");
        echo json_encode($response);

         }
    }else{
        $response = array("status" => "error", "user"=> "NO DATA");
        echo json_encode($response);
    }
}else{
    $response = array("status" => "error", "user"=> "NO DATA");
        echo json_encode($response);
}

}else{
    $response = array("status" => "error", "user"=> "NO DATA");
        echo json_encode($response);
}