<?php
include "../db.php";

$data = json_decode(file_get_contents('php://input'), true);


$firstname = $data["firstname"];
$lastname =$data["lastname"];
$address = $data["address"];
$state = $data["state"];
$zip = $data["zip"];
$city =  $data["city"];
$user_id = $data["user_id"];


if(isset($user_id)){
    $stmt = $con->prepare("SELECT * FROM `user_data` WHERE md5(`id`) = ? AND verification_email != 'unverified'");
    $stmt->bind_param("s", $user_id);
    
    if($stmt->execute()){

    $result = $stmt->get_result();
    
    // $run_query = mysqli_query($con,$sql);
    $run_query = $result;    
    $count = mysqli_num_rows($run_query);

	if($count > 0){
        // Get and verify the users password if the account exists  
        $row = mysqli_fetch_array($run_query);
        
        $stmt = $con->prepare("UPDATE `user_data` SET `first_name` = ?, `last_name` = ?, `address` = ?, `state` = ?, `zip_code` = ?, `city` = ? WHERE md5(`id`) = ? AND verification_email != 'unverified'");

        $stmt->bind_param("sssssss", $firstname, $lastname, $address, $state, $zip, $city, $user_id);
        
        if($stmt->execute()){
            $response = array("status" => "success", "message" => "Settings Saved", "userDataRES" => $firstname."". $zip);
        }else{
            $response = array("status" => "error", "message" => "Failed TO load resouce, Please try again");

        }
        echo json_Encode($response);

    }
    else{
        $response = array("status" => "error", "message" => "Failed TO load resouce, Please try again");
        echo json_Encode($response);

    }
}else{
    $response = array("status" => "error", "message" => "Failed TO load resouce, Please try again");
    echo json_Encode($response);
}
}