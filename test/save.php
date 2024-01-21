<?php

header('Content-Type: application/json');

$method = $_SERVER['REQUEST_METHOD'];

include 'imgDB.php';

switch ($method) {
    // display events
    case 'POST':
        $justText = $_POST['justText'];

        // Function to generate a random filename with 16 characters
        function generateRandomFilename() {
            $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
            $randomString = '';
            for ($i = 0; $i < 32; $i++) {
                $randomString .= $characters[rand(0, strlen($characters) - 1)];
            }
            return $randomString;
        }
        
        // Process the BLOB file
        $imageBlob = $_FILES['imageBlob'];
        $blobPath = 'images/' . generateRandomFilename() . '_blob.' . pathinfo($imageBlob['name'], PATHINFO_EXTENSION);
        if (!in_array(strtolower(pathinfo($blobPath, PATHINFO_EXTENSION)), array('jpg', 'jpeg', 'png'))) {
            $response = array('success' => false, 'message' => 'Invalid file format. Only JPG, JPEG, and PNG allowed.');
            header('Content-Type: application/json');
            echo json_encode($response);
            exit;
        }
        move_uploaded_file($imageBlob['tmp_name'], $blobPath);
        
        // Process the VARCHAR file
        $imageVarchar = $_FILES['imageVarchar'];
        $varcharPath = 'images/' . generateRandomFilename() . '_' . basename($imageVarchar['name']);
        move_uploaded_file($imageVarchar['tmp_name'], $varcharPath);
        
        $sql = "INSERT INTO imageTest (imageBlob, imageVarchar, text) VALUES (?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sss", $blobPath, $varcharPath, $justText);
        
        if ($stmt->execute()) {
            $response = array('success' => true, 'message' => 'Data saved successfully');
        } else {
            $response = array('success' => false, 'message' => 'Error saving data');
        }
        
        $stmt->close();
        $conn->close();
        echo json_encode($response);
    }

?>