<?php
include '../cors.php';
// enableCORS();
include "../db.php";

session_start();

$userId = $_GET["uid"];


if(isset($userId)){

    $stmt = $con->prepare("SELECT * FROM `user_data` WHERE md5(`id`) = ? AND verification_email != 'unverified' LIMIT 1");
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
        $email = $row["email"];
        $username = $row["username"];
        $accountBalance = $row["current_balance"];

        $stmt = $con->prepare("SELECT SUM(`amount`) AS `totalcompleted` FROM `transactions` WHERE `username` = ? AND `type` = 'interest' AND `status` = 'completed'");
        $stmt->bind_param("s", $username);
        
        if($stmt->execute()){
            $result = $stmt->get_result();
    
            $count = mysqli_num_rows($run_query);    
    
        if($count > 0){
     
        if ($row = $result->fetch_assoc()) {
            $completed = $row["totalcompleted"];
            // Loop through each row in the result set and append it to the transactionsList array
            $stmt = $con->prepare("SELECT SUM(`amount`) AS `totalWithdrawals` FROM `transactions` WHERE `username` =?  AND `type` = 'Withdrawal'");
            $stmt->bind_param("s", $username);
            if($stmt->execute()){
                $res = $stmt->get_result();
                $row = $res->fetch_assoc();
                $totalWithdrawals = $row["totalWithdrawals"];

                $newInterestBalance = $completed - $totalWithdrawals;

         
            $response = array('status' => 'success', 'message' => 'completed Interest Transaction History', 'completed' => $newInterestBalance);
            echo json_encode($response);
        }else{
            $response = array('status' => 'error', 'message' => "Error: " . $stmt->error, 'completed' => '0');
            echo json_encode($response);
        }
        }
   
    
    }
    
    }
    else {
        $response = array('status' => 'error', 'message' => 'No Interest Transaction Found', 'completed' => '0');
        echo json_encode($response);
    }
    
}else{
   
    $response = array('status' => 'error', 'message' => "Error: " . $stmt->error, 'completed' => '0');
    echo json_encode($response);
}
}
}