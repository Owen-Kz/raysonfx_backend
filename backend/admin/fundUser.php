<?php
// include __DIR__."../cors.php";
// enableCORS(); 
if (isset($_SERVER['HTTP_ORIGIN'])) {
    echo $_SERVER['HTTP_ORIGIN'];
    // Allow from any origin
    header("Access-Control-Allow-Origin: {$_SERVER['HTTP_ORIGIN']}");
    header('Access-Control-Allow-Credentials: true');
    header('Access-Control-Max-Age: 86400');    // cache for 1 day
}

// Access-Control headers are received during OPTIONS requests
if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_METHOD'])) {
        header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
    }
    if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS'])) {
        header("Access-Control-Allow-Headers: {$_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS']}");
    }
    exit(0);
}
include '../db.php';
session_start();
include "../sendTransactionEmail.php"; 

$data = json_decode(file_get_contents('php://input'), true);
if(isset($data)){
// Access the values
$amountToAdd = $data['amount'];
$userID = $data['username'];
$TransactionType = $data["transactionType"];


if (isset($amountToAdd) && isset($userID)) {
    // CHeck if the user already exists

    $stmt = $con->prepare("SELECT * FROM `user_data` WHERE `email` = ? OR `username` = ? ");
    $stmt->bind_param("ss", $email, $userID);
    $stmt->execute();
    $result = $stmt->get_result();
    $run_query = $result;
    // $run_query = $result;    
    $count = mysqli_num_rows($run_query);

    //if user record is available in database then $count will be equal to 1
    if ($count > 0) {
        $row = mysqli_fetch_array($run_query);
        $Balance = $row["current_balance"];
        $email = $row["email"];
        $NewBalance = (int)$amountToAdd + (int)$Balance;
        $firstname = $row["first_name"];
        $lastname = $row["last_name"];
        $fullname = "$firstname $lastname";
        $formattedAmount = number_format($amountToAdd);

        // Create a NEw account if the user does not exist i.e record is not >  0
        if ($TransactionType === "depositWalletCredit") {

            $stmt = $con->prepare("UPDATE `user_data` SET `current_balance` = ? WHERE `email` = ?");
            $stmt->bind_param("ss", $NewBalance, $emai);

            if ($stmt->execute()) {


            } else {
                echo "Error: " . $stmt->error;
            }
            $year = date("Y");
                $content = " 
                <div><img src=https://res.cloudinary.com/dll8awuig/image/upload/v1717282518/raysonFinance_lg8whf.jpg width=100% alt=www.alphaforexlyfe.com></div>
                <h1>Hi there, $firstname $lastname</h1>
                
                <h2><b>$formattedAmount</b> USD has been added to your wallet.</h2>
                <p>Your New balance is </p>
                <p>
                <button style='padding:10px 50px 10px 50px; display:flex; align-self:center; alignt-items:center; justify-self:center; background:dodgerblue; color:white; border:none; outline:none; border-radius:24px; text-align:center;  justfy-content:center;'>
                $NewBalance USD
                </button></p>
                <p>(c) $year . Rayson Finance</p>";
                
                SendTransactionEmail($email, "Deposit Alert", $fullname, $content);

            }else{
                // $newBalance = $currentBalance - $amount;

                // $stmt = $con->prepare("UPDATE `user_data` SET `current_balance` = ? WHERE `username` = ? LIMIT 1");
                // $stmt->bind_param("ss", $newBalance, $userID);
          
        }
        $approved = "completed";
        $stmtTransaction = $con->prepare("INSERT INTO `transactions` (`amount`, `type`, `username`,`status`) VALUES (?,?,?,?)");
        $stmtTransaction->bind_param("ssss", $amountToAdd, $TransactionType, $userID, $approved);

        $stmtTransaction->execute();

        if($TransactionType === "interest" || $TransactionType === "Interest"){
            $year = date("Y");
            $formattedAmount = number_format($amountToAdd);
            $content = " 
            <div><img src=https://res.cloudinary.com/dll8awuig/image/upload/v1717282518/raysonFinance_lg8whf.jpg width=100% alt=www.alphaforexlyfe.com></div>
            <h1>Hi there, $firstname $lastname</h1>
            
            <h3>You have received <b> $formattedAmount </b> USD Interest.</h3>
 
            <p>(c) $year . Rayson Finance</p>";
            
            SendTransactionEmail($email, "Interest Added", $fullname, $content);
        }
        header('Location: https://www.raysonfinance.org/foreman/dashboard');
        echo 'Account Funded Succesfully';
        // $response = array('status' => 'success', 'message' => 'Account Funded Succesfully', 'statement' => $stmt, 'result' => $result);
        // echo json_encode($response);
    } else {
        $response = array('status' => 'error', 'message' => "Requested Account Does Not Exist $userID ");
        echo json_encode($response);
    }
} else {
    $response = array('status' => 'error', 'message' => 'Incomplete data');
    echo json_encode($response);

}
}