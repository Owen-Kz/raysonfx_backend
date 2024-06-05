<?php
include '../cors.php';
enableCORS();
include "../db.php";
session_start();


// Access the values
$transactionID = $_POST['transactionID'];
$userID = $_POST['userID'];

if(isset($userID)){

    if(isset($transactionID) &&  isset($userID)){
        // Find Transaction Data 
        $stmt = $con->prepare("SELECT * FROM `withdrawals` WHERE md5(`id`) = ? AND `user_id` = ?");
        $stmt->bind_param("ss", $transactionID, $userID);
        $stmt->execute();
        $result = $stmt->get_result();
        $run_query = $result;
        $count = mysqli_num_rows($run_query);

		$row = mysqli_fetch_array($run_query);
        $transactionType = $row["type"];
        $amount = $row["amount"];

        if($count > 0){
            // Approve TRansaction if found 
            $stmt = $con->prepare("UPDATE `withdrawals` SET `status` = 'completed' WHERE md5(`id`) = ? AND `user_id` = ?");
            $stmt->bind_param("ss", $transactionID, $userID);
           if($stmt->execute()){

    


            $response = array("status" => "succsss", "message" => "Transaction Approved Successfully");
            echo json_encode($response);
            header('Location: https://raysonfinance.vercel.app/foreman/dashboard');
           }else{
            $response = array("status" => "error", "message" =>  "Error: " . $stmt->error);
           echo json_encode($response);
           }
           
        }else{
            $response = array("status" => "error", "message" => "Transaction Not Found");
           echo json_encode($response);
        }
    }

}else{
    $response = array("status" => "error", "message" => "Not Logged IN");
    echo json_encode($response);
}