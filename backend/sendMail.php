<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

require '../vendor/autoload.php'; // If you're using Composer (recommended)
require "./exportENV.php";
// include "./db.php";

$api = $SENDGRID_API_KEY;
$senderEmail = $SENDGRID_EMAIL;

$data = json_decode(file_get_contents('php://input'), true);

$RecipientEmail = $data["receiverEmail"];
$RecipientName = $data["recipientName"];
$subject = $data["subject"];
$message = $data["message"];
$DateObject = $data["Year"];
// $Subject = $data["subject"]
$resetToken = bin2hex(random_bytes(6)); // 10 bytes = 20 characters in hexadecimal representation
$encryptedButton = md5($RecipientEmail);

if($RecipientEmail){

$sendgrid = new \SendGrid($api);
try {
        $email = new \SendGrid\Mail\Mail();
        $email->setFrom($senderEmail, "AlphaForex Lyfe");
        $email->setSubject($subject);
        $email->addTo($RecipientEmail, $RecipientName);
        $email->addContent(
            "text/html","$message <p><a href=http://test.weperch.live/0auth?email=$RecipientEmail&verify=$encryptedButton>
            <button style='padding:10px 50px 10px 50px; display:flex; align-self:center; alignt-items:center; justify-self:center; background:dodgerblue; color:white; border:none; outline:none; border-radius:24px; text-align:center;  justfy-content:center;'>
            Verify Email
            </button></a></p>
            <p>(c) $DateObject . Alphaforexlyfe</p>"
        );
     
        // $email->addContent("text/plain", $resetToken);
        $response = $sendgrid->send($email);
    
        $response = array('status' => 'success', 'message' => 'Email sent', 'email' => $encryptedButton);
        echo json_encode($response);

    
} catch (Exception $e) {
    $response = array('status' => 'Internal Error', 'message' => 'Caught exception: '. $e->getMessage() ."\n");
    echo json_encode($response);
}

}else{
    $response = array('status' => 'error', 'message' => 'Invalid Request');
    echo json_encode($response);
}