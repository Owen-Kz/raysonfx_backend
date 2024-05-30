<?php
include "../db.php";
session_start();

// Access the values
$transactionID = $_POST['transactionID'];
$userID = $_POST['userID'];


if(isset($_SESSION["administrator"])){

    if(isset($transactionID) &&  isset($userID)){
        // Find Transaction Data 
        $stmt = $con->prepare("SELECT * FROM `transactions` WHERE md5(`id`) = ? AND `username` = ?");
        $stmt->bind_param("ss", $transactionID, $userID);
        $stmt->execute();
        $result = $stmt->get_result();
        $run_query = $result;
        // $run_query = $result;    
        $count = mysqli_num_rows($run_query);

		$row = mysqli_fetch_array($run_query);

        if($count > 0){
            // Approve TRansaction if found 
            $stmt = $con->prepare("UPDATE `transactions` SET `status` = 'rejected' WHERE md5(`id`) = ? AND `username` = ?");
            $stmt->bind_param("ss", $transactionID, $userID);
           if($stmt->execute()){
            $stmt = $con->prepare("SELECT SUM(`amount`) AS `totalApproved` FROM `transactions` WHERE `username` = ? AND `status` = 'completed' ");
            $stmt->bind_param("s", $userID);
            $stmt->execute();
            $result = $stmt->get_result();
            $run_query = $result;
            $row = mysqli_fetch_assoc($run_query);
            $newBalance = $row["totalApproved"];

            if($newBalance > 0){
                $stmt = $con->prepare("UPDATE `user_data` SET `current_balance` = ? WHERE `username` = ? LIMIT 1");
                $stmt->bind_param("ss", $newBalance, $userID);
                $stmt->execute();
            }else{
                $stmt = $con->prepare("UPDATE `user_data` SET `current_balance` = 0 WHERE `username` = ? LIMIT 1");
                $stmt->bind_param("s", $userID);
                $stmt->execute();
            }

                
            
            $response = array("status" => "succsss", "message" => "Transaction Rejected Successfully");
            echo json_encode($response);
            header('Location: ../../foreman/dashboard');
           }else{
            $response = array("status" => "error", "message" =>  "Error: " . $stmt->error);
           echo json_encode($response);
           }
           
        }else{
            $response = array("status" => "error", "message" => "Transaction Not Found");
           echo json_encode($response);
        }
    }

}