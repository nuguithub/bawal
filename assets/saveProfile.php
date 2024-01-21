<?php
session_start();

header('Content-Type: application/json');

$method = $_SERVER['REQUEST_METHOD'];

include '../connectDB.php';

$user_id = $_SESSION['user_id'];

switch ($method) {
    // display events
    case 'POST':
        $oldImagePath = "";
    
        // Fetch the old image path
        if (!empty($user_id)) {
            $stmt = $conn->prepare("SELECT image FROM profile WHERE id=?");
            $stmt->bind_param("i", $user_id);
            $stmt->execute();
            $stmt->bind_result($oldImagePath);
            $stmt->fetch();
            $stmt->close();
        }
    
        // Validate and process the uploaded image
        if (!empty($_FILES["image"]["name"])) {
            $targetDir = "../images/";
            $imageFileType = strtolower(pathinfo($_FILES["image"]["name"], PATHINFO_EXTENSION));
            $targetFile = $targetDir . generateUniqueFileName(32) . "." . $imageFileType;
    
            $check = getimagesize($_FILES["image"]["tmp_name"]);
            if ($check === false) {
                echo json_encode(['status' => 'error', 'message' => 'File is not an image or unsupported image format.']);
                exit();
            }
    
            if (move_uploaded_file($_FILES["image"]["tmp_name"], $targetFile)) {
                // Extract file name from the full path
                $fileNameOnly = basename($targetFile);
    
                // Update the database with the new image path
                $stmt = $conn->prepare("UPDATE profile SET image=? WHERE id=?");
                $stmt->bind_param("si", $fileNameOnly, $user_id);
                $stmt->execute();
                $stmt->close();
    
                // Delete the old image file
                if (!empty($oldImagePath) && is_file($oldImagePath)) {
                    unlink($oldImagePath);
                }
            } else {
                echo json_encode(['status' => 'error', 'message' => 'Failed to move the uploaded image.']);
                exit();
            }
        }
    
        echo json_encode(['status' => 'success', 'message' => 'Profile pic updated successfully']);
        break;
}

    function generateUniqueFileName($length) {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, strlen($characters) - 1)];
        }
        return $randomString;
    }

?>