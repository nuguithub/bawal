<?php
    include '../connectDB.php';

    if ($method === 'POST') {
        $data = json_decode(file_get_contents("php://input"));
    
        if (isset($data->username) && isset($data->password)) {
            $username = $data->username;
            $password = $data->password;

            // Retrieve the hashed password from the database
            $stmt = $conn->prepare("SELECT id, username, hashed_password FROM users WHERE username = ?");
            $stmt->bind_param('s', $username);
            $stmt->execute();
            $result = $stmt->get_result();
            $user = $result->fetch_assoc();

            if ($user && password_verify($password, $user['hashed_password'])) {
                // Start a session
                session_start();

                // Store user information in the session
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['username'] = $user['username'];

                // Close the database connection
                $conn->close();

                // Return a success message
                http_response_code(200);
                echo json_encode(array("message" => "Login successful", "redirect" => "index.php"));
            } else {
                http_response_code(401);
                echo json_encode(array("message" => "Invalid credentials"));
            }
        }
    } else {
        http_response_code(405);
        echo json_encode(array("message" => "Method not allowed"));
    }
?>