<?php
require '../vendor/autoload.php'; // If you're using Composer (recommended)

require "./exportENV.php";
include "./db.php";
$data = json_decode(file_get_contents('php://input'), true);

$userEmail  = $data["email"];
$verificationToken = $data["verification"];


if(isset($userEmail) && isset($verificationToken)){
    // CHeck if the user exists
    $stmt = $con->prepare("SELECT * FROM `user_data` WHERE `email` = ? ");
    $stmt->bind_param("s", $userEmail);

    
    if($stmt->execute()){
        $result = $stmt->get_result();
        $run_query = $result;
        // $run_query = $result;    
        $count = mysqli_num_rows($run_query);
    
    
        //if user record is available in database then $count will be equal to 1
        if($count > 0){

            $stmtUpdate = $con->prepare("UPDATE `user_data` SET `verification_email` = ? WHERE `email` = ?");
            $stmtUpdate->bind_param("ss", $verificationToken, $userEmail);
            
            // $result = $stmt->get_result();
            // $run_query = $result;
            
            if($stmtUpdate->execute()){
                $response = array('status' => 'success', 'message' => 'Verification Complete Please Login');
                echo json_encode($response);
            }else{
                $response = array('status' => 'error', 'message' => 'Internal Server Error / Unable to verify email please try again');
                echo json_encode($response);
            }
           
        }else{
            $response = array('status' => 'error', 'message' => 'Internal Server Error / NOt Found');
            echo json_encode($response);
        }
    
    
    }else{
        $response = array('status' => 'error', 'message' => "Stage One Exception, $userEmail, $verificationToken");
        echo json_encode($response);
    }
 
}else{
    $response = array('status' => 'error', 'message' => "Invalid Parameters Provided, $userEmail, $verificationToken");
    echo json_encode($response);
}
?>