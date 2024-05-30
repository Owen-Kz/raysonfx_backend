<?php 



function GetAdminInfo($userId){
    
include '../db.php';
// include "../getInitials.php";
session_start();

if(isset($userId)){

    $stmt = $con->prepare("SELECT * FROM `administrators` WHERE md5(`id`) = ? LIMIT 1");
    $stmt->bind_param("s", $userId);
    
    if($stmt->execute()){

    $result = $stmt->get_result();
    
    // $run_query = mysqli_query($con,$sql);
    $run_query = $result;    
    $count = mysqli_num_rows($run_query);
    
   
	//if user record is available in database then $count will be equal to 1

	if($count == 1){

        return true;
    
}else{
    return false;
}
 }else{
    return false;
 }
}else{
    return false;
}
}