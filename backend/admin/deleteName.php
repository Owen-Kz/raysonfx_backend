<?php
include '../cors.php';
enableCORS();
include "../db.php";
session_start();


$NameId = $_GET["id"];
if($NameId){
    $stmt = $con->prepare("DELETE FROM `notify_names` WHERE md5(`id`) = ?");
    $stmt->bind_param("s", $NameId);

    if($stmt->execute()){
        header('Location: ../../foreman/names');
    }else{
        $response = array("status" => "error", "messagae" => "Could Not Delete" . $stmt->error);
        echo json_encode($response);
    }

}else{
    $response = array("status" => "error", "messagae" => "Unathorized Access");
    echo json_encode($response);

}