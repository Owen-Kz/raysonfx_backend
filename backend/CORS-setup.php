<?php 
// Allow from any origin
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Credentials: true");
header("Access-Control-Max-Age: 1000");
header("Access-Control-Allow-Headers: X-Requested-With, Content-Type, Origin, Cache-Control, Pragma, Authorization, Accept, Accept-Encoding");
header("Access-Control-Allow-Methods: PUT, POST, GET, OPTIONS, DELETE");
// // Handle the CORS preflight request (OPTIONS method)
// if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
//     // Return response with status code 200 OK
//     http_response_code(200);
//     exit();
// }