<?php

$data = json_decode(file_get_contents('php://input'), true);
$adminId = $_GET["a_id"];

$fullname = $data["fullname"];
$transactionType = $data["transaction_type"];
$amount = $data["amount"];

if(isset($fullname)){

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    include "../db.php";
include "./validateAdmin.php";
session_start();


    $AdminCheck = "GetAdminInfo($adminId)";
    if($AdminCheck){
        
        $stmt = $con->prepare("SELECT * FROM `notify_names` WHERE `fullname` = ?  AND `amount` = ?  AND `transaction_type` = ? AND date_created BETWEEN (NOW() - INTERVAL 5 MINUTE) AND NOW()");

        $stmt->bind_param("sss", $fullname, $transactionType, $amount);
        
        if($stmt->execute()){
    
        $result = $stmt->get_result();
        
        // $run_query = mysqli_query($con,$sql);
        $run_query = $result;    
        $count = mysqli_num_rows($run_query);
        
        if($count > 0){
            $response = array("status" => "error", "message" =>"Dupplicate Transaction Try in 5 Minutes");
            echo json_encode($response);
        }else{
            $stmt = $con->prepare("INSERT INTO `notify_names` (`fullname`, `amount`, `transaction_type`) VALUES(?,?,?)");
            $stmt->bind_param("sss", $fullname, $amount, $transactionType);
            
            if($stmt->execute()){
                $response = array("status" => "success", "message" =>"TransactionCreated");
                echo json_encode($response);
            }else{
                $response = array("status" => "error", "message" =>"Internal Server Error");
                echo json_encode($response);
            }
        }
    }else{
        $response = array("status" => "error", "message" =>"Insert NOt Executed", $stmt->error);
        echo json_encode($response);
    }
    }else{
        $response = array("status" => "error", "message" =>"noAdminLoggedin");
        echo json_encode($response);
    }
}else{
    $response = array("message"=>"Method Not Allowed");
    echo json_encode($response);
}
}else{
    $response = array("status" => "error", "message" =>$data);
    echo json_encode($response);
}