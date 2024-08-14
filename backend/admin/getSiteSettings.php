<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

include '../cors.php';
// enableCORS();
include "../db.php";
// session_start();
$data = json_decode("php://input", true);

$dataSite = $data["origin"];

if($dataSite){
$stmt = $con->prepare("SELECT * FROM `site_settings` WHERE 1");

if (!$stmt) {
    $response = array("status" => "error", "message" => $con->error);
    echo json_encode($response);
    exit;
}

if ($stmt->execute()) {
    $result = $stmt->get_result();
    $count = $result->num_rows;

    if ($count > 0) {
        $row = $result->fetch_assoc();

        $btcWallet = $row["btc_wallet"];
        $ethWallet = $row["eth_wallet"];
        $phonenumber = $row["phonenumber"];
        $address = $row["address"];
        $eth_rate = $row["current_eth_rate"];
        $btc_rate = $row["current_btc_rate"];

        $response = array(
            "status" => "success",
            "message" => "Site Data",
            'address' => $address,
            "BTCWallet" => $btcWallet,
            "ETHWallet" => $ethWallet,
            "phonenumber" => $phonenumber,
            "btc_rate" => $btc_rate,
            "eth_rate" => $eth_rate
        );
    } else {
        $response = array("status" => "error", "message" => "NoDataAvailable");
    }
} else {
    $response = array("status" => "error", "message" => "QueryExecutionFailed");
}



}else {
    $response = array("status" => "error", "message" => "QueryExecutionFailed");
}

echo json_encode($response);
ob_end_flush();