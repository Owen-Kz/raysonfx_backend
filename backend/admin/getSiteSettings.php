<?php
include '../cors.php';
// enableCORS();
include "../db.php";
session_start();

// CHeck if the user already exists
    $stmt = $con->prepare("SELECT * FROM `site_settings` WHERE 1");
    // $stmt->bind_param("ss", $email, $username_post);
    $stmt->execute();
    $result = $stmt->get_result();
    $run_query = $result;
    // $run_query = $result;    
    $count = mysqli_num_rows($run_query);
    if($count > 0){        
            $row = mysqli_fetch_array($result);

            $btcWallet = $row["btc_wallet"];
            $ethWallet = $row["eth_wallet"];
            $phonenumber = $row["phonenumber"];
            $address = $row["address"];
            $eth_rate = $row["current_eth_rate"];
            $btc_rate = $row["current_btc_rate"];

            $response = array("status" => "success", "message" => "Site Data", 'address' => $address, "BTCWallet" => $btcWallet, "ETHWallet" => $ethWallet, "phonenumber" => $phonenumber, "btc_rate" => $btc_rate, "eth_rate" => $eth_rate);
            echo json_encode($response);
        
    }else{
        $response = array("status" => "error", "message" => "NoDataAvailable");
        echo json_encode($response);
    }
