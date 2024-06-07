<?php
function SendTransactionEmail($receiver, $subject, $fullname, $emailContent, ){
    // Node.js endpoint URL
$url = "https://asfischolar.org/api/email/external";

// Email details
$emailData = array(
    'to' => "$receiver",
    'fullname' => "$fullname",
    'subject' => "$subject",
    'html' => "$emailContent"
);

// Initialize cURL session
$ch = curl_init($url);

// Set cURL options
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($emailData));
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, array(
    'Content-Type: application/json'
));

// Execute cURL request and get the response
$response = curl_exec($ch);

// Check for errors
if(curl_errno($ch)) {
    echo 'cURL error: ' . curl_error($ch);
    // return false;
} else {
    // Process the response
    echo 'Response: ' . $response;
    // return true;
}

// Close cURL session
curl_close($ch);
}
// return true;

