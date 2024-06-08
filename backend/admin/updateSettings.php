<?php
include '../cors.php';
// enableCORS();
include "../db.php";
session_start();

// Get the JSON data from the POST request

// $data = json_decode(file_get_contents('php://input'), true);

// // Access the values
$eth_Wallet = $_POST['eth_wallet'];
$btc_wallet = $_POST['b_wallet'];
$phonenumber = $_POST['phonenumber'];
$contactAddress = $_POST['contact_address'];
$eth_rate = $_POST["eth_rate"];
$btc_rate = $_POST["btc_rate"];

if(isset($eth_Wallet)){
// CHeck if the user already exists
    $stmt = $con->prepare("SELECT * FROM `site_settings` WHERE 1");
    // $stmt->bind_param("ss", $email, $username_post);
    $stmt->execute();
    $result = $stmt->get_result();
    $run_query = $result;
    // $run_query = $result;    
    $count = mysqli_num_rows($run_query);
    if($count > 0){
        $stmt = $con->prepare("UPDATE `site_settings` SET `phonenumber`= ?, `address`= ?, `btc_wallet`= ?, `eth_wallet`= ?, `current_btc_rate` =?, `current_eth_rate` = ? WHERE 1");
        $stmt->bind_param("ssssss", $phonenumber, $contactAddress, $btc_wallet, $eth_Wallet, $btc_rate, $eth_rate);
        
        if($stmt->execute()){
            $response = array("status" => "success", "message" => "Site Updated");
            echo json_encode($response);
        }else{
            $response = array("status" => "error", "message" => "Could Not update Site");
            echo json_encode($response);
        }
    }else{
        $stmtINSERT = $con->prepare("INSERT INTO `site_settings` (`phonenumber`, `eth_wallet`, `address`, `btc_wallet`, `current_btc_rate`, `current_eth_rate`) VALUES (?, ?, ?, ?, ?, ?)");
        $stmtINSERT->bind_param("ssssss", $phonenumber, $eth_Wallet, $contactAddress, $btc_wallet, $btc_rate, $eth_rate);

        if($stmtINSERT->execute()){
            $response = array("status" => "success", "message" => "Site Settings Created");
            echo json_encode($response);
        }else{
            $response = array("status" => "error", "message" => "Could Not Create Site Data .$stmtINSERT->error, $btc_wallet");
            echo json_encode($response);
        }
    }
}else{
    $response = array("status" => "error", "message" => "Incomplete Data" . $btc_Wallet, $eth_Wallet,  $phonenumber,  $contactAddress);
    echo json_encode($response);
}