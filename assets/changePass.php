<?php
session_start();
require_once '../connectDB.php';

$method = $_SERVER['REQUEST_METHOD'];

switch ($method) {
    case 'POST':
        handlePostRequest();
        break;

    // Handle other HTTP methods if needed

    default:
        // Handle unsupported methods
        http_response_code(405); // Method Not Allowed
        echo json_encode(['error' => 'Unsupported method']);
}

function handlePostRequest() {
    global $conn;

    // Check if the changePass key is set in the POST data
    if (isset($_POST['changePass'])) {
        $currentPass = $_POST['currentPass'];
        $newPass = $_POST['newPass'];
        $confirmPass = $_POST['confirmPass'];

        // Validate input data
        if (empty($currentPass) || empty($newPass) || empty($confirmPass)) {
            http_response_code(400); // Bad Request
            echo json_encode(['error' => 'All fields are required']);
            return;
        }
        
        if ($newPass != $confirmPass) {
            echo json_encode(['error' => 'Passwords don\'t match.']);
            return;
        }

        if ($currentPass === $newPass) {
            echo json_encode(['error' => 'Current password and new password are the same.']);
            return;
        }

        // Your SQL to check the current password
        $sql = "SELECT * FROM users WHERE username= ?";
        $stmt = mysqli_prepare($conn, $sql);

        if ($stmt) {
            mysqli_stmt_bind_param($stmt, 's', $_SESSION["username"]);
            mysqli_stmt_execute($stmt);
            $result = mysqli_stmt_get_result($stmt);
            $row = mysqli_fetch_assoc($result);

            // Check if the current password is correct
            if (password_verify($currentPass, $row['password'])) {
                $userId = $_SESSION['user_id'];
                $hashedNewPass = password_hash($newPass, PASSWORD_DEFAULT);

                $query = "UPDATE users SET password = ? WHERE id = ?";
                $stmt = mysqli_prepare($conn, $query);

                if ($stmt) {
                    mysqli_stmt_bind_param($stmt, 'si', $hashedNewPass, $userId);
                    $result = mysqli_stmt_execute($stmt);

                    // Check if the password update was successful
                    if ($result) {
                        echo json_encode(['success' => true, 'message' => 'Password changed successfully']);
                    } else {
                        echo json_encode(['success' => false, 'error' => 'Failed to change password']);
                    }
                } else {
                    echo json_encode(['error' => 'Failed to prepare statement']);
                }
            } else {
                echo json_encode(['error' => 'Current password is incorrect']);
            }
        } else {
            echo json_encode(['error' => 'Failed to prepare statement']);
        }

    } else {
        // If changePass key is not set, return an error
        http_response_code(400); // Bad Request
        echo json_encode(['error' => 'Invalid request']);
    }
}
?>