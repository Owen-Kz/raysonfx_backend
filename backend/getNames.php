<?php
include "./db.php";
session_start();




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
                $usersList[] = $row;
            }
            $response = array("status" => "success", "message" => "users List", "names" => $usersList);
            echo json_encode($response);
      
        }else{
            $response = array("status" => "error", "message" => "users Not Found", "usersList" => "[]");
           echo json_encode($response);
        }



