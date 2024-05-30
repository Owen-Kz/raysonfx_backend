<?php


include "../db.php";

$data = json_Decode(file_get_contents('php://input'), true);

$email = $data["email"];
$resetCode = $data["resetCode"];

if(isset($email)){
    $email_esc = mysqli_real_escape_string($con, $email);
    $resetCode = mysqli_real_escape_string($con, $resetCode);

    $stmt = $con->prepare("SELECT * FROM `user_data` WHERE md5(`email`) = ? AND `resetToken` = ?");
    $stmt->bind_param("ss", $email_esc, $resetCode);
    
    if($stmt->execute()){
        $result = $stmt->get_result();

        $count = mysqli_num_rows($result);
        if($count > 0){
            $response = array("success" => "Code Confirmed");
            echo json_encode($response);
        }else{
            $response = array("error" => "Invalid Data");
            echo json_encode($response);
        }
    }else{
        $response = array("error" => "$stmt->error");
        echo json_encode($response);
    }
}else{
    $response = array("error" => "Invalid Data");
    echo json_encode($response);
}