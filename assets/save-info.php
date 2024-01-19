<?php
session_start();

header("Content-Type: application/json");

$method = $_SERVER['REQUEST_METHOD'];

include '../connectDB.php';

switch ($method) {
    case 'POST':
        $response = array();

        $firstname = isset($_POST['firstname']) ? ucfirst($_POST['firstname']) : '';
        $lastname = isset($_POST['lastname']) ? ucfirst($_POST['lastname']) : '';
        $email = isset($_POST['email']) ? $_POST['email'] : '';

        if (empty($firstname) || empty($lastname) || empty($email)) {
            $response['status'] = 'error';
            $response['message'] = 'Fill up all form.';
        } else {
            // Check for duplicate email before updating
            $checkQuery = "SELECT email FROM profile WHERE account_id = ? AND email = ?";
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
                    unset($_SESSION['token']);
                } else {
                    $response['status'] = 'error';
                    $response['message'] = 'Updating information failed. Please try again.';
                }
                
                $stmtUpdProf->close();
            }
            
            $checkStmt->close();
        }

        echo json_encode($response);
        break;

    default:
        http_response_code(405);
        echo json_encode(['error' => 'Method not allowed']);
        break;
}
?>