<?php
include '../cors.php';
// enableCORS();
include '../db.php';


$data = json_decode(file_get_contents('php://input'), true);

// Access the values
$amountToAdd = (int)$data['amount'];
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

    
        //if user record is available in database then $count will be equal to 1
        if($count > 0){

            $username = $row["username"];
            $INTEREST = 0;

            $stmt = $con->prepare("SELECT SUM(`amount`) AS `totalcompleted` FROM `transactions` WHERE `username` = ? AND `type` = 'interest' AND `status` = 'completed'");
            $stmt->bind_param("s", $username);
            
            if($stmt->execute()){
                $result = $stmt->get_result();
          
                $count = mysqli_num_rows($run_query);    
          
          
            if ($row = $result->fetch_assoc()) {
                $completed = $row["totalcompleted"];
            $stmt = $con->prepare("SELECT SUM(`amount`) AS `totalWithdrawals` FROM `transactions` WHERE `username` =?  AND `type` = 'Withdrawal'");
            $stmt->bind_param("s", $username);
            if($stmt->execute()){
                $res = $stmt->get_result();
                $row = $res->fetch_assoc();
                $totalWithdrawals = $row["totalWithdrawals"];
      
                $newInterestBalance = $completed - $totalWithdrawals;
                if($newInterestBalance > 0){
                  $INTEREST = $newInterestBalance;
              }else{
                  $INTEREST = 0;
              }
    
            $Balance = $INTEREST;
    
            $NewBalance = $INTEREST - $amountToAdd ;
            $TransactionType = "Withdrawal";


            if($amountToAdd > $Balance){
                $response = array('status' => 'error', 'message' => 'Insufficient Funds');
                echo json_encode($response);
            }else if($Balance > 0 && $amountToAdd <= $Balance){

                $stmtTransaction = $con->prepare("INSERT INTO `transactions` (`amount`, `type`, `username`) VALUES (?,?,?)");
                $stmtTransaction->bind_param("sss", $amountToAdd, $TransactionType, $username);

                if($stmtTransaction->execute()){
                    $stmtWithdraw = $con->prepare("INSERT INTO `withdrawals` (`user_id`, `amount`, `gateway`, `address`) VALUES (?,?,?,?)");
                    $stmtWithdraw->bind_param("ssss", $username, $amountToAdd, $gateway, $walletAddress);

                    $stmtWithdraw->execute();
    
                    $response = array('status' => 'success', 'message' => "WIthdrawal Successful, Check withdrawal History for status.", 'statement' => $stmt, 'result' => $result);
                }

        
            echo json_encode($response);
    

   
    }else{
        $response = array("status"=>'error', "message" => "Cannot withdraw at this time, please check the amount entered or contact our support team.");
        echo json_encode($response);
    }
    }
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