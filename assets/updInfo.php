<?php
session_start();

header("Content-Type: application/json");

$method = $_SERVER['REQUEST_METHOD'];

include '../connectDB.php';

switch ($method) {
    case 'POST':
        $response = array();

        $firstname = isset($_POST['fname']) ? ucfirst($_POST['fname']) : '';
        $lastname = isset($_POST['lname']) ? ucfirst($_POST['lname']) : '';
        $email = isset($_POST['email']) ? $_POST['email'] : '';
        $password = isset($_POST['password']) ? $_POST['password'] : '';

        if (empty($firstname) || empty($lastname) || empty($email) || empty($password)) {
            $response['status'] = 'error';
            $response['message'] = 'Fill up all form.';
        } else {
            // Check if the provided password matches the existing password
            $checkPasswordQuery = "SELECT password FROM users WHERE username=?";
            $stmtCheckPassword = mysqli_prepare($conn, $checkPasswordQuery);
            mysqli_stmt_bind_param($stmtCheckPassword, 's', $_SESSION['username']);
            mysqli_stmt_execute($stmtCheckPassword);
            $resultCheckPassword = mysqli_stmt_get_result($stmtCheckPassword);

            if ($rowCheckPassword = mysqli_fetch_assoc($resultCheckPassword)) {
                $hashedPassword = $rowCheckPassword['password'];

                if (password_verify($password, $hashedPassword)) {
                    // Check if the email is already used by another user
                    $checkQuery = "SELECT email FROM profile WHERE account_id != ? AND email = ?";
                    $checkStmt = $conn->prepare($checkQuery);
                    $checkStmt->bind_param('is', $_SESSION['user_id'], $email);
                    $checkStmt->execute();
                    $checkResult = $checkStmt->get_result();

                    if ($checkResult->num_rows > 0) {
                        $response['status'] = 'error';
                        $response['message'] = 'This email is already used by another user. Use another one.';
                    } else {
                        // Update profile information
                        $updateProfileQuery = "UPDATE profile SET firstname = ?, lastname = ?, email = ? WHERE account_id = ?";
                        $stmtUpdProf = $conn->prepare($updateProfileQuery);
                        $stmtUpdProf->bind_param('sssi', $firstname, $lastname, $email, $_SESSION['user_id']);

                        if ($stmtUpdProf->execute()) {
                            $response['status'] = 'success';
                            $response['message'] = 'Information saved!';
                        } else {
                            $response['status'] = 'error';
                            $response['message'] = 'Updating information failed. Please try again.';
                        }

                        $stmtUpdProf->close();
                    }

                    $checkStmt->close();
                } else {
                    $response['status'] = 'error';
                    $response['message'] = 'Incorrect password.';
                }
            }

            $stmtCheckPassword->close();
        }

        echo json_encode($response);
        break;

    default:
        http_response_code(405);
        echo json_encode(['error' => 'Method not allowed']);
        break;
}
?>