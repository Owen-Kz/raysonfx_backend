<?php
include '../cors.php';
// enableCORS();
$resonse = array("status" => "404", "message" => "Unathourized, Take a step BACK!");
echo json_encode($resonse);