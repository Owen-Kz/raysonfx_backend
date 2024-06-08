<?php
include '../cors.php';
enableCORS();
include "../db.php";
include "../sendTransactionEmail.php";

session_start();


// Access the values
$transactionID = $_POST['transactionID'];
$userID = $_POST['userID'];

if(isset($userID)){

    if(isset($transactionID) &&  isset($userID)){
        // Find Transaction Data 
        $stmt = $con->prepare("SELECT * FROM `transactions` WHERE md5(`id`) = ? AND `username` = ?");
        $stmt->bind_param("ss", $transactionID, $userID);
        $stmt->execute();
        $result = $stmt->get_result();
        $run_query = $result;
        $count = mysqli_num_rows($run_query);

		$row = mysqli_fetch_array($run_query);
        $transactionType = $row["type"];
        $amount = $row["amount"];

        if($count > 0){
            // Approve TRansaction if found 
            $stmt = $con->prepare("UPDATE `transactions` SET `status` = 'completed' WHERE md5(`id`) = ? AND `username` = ?");
            $stmt->bind_param("ss", $transactionID, $userID);
           if($stmt->execute()){

            $stmt = $con->prepare("SELECT * FROM `user_data`  WHERE `username` = ? LIMIT 1");
            $stmt->bind_param("s", $userID);
            $stmt->execute();
            $result = $stmt->get_result();
            $run_query = $result;  
            $row = mysqli_fetch_array($run_query);
            $currentBalance = $row["current_balance"];
            $userEmail = $row["email"];
            $firstname = $row["first_name"];
            $lastname = $row["last_name"];
            $fullname = "$firstname $lastname";
            
            
            if($transactionType === "deposit" || $transactionType === "depositWalletCredit" && $transactionType != "interestDeposit" && $transactionType != "interestCredit"){
            
                $newBalance = $currentBalance + $amount;
        $formattedAmount = number_format($amount);
                

                $stmt = $con->prepare("UPDATE `user_data` SET `current_balance` = ? WHERE `username` = ? LIMIT 1");
                $stmt->bind_param("ss", $newBalance, $userID);
                $stmt->execute();


                $year = date("Y");
                $content = " 
                <div><img src=https://res.cloudinary.com/dll8awuig/image/upload/v1717282518/raysonFinance_lg8whf.jpg width=100% alt=www.alphaforexlyfe.com></div>
                <h1>Hi there, $firstname $lastname</h1>
               
                <h2>Your Deposit of <b>$formattedAmount</b> USD has been added to your wallet.</h2>
                <p>Your New balance is:</p>
                <p>
                <button style='padding:10px 50px 10px 50px; display:flex; align-self:center; alignt-items:center; justify-self:center; background:dodgerblue; color:white; border:none; outline:none; border-radius:24px; text-align:center;  justfy-content:center;'>
                $newBalance
                </button></p>
                <p>(c) $year . Rayson Finance</p>";
                
                SendTransactionEmail($userEmail, "Deposit Approved", $fullname, $content);

            }else{
                $newBalance = $currentBalance - $amount;

                $stmt = $con->prepare("UPDATE `user_data` SET `current_balance` = ? WHERE `username` = ? LIMIT 1");
                $stmt->bind_param("ss", $newBalance, $userID);
                $stmt->execute();


            }


            $response = array("status" => "succsss", "message" => "Transaction Approved Successfully");
            echo json_encode($response);
            header('Location: https://www.raysonfinance.org/foreman/dashboard');
           }else{
            $response = array("status" => "error", "message" =>  "Error: " . $stmt->error);
           echo json_encode($response);
           }
           
        }else{
            $response = array("status" => "error", "message" => "Transaction Not Found");
           echo json_encode($response);
        }
    }

}else{
    $response = array("status" => "error", "message" => "Not Logged IN");
    echo json_encode($response);
}