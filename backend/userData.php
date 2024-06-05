<?php 

include 'db.php';
include "getInitials.php";
session_start();

$userId = $_GET["uid"];


if(isset($userId)){

    $stmt = $con->prepare("SELECT * FROM `user_data` WHERE md5(`id`) = ? AND verification_email != 'unverified' LIMIT 1");
    $stmt->bind_param("s", $userId);
    
    if($stmt->execute()){

    $result = $stmt->get_result();
    
    // $run_query = mysqli_query($con,$sql);
    $run_query = $result;    
    $count = mysqli_num_rows($run_query);
    
   
	//if user record is available in database then $count will be equal to 1

	if($count == 1){
        // Get and verify the users password if the account exists  
        $row = mysqli_fetch_array($run_query);
        $fullname = $row["first_name"] . " " . $row["last_name"];
        $firstname = $row["first_name"];
        $lastname = $row["last_name"];
        $zip = $row["zip_code"];
        $country = $row["country"];
        $address = $row["address"];
        $state = $row["state"];
        $city = $row["city"];
        $phonenumber = $row["phonenumber"];
        

  $email = $row["email"];
  $username = $row["username"];
  $accountBalance = $row["current_balance"];
  $INTEREST = 0;

  $stmt = $con->prepare("SELECT SUM(`amount`) AS `totalcompleted` FROM `transactions` WHERE `username` = ? AND `type` = 'interest' AND `status` = 'completed'");
  $stmt->bind_param("s", $username);
  
  if($stmt->execute()){
      $result = $stmt->get_result();

      $count = mysqli_num_rows($run_query);    


  if ($row = $result->fetch_assoc()) {
      $completed = $row["totalcompleted"];
      // Loop through each row in the result set and append it to the transactionsList array
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

      }
    }else{
            echo "Error: " . $stmt->error;
    }
        
        // $HOTEL =  $_SESSION["Hotel_name"];
        $user = array("username" => $username, "user_fullname" => $fullname, "account_balance" => $accountBalance, "totalInterest" => $INTEREST, "user_email" => $email, "Intitials" => getInitials($fullname), "firstname" => $firstname, "lastname" => $lastname, "phonenumber" => $phonenumber, "zip" => $zip, "city" => $city, "state" => "$state", "address" => $address, "country" => $country);

        $response = array('status' => 'success', 'message' => 'User Loggedin', 'user' => $user);
        echo json_encode($response);

    
    }
    else {
        $response = array('status' => 'error', 'message' => 'User not found', 'user' => '[]');
        echo json_encode($response);
    }
    
}else{
   
    $response = array('status' => 'error', 'message' => "Error: " . $stmt->error, 'user' => '[]');
    echo json_encode($response);
}
}
}