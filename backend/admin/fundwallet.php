<?php

include '../db.php';


$data = json_decode(file_get_contents('php://input'), true);

// Access the values
$amountToAdd = $data['amount'];
$userID = $data['userID'];


if(isset($amountToAdd) && isset($userID) ){
    // CHeck if the user already exists
    
        $stmt = $con->prepare("SELECT * FROM `user_data` WHERE `email` = ? OR `username` = ? ");
        $stmt->bind_param("ss", $email, $username_post, $first_name, $last_name);
        $stmt->execute();
        $result = $stmt->get_result();
        $run_query = $result;
        // $run_query = $result;    
        $count = mysqli_num_rows($run_query);

		$row = mysqli_fetch_array($run_query);

        $Balance = $row["current_balance"];
        $NewBalance = $amountToAdd + $Balance;
        $TransactionType = "Credit";
        //if user record is available in database then $count will be equal to 1
        if($count > 0){
            // Create a NEw account if the user does not exist i.e record is not >  0
            $stmt = $con->prepare("UPDATE `user_data` SET `current_balance` = ? ");
            $stmt->bind_param("s", $NewBalance);
    
            if($stmt->execute()){

                $stmtTransaction = $con->prepare("INSERT INTO `transactions` (`amount`, `type`, `username`) VALUES (?,?,?)");
                $stmtTransaction->bind_param("sss", $amountToAdd, $TransactionType,  $userID);

                $stmtTransaction->execute();

            $response = array('status' => 'success', 'message' => 'Account Funded Succesfullt', 'statement' => $stmt, 'result' => $result);
            echo json_encode($response);
    
        }
        else{
            echo "Error: " . $stmt->error;
        }
        }
        else {
            $response = array('status' => 'error', 'message' => 'Requested Account Does Not Exist');
            echo json_encode($response);
        }
    }else{
        $response = array('status' => 'error', 'message' => 'Incomplete data');
    echo json_encode($response);
        
    }