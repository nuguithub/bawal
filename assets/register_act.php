<?php
header("Content-Type: application/json");

$method = $_SERVER['REQUEST_METHOD'];

include '../connectDB.php';

switch ($method) {
    case 'POST':
        $response = array();

        $username = isset($_POST['username']) ? $_POST['username'] : '';
        $password = isset($_POST['password']) ? $_POST['password'] : '';
        $confirmPassword = isset($_POST['confirm_password']) ? $_POST['confirm_password'] : '';

        if (empty($username) || empty($password) || empty($confirmPassword)) {
            $response['status'] = 'error';
            $response['message'] = 'Invalid input. Please provide username, password, and confirm_password.';
        } else {
            $checkQuery = "SELECT username FROM users WHERE username = ?";
            $checkStmt = $conn->prepare($checkQuery);
            $checkStmt->bind_param('s', $username);
            $checkStmt->execute();
            $checkResult = $checkStmt->get_result();

            if (strlen($username) >= 8 && strlen($password) >= 8) {
                if ($checkResult->num_rows > 0) {
                    $response['status'] = 'error';
                    $response['message'] = 'Username already exists. Please choose a different username.';
                } else {
                    if ($password !== $confirmPassword) {
                        $response['status'] = 'error';
                        $response['message'] = "Passwords don't match.";
                    }
                    else {
                        $passwordHash = password_hash($password, PASSWORD_DEFAULT);
        
                        $stmt = $conn->prepare("INSERT INTO users (username, password) VALUES (?, ?)");
                        $stmt->bind_param('ss', $username, $passwordHash);
            
                        if ($stmt->execute()) {
                            $response['status'] = 'success';
                            $response['message'] = 'Registration successful! You can now login.';
                        } else {
                            $response['status'] = 'error';
                            $response['message'] = 'Registration failed. Please try again.';
                        }
            
                        $stmt->close();
                    }
                }
            } else {
                $response['status'] = 'error';
                $response['message'] = 'Username and password must be at least 8 characters long.';
            }
        }

        echo json_encode($response);
        break;

    default:
        http_response_code(405);
        echo json_encode(['error' => 'Method not allowed']);
        break;
}
?>