<?php
function SendTransactionEmail($receiver, $subject, $fullname, $emailContent) {
    // Node.js endpoint URL
    $url = "https://asfischolar.org/email/external";

    // Email details
    $emailData = array(
        'to' => $receiver,
        'fullname' => $fullname,
        'subject' => $subject,
        'html' => $emailContent
    );

    // Path to the downloaded certificate bundle
    $cacert = "/etc/ssl/certs/cacert.pem";

    // Initialize cURL session
    $ch = curl_init($url);

    // Set cURL options
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($emailData));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array(
        'Content-Type: application/json'
    ));
    curl_setopt($ch, CURLOPT_CAINFO, $cacert); // Set the path to the certificate bundle

    // Execute cURL request and get the response
    $response = curl_exec($ch);

    // Check for errors
    if (curl_errno($ch)) {
        echo json_encode(array("status" => "error", "message" => 'cURL error: ' . curl_error($ch)));
    } else {
        echo json_encode(array("status" => "emailSent", "message" => 'Response: ' . $response));
    }

    // Close cURL session
    curl_close($ch);
}
