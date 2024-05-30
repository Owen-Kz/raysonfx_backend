<?php

include '../db.php';


$data = json_decode(file_get_contents('php://input'), true);

// Access the values
$amountToAdd = $data['amount'];
$walletAddress = $data['walletAddress'];
$gateway = $data["gateway"];
$userID = $data['user_id'];


if(isset($amountToAdd) && isset($userID) ){
    // CHeck if the user already exists
    
        $stmt = $con->prepare("SELECT * FROM `user_data` WHERE md5(`id`) =?");
        $stmt->bind_param("s", $userID);
        $stmt->execute();
        $result = $stmt->get_result();
        $run_query = $result;
        // $run_query = $result;    
        $count = mysqli_num_rows($run_query);

		$row = mysqli_fetch_array($run_query);

        $Balance = $row["current_balance"];
        $username = $row["username"];
        $NewBalance = $Balance - $amountToAdd ;
        $TransactionType = "Withdrawal";
        //if user record is available in database then $count will be equal to 1
        if($count > 0){
            if($amountToAdd > $Balance){
                $response = array('status' => 'error', 'message' => 'Insufficient Funds');
                echo json_encode($response);
            }else{

            // Create a NEw account if the user does not exist i.e record is not >  0
            $stmt = $con->prepare("UPDATE `user_data` SET `current_balance` = ? ");
            $stmt->bind_param("s", $NewBalance);
    
            if($stmt->execute()){

                $stmtTransaction = $con->prepare("INSERT INTO `transactions` (`amount`, `type`, `username`) VALUES (?,?,?)");
                $stmtTransaction->bind_param("sss", $amountToAdd, $TransactionType, $username);

                if($stmtTransaction->execute()){
                    $stmtWithdraw = $con->prepare("INSERT INTO `withdrawals` (`user_id`, `amount`, `gateway`, `address`) VALUES (?,?,?,?)");
                    $stmtWithdraw->bind_param("ssss", $username, $amountToAdd, $gateway, $walletAddress);

                    $stmtWithdraw->execute();
    
                    $response = array('status' => 'success', 'message' => 'WIthdrawal Succesful, Check withdrawal History for status', 'statement' => $stmt, 'result' => $result);
                }

        
            echo json_encode($response);
    
        }
        else{
            echo "Error: " . $stmt->error;
        }
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