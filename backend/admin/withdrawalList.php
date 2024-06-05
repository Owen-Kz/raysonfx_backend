<?php
include "../cors.php";
enableCORS();
include "../db.php";
session_start();



$admin_id = $_GET["id"];

if(isset($admin)){
    $stmt  = $con->prepare("SELECT * FROM `administrators` WHERE md5(`id`) = ?");
    $stmt->bind_param("s", $admin_id);
    if(!$stmt){
        throw new Exception("Error Preparing statment", $con->error);
    }
    if(!$stmt->execute()){
        throw new Exception("error Could not execute", $stmt->error);
    }else{
        $result = $stmt->get_result();
        $count = mysqli_num_rows($result);

        if($count > 0){
            $stmt = $con->prepare("SELECT * FROM `withdrawals` WHERE 1");
            if($stmt->execute()){
                $result = $stmt->get_result();
                $count = mysqli_num_rows($result);
                if($count > 0){
                    $withdrawalsList = array(); // Initialize an array to store all withdrawals

                    while ($row = $result->fetch_assoc()) {
                        // Loop through each row in the result set and append it to the withdrawalsList array
                 
                 
                        $withdrawalsList[] = array("withdrawalId" => md5($row['id']), "withdrawalDetails" => $row);
                    }
               
                    $response = array('status' => 'success', 'message' => ' History', 'withdrawalHistory' => $withdrawalsList);
                    echo json_encode($response);
                }else{
                    $response = array('status' => 'success', 'message' => ' History', 'withdrawalHistory' => "[]");
                    echo json_encode($response);
                }
            }else{
                $response = array("status" => "error", "message" => "Could not execute transactions statement");
            echo json_encode($response);
            }

        }else{
            $response = array("status" => "error", "message" => "Unathorized");
            echo json_encode($response);
        }
    }
}