<?php
include 'cors.php';
// enableCORS();

// Function to upload file to Cloudinary
include "./cloudinaryFileUpload.php";
include "./db.php";

function uploadToCloudinary($file)
{
    global $cloudinary; // Access the Cloudinary instance

    // Generate a new filename with timestamp
    $newFilename = "receipt_" . time(); // Example: receipt_1646861434

    // Upload the file to Cloudinary with the new filename
    $result = $cloudinary->uploadApi()->upload(
        $file["tmp_name"],
        ["public_id" => $newFilename] // Use the new filename as the public_id
    );
    return $result;
}

$formData = json_decode($_POST["transaction_details"], true);

// Check if form is submitted and file is uploaded
if (isset($_FILES["file"])) {
    $type = $formData["type"];
    $userID = $formData["user_id"];
    $amount = $formData["amount"];
    $currentDateTime = date('Y-m-d H:i:s');

    // Debugging log
    error_log("UserID: $userID, Type: $type, Amount: $amount");

    $stmt = $con->prepare("SELECT * FROM `user_data` WHERE md5(`id`) = ?");
    $stmt->bind_param("s", $userID);

    if ($stmt->execute()) {
        $result = $stmt->get_result();
        $count = mysqli_num_rows($result);

        if ($count > 0) {
            $row = mysqli_fetch_array($result);
            $username = $row["username"];

            // Debugging log
            error_log("Username: $username found for UserID: $userID");

            $stmt = $con->prepare("SELECT * FROM `transactions` WHERE `username` = ? AND `amount` = ? AND `type` = ? AND TIMESTAMPDIFF(MINUTE, `date`, ?) <= 5");
            $stmt->bind_param("sssi", $username, $amount, $type, $currentDateTime);
            $stmt->execute();
            $result = $stmt->get_result();
            $count = mysqli_num_rows($result);

            if ($count > 0) {
                $response = array("status" => "success", 'message' => "This Transaction Already Exists, Wait a few minutes and try again");
                echo json_encode($response);
                exit();
            } else {
                $cloudinaryResult = uploadToCloudinary($_FILES["file"]);
                if ($cloudinaryResult && isset($cloudinaryResult["secure_url"])) {
                    $cloudinaryUrl = $cloudinaryResult["secure_url"];
                    $normalUrl = str_replace('\/', '/', $cloudinaryUrl);

                    $stmt = $con->prepare("INSERT INTO `transactions`(`amount`, `type`, `username`, `fileURL`) VALUES (?,?,?,?)");
                    $stmt->bind_param("ssss", $amount, $type, $username, $normalUrl);

                    if ($stmt->execute()) {
                        $response = array("status" => "success", 'message' => "Transaction successful");
                        echo json_encode($response);
                        exit();
                    } else {
                        $response = array("status" => "error", 'message' => "Error: " . $stmt->error);
                        echo json_encode($response);
                        exit();
                    }
                } else {
                    $response = array("status" => "error", 'message' => "Error uploading file to Cloudinary");
                    echo json_encode($response);
                    exit();
                }
            }
        } else {
            $response = array("status" => "error", 'message' => "User Does not Exist");
            echo json_encode($response);
            exit();
        }
    } else {
        $response = array("status" => "error", 'message' => "Error executing query: " . $stmt->error);
        echo json_encode($response);
        exit();
    }
} else {
    $response = array("status" => "error", 'message' => "No file uploaded");
    echo json_encode($response);
    exit();
}
?>
