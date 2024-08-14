<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

function SendTransactionEmail($receiver, $subject, $fullname, $emailContent) {
    // Node.js endpoint URL
    // $url = "https://asfischolar.org/email/external";

    // // Email details
    // $emailData = array(
    //     'to' => $receiver,
    //     'fullname' => $fullname,
    //     'subject' => $subject,
    //     'html' => $emailContent
    // );

    // // Path to the downloaded certificate bundle
    // $cacert = "/var/www/html/cacert.pem";

    // // Initialize cURL session
    // $ch = curl_init($url);

    // // Set cURL options
    // curl_setopt($ch, CURLOPT_POST, true);
    // curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($emailData));
    // curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    // curl_setopt($ch, CURLOPT_HTTPHEADER, array(
    //     'Content-Type: application/json'
    // ));
    // // curl_setopt($ch, CURLOPT_CAINFO, $cacert); // Set the path to the certificate bundle
    // curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); // Disable SSL verification

    // // Execute cURL request and get the response
    // $response = curl_exec($ch);

    // // Check for errors
    // if (curl_errno($ch)) {
    //     // echo json_encode(array("status" => "error", "message" => 'cURL error: ' . curl_error($ch)));
    // } else {
    //     // echo json_encode(array("status" => "emailSent", "message" => 'Response: ' . $response));
    // }

    // // Close cURL session
    // curl_close($ch);

    // require_once __DIR__ . '/../vendor/autoload.php'; // If you're using Composer (recommended)

    // // Import Environment Variables
    // include __DIR__ . '/exportENV.php';
    // include __DIR__ . '/db.php';

    // $apiKey = $_ENV['BREVO_API_KEY'];
    // $senderEmail = $_ENV["BREVO_EMAIL"];

    // if ($receiver) {
    //     // Configure the Brevo client
    //     $config = \Brevo\Client\Configuration::getDefaultConfiguration()->setApiKey('api-key', $apiKey);
    //     $apiInstance = new \Brevo\Client\Api\TransactionalEmailsApi(
    //         new \GuzzleHttp\Client(),
    //         $config
    //     );

    //     // Create email object
    //     $email = new \Brevo\Client\Model\SendSmtpEmail();

    //     // Set the sender
    //     $sender = new \Brevo\Client\Model\SendSmtpEmailSender();
    //     $sender->setEmail($senderEmail);
    //     $sender->setName('Rayson Finance');
    //     $email->setSender($sender);

    //     // Set the recipient
    //     $recipient = new \Brevo\Client\Model\SendSmtpEmailTo();
    //     $recipient->setEmail($receiver);
    //     $email->setTo([$recipient]);

    //     // Set the subject and content
    //     $email->setSubject("$subject");
    //     $email->setHtmlContent("$emailContent");

    //     try {
    //         $response = $apiInstance->sendTransacEmail($email);
    //         $response = array('status' => 'success', 'message' => 'Email sent');
    //         // print $response;
    //     } catch (\Brevo\Client\ApiException $e) {
    //         $response = array('status' => 'Internal Error', 'message' => 'Caught exception: ' . $e->getMessage() . "\n");
    //         // print $response;
    //     }
    // } else {
    //     $response = array('status' => 'error', 'message' => 'Invalid Request');
    //     // print $response;
    // }

    // print_r($response);



// require 'vendor/autoload.php';
  require_once __DIR__ . '/../vendor/autoload.php'; // If you're using Composer (recommended) 

$mail = new PHPMailer(true);

try {
    //Server settings
    $mail->SMTPDebug = 2;                                       // Enable verbose debug output
    $mail->isSMTP();                                            // Set mailer to use SMTP
    $mail->Host       = 'mail.raysonfinance.org';                  // Specify main and backup SMTP servers
    $mail->SMTPAuth   = true;                                   // Enable SMTP authentication
    $mail->Username   = 'handler@raysonfinance.org';              // SMTP username
    $mail->Password   = 'dearTrader';                         // SMTP password
    $mail->SMTPSecure = 'tls';                                  // Enable TLS encryption, `ssl` also accepted
    $mail->Port       = 587;                                    // TCP port to connect to

    //Recipients
    $mail->setFrom('traits@raysonfinance.org', 'Mailer');
    $mail->addAddress('test-e5tvgx926@srv1.mail-tester.com', 'Joe User');     // Add a recipient

    // Content
    $mail->isHTML(true);                                        // Set email format to HTML
    $mail->Subject = 'Here is the subject';
    $mail->Body    = 'This is the HTML message body <b>in bold!</b>';
    $mail->AltBody = 'This is the body in plain text for non-HTML mail clients';

    $mail->send();
    echo 'Message has been sent';
} catch (Exception $e) {
    echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
}

}
