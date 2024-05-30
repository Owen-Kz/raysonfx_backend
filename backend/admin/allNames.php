<?php
include "../db.php";
session_start();

$adminID = $_GET["a_id"];

if(isset($adminID)){
    $stmt = $con->prepare("SELECT * FROM `administrators` WHERE md5(`id`) = ?");
    $stmt->bind_param("s", $adminID);
    $stmt->execute();
    $result = $stmt->get_result();
    $run_query = $result;

    $row = mysqli_fetch_array($run_query);

    $adminUSER = $row["username"];
    if(isset($_SESSION["administrator"]) && $adminUSER){


        $stmt = $con->prepare("SELECT * FROM `notify_names` WHERE 1 ORDER BY `id` DESC");
        // $stmt->bind_param("ss", $usersID, $userID);
        $stmt->execute();
        $result = $stmt->get_result();
        $run_query = $result;
        // $run_query = $result;    
        $count = mysqli_num_rows($run_query);

        if($count > 0){
            $usersList = array(); // Initialize an array to store all users

            while ($row = $result->fetch_assoc()) {
                // Loop through each row in the result set and append it to the usersList array
                // $usersList[] =  $row;
                $usersList[] = array("usersId" => md5($row['id']), "usersDetails" => $row);
            }
            $response = array("status" => "success", "message" => "users List", "usersList" => $usersList);
            echo json_encode($response);
      
        }else{
            $response = array("status" => "error", "message" => "users Not Found", "usersList" => "[]");
           echo json_encode($response);
        }

} 
else{
    $response = array("status" => "error", "message" => "Invalid Session Please login", "usersList" => "[]");
   echo json_encode($response);
} 
}
else{
    $response = array("status" => "error", "message" => "Unathorized Access", "usersList" => "[]");
   echo json_encode($response);
}
